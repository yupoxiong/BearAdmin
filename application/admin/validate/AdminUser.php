<?php
/**
 *
 * @author yupoxiong<i@yufuping.com>
 * @version 1.0
 * Date: 2017/6/16
 */
namespace app\common\validate;

use think\Validate;

class AdminUser extends Validate
{
    protected $add = [
        ['parent_id|角色', 'require'],
        ['user_name|帐号', 'require|token'],
        ['nick_name|用户名', 'require'],
        ['status|状态', 'require'],
    ];
}
