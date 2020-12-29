<?php
/**
 * 数字输入
 * @author yupoxiong<i@yufuping.com>
 */

namespace generate\field;

class Number extends Field
{
    public static $html = <<<EOF
<div class="form-group">
    <label for="[FIELD_NAME]" class="col-sm-2 control-label">[FORM_NAME]</label>
    <div class="col-sm-10 col-md-4">
         <div class="input-group">
            <input id="[FIELD_NAME]" name="[FIELD_NAME]" value="{\$data.[FIELD_NAME]|default='[FIELD_DEFAULT]'}" placeholder="请输入[FORM_NAME]" type="number" class="form-control field-number">
        </div>
    </div>
</div>
<script>
    $('#[FIELD_NAME]')
        .bootstrapNumber({
            upClass: 'success',
            downClass: 'primary',
            center: true
        });
</script>\n
EOF;

    public static $rules = [
        //非空
        'required'        => '非空',
        //纯数字
        'number'          => '纯数字',
        //整数
        'integer'         => '整数',
        //在某个范围
        'in:1,2,3'        => '在某个范围',
        //不在某个范围
        'notIn:1,2,3'     => '不在某个范围',
        //长度在某个范围
        'length:4,25'     => '长度在某个范围',
        //指定长度
        'length:[LENGTH]' => '指定长度',
        //最大长度
        'max:[LENGTH]'    => '最大长度',
        //最小长度
        'min:[LENGTH]'    => '最小长度',
        //浮点数
        'float'           => '浮点数',
        //等于某个值
        'eq:100'          => '等于某个值',
        //大于等于
        'egt:60'          => '大于等于',
        //大于
        'gt:60'           => '大于',
        //小于等于
        'elt:60'          => '小于等于',
        //小于
        'lt:60'           => '小于',
        //自定义正则
        // regex:\d{6}
        'regular'         => '自定义正则'
    ];

    public static function create($data)
    {
        return str_replace(array('[FORM_NAME]', '[FIELD_NAME]', '[FIELD_DEFAULT]'), array($data['form_name'], $data['field_name'], $data['field_default']), self::$html);
    }


}