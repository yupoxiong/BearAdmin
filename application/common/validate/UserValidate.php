<?php
/**
 * 用户验证器
 */

namespace app\common\validate;

class UserValidate extends Validate
{
    protected $rule = [
        'username|用户名'       => 'require',
        'nickname|昵称'        => 'require',
        'mobile|手机号'         => 'require',
        'user_level_id|用户等级' => 'require',
        'password|密码'        => 'require',
        'status|是否启用'        => 'require',

    ];

    protected $message = [
        'username.require'      => '用户名不能为空',
        'nickname.require'      => '昵称不能为空',
        'mobile.require'        => '手机号不能为空',
        'user_level_id.require' => '用户等级不能为空',
        'password.require'      => '密码不能为空',
        'status.require'        => '是否启用不能为空',

    ];

    protected $scene = [
        'add'  => ['username', 'nickname', 'mobile', 'user_level_id', 'password', 'status',],
        'edit' => ['username', 'nickname', 'mobile', 'user_level_id', 'password', 'status',],

    ];


}
