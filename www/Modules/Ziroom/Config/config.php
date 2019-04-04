<?php

return [
    'name' => 'Ziroom',
    'domain'    =>  'http://ziroom.koukousky.com',

    //cache key
    'nav_cache'    =>  'ziroom_cache_nav_key',

    'Grab_Urls'  =>  [
        'list1'  =>  'http://www.ziroom.com/z/nl/z2.html',//合租页面
        'list1_page'  =>  'http://www.ziroom.com/z/nl/z2.html?p=',//合租页面分页

        'list2' =>  'http://www.ziroom.com/z/nl/z1-x1.html',
        'list2_page' =>  'http://www.ziroom.com/z/nl/z1-x1.html?p=',

        'list3' =>  'www.ziroom.com/z/nl/z6.html',
        'list3_page' =>  'http://www.ziroom.com/z/nl/z6.html?p=',

        'room_info_api' =>  'http://www.ziroom.com/detail/info',//id=&house_id
    ],
    'disk'  =>  'ziroom',

    'room_slave_sex'    =>  ['current','man','woman'],

];
