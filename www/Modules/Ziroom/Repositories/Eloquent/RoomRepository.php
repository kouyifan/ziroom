<?php
/**
 * Created by PhpStorm.
 * User: 技术主管寇一凡
 * Date: 2019/3/31
 * Time: 23:00
 */
namespace Modules\Ziroom\Repositories\Eloquent;
use Modules\Ziroom\Repositories\Contracts\RoomInterface;
use Modules\Ziroom\Entities\Room;
use Modules\Ziroom\Entities\RoomsSlave;
use Modules\Ziroom\Services\FileSystemService;

class RoomRepository implements RoomInterface{

//    protected  $room_model;
    public function __construct()
    {
//        $this->room_model = new Room();
    }

    public function add_room($data = [],$insert_data = []){

        $is_have = \Modules\Ziroom\Entities\Room::where('z_room_id',$insert_data['detail']['room_id'])->value('id');
        if ($is_have) return 0;

        $FileService = new FileSystemService();
        //处理图片
        $data['thumb'] = $FileService->handle_thumb($insert_data['parent']['thumb']);
        $data['img_list'] = $FileService->handle_img_list($insert_data['detail']['img_list']);
        $room = Room::create($data);
        if ($room->id){
            //添加入住人信息
            $roomGrabDataModel = new \Modules\Ziroom\Entities\roomGrabData();
            $roomGrabDataModel->room_id = $room->id;
            $roomGrabDataModel->data = json_encode($insert_data);
            $roomGrabDataModel->save();
            //添加房源附表信息，rooms_slaves
            $area = $insert_data['detail']['ellipsis']['0'] ?? 0;
            $area = \Modules\Ziroom\Entities\Area::where('name',$area)->value('id');
            $street = $insert_data['detail']['ellipsis']['1'] ?? 0;
            $street = \Modules\Ziroom\Entities\Area::where('name',$street)->value('id');

            $roomSlaveModel = new \Modules\Ziroom\Entities\RoomsSlave();
            $roomSlaveModel->room_id = $room->id;
            $roomSlaveModel->city = '1';
            $roomSlaveModel->area = $area;
            $roomSlaveModel->street = $street;
            $roomSlaveModel->room_type = $insert_data['room_type'] ?? '0';
            $roomSlaveModel->luxury_house = $insert_data['luxury_house'] ?? '0';
            $roomSlaveModel->latitude = $insert_data['detail']['lat'] ?? '';
            $roomSlaveModel->longitude = $insert_data['detail']['lng'] ?? '';
            $roomSlaveModel->subway_pid = $insert_data['traffics']['subway_pid'] ?? '';
            $roomSlaveModel->subway_id = $insert_data['traffics']['subway_id'] ?? '';
            $roomSlaveModel->save();
        }

        return $room->id;
    }

    //获取自如数据
    public function selectRoomData(){
        $room_type = 0;
        $area = 0;
        $subway = 0;
        $price = 0;
        $house_type = 0;
        $measure_area = 0;
        $housing_features = 0;
        $ten_minutes_underground = 0;




    }

}