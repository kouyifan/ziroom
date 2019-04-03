<?php

namespace Modules\Ziroom\Entities;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'citys';
    protected $fillable = [
        'city_name'
    ];
    public $timestamps = false;
}
