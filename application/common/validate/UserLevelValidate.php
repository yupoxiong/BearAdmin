<?php
/**
 * 用户等级验证器
 */

namespace app\common\validate;

class UserLevelValidate extends Validate
{
    protected $rule = [
        'name|名称'        => 'require',
        'description|简介' => 'require',
        'status|是否启用'    => 'require',

    ];

    protected $message = [
        'name.require'        => '名称不能为空',
        'description.require' => '简介不能为空',
        'status.require'      => '是否启用不能为空',

    ];

    protected $scene = [
        'add'  => ['name', 'description', 'status',],
        'edit' => ['name', 'description', 'status',],

    ];


}
