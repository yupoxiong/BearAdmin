<?php
/**
 * 邮箱
 * @author yupoxiong<i@yufuping.com>
 */

namespace generate\field;

class Email extends Field
{
    public static $html = <<<EOF
<div class="form-group">
    <label for="[FIELD_NAME]" class="col-sm-2 control-label">[FORM_NAME]</label>
    <div class="col-sm-10 col-md-4">
        <input id="[FIELD_NAME]" name="[FIELD_NAME]" value="{\$data.[FIELD_NAME]|default='[FIELD_DEFAULT]'}" placeholder="请输入[FORM_NAME]" type="email" class="form-control field-email">
    </div>
</div>\n
EOF;

    public static $rules = [
        'required' => '非空',
        'email'    => '邮箱',
        'regular'  => '自定义正则'
    ];


    public static function create($data)
    {
        return str_replace(array('[FORM_NAME]', '[FIELD_NAME]', '[FIELD_DEFAULT]'), array($data['form_name'], $data['field_name'], $data['field_default']), self::$html);
    }
}