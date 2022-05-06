<?php
/**
 * 数字输入
 * @author yupoxiong<i@yufuping.com>
 */

namespace generate\field;

class Number extends Field
{
    public static string $html = <<<EOF
<div class="form-group row rowNumber">
    <label for="[FIELD_NAME]" class="col-sm-2 col-form-label">[FORM_NAME]</label>
    <div class="col-sm-10 col-md-4 formInputDiv">
         <div class="input-group">
            <input id="[FIELD_NAME]" name="[FIELD_NAME]" value="{\$data.[FIELD_NAME]|default='[FIELD_DEFAULT]'}" placeholder="请输入[FORM_NAME]" type="number" class="form-control fieldNumber">
        </div>
    </div>

    <script>
        $('#[FIELD_NAME]')
            .bootstrapNumber({
                upClass: 'success',
                downClass: 'primary',
                center: true
            });
    </script>
</div>\n
EOF;

    public static array $rules = [
        //非空
        'required'        => '非空',
        //纯数字
        'number'          => '纯数字',
    ];

    public static function create($data): string
    {
        return str_replace(array('[FORM_NAME]', '[FIELD_NAME]', '[FIELD_DEFAULT]'), array($data['form_name'], $data['field_name'], $data['field_default']), self::$html);
    }


}