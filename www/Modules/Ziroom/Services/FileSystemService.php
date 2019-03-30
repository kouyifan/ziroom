<?php
namespace Modules\Ziroom\Services;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;

class FileSystemService{

    protected $storage;

    public function __construct()
    {
        $this->storage = Storage::disk('ziroom');
    }

    //下载文件
    public function _down($file = '',$name = '',$headers = ''){
        return $this->storage->download($file);
    }
    //存储文件
    public function _move($old_file = '',$new_file = ''){
        return $this->storage->move($old_file, $new_file);
    }
    //

    public function _put_file($file){
        return $this->storage->putFile($file);
    }

    public function _get($file){
        return $this->storage->get($file);
    }

    public function _put($file,$content){
        return $this->storage->put($file,$content);
    }
    public function _url($filename){
        return $this->storage->url($filename);
    }

    public function _down_file_http($http_file = '',$path = ''){
        $pathinfo = pathinfo($http_file);
        $new_path = public_path() . '/'.$pathinfo['basename'];
        $path = $path ? $new_path : public_path().'/tmp.png';
        $client = new Client(['verify' => false]);  //忽略SSL错误

        $response = $client->get($http_file, ['save_to' => $path]);  //保存远程url到文件
        if ($response->getStatusCode() == 200) {
            return $path;
        }
    }

    //文件生成入库，返回ID


}