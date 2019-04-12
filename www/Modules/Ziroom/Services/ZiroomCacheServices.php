<?php
/**
 * Created by PhpStorm.
 * User: 技术主管寇一凡
 * Date: 2019/3/21
 * Time: 23:49
 */
namespace Modules\Ziroom\Services;
use Illuminate\Support\Facades\Cache;

class ZiroomCacheServices{

    private function _cache_data_public($key = '', $data = [],$ttl = 60){
        return '';
        if(Cache::has($key)){
            return Cache::get($key);
        } else{
            if (!empty($data)){
                Cache::add($key,$data,$ttl);
            }
            return $data;
        }
    }

    //缓存地区和地铁
    public function _cache_subway_area_data($key = '',$data = []){
        return $this->_cache_data_public($key,$data,10);
    }

    //缓存导航数据
    public function _cache_nav_data($key = '',$data = []){
        return $this->_cache_data_public($key,$data,10);
    }

}
