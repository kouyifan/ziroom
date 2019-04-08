<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller{

    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;

    }



    //登录
    public function postLogin(Request $request)
    {
        if (! $token = $this->jwt->attempt($request->only('email', 'password'))) {
            return response()->json(['user_not_found'], 404);
        }

        return response()->json(compact('token'));
    }

    //获得用户信息
    public function me(Request $request){

        return response()->json($user = $this->jwt->user());
    }
}