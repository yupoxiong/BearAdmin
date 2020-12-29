<?php
/**
 * 复选框
 * @author yupoxiong<i@yufuping.com>
 */

namespace generate\field;

class Checkbox extends Field
{
    public static $html = <<<EOF
            <div class="form-group">
                <label class="col-sm-2 control-label">[FORM_NAME]</label>
                <div class="col-sm-10 col-md-4">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="[FIELD_NAME][]" class="field-checkbox"> [FORM_NAME]
                        </label>
                    </div>
                </div>
            </div>\n
EOF;

    public static $rules = [
        'required' => '非空',
        'regular'  => '自定义正则'
    ];


    public static function create($data)
    {
        $html = self::$html;
        $html = str_replace(array('[FORM_NAME]', '[FIELD_NAME]'), array($data['form_name'], $data['field_name']), $html);
        return $html;
    }
}