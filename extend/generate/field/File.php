<?php
/**
 * @author yupoxiong<i@yufuping.com>
 */

namespace generate\field;

class File extends Field
{

    public static string $html = <<<EOF
<div class="form-group row">
    <label for="[FIELD_NAME]" class="col-sm-2 col-form-label">[FORM_NAME]</label>
    <div class="col-sm-10 col-md-4 formInputDiv"> 
        <input id="[FIELD_NAME]" name="[FIELD_NAME]"  placeholder="请上传[FORM_NAME]" data-initial-preview="{\$data.[FIELD_NAME]|default=''}" type="file" class="form-control fieldFile" >
    </div>
</div>
<script>
    initUploadFile('[FIELD_NAME]');
</script>   
EOF;

    public static function create($data): string
    {
        $html = self::$html;
        return str_replace(array('[FORM_NAME]', '[FIELD_NAME]'), array($data['form_name'], $data['field_name']), $html);
    }
}
