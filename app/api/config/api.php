<?php
/**
 * api模块相关配置
 * @author yupoxiong<i@yupoxiong.com>
 */

return [

    // api跨域设置
    'cross_domain' => [
        // 是否允许跨域
        'allow'  => env('api.allow_cross_domain', true),
        // header设置
        'header' => [
            'Access-Control-Allow-Origin'    => '*',
            'Access-Control-Allow-Methods'   => '*',
            'Access-Control-Allow-Headers'   => 'Content-Type,' . (env('api.token_position') === 'header' ? env('api.token_field') : 'token'),
            'Access-Control-Request-Headers' => 'Origin, Content-Type, Accept, ' . (env('api.token_position') === 'header' ? env('api.token_field') : 'token'),
        ],
    ],
    // api响应配置
    'response'     => [
        // HTTP状态码和业务状态码同步
        'http_code_sync' => env('api.http_code_sync', false),
    ],
    'auth'         => [
        'jwt_key'              => env('api.jwt_key', 'f2244f5316b70ef2887514b65caf795f'),
        'jwt_exp'              => (int)env('api.jwt_exp', 3600),
        'jwt_aud'              => env('api.jwt_aud', 'a'),
        'jwt_iss'              => env('api.jwt_iss', 's'),
        'enable_refresh_token' => (bool)env('api.enable_refresh_token', true),
        'refresh_token_exp'    => (int)env('api.refresh_token_exp', 1296000),
        'reuse_check'          => (bool)env('api.reuse_check', true),
        'token_position'       => env('api.token_position', 'header'),
        'token_field'          => env('api.token_field', 'token'),
    ]

];
