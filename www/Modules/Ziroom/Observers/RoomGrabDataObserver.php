<?php
/**
 * Created by PhpStorm.
 * User: 技术主管寇一凡
 * Date: 2019/3/30
 * Time: 21:59
 */
namespace Modules\Ziroom\Observers;

use Modules\Ziroom\Entities\RoomGrabData;
use Modules\Ziroom\Entities\RoomsPerson;

class RoomGrabDataObserver
{
    /**
     * 监听创建用户事件.
     *
     * @param
     * @return void
     */
    public function created(RoomGrabData $grab)
    {
        $this->__add_room_person($grab);
    }

    public function updated(RoomGrabData $grab){
        $this->__add_room_person($grab);
    }
    //添加入住人信息
    private function __add_room_person($grab){
        $roomGrabData = is_null(json_decode($grab->data)) ? '' : json_decode($grab->data,TRUE);

        if (!empty($roomGrabData['detail']['greatRoommate'])){
            $number = 0;
            foreach($roomGrabData['detail']['greatRoommate'] as $k => $value){
                $number += 1;
                $number_str = '0'.$number;
                //先查询，有就更新
                $roomPreson = RoomsPerson::where([
                    ['room_id','=',$grab->room_id],
                    ['room_slave','=',$number_str]
                ])->first();

                if (!$roomPreson) $roomPreson = new RoomsPerson;

                foreach(config('ziroom.room_slave_sex') as $config){
                    if (strstr($value['sexs'],$config)){
                        $roomPreson->sex = $config;
                    }
                }
                $roomPreson->room_id = $grab->room_id;
                $roomPreson->room_slave = $number_str;
                $roomPreson->job = str_replace('...','',$value['job']);
                $roomPreson->constellation = str_replace('...','',$value['constellation']);//星座
                if (!empty($value['residence_time']['0'])){
                    $begin_time = str_replace('/','-',$value['residence_time']['0']);
                    $roomPreson->begin_time = date('Y-m-d H:i:s',strtotime($begin_time));
                }

                if (!empty($value['residence_time']['1'])){
                    $end_time = str_replace('/','-',$value['residence_time']['1']);
                    $roomPreson->end_time = date('Y-m-d H:i:s',strtotime($end_time));
                }

                $roomPreson->save();
            }
        }
    }


}