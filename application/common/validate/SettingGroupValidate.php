<?php
/**
 * 设置分组验证器
 */

namespace app\common\validate;

class SettingGroupValidate extends Validate
{
    protected $rule = [
        'name|名称'                   => 'require',
        'description|描述'            => 'require',
        'module|作用模块'               => 'require',
        'code|代码'                   => 'require|unique:setting',
        'sort_number|排序'            => 'require',
        'auto_create_menu|自动生成菜单'   => 'require',
        'auto_create_file|自动生成配置文件' => 'require',

    ];

    protected $message = [
        'name.require'             => '名称不能为空',
        'description.require'      => '描述不能为空',
        'module.require'           => '作用模块不能为空',
        'code.require'             => '代码不能为空',
        'sort_number.require'      => '排序不能为空',
        'auto_create_menu.require' => '自动生成菜单不能为空',
        'auto_create_file.require' => '自动生成配置文件不能为空',

    ];

    protected $scene = [
        'add'  => ['name', 'description', 'module', 'code', 'sort_number', 'auto_create_menu', 'auto_create_file',],
        'edit' => ['name', 'description', 'module', 'code', 'sort_number', 'auto_create_menu', 'auto_create_file',],

    ];


}
