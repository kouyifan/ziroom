<?php

namespace Modules\Ziroom\Entities;

use Illuminate\Database\Eloquent\Model;

class Subway extends Model
{
    protected $fillable = [];
    public $timestamps = false;

    protected $table = 'subways';

    public function sons(){
        return $this->hasMany('\Modules\Ziroom\Entities\subway','pid');
    }
}
