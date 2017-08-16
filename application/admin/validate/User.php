<?php
/**
 *
 * @author yupoxiong<i@yufuping.com>
 * @version 1.0
 * Date: 2017/6/16
 */
namespace app\common\validate;

use think\Validate;

class User extends Validate
{
    protected $rule = [
        ['user_name|用户名', 'require'],
        ['password|密码', 'require'],
    ];

    protected $scene = [
        'add'   =>   ['user_name','password'],
        'edit'   =>   ['user_name','password'],
    ];
}
