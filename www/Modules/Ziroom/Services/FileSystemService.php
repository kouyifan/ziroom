<?php
namespace Modules\Ziroom\Services;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;

class FileSystemService{

    protected $storage;

    public function __construct()
    {
//        $this->storage = Storage::disk('public_folder');
    }

    //下载文件
    public function _put_file($file = '',$name = '',$headers = ''){
        return $this->storage->download($file);
    }

    public function _down_file_http($http_file = ''){


        $client = new Client(['verify' => false]);  //忽略SSL错误
        $path = public_path().'/tmp.png';
        $response = $client->get($http_file, ['save_to' => $path]);  //保存远程url到文件
        if ($response->getStatusCode() == 200) {
            return $path;
        }
    }

}