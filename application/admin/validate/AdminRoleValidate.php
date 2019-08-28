<?php
/**
 * 后台角色验证类
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\validate;

class AdminRoleValidate extends Validate
{
    protected $rule = [
        'name|名称'        => 'require|unique:admin_role',
        'description|介绍' => 'require',
        'rules|权限'       => 'require',
    ];

    protected $scene = [
        'add'  => ['name', 'description'],
        'edit' => ['name', 'description'],
    ];
}