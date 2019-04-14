<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    //
    protected $connection = 'stackoverflow2013';
    protected $table = 'Posts';
    public $primaryKey = 'Id';
    public $timestamps = false;

}
