<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
class TestController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function test(){


    }

    public function test_db(){
        $res = app('db')->select('select * from users limit 10');
        $res =  DB::table('users')->get();
        p($res);
    }

    //
}
