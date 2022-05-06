<?php
/**
 * @author yupoxiong<i@yufuping.com>
 */

namespace generate\field;

class File extends Field
{

    public static string $html = <<<EOF
<div class="form-group row rowFile">
    <label for="[FIELD_NAME]" class="col-sm-2 col-form-label">[FORM_NAME]</label>
    <div class="col-sm-10 col-md-4 formInputDiv"> 
        <input id="[FIELD_NAME]_file" name="[FIELD_NAME]_file" type="file" class="file-loading " data-initial-preview="{if isset(\$data)}{\$data.[FIELD_NAME]}{/if}">
        <input name="[FIELD_NAME]" id="[FIELD_NAME]" value="{\$data.[FIELD_NAME]|default='[FIELD_DEFAULT]'}" hidden placeholder="请上传[FORM_NAME]" class="fieldFile">
    </div>

    <script>
        initUploadFile('[FIELD_NAME]','','file');
    </script>
</div>\n
EOF;

    public static function create($data): string
    {
        $html = self::$html;
        return str_replace(array('[FORM_NAME]', '[FIELD_NAME]'), array($data['form_name'], $data['field_name']), $html);
    }
}
