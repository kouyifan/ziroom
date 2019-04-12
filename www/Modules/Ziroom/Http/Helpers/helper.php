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
//递归删除数组的某一个值
function delete_array_value_recursion($array,$str){
    if (is_array($array)){
        foreach ($array as $k =>$value){

            if (is_array($value)){
                $array[$k] = delete_array_value_recursion($value,$str);
            } else{
                $array[$k] = str_replace($str,'',$value);
            }
        }
    }
    return $array;
}


function Rand_IP(){

    $ip2id= round(rand(600000, 2550000) / 10000); //第一种方法，直接生成
    $ip3id= round(rand(600000, 2550000) / 10000);
    $ip4id= round(rand(600000, 2550000) / 10000);
//    return implode('.',[$ip2id,$ip3id,$ip4id]);
//    //下面是第二种方法，在以下数据中随机抽取
    $arr_1 = array("218","218","66","66","218","218","60","60","202","204","66","66","66","59","61","60","222","221","66","59","60","60","66","218","218","62","63","64","66","66","122","211");
    $randarr= mt_rand(0,count($arr_1)-1);
    $ip1id = $arr_1[$randarr];
    return $ip1id.".".$ip2id.".".$ip3id.".".$ip4id;
}