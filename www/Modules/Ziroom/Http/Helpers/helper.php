<?php
//过滤抓取的数据
function _serialize_grab_filter_space_html($string = ''){
    if (empty($string)) return '';

    $item = explode(' ',$string);
    $item = array_filter($item);

    $item2 = array_map(function($value){
        if (preg_match('/[\x{4e00}-\x{9fa5}]/u',$value)){
            return preg_replace('/\s/','',$value);
        }
    },$item);
    return array_values(array_filter($item2));
}

//删除内容换行空格
function DeleteHtml($str) {
    $str = trim($str); //清除字符串两边的空格
    $str = preg_replace("/\t/", "", $str); //使用正则表达式替换内容，如：空格，换行，并将替换为空。
    $str = preg_replace("/\r\n/", "", $str);
    $str = preg_replace("/\r/", "", $str);
    $str = preg_replace("/\n/", "", $str);
    // $str = preg_replace("/ /","",$str);
    // $str = preg_replace("/  /","",$str);  //匹配html中的空格
    return trim($str); //返回字符串
}