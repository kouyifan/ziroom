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
//        ziroomGrabJobs::dispatch(['test'    =>  '001'])->delay(now()->addMinutes(1));
//        $data = $test->findZiroomAreaOrSubwayDbData();
//        $data = $test->getPageByUrl(config('ziroom.Grab_Urls.rent_sharing'),config('ziroom.Grab_Urls.rent_sharing_page'));
        $data = $test->getListDataByPage(config('ziroom.Grab_Urls.rent_sharing'));
        $command = new Command('php --version');
        if ($command->execute()) {
            p($command->getOutput());
        } else {
            echo $command->getError();
            $exitCode = $command->getExitCode();

        }
    }


}
