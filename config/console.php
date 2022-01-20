<?php
// +----------------------------------------------------------------------
// | 控制台配置
// +----------------------------------------------------------------------
use app\command\GenerateAppKey;
use app\command\GenerateJwtKey;
use app\command\InitEnv;


return [
    // 指令定义
    'commands' => [
        // 测试指令
        'test'             => Test::class,
        // 初始化env文件
        'init:env'         => InitEnv::class,
        // 生成新的app_key
        'generate:app_key' => GenerateAppKey::class,
        // 生成新的jwt_key
        'generate:jwt_key' => GenerateJwtKey::class,
    ],
];
