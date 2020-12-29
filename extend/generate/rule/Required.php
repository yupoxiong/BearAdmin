<?php
/**
 * required规则相关
 */
namespace generate\rule;

class Required
{
    public static $ruleValidate =  <<<EOF
    '[FIELD_NAME]|[FORM_NAME]' => 'require',\n
EOF;

    public static $msgValidate =  <<<EOF
    '[FIELD_NAME].require' => '[FORM_NAME]不能为空',\n
EOF;

    public static $ruleForm =  <<<EOF
    '[FIELD_NAME]': {
        required: true,
    },\n
EOF;

    public static $msgForm =  <<<EOF
    '[FIELD_NAME]': {
        required: "[FORM_NAME]不能为空",
    },\n
EOF;

}