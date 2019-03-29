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
use phpDocumentor\Reflection\Types\This;
use \Modules\Ziroom\Services\PythonServices;

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
        $python_service = new PythonServices();
        $price = $python_service->_get_image_font();
        p($price);die;
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

        p($res);die;
    }

    //查询面积、朝向、户型等等
    private function _find_detail_room($html,$class = '',$eq = ''){

        if ($eq !== '')
            $data = $html->find($class)->eq($eq)->text();
        else
            $data = $html->find($class)->text();
        $data = DeleteHtml($data);
        $data = array_values(array_filter(explode(' ',$data))) ;
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