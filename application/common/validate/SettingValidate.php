<?php
/**
 * 设置验证器
 */

namespace app\common\validate;

class SettingValidate extends Validate
{
    protected $rule = [
        'setting_group_id|所属分组' => 'require',
        'name|名称'               => 'require',
        'description|描述'        => 'require',
        'code|代码'               => 'require|unique:setting',
        'sort_number|排序'        => 'require',

    ];

    protected $message = [
        'setting_group_id.require' => '所属分组不能为空',
        'name.require'             => '名称不能为空',
        'description.require'      => '描述不能为空',
        'code.require'             => '代码不能为空',
        'sort_number.require'      => '排序不能为空',

    ];

    protected $scene = [
        'add'  => ['setting_group_id', 'name', 'description', 'code', 'sort_number',],
        'edit' => ['setting_group_id', 'name', 'description', 'code', 'sort_number',],

    ];


}
