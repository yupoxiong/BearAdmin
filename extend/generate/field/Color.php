<?php
/**
 * 颜色选择
 * @author yupoxiong<i@yufuping.com>
 */

namespace generate\field;

class Color extends Field
{
    public static $html = <<<EOF
<div class="form-group">
    <label for="[FIELD_NAME]" class="col-sm-2 control-label">[FORM_NAME]</label>
    <div class="col-sm-10 col-md-4">
        <div class="input-group" id="color-[FIELD_NAME]">
            <input id="[FIELD_NAME]" name="[FIELD_NAME]" value="{\$data.[FIELD_NAME]|default='[FIELD_DEFAULT]'}" placeholder="请输入[FORM_NAME]" type="text" class="form-control field-map">
            <div class="input-group-addon"><i></i></div>
        </div>
    </div>
</div>
<script>
    $('#color-[FIELD_NAME]').colorpicker();
</script>\n
EOF;

    public static $rules = [
        'required' => '非空',
        'color'    => '颜色',
        'regular'  => '自定义正则'
    ];


    public static function create($data)
    {
        return str_replace(array('[FORM_NAME]', '[FIELD_NAME]', '[FIELD_DEFAULT]'), array($data['form_name'], $data['field_name'], $data['field_default']), self::$html);
    }
}