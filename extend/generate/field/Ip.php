<?php
/**
 * 网址输入
 * @author yupoxiong<i@yufuping.com>
 */

namespace generate\field;

class Ip extends Field
{
    public static string $html = <<<EOF
<div class="form-group row rowIp">
    <label for="[FIELD_NAME]" class="col-sm-2 col-form-label">[FORM_NAME]</label>
    <div class="col-sm-10 col-md-4 formInputDiv">
        <input id="[FIELD_NAME]" name="[FIELD_NAME]" value="{\$data.[FIELD_NAME]|default='[FIELD_DEFAULT]'}" placeholder="请输入[FORM_NAME]" type="text" class="form-control fieldIp">
    </div>
</div>\n
EOF;

    public static array $rules = [
        'required' => '非空',
        'ipv4'     => 'IPV4',
        'ipv6'     => 'IPV6',
        'ip'     => 'IP',
    ];


    public static function create($data): string
    {
        return str_replace(array('[FORM_NAME]', '[FIELD_NAME]', '[FIELD_DEFAULT]'), array($data['form_name'], $data['field_name'], $data['field_default']), self::$html);
    }
}