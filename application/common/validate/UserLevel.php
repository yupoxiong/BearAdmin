<?php
/**
 * 前台用户等级验证类
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\common\validate;

class UserLevel extends Validate
{
    protected $rule = [
        'name|名称'     => 'require',
    ];


    protected $scene = [
        'admin_add'   => ['name' ],
        'admin_edit'  => [ 'name'],
    ];
}