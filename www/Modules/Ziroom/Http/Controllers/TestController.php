<?php

namespace Modules\Ziroom\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Ziroom\Repositories\Contracts\GrabZiroomInterface;
use Modules\Ziroom\Jobs\ziroomGrabJobs;
use mikehaertl\shellcommand\Command;
use Modules\Ziroom\Entities\RoomsPerson;
use Modules\Ziroom\Jobs\ZiroomHandleJobs;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function test(GrabZiroomInterface $test)
    {
//        p(Rand_IP());die;
        $data = $test->findZiroomSubwayData();
        p($data);die;
//        \Mail::to('26745709@qq.com')->send(new \Modules\Ziroom\Emails\TestMail);

//        ZiroomHandleJobs::dispatch(['name'=>date('Y-m-d H:i:s')])->onConnection('redis_grab')->onQueue('queue_grabs');
//        $page_list_data = $test->getListDataByPage(config('ziroom.Grab_Urls.rent_sharing'));
//        if (!empty($page_list_data)){
//            foreach ($page_list_data as $value){
//
//                $detail = $test->getZiroomDetails('http://www.ziroom.com/z/vr/61600373.html');
//
//                $insert['parent'] = $value;
//                $insert['detail'] = $detail;
//                $insert['room_type'] = '0';//房屋类型
//                $insert['luxury_house'] = '0';//豪宅
//                $test->insertZiroomDataDB($insert);
//            }
//
//        }

    }

    public function test2(){

    }


}
