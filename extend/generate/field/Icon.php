<?php
/**
 * 图标选择
 * @author yupoxiong<i@yufuping.com>
 */

namespace generate\field;

class Icon extends Field
{
    public static $html = <<<EOF
<div class="form-group">
    <label for="[FIELD_NAME]" class="col-sm-2 control-label">[FORM_NAME]</label>
    <div class="col-sm-10 col-md-4">
        <div class="input-group iconpicker-container">
            <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
            <input maxlength="30" id="[FIELD_NAME]" name="[FIELD_NAME]"
                   value="{\$data.[FIELD_NAME]|default='[FIELD_DEFAULT]'}" class="form-control "
                   placeholder="请选择[FORM_NAME]">
        </div>
    </div>
</div>
<script>
    $('#[FIELD_NAME]').iconpicker({placement: 'bottomLeft'});
</script>\n
EOF;

    public static $rules = [
        'required' => '非空',
        'icon'     => '图标',
        'regular'  => '自定义正则'
    ];


    public static function create($data)
    {
        return str_replace(array('[FORM_NAME]', '[FIELD_NAME]', '[FIELD_DEFAULT]'), array($data['form_name'], $data['field_name'], $data['field_default']), self::$html);
    }
}