<?php
/**
 * 颜色选择
 * @author yupoxiong<i@yufuping.com>
 */

namespace generate\field;

class Color extends Field
{
    public static string $html = <<<EOF
<div class="form-group row rowColor">
    <label for="[FIELD_NAME]" class="col-sm-2 col-form-label">[FORM_NAME]</label>
    <div class="col-sm-10 col-md-4 formInputDiv">
        <div class="input-group" id="color-[FIELD_NAME]">
            <input id="[FIELD_NAME]" name="[FIELD_NAME]" autocomplete="off" value="{\$data.[FIELD_NAME]|default='[FIELD_DEFAULT]'}" placeholder="请输入[FORM_NAME]" type="text" class="form-control fieldColor">
            <div class="input-group-append">
                <span class="input-group-text"><i class="fas fa-square"></i></span>
            </div>
        </div>
    </div>
    <script>
        $(function (){
            $('#color-[FIELD_NAME] .fa-square').css('color', $('#[FIELD_NAME]').val());
        });
        $('#color-[FIELD_NAME]').colorpicker().on('colorpickerChange', function(event) {
            $('#color-[FIELD_NAME] .fa-square').css('color', event.color!==null?event.color.toString():'');
        })
    </script>
</div>
\n
EOF;

    public static array $rules = [
        'required' => '非空',
        'color16'  => '16进制颜色',
    ];

    public static function create($data): string
    {
        return str_replace(array('[FORM_NAME]', '[FIELD_NAME]', '[FIELD_DEFAULT]'), array($data['form_name'], $data['field_name'], $data['field_default']), self::$html);
    }
}
