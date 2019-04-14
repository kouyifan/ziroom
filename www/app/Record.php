<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected $connection = 'stackoverflow2013';
    protected $table = 'Records';
    public $primaryKey = 'Id';

    protected $fillable = [
        'count','max_id'
    ];
}
