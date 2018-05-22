<?php
//配置文件
return [

    // 模板参数替换
    'view_replace_str' => [
        '__STATIC__'  => '/static/admin',
        '__CSS__'     => '/static/admin/css',
        '__JS__'      => '/static/admin/js',
        '__IMAGES__'  => '/static/admin/images',
        '__FONTS__'   => '/static/admin/fonts',
        '__PLUGINS__' => '/static/admin/plugins',
        '__AVATAR__' => '/uploads/admin/avatar',
    ],

    'template'                   => [
        'layout_on'       => true,
        'layout_name'     => 'template/layout',
        'layout_item'     => '[__REPLACE__]',
    ],
    //分页配置
    'paginate'                   => [
        'type'      => '\tools\Bearpage',
        'var_page'  => 'page',
        'list_rows' => 10,
    ],

    'admin_auth'=>[
        'auth_on'           => 1, // 权限开关
        'auth_type'         => 1, // 认证方式，1为实时认证；2为登录认证。
        'auth_group'        => 'admin_groups', // 用户组数据表名
        'auth_group_access' => 'admin_group_access', // 用户-用户组关系表
        'auth_rule'         => 'admin_menus', // 权限规则表
        'auth_user'         => 'admin_users', // 用户信息表
    ],


];