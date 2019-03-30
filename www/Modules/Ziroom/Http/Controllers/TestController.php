<?php

namespace Modules\Ziroom\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Ziroom\Repositories\Contracts\GrabZiroomInterface;
use Modules\Ziroom\Jobs\ziroomGrabJobs;
use mikehaertl\shellcommand\Command;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function test(GrabZiroomInterface $test)
    {

        $page_list_data = $test->getListDataByPage(config('ziroom.Grab_Urls.rent_sharing'));
        if (!empty($page_list_data)){
            foreach ($page_list_data as $value){

                $detail = $test->getZiroomDetails('http://www.ziroom.com/z/vr/61486040.html');
                $insert['parent'] = $value;
                $insert['detail'] = $detail;
                $test->insertZiroomDataDB($insert);
            }

        }

    }

    public function test2(){

    }


}
