<?php
/**
 * Created by PhpStorm.
 * User: 技术主管寇一凡
 * Date: 2019/3/30
 * Time: 21:59
 */
namespace Modules\Ziroom\Observers;

use Modules\Ziroom\Entities\Room;
use Modules\Ziroom\Entities\RoomsPerson;
use Modules\Ziroom\Entities\RoomGrabData;

class RoomObserver
{
    /**
     * 监听创建用户事件.
     *
     * @param
     * @return void
     */
    public function created(Room $room)
    {

    }


}