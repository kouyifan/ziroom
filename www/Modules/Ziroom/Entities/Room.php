<?php

namespace Modules\Ziroom\Entities;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'z_room_id',
        'z_house_id',
        'name',
        'thumb',
        'img_list',
        'month_price',
        'measure_area',
        'orientation',
        'house_type',
        'floor',
        'traffic',
        'room_number',
        'room_number_slave',
        'room_nums',
        'housing_allocation',
        'housing_desc',
        'bathroom',
        'independent_balcony',
        'ten_minutes_underground',
        'housing_detection',
        'housing_features',
        'begin_time',
        'end_time'
    ];
}
