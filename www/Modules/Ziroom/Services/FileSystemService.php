<?php
namespace Modules\Ziroom\Services;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use Modules\Ziroom\Entities\Asset;


class FileSystemService{

    protected $storage;
    protected $disk;

    public function __construct()
    {
        $this->disk = config('ziroom.disk');
        $this->storage = Storage::disk($this->disk);
    }

    //清空磁盘
    public function _deleteDirectory(){
        $directories = $this->storage->directories();
        foreach ($directories as $directory){
            $this->storage->deleteDirectory($directory);
        }
    }

    //下载文件
    public function _down($file = '',$name = '',$headers = ''){
        return $this->storage->download($file);
    }
    //存储文件
    public function _move($old_file = '',$new_file = ''){
        return $this->storage->move($old_file, $new_file);
    }
    //获得URL
    public function _get_file_url($filename,$disk){
        return Storage::disk($disk)->url($filename);
    }
    //获得hash
    public function _get_file_hash($filename,$disk){
        $fileSystem = new \Illuminate\Filesystem\Filesystem();
        $hash = $fileSystem->hash(config('filesystems.disks.'.$disk.'.root').'/'.$filename);
        return $hash;
    }
    //获得文件大小
    public function _get_file_size($filename,$disk){
        return Storage::disk($disk)->size($filename);
    }
    //获得文件类型
    public function _get_file_mime($filename,$disk){
        return Storage::disk($disk)->mimeType($filename);
    }

    //put file
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

    public function _delete_file($file){
        return $this->storage->delete($file);
    }

    public function _down_file_http($http_file = '',$path = ''){
        $new_path = public_path() . '/'.fn_get_file_basename($http_file);
        $path = $path ? $new_path : public_path().'/tmp.png';
        $client = new Client(['verify' => false]);  //忽略SSL错误

        $response = $client->get($http_file, ['save_to' => $path]);  //保存远程url到文件
        if ($response->getStatusCode() == 200) {
            return $path;
        }
    }
    //处理缩略图
    public function handle_thumb($src = ''){
        $thumb_path = $this->_down_file_http($src,true);
        $new_file_path = fn_create_dir_date_path($thumb_path);
        $file_re = $this->_put($new_file_path,file_get_contents($thumb_path));
        $insert = [
            'name'    =>   fn_get_file_name($src),
            'file_path' =>  $new_file_path
        ];
        //附件ID
        $asset_id = $this->_insert_asset_file($insert);

        if ($file_re && is_file($thumb_path)){
            @unlink($thumb_path);
        }
        return $asset_id;
    }

    //处理图集
    public function handle_img_list($img_list = ''){
        if (empty($img_list)) return [];
        $res = [];
        foreach($img_list as $value){
            $res[] = $this->handle_thumb($value);
        }
        return implode(',',$res);
    }

    //获得图集
    public function get_room_img_list($imgs){

        $asset_model = new \Modules\Ziroom\Entities\Asset();
        if (!empty($imgs) && !is_array($imgs)) $imgs =  explode(',',$imgs);

        $imgs = array_map(function($v){
            return str_replace(',,,,,,,,','',$v);
        },$imgs);
        $assets = $asset_model->whereIn('id',$imgs)->get();
        foreach ($assets as $asset){
            $asset->img_path = $this->_get_file_url($asset->file_path,$asset->disk);
        }
        return $assets;
    }

    //文件生成入库，返回ID
    public function _insert_asset_file($insert = []){
        $file_hash = $this->_get_file_hash($insert['file_path'],$this->disk);
        $find = \Modules\Ziroom\Entities\Asset::where('file_hash',$file_hash)->value('id');

        if ($find) {
            $this->_delete_file($insert['file_path']);
            return false;
        }

        $asset = new Asset;
        $asset->name = $insert['name'];
        $asset->file_path = $insert['file_path'];
        $asset->disk = $this->disk;
        $asset->save();
        return $asset->id ?? $asset->id;
    }

}