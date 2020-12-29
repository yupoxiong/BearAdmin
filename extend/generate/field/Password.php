<?php
/**
 * 密码输入
 * @author yupoxiong<i@yufuping.com>
 */

namespace generate\field;

class Password extends Field
{
    public static $html = <<<EOF
<div class="form-group">
    <label for="[FIELD_NAME]" class="col-sm-2 control-label">[FORM_NAME]</label>
    <div class="col-sm-10 col-md-4">
        <input id="[FIELD_NAME]" name="[FIELD_NAME]" value="{\$data.[FIELD_NAME]|default='[FIELD_DEFAULT]'}" placeholder="请输入[FORM_NAME]" type="password" class="form-control field-password">
    </div>
</div>\n
EOF;

    public static $rules = [
        'required'         => '非空',
        'simple_password'  => '简单密码限制',
        'middle_password'  => '中等密码限制',
        'complex_password' => '复杂密码限制',
        'number'           => '纯数字',
        'length'           => '固定长度',
        'regular'          => '自定义正则'
    ];

    public static function create($data)
    {
        return str_replace(array('[FORM_NAME]', '[FIELD_NAME]', '[FIELD_DEFAULT]'), array($data['form_name'], $data['field_name'], $data['field_default']), self::$html);
    }
}