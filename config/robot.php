<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default DingTalk Robot Connection Name
    |--------------------------------------------------------------------------
    |
    | DingTalk Robot API supports an assortment of back-ends via a single
    | API, giving you convenient access to each back-end using the same
    | syntax for every one. Here you may define a default connection.
    |
    */
    'default'     => env('DING_TALK_CONNECTION', 'default'),

    /*
    |--------------------------------------------------------------------------
    | DingTalk Robot Send Host
    |--------------------------------------------------------------------------
    |
    | DingTalk Robot message sending request address.
    |
    */
    'host'        => env('DING_TALK_HOST', 'https://oapi.dingtalk.com/robot/send'),

    /*
    |--------------------------------------------------------------------------
    | DingTalk Robot Connections
    |--------------------------------------------------------------------------
    |
    | Here you may configure the connection information for each server that
    | is used by your application. A default configuration has been added
    | for each back-end shipped with Laravel. You are free to add more.
    |
    */
    'connections' => [
        "default" => [
            "access_token" => env('DING_TALK_ACCESS_TOKEN', ''),
            'secret'       => env('DING_TALK_SECRET', '')
        ]
    ]
];