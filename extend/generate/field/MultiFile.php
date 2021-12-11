<?php
/**
 * 上传多文件
 * @author yupoxiong<i@yufuping.com>
 */

namespace generate\field;

class MultiFile extends Field
{
    public static string $html = <<<EOF
<div class="form-group row">
    <label for="[FIELD_NAME]" class="col-sm-2 col-form-label">[FORM_NAME]</label>
    <div class="col-sm-8"> 
        <input id="[FIELD_NAME]" name="[FIELD_NAME][]"  placeholder="请上传[FORM_NAME]" multiple="multiple" type="file" class="form-control fieldMultiFile" >
        <script>
            initUploadMultiFile('[FIELD_NAME]');
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