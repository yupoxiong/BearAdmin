<?php
/**
 * 富文本编辑器
 * @author yupoxiong<i@yufuping.com>
 */

namespace generate\field;

class Editor extends Field
{
    public static string $html = <<<EOF
<div class="form-group row rowEditor">
    <label for="[FIELD_NAME]" class="col-sm-2 col-form-label">[FORM_NAME]</label>
        <div class="col-sm-10">
            <div id="[FIELD_NAME]Editor">{\$data.[FIELD_NAME]|raw|default='<p>[FIELD_DEFAULT]</p>'}</div>
            <textarea id="[FIELD_NAME]" name="[FIELD_NAME]" style="display: none">{\$data.[FIELD_NAME]|raw|default='[FIELD_DEFAULT]'}</textarea>
        </div>
    <script>
        var E = E||window.wangEditor;
        if(editor_[FIELD_NAME]!==undefined){
            editor_[FIELD_NAME].destroy();
        }
        var editor_[FIELD_NAME] = new E('#[FIELD_NAME]Editor');
        editor_[FIELD_NAME].config.zIndex=1000;
        editor_[FIELD_NAME].config.uploadImgServer = editorUploadUrl;
        editor_[FIELD_NAME].config.uploadFileName = 'file';
        editor_[FIELD_NAME].create();
        editor_[FIELD_NAME].config.onchange = function (newHtml) {
            $('#[FIELD_NAME]').val(newHtml);
        };
</script>
</div>\n
EOF;

    /**
     * @var string 富文本字段添加处理
     */
    public static string $controllerAddCode = <<<EOF
\$param['[FIELD_NAME]'] = \$request->param(false)['[FIELD_NAME]'];
\n
EOF;
    /**
     * @var string 富文本字段修改处理
     */
    public static string $controllerEditCode = <<<EOF
\$param['[FIELD_NAME]'] = \$request->param(false)['[FIELD_NAME]'];
\n
EOF;

    public static function create($data): string
    {
        return str_replace(array('[FORM_NAME]', '[FIELD_NAME]', '[FIELD_DEFAULT]'), array($data['form_name'], $data['field_name'], $data['field_default']), self::$html);
    }
}