<?php
/**
 * Created by PhpStorm.
 * User: 技术主管寇一凡
 * Date: 2019/3/22
 * Time: 17:27
 */

namespace Modules\Ziroom\Repositories\Eloquent;

use Modules\Ziroom\Repositories\Contracts\GetCommonDataInterface;
use \Modules\Ziroom\Services\ZiroomCacheServices as service;


class GetCommonDataRepository implements GetCommonDataInterface
{
    protected $ziroom_service;
    public function __construct()
    {
        $this->ziroom_service = new service;
    }

    public function get_web_nav()
    {
        // TODO: Implement get_web_nav() method.
        $cache = $this->ziroom_service->_cache_nav_data(config('ziroom.nav_cache'));
        if (!empty($cache)) return $cache;

        $data = \Modules\Ziroom\Entities\nav::orderBy('sort','asc')->get();
        $cache = $this->ziroom_service->_cache_nav_data(config('ziroom.nav_cache'),$data);
        return $data;
    }

}