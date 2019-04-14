<?php
/**
 * Created by PhpStorm.
 * User: 技术主管寇一凡
 * Date: 2019/3/31
 * Time: 22:58
 */
namespace Modules\Ziroom\Repositories\Contracts;

interface RoomInterface {

    //create
    public function add_room();
    //select
    public function selectRoomData();
    //room detail
    public function getRoomDetail($id);

}

