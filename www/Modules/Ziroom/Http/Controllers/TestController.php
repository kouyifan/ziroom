<?php

namespace Modules\Ziroom\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Ziroom\Repositories\Contracts\GrabZiroomInterface;
use Modules\Ziroom\Jobs\ziroomGrabJobs;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function test(GrabZiroomInterface $test)
    {
        ziroomGrabJobs::dispatch(['test'    =>  '001'])->delay(now()->addMinutes(1));

    }


}
