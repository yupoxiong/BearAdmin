<?php
/**
 * 文本域
 * @author yupoxiong<i@yufuping.com>
 */

namespace generate\field;

class Textarea extends Field
{
    public static string $html = <<<EOF
<div class="form-group row rowTextarea">
    <label for="[FIELD_NAME]" class="col-sm-2 col-form-label">[FORM_NAME]</label>
    <div class="col-sm-10 col-md-4 formInputDiv">
        <textarea id="[FIELD_NAME]" name="[FIELD_NAME]" class="form-control" rows="[ROWS]" placeholder="请输入[FORM_NAME]">{\$data.[FIELD_NAME]|default='[FIELD_DEFAULT]'}</textarea>
    </div>
</div>\n
EOF;

    public static function create($data): string
    {
        return str_replace(array('[FORM_NAME]', '[FIELD_NAME]', '[FIELD_DEFAULT]', '[ROWS]'), array($data['form_name'], $data['field_name'], $data['field_default'], $data['rows'] ?? 3), self::$html);

    }
}