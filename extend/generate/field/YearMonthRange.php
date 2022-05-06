<?php
/**
 * 年月范围
 * @author yupoxiong<i@yufuping.com>
 */

namespace generate\field;

class YearMonthRange extends Field
{

    public static string $html = <<<EOF
<div class="form-group row rowYearMonthRange">
    <label for="[FIELD_NAME]" class="col-sm-2 col-form-label">[FORM_NAME]</label>
    <div class="col-sm-10 col-md-4 formInputDiv">
        <input id="[FIELD_NAME]" readonly name="[FIELD_NAME]" value="{\$data.[FIELD_NAME]|default='[FIELD_DEFAULT]'}" placeholder="请选择[FORM_NAME]" type="text" class="form-control filed-year-month-range">
    </div>
    <script>
        laydate.render({
            elem: '#[FIELD_NAME]',
            type: 'month',
            range: true,
        });
    </script>
</div>\n
EOF;

    public static array $rules = [
        'required'         => '非空',
        'year_month_range' => '年月范围',
    ];


    public static function create($data): string
    {
        return str_replace(array('[FORM_NAME]', '[FIELD_NAME]', '[FIELD_DEFAULT]'), array($data['form_name'], $data['field_name'], $data['field_default']), self::$html);
    }
}