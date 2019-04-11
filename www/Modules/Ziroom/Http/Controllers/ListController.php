<?php

namespace Modules\Ziroom\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Ziroom\Repositories\Contracts\GrabZiroomInterface;
class ListController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(GrabZiroomInterface $grabZiroom)
    {
        $area_data = $grabZiroom->findZiroomAreaData();
        $subway_data = $grabZiroom->findZiroomSubwayData();

        return view('ziroom::list.index',compact('area_data','subway_data'));
    }


}
