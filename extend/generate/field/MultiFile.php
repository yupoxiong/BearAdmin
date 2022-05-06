<?php
/**
 * 上传多文件
 * @author yupoxiong<i@yufuping.com>
 */

namespace generate\field;

class MultiFile extends Field
{
    public static string $html = <<<EOF
<div class="form-group row rowMultiFile">
    <label for="[FIELD_NAME]" class="col-sm-2 col-form-label">[FORM_NAME]</label>
    <div class="col-sm-8">
        <input id="[FIELD_NAME]_file" name="[FIELD_NAME]_file" type="file" class="file-loading " multiple>
            <input name="[FIELD_NAME]" id="[FIELD_NAME]" value="{\$data.[FIELD_NAME]|default=''}" hidden placeholder="请上传[FORM_NAME]" class="fieldMultiFile">
            <script>
                initUploadMultiFile('[FIELD_NAME]','','file');
            </script> 
    </div>
</div>
EOF;

    public static function create($data): string
    {
        $html = self::$html;
        return str_replace(array('[FORM_NAME]', '[FIELD_NAME]'), array($data['form_name'], $data['field_name']), $html);
    }
}