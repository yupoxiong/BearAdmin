<?php
/**
 * @author yupoxiong<i@yufuping.com>
 */

namespace generate\field;

class File extends Field
{

    public static $html = <<<EOF
<div class="form-group">
    <label for="[FIELD_NAME]" class="col-sm-2 control-label">[FORM_NAME]</label>
    <div class="col-sm-10 col-md-4"> 
        <input id="[FIELD_NAME]" name="[FIELD_NAME]"  placeholder="请上传[FORM_NAME]" data-initial-preview="{\$data.[FIELD_NAME]|default=''}" type="file" class="form-control field-file" >
    </div>
</div>
<script>
    $('#[FIELD_NAME]').fileinput({
        language: 'zh',
        browseLabel: '浏览',
        initialPreviewAsData: true,
        dropZoneEnabled: false,
        showUpload:false,
        showRemove: false,
        allowedFileExtensions: ['jpg', 'png', 'gif','bmp','svg','jpeg','mp4','doc','docx','pdf','xls','xlsx','ppt','pptx','txt'],
        //默认限制10M
        maxFileSize:10240
    });
</script>\n
EOF;



    public static $rules = [
        'required'  => '非空',
        'file_size' => '文件大小限制',
        'file_ext'  => '文件后缀限制',
        'regular'   => '自定义正则'
    ];


    //控制器添加上传
    public static $controllerAddCode =
        <<<EOF
            //处理[FORM_NAME]上传
            \$attachment_[FIELD_NAME] = new \app\common\model\Attachment;
            \$file_[FIELD_NAME]       = \$attachment_[FIELD_NAME]->upload('[FIELD_NAME]');
            if (\$file_[FIELD_NAME]) {
                \$param['[FIELD_NAME]'] = \$file_[FIELD_NAME]->url;
            } else {
                return admin_error(\$attachment_[FIELD_NAME]->getError());
            }
            \n
EOF;


    //控制器修改上传
    public static $controllerEditCode =
        <<<EOF
            //处理[FORM_NAME]上传
            if (!empty(\$_FILES['[FIELD_NAME]']['name'])) {
                \$attachment_[FIELD_NAME] = new \app\common\model\Attachment;
                \$file_[FIELD_NAME]       = \$attachment_[FIELD_NAME]->upload('[FIELD_NAME]');
                if (\$file_[FIELD_NAME]) {
                    \$param['[FIELD_NAME]'] = \$file_[FIELD_NAME]->url;
                }
            }
            \n
EOF;



    public static function create($data)
    {
        $html = self::$html;
        $html = str_replace(array('[FORM_NAME]', '[FIELD_NAME]'), array($data['form_name'], $data['field_name']), $html);
        return $html;
    }
}
