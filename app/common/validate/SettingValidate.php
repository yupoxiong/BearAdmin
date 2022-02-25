<?php
/**
 * 设置验证器
 */

namespace app\common\validate;

class SettingValidate extends CommonBaseValidate
{
    protected $rule = [
        'setting_group_id|所属分组' => 'require|number|elt:9999|egt:1',
        'name|名称'               => 'require|chsDash',
        'description|描述'        => 'require|chsDash',
        'code|代码'               => 'require|unique:setting|code',
        'sort_number|排序'        => 'require|number|elt:9999999999|egt:1',

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
        return preg_match($pattern, $value) ? true : $desc . '的值只能是字母、数字、下划线组成，字母开头，3-50位';
    }

}
