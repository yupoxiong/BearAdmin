<?php
/**
 * 时间
 * @author yupoxiong<i@yufuping.com>
 */

namespace generate\field;

class Time extends Field
{
    public static string $html = <<<EOF
<div class="form-group row rowTime">
    <label for="[FIELD_NAME]" class="col-sm-2 col-form-label">[FORM_NAME]</label>
    <div class="col-sm-10 col-md-4 formInputDiv">
        <input id="[FIELD_NAME]" readonly name="[FIELD_NAME]" value="{\$data.[FIELD_NAME]|default='[FIELD_DEFAULT]'}" placeholder="请选择[FORM_NAME]" type="text" class="form-control filed-time">
    </div>

    <script>
        laydate.render({
            elem: '#[FIELD_NAME]',
            type: 'time',
        });
    </script>
</div>\n
EOF;

    public static array $rules = [
        'required' => '非空',
        'time'     => '时间',
    ];


    public static function create($data): string
    {
        return str_replace(array('[FORM_NAME]', '[FIELD_NAME]', '[FIELD_DEFAULT]'), array($data['form_name'], $data['field_name'], $data['field_default']), self::$html);
    }
}