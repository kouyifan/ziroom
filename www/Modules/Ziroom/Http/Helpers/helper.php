<?php

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