<?php
/**
 * 后台用户验证类
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\validate;

class AdminUser extends Admin
{
    protected $rule = [
        'name|帐号'      => 'require|unique:admin_users',
        'parent_id|角色' => 'require',
        'password|密码'  => 'require',
        'nickname|昵称' => 'require',
        'mobile|手机号'   => ['require', 'regex' => '/^1(3|4|5|7|8)[0-9]\d{8}$/'],
        'email|邮箱'     => 'email',
        'status|是否启用'  => 'require',
    ];

    protected $message = [
        'email.email'  => '邮箱格式错误',
        'mobile.regex' => '手机格式错误',
    ];

    protected $scene = [
        'add'   => ['parent_id', 'name', 'password', 'nickname'],
        'edit'  => ['parent_id', 'name', 'nickname'],
        'login' => ['name'=>'require', 'password'],
    ];
}