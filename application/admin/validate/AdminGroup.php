<?php
/**
 * 后台角色验证类
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\validate;

class AdminGroup extends Admin
{
    protected $rule = [
        'name|角色名称' => 'require',
        'description|角色描述' => 'require',
        'rules'    => 'require',
    ];

    protected $scene = [
        'add'  => ['name', 'description'],
        'edit' => ['name', 'description'],
    ];
}