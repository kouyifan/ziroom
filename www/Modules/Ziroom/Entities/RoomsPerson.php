<?php

namespace Modules\Ziroom\Entities;

use Illuminate\Database\Eloquent\Model;

class RoomsPerson extends Model
{
    protected $fillable = [];
    protected $table = 'rooms_persons';
    protected $primaryKey = 'room_id';
}
