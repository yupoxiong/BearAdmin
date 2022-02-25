<?php
/**
 * 设置分组验证器
 */

namespace app\common\validate;

class SettingGroupValidate extends CommonBaseValidate
{
    protected $rule = [
        'name|名称'                   => 'require|chsDash',
        'description|描述'            => 'require|chsDash',
        'module|作用模块'               => 'require|alpha|length:1,30',
        'code|代码'                   => 'require|unique:setting_group|code',
        'sort_number|排序'            => 'require|number|elt:9999999999|egt:1',
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


    /**
     * 验证code
     * @param $value
     * @param $rule
     * @param array $data
     * @param string $field
     * @param string $desc
     * @return bool|string
     */
    protected function code($value, $rule, array $data = [], string $field = '', string $desc = '')
    {
        $pattern = '/^[a-zA-z]\w{2,29}$/';
        return preg_match($pattern, $value) ? true : $desc . '的规则为[字母、数字、下划线组成，字母开头，3-30位]';
    }
}
