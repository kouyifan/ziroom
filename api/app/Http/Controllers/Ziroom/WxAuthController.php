<?php
/**
 * Created by PhpStorm.
 * User: 技术主管寇一凡
 * Date: 2019/4/7
 * Time: 11:02
 */
namespace App\Http\Controllers\Ziroom;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\HttpService;

class WxAuthController extends Controller{

    public function __construct()
    {
        //
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     * api:https://api.weixin.qq.com/sns/jscode2session?appid=APPID&secret=SECRET&js_code=JSCODE&grant_type=authorization_code
     * appid	是	小程序唯一标识
     * secret	是	小程序的 app secret
     * js_code	是	登录时获取的 code
     * grant_type	是	填写为 authorization_code
     * res: openid	用户唯一标识,session_key	会话密钥, if 开放平台 true return add unionid	用户在开放平台的唯一标识符
     */
    public function get_openid(Request $request,HttpService $httpservice){
        $param = $this->validate($request,[
            'appid' =>  'required',
            'secret'=>  'required',
            'code'  =>  'required'
        ]);

        $res = $httpservice->setUrl(config('wx.api.jscode2session'))
            ->setMthod('GET')->setRequestData($param)->getResult();



        return $this->responseJson($res,'error',404);
    }


}