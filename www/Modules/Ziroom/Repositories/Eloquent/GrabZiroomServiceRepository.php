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

class GrabZiroomServiceRepository implements GrabZiroomInterface{

    protected $config = [
        'home'  =>  'http://www.ziroom.com/z/nl/z3.html'
    ];
    protected $ziroom_cache_service;

    public function __construct()
    {
        $this->ziroom_cache_service = new ZiroomCacheServices;
    }
    //查询区域数据
    public function findZiroomAreaData()
    {
        $data = $this->find_area_subway_data($this->config['home'],'.clearfix.filterList:eq(0)>li .tag a');
        return $this->handle_area_data($data);
    }
    //查询地铁数据
    public function findZiroomSubwayData()
    {
        $data = $this->find_area_subway_data($this->config['home'],'.clearfix.filterList:eq(1)>li .tag a');
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

    //查询首页地铁和区域数据
    private function find_area_subway_data($url = '',$class = ''){

        $ql = \QL\QueryList::get($url);
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