<?php
/**
 * Created by PhpStorm.
 * User: 技术主管寇一凡
 * Date: 2019/3/21
 * Time: 11:19
 */

if (!function_exists('p')){
    function p($var)
    {
        if (is_bool($var)) {
            var_dump($var);
        } else if (is_null($var)) {
            var_dump(NULL);
        } else {
            echo "<pre style='position:relative;z-index:1000;padding:10px;border-radius:5px;background:#F5F5F5;border:1px solid #aaa;font-size:14px;line-height:18px;opacity:0.9;'>" . print_r($var, true) . "</pre>";
        }
    }

}
//curl post
if (!function_exists('fn_curl_get')) {
    function fn_curl_post($uri, $data)
    {
        // 参数数组
        $ch = curl_init();
        // print_r($ch);
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $return = curl_exec($ch);
        curl_close($ch);
        return $return;
    }
}
//curl get
if (!function_exists('fn_curl_get')) {
    function fn_curl_get($url,$param)
    {
        if (!empty($param)){
            $url .= '?'.http_build_query($param);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $dom = curl_exec($ch);
        curl_close($ch);
        return $dom;
    }
}
//获得当前http 或者 https
if (!function_exists('fn_curl_get_http_or_https')) {
    function fn_curl_get_http_or_https()
    {
        $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https:' : 'http:';
        return $http_type;
    }
}
//根据日期生成目录
if (!function_exists('fn_create_dir_date_path')) {
    function fn_create_dir_date_path($file = '')
    {
        $uuid = str_replace('-','_',(string)\Illuminate\Support\Str::uuid());
        $date = date('Y/m/d');
        if (!empty($file)){
            $file = pathinfo($file);
            $date = $date.'/'.$uuid.'.'.$file['extension'];
        }
        return $date;
    }
}
//获得文件ext
if (!function_exists('fn_get_file_ext')) {
    function fn_get_file_ext($file = '')
    {
        $pathinfo = pathinfo($file);
        return $pathinfo['extension'];
    }
}
//获得文件filename
if (!function_exists('fn_get_file_name')) {
    function fn_get_file_name($file = '')
    {
        $pathinfo = pathinfo($file);
        return $pathinfo['filename'];
    }
}
//获得文件basename
if (!function_exists('fn_get_file_basename')) {
    function fn_get_file_basename($file = '')
    {
        $pathinfo = pathinfo($file);
        return $pathinfo['basename'];
    }
}

function insert_db(){

    $faker = \Faker\Factory::create();
    for ($i = 0 ; $i < 10 ; $i++){
        $insert = [
            'name'    => $faker->name,
            'email' =>  $faker->email,
            'age'   =>  $faker->numberBetween(10,40),
            'text'  =>  $faker->text(200)
        ];
        $id = \Illuminate\Support\Facades\DB::connection('my_pc')->table('users')->insertGetId($insert);
    }
    return $id;
}