<?php

namespace Modules\Ziroom\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;


class BlogsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $blog = factory(\Modules\Ziroom\Entities\blogs::class,200)->create();
        \Modules\Ziroom\Entities\blogs::create([
            'title' => '测试文章',
            'content'   =>  '你好呀，我是你的朋友，我的名字叫小李，他的名字叫小吴，今天天气好晴朗，我数学考了90分',
            'author'    =>   '小寇',
            ]
        );
        // $this->call("OthersTableSeeder");
    }
}
