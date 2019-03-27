<?php

namespace Modules\Ziroom\Entities;

use Illuminate\Database\Eloquent\Model;

class area extends Model
{
    protected $fillable = [
        'city_id','area_name','area_pid'
    ];
    public $timestamps = false;

    public function sons(){
        return $this->hasMany('\Modules\Ziroom\Entities\area','pid');
    }
}
