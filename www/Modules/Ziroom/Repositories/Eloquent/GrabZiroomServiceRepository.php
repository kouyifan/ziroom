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

class GrabZiroomServiceRepository implements GrabZiroomInterface{

    protected $url_config = [
        'home'  =>  'http://www.ziroom.com/z/nl/z3.html',
    ];
    protected $ziroom_cache_service;
    protected $QueryList;

    public function __construct()
    {
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
        $ql = $this->QueryList->get($url);
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

    //获取列表list数据
    public function getListDataByPage($page_url = ''){
        $html = $this->QueryList->get($page_url);

        $data = $html->rules([
                'title' => ['a.t1','text'],
                'url'   => ['a.t1','href'],
                'thumb'   => ['.clearfix .img a > img','_webpsrc'],
                'housing_detection'   =>  ['.clearfix .txt h4','text'],
                'housing_features' => ['.room_tags.clearfix','text']
            ])
            ->query()->getData();

        $res = [];
        foreach($data as $k => $datum){
//            $husing_features = preg_replace('/\s/','',$datum['husing_features']);
            $housing_detection = _serialize_grab_filter_space_html($datum['housing_detection']);
            $housing_features = _serialize_grab_filter_space_html($datum['housing_features']);
            $res[$k]['housing_detection'] = $housing_detection;
            $res[$k]['housing_features'] = $housing_features;
            $res[$k] = array_merge($data[$k],$res[$k]);
        }
//        p($res);



    }
    //获取详情页数据
    public function getZiroomDetails($url = ''){

    }



    //查询首页地铁和区域数据
    private function find_area_subway_data($url = '',$class = ''){

        $ql = $this->QueryList::get($url);
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