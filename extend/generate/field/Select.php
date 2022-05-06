<?php
/**
 * 列表选择
 * @author yupoxiong<i@yufuping.com>
 */

namespace generate\field;

class Select extends Field
{

    public static string $html = <<<EOF
<div class="form-group row rowSelect">
    <label for="[FIELD_NAME]" class="col-sm-2 col-form-label">[FORM_NAME]</label>
    <div class="col-sm-10 col-md-4 formInputDiv">
        <select name="[FIELD_NAME]" id="[FIELD_NAME]" class="form-control select2bs4 fieldSelect" data-placeholder="请选择[FORM_NAME]">
            <option value=""></option>
            [OPTION_DATA]
        </select>
    </div>

    <script>
        $('#[FIELD_NAME]').select2({
            theme: 'bootstrap4'
        });
    </script>
</div>\n
EOF;

    public static function create($data): string
    {
        $html = self::$html;
        return str_replace(array('[FORM_NAME]', '[FIELD_NAME]', '[OPTION_DATA]'), array($data['form_name'], $data['field_name'] ?? '', $data['option_data'] ?? ''), $html);
    }

}