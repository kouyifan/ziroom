<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Tymon\JWTAuth\JWTAuth;
use Lcobucci\JWT\Builder;

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

    public function get_token(Request $request)
    {

        $this->validate($request, [
            'app_id' => 'required',
            'app_sercet' => 'required'
        ]);

        $jwtBuilder = new Builder();
        //设置加密对象
        $signer = new Sha256();

        //设置签发者
        $jwtBuilder->setIssuer('xx9090950@gmail.com');
        //设置签发时间
        $jwtBuilder->setIssuedAt(time());
        //设置当前时间不能早于设置时间
        $jwtBuilder->setNotBefore(time() + 10);
        //设置过期时间
        $jwtBuilder->setExpiration(time() + 3600);
        //设置uid
        $jwtBuilder->set('app_id', $request['app_id']);
        //设置username
        $jwtBuilder->set('app_sercet', $request['app_sercet']);
        //使用算法签名
        $jwtBuilder->sign($signer, env('JWT_SECRET'));
        //调用获取token方法
        $token = $jwtBuilder->getToken();
        return $token;


//        $jwt = new JWTAuth('app');
//        die;
//        if (! $token = $jwt->attempt($request)) {
//            return response()->json(['appid not found'], 404);
//        }

        return response()->json(compact('token'));
    }

    public function test_db()
    {
        $res = app('db')->select('select * from users limit 10');
        $res = DB::table('users')->get();
        return $res;
    }

    //
}
