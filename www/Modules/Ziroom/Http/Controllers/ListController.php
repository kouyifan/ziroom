<?php

namespace Modules\Ziroom\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Ziroom\Repositories\Contracts\GrabZiroomInterface;
use Modules\Ziroom\Repositories\Contracts\RoomInterface;

class ListController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request,GrabZiroomInterface $grabZiroom,RoomInterface $room)
    {
        $area_data = $grabZiroom->findZiroomAreaOrSubwayDbData(\Modules\Ziroom\Entities\Area::class);
        $subway_data = $grabZiroom->findZiroomAreaOrSubwayDbData(\Modules\Ziroom\Entities\Subway::class);
        //获得房源数据
        $rooms = $room->selectRoomData();

        return view('ziroom::list.index',compact('area_data','subway_data','rooms'));
    }


}
