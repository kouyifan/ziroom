<?php

namespace Modules\Ziroom\Entities;

use Illuminate\Database\Eloquent\Model;

class Nav extends Model
{
    protected $fillable = [
        'name','sort','url'
    ];
}
