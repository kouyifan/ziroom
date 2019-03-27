<?php

namespace Modules\Ziroom\Entities;

use Illuminate\Database\Eloquent\Model;

class nav extends Model
{
    protected $fillable = [
        'name','sort','url'
    ];
}
