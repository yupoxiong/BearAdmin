<?php
/**
 * 图标选择
 * @author yupoxiong<i@yufuping.com>
 */

namespace generate\field;

class Icon extends Field
{
    public static string $html = <<<EOF
<div class="form-group row rowIcon">
    <label for="[FIELD_NAME]" class="col-sm-2 col-form-label">[FORM_NAME]</label>
    <div class="col-sm-10 col-md-4 formInputDiv">
        <div class="input-group iconpicker-container">
            <div class="input-group-prepend">
                <span class="input-group-text iconpicker-component">
                    <i class="far fa-calendar-alt"></i>
                </span>
            </div>
            <input maxlength="64" id="[FIELD_NAME]" name="[FIELD_NAME]"
                   value="{\$data.[FIELD_NAME]|default='[FIELD_DEFAULT]'}" class="form-control fieldIcon"
                   placeholder="请选择[FORM_NAME]">
        </div>
    </div>

    <script>
        $('#[FIELD_NAME]').iconpicker({placement: 'bottomLeft'});
    </script>
</div>\n
EOF;

    public static function create($data): string
    {
        return str_replace(array('[FORM_NAME]', '[FIELD_NAME]', '[FIELD_DEFAULT]'), array($data['form_name'], $data['field_name'], $data['field_default']), self::$html);
    }
}