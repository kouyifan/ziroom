<?php
/**
 * Created by PhpStorm.
 * User: 技术主管寇一凡
 * Date: 2019/3/21
 * Time: 16:18
 */
namespace Modules\Ziroom\Repositories\Eloquent;
use Modules\Ziroom\Repositories\Contracts\GrabZiroomInterface;
use Modules\Ziroom\Services\ZiroomCacheServices;
use Modules\Ziroom\Services\PythonServices;
use Modules\Ziroom\Services\FileSystemService;
use Modules\Ziroom\Repositories\Eloquent\RoomRepository;

class GrabZiroomServiceRepository implements GrabZiroomInterface{

    protected $url_config = [
        'home'  =>  'http://www.ziroom.com/z/nl/z3.html',
    ];
    protected $ziroom_cache_service;
    protected $QueryList;
    protected $header;


    public function __construct()
    {
        $this->header = [
            'timeout' => '30',
            'headers' => [
                'Referer' => 'https://www.ziroom.com',
                "User-Agent"    => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36",
                "Accept"    =>" text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
                "Accept-Encoding"   => "gzip",
                "Accept-Language"   => "zh-CN,zh;q=0.8"
            ]
        ];
        $this->ziroom_cache_service = new ZiroomCacheServices;
        $this->QueryList = new \QL\QueryList;
    }
    //查询区域数据
    public function findZiroomAreaData()
    {
        $data = $this->find_area_subway_data($this->url_config['home'],'.clearfix.filterList:eq(0)>li .tag a');
        return $this->handle_area_data($data);
    }
    //查询地铁数据
    public function findZiroomSubwayData()
    {
        $data = $this->find_area_subway_data($this->url_config['home'],'.clearfix.filterList:eq(1)>li .tag a');
        return $this->handle_area_data($data);
    }
    //查询
    public function findZiroomAreaOrSubwayDbData($class,$type = 'all',$id = 0){
        $key = md5((string)$class.$type.$id);
        $cache_data = $this->ziroom_cache_service->_cache_subway_area_data($key);

        if (!empty($cache_data)) return $cache_data;

        switch ($type){
            case 'all':
                $datas = $class::where('pid',0)->get();
                foreach ($datas as $data){
                    $data->sons;
                }
                $this->ziroom_cache_service->_cache_subway_area_data($key,$datas->toArray());
                return $datas->toArray();
                break;
            case 'parent':
                $res = [];
                $data = $class::find($id);
                $res['parent'] = $data->toArray();
                $res['sons'] = $data->sons->toArray();
                $this->ziroom_cache_service->_cache_subway_area_data($key,$res);
                return $res;
                break;
            case 'single':
                $data = $class::find($id)->toArray();
                $this->ziroom_cache_service->_cache_subway_area_data($key,$data);
                return $data;
                break;
        }


    }

    //获取分页列表
    public function getPageByUrl($url = '',$page_url = ''){
        $ql = $this->QueryList->get($url,[],$this->header);
        $data = $ql->find('#page > a')->texts();
        $res = [];
        foreach ($data as $datum){
            if (is_numeric($datum)){
                $res[] = $datum;
            }
        }
        if (empty($res)) return [];
        $pages = [];
        for ($i = min($res); $i <= max($res) ; $i++){
            $pages[] = $page_url . $i;
        }
        return $pages;
    }

    //获取自如页面list数据
    public function getListDataByPage($page_url = '')
    {
        $html = $this->QueryList->get($page_url,null,$this->header);

        $data = $html->rules([
            'title' => ['a.t1', 'text'],
            'url' => ['a.t1', 'href'],
            'thumb' => ['.clearfix .img a > img', '_webpsrc'],
            'housing_detection' => ['.clearfix .txt h4', 'text'],
            'housing_features' => ['.room_tags.clearfix', 'text']
        ])->query()->getData();

        $res = [];
        foreach ($data as $k => $datum) {
//            $husing_features = preg_replace('/\s/','',$datum['husing_features']);
            $housing_detection = _serialize_grab_filter_space_html($datum['housing_detection']);
            $housing_features = _serialize_grab_filter_space_html($datum['housing_features']);
            $res[$k]['housing_detection'] = $housing_detection;
            $res[$k]['housing_features'] = $housing_features;
            $res[$k] = array_merge($data[$k], $res[$k]);
        }
        return $res;
    }

    //获取详情页数据
    public function getZiroomDetails($url = ''){
        $html = $this->QueryList->get($url,null,$this->header);
        $image_list = $html->find('#lofslidecontent45 .lof-main-wapper')->find('img')->attrs('src');
//        $ellipsis = $html->find('.room_name .ellipsis')->text();
//        $ellipsis = array_filter(explode(' ',$ellipsis));
        $ellipsis = $this->_find_detail_room($html,'.room_name .ellipsis');
        $measure_area = $this->_find_detail_room($html,'.detail_room > li)',0);
        $orientation = $this->_find_detail_room($html,'.detail_room > li)',1);
        $house_type = $this->_find_detail_room($html,'.detail_room > li)',2);
        $floor = $this->_find_detail_room($html,'.detail_room > li)',3);
        $traffic = $this->_find_detail_room($html,'.detail_room > li)',4);
        $room_id = $html->find('#room_id')->val();
        $house_id = $html->find('#house_id')->val();
        $room_number = $this->_find_detail_room($html,'.fb');
        $housing_desc = str_replace('房源介绍：','',$html->find('.aboutRoom > p')->text());
        $housing_allocation = $html->find('.configuration > li')->texts();
        $lng = $html->find('#mapsearchText')->attr('data-lng');
        $lat = $html->find('#mapsearchText')->attr('data-lat');

        $price = $this->_get_price_by_img($url,$room_id,$house_id);

        $roommates = $html->rules([
            'sexs'  =>  ['.greatRoommate li','class'],
            'constellation' =>  ['.greatRoommate li .sign','text','',function($item){
                return preg_replace('[星座|…]','',$item);
            }],//星座
            'job'  =>  ['.greatRoommate li .ellipsis','text','',function($item){
                return preg_replace('[…]','',$item);
            }],
            'residence_time'    =>  ['.greatRoommate li .user_bottom p','text','',function($item){
                $item = preg_replace('[…]','',$item);
                $item = explode('-',$item);
                return $item;
            }]
        ])->query()->getData();

        $res = [
            'img_list'      =>  $image_list,//图集
            'ellipsis'      =>  $ellipsis,//区 街道
            'measure_area'  => $measure_area,//面积
            'orientation'   =>  $orientation,//朝向
            'house_type'    =>  $house_type,//户型
            'floor'         =>  $floor,
            'traffic'       =>  $traffic,
            'room_id'       =>  $room_id,
            'house_id'      =>  $house_id,
            'room_number'   =>  $room_number,
            'housing_desc'  =>  $housing_desc,
            'housing_allocation'    =>  $housing_allocation,
            'greatRoommate' =>  $roommates,
            'lng'           =>  $lng,
            'lat'           =>  $lat,
            'price'         =>  $price
        ];

        return $res;
    }

    //数据入库
    public function insertZiroomDataDB($insert_data = []){
        $roomRepository = new RoomRepository();
        $traffic = $this->_select_subway_from_traffic($insert_data['detail']['traffic']);
        $insert_data['traffics'] = $traffic;

        $data = [
            'z_room_id' =>  $insert_data['detail']['room_id'],
            'z_house_id' =>  $insert_data['detail']['house_id'],
            'name'      =>  $insert_data['parent']['title'],
            'month_price'   =>  $insert_data['detail']['price'] ?: 0,
            'measure_area'  =>  $insert_data['detail']['measure_area']['1'],
            'orientation'   =>  $insert_data['detail']['orientation']['1'],
            'house_type'    =>  $insert_data['detail']['house_type']['1'],
            'floor'         =>  $insert_data['detail']['floor']['1'],
            'traffic'       =>  $traffic['subways_desc'],
            'room_number'   =>  $insert_data['detail']['room_number']['1'],
            'room_number_slave' =>  $insert_data['detail']['room_number']['2'] ?? '',
            'room_nums'     =>  count($insert_data['detail']['greatRoommate']),
            'housing_allocation'    =>  json_encode($insert_data['detail']['housing_allocation']),
            'housing_desc'  =>  $insert_data['detail']['housing_desc'],
            'bathroom'      =>  in_array('独卫',$insert_data['parent']['housing_features']) ? '1':'0',
            'independent_balcony'   => in_array('独立阳台',$insert_data['parent']['housing_features']) ? '1':'0',
            'ten_minutes_underground'   =>  in_array('离地铁近',$insert_data['parent']['housing_features']) ? '1':'0',
            'housing_detection' =>  json_encode($insert_data['parent']['housing_detection']),
            'housing_features'  =>  json_encode($insert_data['parent']['housing_features'])
        ];

        //插入成功之后写一个观察者，当房源添加成功，去入住人信息表添加数据
        $room_id = $roomRepository->add_room($data,$insert_data);
        return $room_id;
    }
    //处理列表页数据
    public function handleZiroomList($list_url,$page_url,$room_type = '0'){

        $list_pages = $this->getPageByUrl($list_url,$page_url);
        foreach ($list_pages as $list_page) {
            $page_list_data = $this->getListDataByPage($list_page);
            foreach ($page_list_data as $page_list_datum){
                $insert['parent'] = $page_list_datum;
                $detail = $this->getZiroomDetails($page_list_datum['url']);

                //先查询，有就更新
                $ziroomGrabUrl = \Modules\Ziroom\Entities\ZiroomGrabUrl::where([
                    ['url','=',$page_list_datum['url']],
                ])->first();

                if (!$ziroomGrabUrl) $ziroomGrabUrl = new \Modules\Ziroom\Entities\ZiroomGrabUrl();
                $ziroomGrabUrl->url = $page_list_datum['url'];
                $ziroomGrabUrl->save();

                $insert['detail'] = $detail;
                $insert['room_type'] = $room_type;//房屋类型
                $insert['luxury_house'] = preg_match('/豪宅/',$page_list_datum['title']) ? '1' : '0';//豪宅
                //添加到队列
                \Modules\Ziroom\Jobs\ZiroomHandleJobs::dispatch(
                    $insert
                )->onConnection('redis_grab')->onQueue('queue_grabs');
            }
        }
    }

    //地铁查询
    private function _select_subway_from_traffic($traffics){
        if (empty($traffics)) return [];

        $subways = $this->findZiroomAreaOrSubwayDbData(\Modules\Ziroom\Entities\Subway::class);
        $subways_array = [];
        $subways_line = [];
        $subways_desc = [];

        foreach($traffics as $traffic){
            foreach ($subways as $subway){
                if (strstr($traffic,$subway['name'])){
                    $subways_line[] = $subway['id'];
                    $subways_desc[] = $traffic;
                }
                foreach ($subway['sons'] as $subway_sons){
                    if (strstr($traffic,$subway_sons['name'])){
                        $subways_array[] = $subway_sons['id'];
                        $subways_desc[] = $traffic;
                    }
                }
            }
        }
        return [
            'subway_pid'  =>    implode(',',array_unique($subways_line)),
            'subway_id'   =>    implode(',',array_unique($subways_array)),
            'subways_desc' =>   json_encode(array_values(array_unique($subways_desc)))
        ];
    }

    //下载价格图片
    private function _get_price_by_img($url = '',$room_id = 0,$house_id = 0){

        $data = fn_curl_get(config('ziroom.Grab_Urls.room_info_api'),['id'=>$room_id,'house_id'=>$house_id]);
        $res = is_null(json_decode($data)) ? [] : json_decode($data,TRUE);
        if ($res['code'] !== '200' && empty($res['data']['price'])) return '';

        $price_img = fn_curl_get_http_or_https().$res['data']['price']['1'];
        $price_pos = $res['data']['price']['2'];

        $storage = new FileSystemService();
        $img_path = $storage->_down_file_http($price_img);

        $python_service = new PythonServices();
        $price_img_font = $python_service->_get_image_font($img_path);
        $price = '';
        if (!empty($price_pos) && !empty($price_img_font)){
            foreach ($price_pos as $value){
                $price .= $price_img_font[$value];
            }
        }
        return $price;
    }

    //查询面积、朝向、户型等等
    private function _find_detail_room($html,$class = '',$eq = ''){

        if ($eq !== '')
            $data = $html->find($class)->eq($eq)->text();
        else
            $data = $html->find($class)->text();

        $data = DeleteHtml($data);
        $data = array_values(array_filter(explode(' ',$data))) ;

        if ($class === '.fb'){
            if (!empty($data['1'])){
                $explode = explode('_',$data['1']);
                if (!empty($explode['0']) && !empty($explode['1'])){
                    $data['1'] = $explode['0'] ?? $explode['0'];
                    $data['2'] = $explode['1'] ?? $explode['1'];
                }
            }
        }
        return $data;
    }

    //查询首页地铁和区域数据
    private function find_area_subway_data($url = '',$class = ''){

        $ql = $this->QueryList::get($url,[],$this->header);
        $data = $ql->find($class)->texts();
        return $data;
    }

    //处理首页area和subway数据的方法
    private function handle_area_data($data = ''){
        if ($data == '') return [];
        $res = [];
        $area_p_key = '';
        foreach ($data as $k => $v) {
            if ($v == '全部'){
                $res['parent'][] = $data[$k-1];
            }
        }
        foreach ($data as $v) {
            if (in_array($v,$res['parent'])){
                $area_p_key = $v;
            }

            if ($v != '全部' && !in_array($v,$res['parent'])){

                $res['son'][$area_p_key][] = $v;
            }
        }
        return $res;
    }

}