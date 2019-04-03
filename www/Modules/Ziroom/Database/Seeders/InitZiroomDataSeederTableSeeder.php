<?php

namespace Modules\Ziroom\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class InitZiroomDataSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $grab = new \Modules\Ziroom\Repositories\Eloquent\GrabZiroomServiceRepository();
        Model::unguard();
        //增加默认城市北京
        $city = \Modules\Ziroom\Entities\city::create([
                'city_name'  =>  '北京'
            ]
        );
        //area
        $this->add_area_data(\Modules\Ziroom\Entities\area::class,$grab->findZiroomAreaData(),$city->id);
        //subway
        $this->add_area_data(\Modules\Ziroom\Entities\subway::class,$grab->findZiroomSubwayData(),$city->id);
        //nav
        $this->_add_nav_data();
    }

    private function _add_nav_data(){
        $insert = [
            [
                'name'  =>  '首页',
                'url'   =>  '/',
                'sort'  =>  '1'
            ],
            [
                'name'  =>  '租房',
                'url'   =>  'list',
                'sort'  =>  '2'
            ]
        ];
        foreach ($insert as $v){
            \Modules\Ziroom\Entities\nav::create($v);
        }
    }

    //添加area数据
    private function add_area_data($obj = null,$data = [],$cityid = 0){
        if ($data['parent']){
            foreach ($data['parent'] as $v){
                $area_parent = $obj::create([
                    'city_id'   =>  $cityid,
                    'name' =>  $v
                ]);
                if ($data['son'][$v]){
                    foreach ($data['son'][$v] as $v2){
                        $obj::create([
                            'city_id'   =>  $cityid,
                            'name' =>  $v2,
                            'pid'  =>  $area_parent->id
                        ]);
                    }
                }
            }
        }
    }

}
