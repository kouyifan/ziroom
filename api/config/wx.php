<?php

return [
    'api' => [
        //登录凭证校验，临时登录凭证校验接口是一个 HTTPS 接口，开发者服务器使用 临时登录凭证code 获取 session_key 和 openid 等。
        'jscode2session'    =>  'https://api.weixin.qq.com/sns/jscode2session',


    ],
    'ziroom' => [
        'wx_appid' => 'wxe0e7318992e70ae2',
        'wx_secret' => 'd708fc049d8085e929a308d25fc4db69',
    ],


];