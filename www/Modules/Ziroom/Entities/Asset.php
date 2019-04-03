<?php

namespace Modules\Ziroom\Entities;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
        'name','file_path','disk'
    ];


}
