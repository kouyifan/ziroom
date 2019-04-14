<?php

namespace Modules\Ziroom\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Ziroom\Repositories\Contracts\RoomInterface;
use Modules\Ziroom\Services\FileSystemService;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request,$id,RoomInterface $room)
    {
        $room = $room->getRoomDetail($id);

        $fileService = new FileSystemService();
        $room->img_lists = $fileService->get_room_img_list($room->img_list);

        return view('ziroom::room.index',compact('room'));
    }

}
