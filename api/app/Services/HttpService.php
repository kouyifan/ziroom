<?php
/**
 * Created by PhpStorm.
 * User: 技术主管寇一凡
 * Date: 2019/4/7
 * Time: 12:01
 */
namespace App\Services;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class HttpService{

    protected $client;
    protected $error;

    public $url;
    public $request_data = [];
    public $quest_method = 'GET';


    public function __construct()
    {
        $this->client = new Client([
            'timeout'   =>  3.0
        ]);
    }


    //设置URL
    public function setUrl(string $url = ''){
        $this->url = $url;
        return $this;
    }
    //设置访问方法
    public function setMthod(string $method = ''){
        $this->quest_method = $method;
        return $this;
    }
    //设置请求变量
    public function setRequestData(array $request_data = []){
        $this->request_data = $request_data;
        return $this;
    }

    //获取get请求结果
    protected function _request(){
        switch ($this->quest_method){
            case 'GET':
                if ($this->request_data){
                    $this->url = $this->url .'?'. http_build_query($this->request_data);
                    $this->request_data = [];
                }
                break;
            default:
                break;
        }
        $res = [];
        p($this->url);
        die;
        try {
            $res = $this->client->request($this->quest_method,$this->url,$this->request_data);
        } catch (RequestException $e) {
            //echo $e->getRequest();
            if ($e->hasResponse()) {
                $res = $e->getResponse();
                return false;
            }
        }
        return $res;
    }


    //返回结果
    public function getResult(){

        $response = $this->_request();
        p($response);
        if ($response && $response->getStatusCode() == '200'){
            return $response->getBody();
        } else{
            return $this->error;
        }
    }

//    public function __call($name, $args)
//    {
//        array_unshift($args, $this->value);
//        $this->value = call_user_func_array($name, $args);
//        return $this;
//    }


}