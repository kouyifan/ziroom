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

    protected  $room_model;
    protected $fileService;
    public function __construct()
    {
        $this->room_model = new Room();
        $this->fileService = new FileSystemService();
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
    public function selectRoomData($request = []){
        $room_type = 0;
        $area = 0;
        $subway = 0;
        $price = 0;
        $house_type = 0;
        $measure_area = 0;
        $housing_features = 0;
        $ten_minutes_underground = 0;

        $data = $this->room_model->with(['roomAsset'])->orderBy('id','desc')->paginate(20);

        foreach($data as $datum){

            $datum['thumb'] = isset($datum->roomAsset->file_path) ?
                $this->fileService->_get_file_url($datum->roomAsset->file_path,$datum->roomAsset->disk) : config('ziroom.default_room_img');

            $datum['housing_detection'] = is_null(json_decode($datum['housing_detection'])) ? '' : json_decode($datum['housing_detection'],TRUE);
            $datum['housing_features'] = is_null(json_decode($datum['housing_features'])) ? '' : json_decode($datum['housing_features'],TRUE);
            $datum['traffic'] = is_null(json_decode($datum['traffic'])) ? '' : json_decode($datum['traffic'],TRUE);
        }
//        p($data);die;
        return $data;
    }
    //获得房屋详情
    public function getRoomDetail($id = 0){

        $room = $this->room_model->find($id);

//        p($room);
        return $room;
    }

//[id] => 3064
//[z_room_id] => 62091883
//[z_house_id] => 60329714
//[name] => 友家 · 弘善家园3居室-南卧
//[thumb] => 29738
//[img_list] => 29739,29740,29741,29742,29743,29744,
//[month_price] => 0
//[measure_area] => 8.1
//[orientation] => 南
//[house_type] => 3室1厅
//[floor] => 8/28层
//[traffic] => ["\u8ddd14\u53f7\u7ebf\u5341\u91cc\u6cb3349\u7c73","\u8ddd10\u53f7\u7ebf\u5341\u91cc\u6cb3349\u7c73","\u8ddd10\u53f7\u7ebf\u6f58\u5bb6\u56ed879\u7c73","\u8ddd14\u53f7\u7ebf\u65b9\u5e841354\u7c73"]
//[room_number] => BJZRGY0819357216
//[room_number_slave] => 02
//[room_nums] => 3
//[housing_allocation] => ["\u5e8a","\u8863\u67dc","\u4e66\u684c","Wi-Fi","\u6d17\u8863\u673a","\u70ed\u6c34\u5668","\u7a7a\u8c03","\u5fae\u6ce2\u7089","\u667a\u80fd\u9501"]
//[housing_desc] => 冬暖夏凉，早上没有阳光直射打扰睡眠，业主用心维护的房源，附带自如家具，房间干净整洁，配有空调，衣柜，书桌，适合喜欢安静的你。
//[bathroom] => 0
//[independent_balcony] => 0
//[ten_minutes_underground] => 1
//[housing_detection] => ["\u9996\u6b21\u51fa\u79df","\u623f\u5c4b\u7a7a\u6c14\u8d28\u91cf\uff1a\u5df2\u68c0\u6d4b","\u7a7a\u7f6e\u5929\u6570\uff1a\u8d85\u8fc730\u5929"]
//[housing_features] => ["\u79bb\u5730\u94c1\u8fd1","\u72ec\u7acb\u4f9b\u6696","\u53cb\u5bb64.0","\u5e03\u4e01"]
//[created_at] => 2019-04-12 22:41:03
//[updated_at] => 2019-04-12 22:41:03
}