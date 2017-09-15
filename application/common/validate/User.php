<?php
/**
 * 测试用户 验证类
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\common\validate;

use think\Validate;

class User extends Validate
{
    protected $rule = [
        'user_name'    => ['require', 'regex' => '/^[a-zA-Z][a-zA-Z0-9_]{4,9}$/'],
        'password' => 'require',

    ];

    protected $message = [
        'user_name.regex'  => '用户名格式错误',
    ];

    protected $scene = [
        'add'  => [ 'user_name', 'password'],
        'edit' => ['user_name', 'password'],
    ];
}