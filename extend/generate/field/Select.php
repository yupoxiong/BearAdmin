<?php
/**
 * 列表选择
 * @author yupoxiong<i@yufuping.com>
 */

namespace generate\field;

class Select extends Field
{

    public static $html = <<<EOF
<div class="form-group">
    <label for="[FIELD_NAME]" class="col-sm-2 control-label">[FORM_NAME]</label>
    <div class="col-sm-10 col-md-4">
        <select name="[FIELD_NAME]" id="[FIELD_NAME]" class="form-control field-select" data-placeholder="请选择[FORM_NAME]">
            <option value=""></option>
            [RELATION_DATA]
        </select>
    </div>
</div>
<script>
 $('#[FIELD_NAME]').select2();
</script>\n
EOF;

    public static $rules = [
        'required' => '非空',
        'regular'  => '自定义正则'
    ];

    public static function create($data)
    {
        $html = self::$html;
        $html = str_replace(array('[FORM_NAME]', '[FIELD_NAME]', '[RELATION_DATA]'), array($data['form_name'], $data['field_name'] ?? '', $data['relation_data'] ?? ''), $html);
        return $html;
    }

}