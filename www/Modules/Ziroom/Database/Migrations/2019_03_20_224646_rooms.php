<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Rooms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->bigInteger('z_room_id')->default(0)->comment('自如room ID')->index();
            $table->bigInteger('z_house_id')->default(0)->comment('自如house ID')->index();
            //房屋名称
            $table->string('name',50)->default('')->comment('房屋名称');
            //缩略图
            $table->bigInteger('thumb')->default(0)->comment('缩略图');
            //图集
            $table->string('img_list',200)->default('')->comment('图集');
            //价格
            $table->integer('month_price')->default(0)->comment('每月价格');
            //面积
            $table->string('measure_area',10)->default('')->comment('面积');
            //朝向
            $table->string('orientation',10)->default('')->comment('朝向');
            //户型
            $table->string('house_type',20)->default('')->comment('户型');
            //楼层
            $table->string('floor',20)->default('')->comment('楼层');
            //交通
            $table->string('traffic',50)->default('')->comment('交通');
            //房源编号
            $table->string('room_number',50)->default('')->comment('房源编号');
            //房源编号slave
            $table->string('room_number_slave',10)->default('')->comment('房源编号slave,户型，第几间卧室');
            //房间数量
            $table->tinyInteger('room_nums')->default(0)->comment('房间数量');
            //房屋配置
            $table->string('housing_allocation',200)->default('')->comment('房屋配置');
            //房源介绍
            $table->string('housing_desc',200)->default('')->comment('房源介绍');
            //独卫
            $table->tinyInteger('bathroom')->default(0)->comment('独卫');
            //独立阳台
            $table->tinyInteger('independent_balcony')->default(0)->comment('独立阳台');
            //地铁十分钟
            $table->tinyInteger('ten_minutes_underground')->default(0)->comment('地铁十分钟');
            //房屋检测
            $table->string('housing_detection',200)->default('')->comment('房屋检测');
            //房屋特色
            $table->string('housing_features',200)->default('')->comment('房屋特色');
            //入住开始
            $table->date('begin_time')->comment('入住时间');
            //结束时间
            $table->date('end_time')->comment('结束时间');

            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
        \DB::statement("ALTER TABLE `rooms` comment '房屋表'");
        //房屋附表
        Schema::create('rooms_slaves', function (Blueprint $table) {
            $table->bigInteger('room_id')->default(0)->comment('房屋ID')->index();
            //省
            $table->integer('province')->default(0)->comment('省');
            //市
            $table->integer('city')->default(0)->comment('市');
            //区
            $table->integer('area')->default(0)->comment('区');
            //街道
            $table->integer('street')->default(0)->comment('街道');
            //房源类型
            $table->tinyInteger('room_type')->default(0)->comment('0=合租，1=整租，2=直租');
            //是否豪宅
            $table->tinyInteger('luxury_house')->default(0)->comment('豪宅');
            //纬度
            $table->double('latitude',10,6)->default(0)->comment('纬度');
            //精度
            $table->double('longitude',10,6)->default(0)->comment('精度');
            //地铁线路
            $table->string('subway_pid')->default('')->comment('地铁线路');
            //地铁线路子字段
            $table->string('subway_id')->default('')->comment('地铁子ID');
        });
        \DB::statement("ALTER TABLE `rooms_slaves` comment '房屋附表'");
        //房屋入住人信息
        Schema::create('rooms_persons', function (Blueprint $table) {
            $table->bigInteger('room_id')->default(0)->comment('房屋ID')->index();
            //性别
            $table->tinyInteger('sex')->default(0)->comment('0=boy,1=girl');
            //职业
            $table->string('job',20)->default('')->comment('职业');
            //星座
            $table->string('constellation',10)->default('')->comment('星座');
            //入住开始
            $table->date('begin_time')->comment('入住时间');
            //结束时间
            $table->date('end_time')->comment('结束时间');
        });
        \DB::statement("ALTER TABLE `rooms_persons` comment '房屋入住人信息'");
        //地铁表
        Schema::create('subways', function (Blueprint $table) {
            $table->increments('id')->comment('地铁ID');
            $table->integer('pid')->default(0)->comment('父ID');
            $table->integer('city_id')->default(0);
            $table->string('name')->default('')->comment('地铁名称');
            //
        });
        \DB::statement("ALTER TABLE `subways` comment '地铁表'");
        //city
        Schema::create('citys', function (Blueprint $table) {
            $table->increments('id')->comment('city');
            $table->string('city_name',25)->default('')->comment('name');
            //
        });
        \DB::statement("ALTER TABLE `citys` comment 'city'");
        //area
        Schema::create('areas', function (Blueprint $table) {
            $table->increments('id')->comment('area');
            $table->integer('pid')->default(0)->comment('父ID');
            $table->integer('city_id')->default(0);
            $table->string('name')->default('')->comment('name');
            //
        });
        \DB::statement("ALTER TABLE `areas` comment 'area'");
        //nav
        Schema::create('navs',function (Blueprint $table){

            $table->increments('id')->comment('id');
            $table->string('name',20)->default('');
            $table->string('url',50)->default('');
            $table->smallInteger('sort')->default(0);
            $table->dateTime('created_at');
            $table->dateTime('updated_at');

        });

        //file
        Schema::create('assets',function (Blueprint $table){

            $table->bigIncrements('id')->comment('id');
            $table->string('name',30)->default('');
            $table->bigInteger('filesize')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->string('file_key',64)->default('');
            $table->string('filename',150)->default('');
            $table->string('file_hash',150)->default('');
            $table->string('file_suffix',10)->default('');
            $table->tinyInteger('file_type')->default(0);
            $table->dateTime('created_at');
            $table->dateTime('updated_at');

        });

        Schema::create('test',function (Blueprint $table){

            $table->increments('id')->comment('id');
            $table->string('test',20)->default('');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('rooms')) {
            Schema::drop('rooms');
            Schema::drop('rooms_slaves');
            Schema::drop('rooms_persons');
            Schema::drop('subways');
            Schema::drop('citys');
            Schema::drop('areas');
            Schema::drop('navs');
            Schema::drop('test');
        }
    }
}
