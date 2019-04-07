<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class Controller extends BaseController
{
    //重写认证错误消息
    protected function throwValidationException(Request $request, $validator)
    {

        $response = [
            'code' => 400,
            'msg'  => $validator->errors()->first(),
            'data' => []
        ];
        throw new ValidationException($validator,$this->buildFailedValidationResponse(
            $request, $response));

    }

//    返回成功消息
    protected function responseJson($data = [],$msg = 'success', $status = 200, array $headers = [], $options = 0){

        $response = [
            'code'  =>  $status,
            'msg'   =>  $msg,
            'data'  =>  $data
        ];
        return response()->json($response,$status, $headers, $options);
    }

}
