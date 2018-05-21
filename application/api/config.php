<?php
/**
 * @author yupoxiong<i@yufuping.com>
 */

return [
    'default_return_type' => 'json',
    // 应用调试模式
    'app_trace'           => false,
    
    'log'                 => [
        'type'             => 'socket',
        'host'             => 'slog.thinkphp.cn',
        //日志强制记录到配置的client_id
        'force_client_ids' => [],
        //限制允许读取日志的client_id
        'allow_client_ids' => [],
    ],

    //前缀
    'api_source_prefix' => 'http://192.168.31.153:728',
    'slide_path'        => ROOT_PATH . 'public' . DS . 'uploads' . DS . 'slides' . DS,
    'slide_url'         => DS . 'uploads' . DS . 'slides' . DS,
];