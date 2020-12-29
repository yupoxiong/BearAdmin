<?php
/**
 * 上传多图
 * @author yupoxiong<i@yufuping.com>
 */

namespace generate\field;

class MultiImage extends Field
{
    public static $html = <<<EOF

<div class="form-group">
    <label for="[FIELD_NAME]" class="col-sm-2 control-label">[FORM_NAME]</label>
    <div class="col-sm-10 col-md-4"> 
        <input id="[FIELD_NAME]" name="[FIELD_NAME][]"  placeholder="请上传[FORM_NAME]" multiple="multiple" type="file" class="form-control field-multi-image" >
    </div>
</div>
<script>
$(function() {
    $('#[FIELD_NAME]').fileinput({
        "initialPreview":false,
        overwriteInitial: true,
        language: 'zh',
        browseLabel: '浏览',
        initialPreviewAsData: true,
        initialPreviewShowDelete:false,
        dropZoneEnabled: false,
        showUpload:false,
        showRemove: false,
        allowedFileTypes:['image'],
        //默认限制10M
        maxFileSize:10240,
        {if isset(\$data)}
        initialPreview:{\$data->getData('[FIELD_NAME]')|raw},
        {/if}
    });
})
</script>\n
EOF;


    //控制器添加上传多图
    public static $controllerAddCode = <<<EOF
//处理[FORM_NAME]上传
\$attachment_[FIELD_NAME] = new \app\common\model\Attachment;
\$file       = \$attachment_[FIELD_NAME]->uploadMulti('[FIELD_NAME]');
if (\$file) {
    \$param['[FIELD_NAME]'] = \$file;
} else {
    return admin_error(\$attachment_[FIELD_NAME]->getError());
}
\n
EOF;

    //控制器修改上传多图
    public static $controllerEditCode = <<<EOF
//处理[FORM_NAME]上传
if (!empty(\$_FILES['[FIELD_NAME]']['name'][0])) {
    \$attachment_[FIELD_NAME] = new \app\common\model\Attachment;
    \$file       = \$attachment_[FIELD_NAME]->uploadMulti('[FIELD_NAME]');
    if (\$file) {
        \$param['[FIELD_NAME]'] = \$file;
    } else {
        return admin_error(\$attachment_[FIELD_NAME]->getError());
    }
}
\n
EOF;


    //多图上传模型内修改器与获取器
    public static $modelAttrCode = <<<EOF
//[FORM_NAME]获取器
public function get[FIELD_NAME]Attr(\$value)
{
    return json_decode(\$value,true);
}

//[FORM_NAME]修改器
public function set[FIELD_NAME]Attr(\$value)
{
    return json_encode(\$value);
}
\n
EOF;


    public static $rules = [
        'required'   => '非空',
        'file_size'  => '文件大小限制',
        'file_image' => '图片类型',
        'regular'    => '自定义正则'
    ];


    public static function create($data)
    {
        $html = self::$html;
        $html = str_replace(array('[FORM_NAME]', '[FIELD_NAME]'), array($data['form_name'], $data['field_name']), $html);
        return $html;
    }
}