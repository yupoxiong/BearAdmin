<?php
/**
 * 用户验证器
 */

namespace app\common\validate;

class UserValidate extends CommonBaseValidate
{
    protected $rule = [
        'user_level_id|用户等级' => 'require',
        'username|账号'        => 'require',
        'password|密码'        => 'require',
        'mobile|手机号'         => 'require',
        'nickname|昵称'        => 'require',
        'avatar|头像'          => 'require',
        'status|是否启用'        => 'require',

    ];

    protected $message = [
        'user_level_id.required' => '用户等级不能为空',
        'username.required'      => '账号不能为空',
        'password.required'      => '密码不能为空',
        'mobile.required'        => '手机号不能为空',
        'nickname.required'      => '昵称不能为空',
        'avatar.required'        => '头像不能为空',
        'status.required'        => '是否启用不能为空',

    ];

    protected $scene = [
        'admin_add'     => ['user_level_id', 'username', 'password', 'mobile', 'nickname', 'avatar', 'status',],
        'admin_edit'    => ['id', 'user_level_id', 'username', 'password', 'mobile', 'nickname', 'avatar', 'status',],
        'admin_del'     => ['id',],
        'admin_disable' => ['id',],
        'admin_enable'  => ['id',],
        'api_add'       => ['user_level_id', 'username', 'password', 'mobile', 'nickname', 'avatar', 'status',],
        'api_info'      => ['id',],
        'api_edit'      => ['id', 'user_level_id', 'username', 'password', 'mobile', 'nickname', 'avatar', 'status',],
        'api_del'       => ['id',],
        'api_disable'   => ['id',],
        'api_enable'    => ['id',],
        'api_login'     => ['username', 'password'],
        'index_login'     => ['username', 'password'],
    ];

}
