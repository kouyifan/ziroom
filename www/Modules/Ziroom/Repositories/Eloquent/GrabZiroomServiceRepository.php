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
                "Host"  =>  "www.ziroom.com",
                'Referer' => 'http://www.ziroom.com/',
                "User-Agent"    => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36",
                "Accept"    =>"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
                "Accept-Encoding"   => "gzip",
                "Accept-Language"   => "zh-CN,zh;q=0.8",
                "Connection"    =>  "keep-alive",
                "Upgrade-Insecure-Requests" =>  '1',
                'Cookie' => "CURRENT_CITY_CODE=110000; gr_user_id=e2b020c7-e6d0-4d44-9617-503d727e548b; CURRENT_CITY_NAME=%E5%8C%97%E4%BA%AC; PHPSESSID=978g4liu1pais7ben09gsk9hs2; BJ_nlist=%7B%2262041697%22%3A%7B%22id%22%3A%2262041697%22%2C%22sell_price%22%3A2160%2C%22title%22%3A%22%5Cu5927%5Cu5174%5Cu9ec4%5Cu67514%5Cu53f7%5Cu7ebf%5Cu67a3%5Cu56ed%5Cu5f69%5Cu8679%5Cu65b0%5Cu57ce3%5Cu5c45%5Cu5ba4-%5Cu5357%5Cu5367%22%2C%22add_time%22%3A1554043364%2C%22usage_area%22%3A11.35%2C%22floor%22%3A%2213%22%2C%22floor_total%22%3A%2218%22%2C%22room_photo%22%3A%22g2m1%5C%2FM00%5C%2FD1%5C%2F36%5C%2FChAFB1xSw6aAVjmiABph9OGr8ls714.jpg%22%2C%22city_name%22%3A%22%5Cu5317%5Cu4eac%22%7D%2C%2262083291%22%3A%7B%22id%22%3A%2262083291%22%2C%22sell_price%22%3A2060%2C%22title%22%3A%22%5Cu987a%5Cu4e49%5Cu987a%5Cu4e49%5Cu57ce15%5Cu53f7%5Cu7ebf%5Cu77f3%5Cu95e8%5Cu897f%5Cu8f9b%5Cu5357%5Cu533a3%5Cu5c45%5Cu5ba4-%5Cu5357%5Cu5367%22%2C%22add_time%22%3A1554043357%2C%22usage_area%22%3A11.4%2C%22floor%22%3A%226%22%2C%22floor_total%22%3A%226%22%2C%22room_photo%22%3A%22g2m2%5C%2FM00%5C%2F08%5C%2FFC%5C%2FCtgFCVyCNm-AC2OuAANhXy6rmbU32.jpeg%22%2C%22city_name%22%3A%22%5Cu5317%5Cu4eac%22%7D%2C%2261486040%22%3A%7B%22id%22%3A%2261486040%22%2C%22sell_price%22%3A2330%2C%22title%22%3A%22%5Cu6d77%5Cu6dc0%5Cu6d77%5Cu6dc0%5Cu5317%5Cu90e8%5Cu65b0%5Cu533a16%5Cu53f7%5Cu7ebf%5Cu5317%5Cu5b89%5Cu6cb3%5Cu5b89%5Cu6cb3%5Cu5bb6%5Cu56ed3%5Cu5c45%5Cu5ba4-%5Cu5357%5Cu5367%22%2C%22add_time%22%3A1553910337%2C%22usage_area%22%3A12%2C%22floor%22%3A%223%22%2C%22floor_total%22%3A%225%22%2C%22room_photo%22%3A%2282e6402b-3aa3-4df0-804f-3afea96b50f3.jpg%22%2C%22city_name%22%3A%22%5Cu5317%5Cu4eac%22%7D%2C%2262083289%22%3A%7B%22id%22%3A%2262083289%22%2C%22sell_price%22%3A2090%2C%22title%22%3A%22%5Cu987a%5Cu4e49%5Cu987a%5Cu4e49%5Cu57ce15%5Cu53f7%5Cu7ebf%5Cu77f3%5Cu95e8%5Cu897f%5Cu8f9b%5Cu5357%5Cu533a3%5Cu5c45%5Cu5ba4-%5Cu5357%5Cu5367%22%2C%22add_time%22%3A1553850051%2C%22usage_area%22%3A11.3%2C%22floor%22%3A%226%22%2C%22floor_total%22%3A%226%22%2C%22room_photo%22%3A%22g2m2%5C%2FM00%5C%2F08%5C%2FFC%5C%2FCtgFCVyCNk6AeO-4AAMHAx1N4wU92.jpeg%22%2C%22city_name%22%3A%22%5Cu5317%5Cu4eac%22%7D%2C%2260505201%22%3A%7B%22id%22%3A%2260505201%22%2C%22sell_price%22%3A2260%2C%22title%22%3A%22%5Cu987a%5Cu4e49%5Cu987a%5Cu4e49%5Cu57ce15%5Cu53f7%5Cu7ebf%5Cu77f3%5Cu95e8%5Cu897f%5Cu8f9b%5Cu5357%5Cu533a3%5Cu5c45%5Cu5ba4-%5Cu5357%5Cu5367%22%2C%22add_time%22%3A1553850048%2C%22usage_area%22%3A21%2C%22floor%22%3A%225%22%2C%22floor_total%22%3A%226%22%2C%22room_photo%22%3A%22g2m2%5C%2FM00%5C%2F68%5C%2FEA%5C%2FCtgFCFyZ5DqADM2LACPL0Fhq5yI511.jpg%22%2C%22city_name%22%3A%22%5Cu5317%5Cu4eac%22%7D%2C%2261986565%22%3A%7B%22id%22%3A%2261986565%22%2C%22sell_price%22%3A2290%2C%22title%22%3A%22%5Cu6d77%5Cu6dc0%5Cu6d77%5Cu6dc0%5Cu5317%5Cu90e8%5Cu65b0%5Cu533a16%5Cu53f7%5Cu7ebf%5Cu5317%5Cu5b89%5Cu6cb3%5Cu5b89%5Cu6cb3%5Cu5bb6%5Cu56ed3%5Cu5c45%5Cu5ba4-%5Cu5357%5Cu5367%22%2C%22add_time%22%3A1553849962%2C%22usage_area%22%3A13%2C%22floor%22%3A%224%22%2C%22floor_total%22%3A%2214%22%2C%22room_photo%22%3A%22g2m1%5C%2FM00%5C%2F70%5C%2F0D%5C%2FChAFB1w120KANKorAAw-UFtWvHM013.JPG%22%2C%22city_name%22%3A%22%5Cu5317%5Cu4eac%22%7D%2C%2262046352%22%3A%7B%22id%22%3A%2262046352%22%2C%22sell_price%22%3A2890%2C%22title%22%3A%22%5Cu6d77%5Cu6dc0%5Cu897f%5Cu4e09%5Cu65d78%5Cu53f7%5Cu7ebf%5Cu897f%5Cu5c0f%5Cu53e3%5Cu6587%5Cu665f%5Cu5bb6%5Cu56ed3%5Cu5c45%5Cu5ba4-%5Cu5317%5Cu5367%22%2C%22add_time%22%3A1553841711%2C%22usage_area%22%3A15.6%2C%22floor%22%3A%227%22%2C%22floor_total%22%3A%2221%22%2C%22room_photo%22%3A%22g2m1%5C%2FM00%5C%2F0C%5C%2F73%5C%2FChAFBlxwrtCAWOEWAAL6YvcvCKg817.jpg%22%2C%22city_name%22%3A%22%5Cu5317%5Cu4eac%22%7D%2C%2262038782%22%3A%7B%22id%22%3A%2262038782%22%2C%22sell_price%22%3A2190%2C%22title%22%3A%22%5Cu6d77%5Cu6dc0%5Cu6d77%5Cu6dc0%5Cu5317%5Cu90e8%5Cu65b0%5Cu533a16%5Cu53f7%5Cu7ebf%5Cu5317%5Cu5b89%5Cu6cb3%5Cu5b89%5Cu6cb3%5Cu5bb6%5Cu56ed3%5Cu5c45%5Cu5ba4-%5Cu5357%5Cu5367%22%2C%22add_time%22%3A1553841708%2C%22usage_area%22%3A11.1%2C%22floor%22%3A%223%22%2C%22floor_total%22%3A%2213%22%2C%22room_photo%22%3A%22g2m1%5C%2FM00%5C%2FCF%5C%2F49%5C%2FChAFBlxRcLyACMIhAAsW-8c2_t0840.JPG%22%2C%22city_name%22%3A%22%5Cu5317%5Cu4eac%22%7D%7D; mapType=%20; gr_session_id_8da2730aaedd7628=8669d1ac-8205-4e4f-b744-44df7fc57d38; gr_session_id_8da2730aaedd7628_8669d1ac-8205-4e4f-b744-44df7fc57d38=true; passport_token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1aWQiOiJkNGYwZTk2Zi02MTQ1LTQxN2QtODBlZi02MDdmNWE1OWQyODIiLCJ0eXBlIjoyLCJsZW5ndGgiOjEyMCwidG9rZW4iOiI0NzVlNjlmNi02MDNmLTRhY2EtOWE2ZC0xMzBhNjllYTZkMmUiLCJjcmVhdGVUaW1lIjoxNTU0OTk5MjQ0ODY3fQ.d5Yysaq6CB_FktjICrKkb9Nt5usYittQ1bK4c9T3ZbE"
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
            'measure_area'  =>  $insert_data['detail']['measure_area']['1'] ?? '',
            'orientation'   =>  $insert_data['detail']['orientation']['1'],
            'house_type'    =>  $insert_data['detail']['house_type']['1'],
            'floor'         =>  $insert_data['detail']['floor']['1'],
            'traffic'       =>  $traffic['subways_desc'] ?? '',
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
                if (!\Modules\Ziroom\Entities\Room::where('z_room_id',$detail['room_id'])->value('id')){
                    //添加到队列
                    \Modules\Ziroom\Jobs\ZiroomHandleJobs::dispatch(
                        $insert
                    )->onConnection('redis_grab')->onQueue('grabs');
                }

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