<?php
return [

    'engine' => 'MyISAM',       // 存储引擎
    'table' => 'request_logs',  // 数据库表名

    'middleware_alias' => 'request.log', //中间件别名

    //记录的请求方法
    'log_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'],

    //过滤的域名
    'ignore_domains' => [
    ],

    //过滤路径
    'ignore_paths' => [
    ],

    //自定义规则
    /*'custom_rule' => function ($request) {
        // 不记录 UA 包含 bot 的请求
        return stripos($request->userAgent() ?? '', 'bot') === false;
    },*/
    'custom_rule' => null,
];

