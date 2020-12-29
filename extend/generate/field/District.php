<?php
/**
 * 区-省市区
 * @author yupoxiong<i@yufuping.com>
 */

namespace generate\field;

class District extends Field
{

    public static $html = <<<EOF
<div class="form-group">
    <label for="[FIELD_NAME]" class="col-sm-2 control-label">[FORM_NAME]</label>
    <div class="col-sm-10 col-md-4">
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-list"></i></span>
            <select name="[FIELD_NAME]" id="[FIELD_NAME]" class="form-control field-district"  onchange="getRegion(this.value,3)">
                {foreach name='district' id='item'}
                 <option value="{\$item.id}" {if \$item.id==\$info.[FIELD_NAME]}selected{/if}>{\$item.name}</option>
                {/foreach}
            </select>
        </div>
    </div>
</div>
<script>
 $('#[FIELD_NAME]').select2();
 
</script>\n
EOF;

    public static $rules = [
        'required'   => '非空',
        'regular'    => '自定义正则'
    ];


    public static function create($data)
    {
        $html = self::$html;
        $html = str_replace(array('[FORM_NAME]', '[FIELD_NAME]'), array($data['form_name'], $data['field_name']), $html);
        return $html;
    }
}