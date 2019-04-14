<?php

namespace Modules\Ziroom\Providers;

use Illuminate\Support\ServiceProvider;

class MyServiceProvider extends ServiceProvider
{
    public function boot(){
        //注册观察者，附件表
        \Modules\Ziroom\Entities\Asset::observe(\Modules\Ziroom\Observers\AssetObserver::class);
        //房源表
        \Modules\Ziroom\Entities\Room::observe(\Modules\Ziroom\Observers\RoomObserver::class);
        //房源抓取信息表
        \Modules\Ziroom\Entities\RoomGrabData::observe(\Modules\Ziroom\Observers\RoomGrabDataObserver::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //抓取数据
        $this->app->singleton('Modules\Ziroom\Repositories\Contracts\GrabZiroomInterface',function ($app){
            return new \Modules\Ziroom\Repositories\Eloquent\GrabZiroomServiceRepository();
        });
        //获取公共数据
        $this->app->singleton('Modules\Ziroom\Repositories\Contracts\GetCommonDataInterface',function ($app){
            return new \Modules\Ziroom\Repositories\Eloquent\GetCommonDataRepository();
        });
        //获取房源数据
        $this->app->singleton('Modules\Ziroom\Repositories\Contracts\RoomInterface',function ($app){
            return new \Modules\Ziroom\Repositories\Eloquent\RoomRepository();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
