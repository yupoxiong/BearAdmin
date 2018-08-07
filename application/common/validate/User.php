<?php
/**
 * 前台用户验证类
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\common\validate;

class User extends Validate
{
    protected $rule = [
        'name|帐号'     => 'require|unique:users',
        'password|密码' => 'require',
        'nickname|昵称' => 'require',
        'mobile|手机号'  => ['require', 'regex' => '/^1(3|4|5|6|7|8|9)[0-9]\d{8}$/'],
        'email|邮箱'    => 'email',
        'headimg|头像'    => 'require',
        'status|状态' => 'require',
    ];

    protected $message = [
        'email.email'  => '邮箱格式错误',
        'mobile.regex' => '手机格式错误',
    ];

    protected $scene = [
        'admin_add'   => ['name', 'password', 'nickname'],
        'admin_edit'  => [ 'name', 'nickname'],
        'login' => ['name' => 'require', 'password'],
    ];
}