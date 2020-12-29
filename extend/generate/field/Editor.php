<?php
/**
 * 富文本编辑器
 * @author yupoxiong<i@yufuping.com>
 */

namespace generate\field;

class Editor extends Field
{
    public static $html = <<<EOF
<div class="form-group">
    <label for="[FIELD_NAME]" class="col-sm-2 control-label">[FORM_NAME]</label>
        <div class="col-sm-10">
            <script id="[FIELD_NAME]" name="[FIELD_NAME]" type="text/plain">{\$data.[FIELD_NAME]|raw|default='[FIELD_DEFAULT]'}</script>
        </div>
    </div>
<script>
    UE.delEditor('[FIELD_NAME]');
    var UE_[FIELD_NAME] = UE.getEditor('[FIELD_NAME]',{
        serverUrl :UEServer
    });
</script>\n
EOF;

    public static $rules = [
        'required' => '非空',
        'regular'  => '自定义正则'
    ];


    //控制器内代码
    public static $controllerAddCode =<<<EOF
\$param['[FIELD_NAME]'] = \$request->param(false)['[FIELD_NAME]'];
\n
EOF;

    public static $controllerEditCode =<<<EOF
\$param['[FIELD_NAME]'] = \$request->param(false)['[FIELD_NAME]'];
\n
EOF;

    public static function create($data)
    {
        return str_replace(array('[FORM_NAME]', '[FIELD_NAME]', '[FIELD_DEFAULT]'), array($data['form_name'], $data['field_name'], $data['field_default']), self::$html);
    }
}