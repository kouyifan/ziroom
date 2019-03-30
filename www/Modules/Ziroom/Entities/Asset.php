<?php

namespace Modules\Ziroom\Entities;

use Illuminate\Database\Eloquent\Model;

class asset extends Model
{
    protected $fillable = [
        'name','status','filename'
    ];

    protected $dispatchesEvents = [
        'created' => AssetCreated::class,
    ];
}
