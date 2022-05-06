<?php
/**
 * 上传单图
 * @author yupoxiong<i@yufuping.com>
 */

namespace generate\field;

class Video extends Field
{
    public static string $html = <<<EOF
<div class="form-group row rowVideo">
    <label for="[FIELD_NAME]" class="col-sm-2 col-form-label">[FORM_NAME]</label>
    <div class="col-sm-10 col-md-4 formInputDiv"> 
        <input id="[FIELD_NAME]_file" name="[FIELD_NAME]_file" type="file" class="file-loading" data-initial-preview="{if isset(\$data)}{\$data.[FIELD_NAME]}{/if}">
        <input name="[FIELD_NAME]" id="[FIELD_NAME]" value="{\$data.[FIELD_NAME]|default=''}" hidden placeholder="请上传[FORM_NAME]" class="fieldVideo">
    </div>
    
    <script>
        initUploadVideo('[FIELD_NAME]');
    </script>
</div>\n  
EOF;

    public static function create($data): string
    {
        $html = self::$html;
        return str_replace(array('[FORM_NAME]', '[FIELD_NAME]'), array($data['form_name'], $data['field_name']), $html);
    }
}