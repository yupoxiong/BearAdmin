<?php
/**
 * 单选
 * @author yupoxiong<i@yufuping.com>
 */

namespace generate\field;

class Radio extends Field
{
    public static $html = <<<EOF
<div class="radio">
    <label>
        <input type="radio" name="[FIELD_NAME]" value="" checked="">
        [FORM_NAME]
    </label>
</div>\n
EOF;
    public static $rules = [
        'required'   => '非空',
        'regular'    => '自定义正则'
    ];

    public static function create($data)
    {
        $html = self::$html;
        $html = str_replace('[FORM_NAME]', $data['form_name'], $html);
        $html = str_replace('[FIELD_NAME]', $data['field_name'], $html);
        return $html;
    }
}