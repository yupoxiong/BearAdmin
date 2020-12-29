<?php
/**
 * @author yupoxiong<i@yufuping.com>
 */

namespace generate\field;

use generate\traits\Tools;

class Field
{
    use Tools;


    //当前字段可用规则
    public static $rules = [
        //非空
        'required'   => '非空',
        'wechat'     => '微信号',
        'qq'         => 'QQ号',
        'account'    => '账号',
        'cn_name'    => '中文姓名',
        'car_number' => '车牌号',
        //自定义正则
        'regular'    => '自定义正则'
    ];


    //列表名字
    public static $listNameHtml = <<<EOF
            <th>[FORM_NAME]</th>\n
EOF;

    //列表字段
    public static $listFieldHtml = <<<EOF
            <td>{\$item.[FIELD_NAME]}</td>\n
EOF;

    //列表关联筛选
    public static $listSearchRelationHtml = <<<EOF
<div class="form-group">
    <select name="[FIELD_NAME]" id="[FIELD_NAME]" class="form-control input-sm index-search">
        <option value="">[FORM_NAME]</option>
        {foreach name='[FIELD_NAME1]_list' id='item'}
        <option value="{\$item.id}" {if isset($[FIELD_NAME]) && ''!==$[FIELD_NAME] && $[FIELD_NAME]==\$item.id}selected{/if}>{\$item.[RELATION_SHOW]}</option>
        {/foreach}
    </select>
</div>
<script>
    $(function () {
        $('#[FIELD_NAME]').select2({
        width:'100%'
        });
    });
</script>\n
EOF;

    //列表自定义筛选数据
    public static $listSearchSelectHtml = <<<EOF
<div class="form-group">
                        <select name="[FIELD_NAME]" id="[FIELD_NAME]" class="form-control input-sm index-search">
                            <option value="">[FORM_NAME]</option>
                            {foreach \$[FIELD_LIST] as \$key=>\$value}
                            <option value="{\$key}" {if isset($[FIELD_NAME]) && ''!==$[FIELD_NAME] && $[FIELD_NAME]==\$key}selected{/if}>{\$value}</option>
                            {/foreach}
                        </select>
                    </div>
                    <script>
                        $(function () {
                            $('#[FIELD_NAME]').select2({
                            width:'100%'
                            });
                        });
                    </script>\n
EOF;


    //列表日期筛选
    public static $listSearchDate = <<<EOF
<div class="form-group">
    <input value="{\$[FIELD_NAME]|default=''}" readonly name="[FIELD_NAME]" id="[FIELD_NAME]" class="form-control input-sm indexSearchDateRange" placeholder="[FORM_NAME]">
</div>
<script>
    laydate.render({
        elem: '#[FIELD_NAME]',
        range: true,
    });
</script>\n
EOF;

    //列表日期时间筛选
    public static $listSearchDatatime = <<<EOF
<div class="form-group">
    <input value="{\$[FIELD_NAME]|default=''}" readonly name="[FIELD_NAME]" id="[FIELD_NAME]" class="form-control input-sm indexSearchDatetimeRange" placeholder="[FORM_NAME]">
</div>
<script>
    laydate.render({
        elem: '#[FIELD_NAME]',
        type: 'datetime',
        range: true,
    });
</script>\n
EOF;



    //图片字段显示
    public static $listImgHtml = <<<EOF
<td><img class="dataListImg" src="{\$item.[FIELD_NAME]}"></td>\n
EOF;


    public static $listMultiImgHtml = <<<EOF
<td class="dataListMultiImg" id="[FIELD_NAME]ImgViewer{\$data_key}">
{foreach name='item.[FIELD_NAME]' id='item_[FIELD_NAME]'}
<img class="dataListImg" data-img="{\$item_[FIELD_NAME]}" src="{\$item_[FIELD_NAME]}">
{/foreach}
</td>
<script>
    $(function () {
        $('#[FIELD_NAME]ImgViewer{\$data_key}').viewer({
            url:'data-img',
        });
    });
</script>\n
EOF;

    public static $listMultiFileHtml = <<<EOF
<td class="dataListMultiFile">
{foreach name='item.[FIELD_NAME]' id='item_[FIELD_NAME]'}
<a target="_blank" href="{\$item_[FIELD_NAME]}">查看文件</a>
{/foreach}
</td>
EOF;


    //status字段获取器为switch的时候自动显示为field_name_text
    public static $listSwitchHtml = <<<EOF
<td>{\$item.[FIELD_NAME]_text}</td>\n
EOF;


    //验证器场景
    public static $validateSceneCode =
        <<<EOF
'add'  => [[RULE_FIELD]],
'edit' => [[RULE_FIELD]],\n
EOF;


    //获取选择的字段和字段对应的验证optionDOM代码
    public function getFormSelectOption($field_type)
    {
        $result     = ['text', ''];
        $field_info = $this->getFieldInfo($file_name = '', $field_type);
        switch ($field_info['type']) {
            case 'tinyint':
                $result[0] = 'switch';
                break;
            case 'smallint':
                $result[0] = 'number';
                break;
            case 'mediumint':
                $result[0] = 'number';
                break;
            case 'int':
                $result[0] = 'number';
                break;
            case 'bigint':
                $result[0] = 'number';
                break;
            case 'float':
                $result[0] = 'number';
                break;
            case 'double':
                $result[0] = 'number';
                break;
            case 'decimal':
                $result[0] = 'number';
                break;
            case 'char':
                $result[0] = 'text';
                break;
            case 'varchar':
                $result[0] = 'text';
                break;
            case 'tinytext':
                $result[0] = 'editor';
                break;
            case 'tinyblob':
                $result[0] = 'editor';
                break;
            case 'text':
                $result[0] = 'editor';
                break;
            case 'blob':
                $result[0] = 'editor';
                break;
            case 'longtext':
                $result[0] = 'editor';
                break;
            case 'longblob':
                $result[0] = 'editor';
                break;
            case 'date':
                $result[0] = 'date';
                break;
            case 'datetime':
                $result[0] = 'datetime';
                break;
            case 'timestamp':
                $result[0] = 'datetime';
                break;
            case 'time':
                $result[0] = 'time';
                break;
            case 'year':
                $result[0] = 'year';
                break;

            default:
                $result[0] = 'text';
                break;
        }


        $result[1] = $this->getValidateOption($result[0], $field_info['length']);
        return $result;
    }

    //根据表单类型和长度返回相应的验证
    public function getValidateOption($type, $length = '')
    {
        switch ($type) {
            case 'text':
                $data = Text::rule($length);
                break;
            case 'number':
                $data = Number::rule($length);
                break;
            case 'password':
                $data = Password::rule($length);
                break;
            case 'mobile':
                $data = Mobile::rule($length);
                break;
            case 'email':
                $data = Email::rule($length);
                break;
            case 'id_card':
                $data = IdCard::rule($length);
                break;
            case 'url':
                $data = Url::rule($length);
                break;
            case 'ip':
                $data = Ip::rule($length);
                break;
            case 'texterea':
                $data = Textarea::rule($length);
                break;
            case 'checkbox':
                $data = Checkbox::rule($length);
                break;
            case 'switch':
                $data = SwitchField::rule($length);
                break;
            case 'radio':
                $data = Radio::rule($length);
                break;
            case 'select':
                $data = Select::rule($length);
                break;
            case 'multi_select':
                $data = MultiSelect::rule($length);
                break;
            case 'image':
                $data = Image::rule($length);
                break;
            case 'multi_image':
                $data = MultiImage::rule($length);
                break;
            case 'file':
                $data = File::rule($length);
                break;
            case 'multi_file':
                $data = MultiFile::rule($length);
                break;
            case 'date':
                $data = Date::rule($length);
                break;
            case 'date_range':
                $data = DateRange::rule($length);
                break;
            case 'datetime':
                $data = Datetime::rule($length);
                break;
            case 'datetime_range':
                $data = DatetimeRange::rule($length);
                break;
            case 'year':
                $data = Year::rule($length);
                break;
            case 'year_range':
                $data = YearRange::rule($length);
                break;
            case 'year_month':
                $data = YearMonth::rule($length);
                break;
            case 'year_month_range':
                $data = YearMonthRange::rule($length);
                break;
            case 'map':
                $data = Map::rule($length);
                break;
            case 'color':
                $data = Color::rule($length);
                break;
            case 'icon':
                $data = Icon::rule($length);
                break;
            case 'editor':
                $data = Editor::rule($length);
                break;
            default:
                $data = Text::rule($length);
                break;
        }


        return $data;
    }


    //规则
    public static function rule($length = '')
    {

        $html  = '';
        $rules = static::$rules;
        foreach ($rules as $key => $value) {
            $key  = str_replace('[LENGTH]', $length, $key);
            $html .= '<option value="' . $key . '">' . $value . '</option>';
        }
        return $html;
    }

    public static function createHtmlRule($rule)
    {
        $html = '';
        if (key_exists($rule, self::$rules)) {
            $html .= '';
        }
    }

}