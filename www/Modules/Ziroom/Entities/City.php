<?php

namespace Modules\Ziroom\Entities;

use Illuminate\Database\Eloquent\Model;

class city extends Model
{
    protected $table = 'citys';
    protected $fillable = [
        'city_name'
    ];
    public $timestamps = false;
}
