<?php
/**
 * 后台角色验证类
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\common\validate;

use think\Validate;

class Role extends Validate
{
    protected $rule = [
        'parent_id' => 'require',
        'user_name' => 'require',
        'nick_name' => 'require',
        'mobile'    => ['require', 'regex' => '/^1(3|4|5|7|8)[0-9]\d{8}$/'],
        'email'     => 'email',
        'status'    => 'require',
    ];

    protected $message = [
        'email.email'  => '邮箱格式错误',
        'mobile.regex' => '手机格式错误',
    ];

    protected $scene = [
        'backend_add'  => ['parent_id', 'user_name', 'nick_name', 'status', 'mobile', 'email'],
        'backend_edit' => ['parent_id', 'user_name', 'nick_name', 'status', 'mobile', 'email'],
    ];
}