<?php
/**
 * 验证规则
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);

namespace generate\validate;

class Rule
{
    // 规则名称
    protected string $name = '';
    // 提示信息
    protected string $msg = '格式不正确';

    // 验证器规则
    public string $validateRule = <<<EOF
    '[FIELD_NAME]|[FORM_NAME]' => '[RULE_LIST]',\n
EOF;

    // 验证器提示信息
    public string $validateMsg = <<<EOF
    '[FIELD_NAME].[RULE_NAME]' => '[FORM_NAME][ERR_MSG]',\n
EOF;

    // 表单规则
    public string $formRule = <<<EOF
        [RULE_NAME]: true,\n
EOF;

    // 表单提示信息
    public string $formMsg = <<<EOF
        [RULE_NAME]: "[FORM_NAME][ERR_MSG]",\n
EOF;


    public static string $ruleList = <<<EOF
    '[FIELD_NAME]': {
        [RULE_LIST]
    },\n
EOF;

    public static string $msgList = <<<EOF
    '[FIELD_NAME]': {
       [MSG_LIST]
    },\n
EOF;

    public function getValidateRule($data = [], $rule_list = ''): string
    {
        $search  = [
            '[RULE_NAME]',
            '[ERR_MSG]',
            '[RULE_LIST]',
        ];
        $replace = [
            $this->name,
            $this->msg,
            $rule_list,
        ];

        if (isset($data['field_name'])) {
            $search[]  = '[FIELD_NAME]';
            $replace[] = $data['field_name'];
        }
        if (isset($data['form_name'])) {
            $search[]  = '[FORM_NAME]';
            $replace[] = $data['form_name'];
        }

        return str_replace($search, $replace, $this->validateRule);
    }

    public function getValidateMsg($data = [])
    {
        $search  = [
            '[RULE_NAME]', '[ERR_MSG]',
        ];
        $replace = [
            $this->name, $this->msg,
        ];

        if (isset($data['field_name'])) {
            $search[]  = '[FIELD_NAME]';
            $replace[] = $data['field_name'];
        }
        if (isset($data['form_name'])) {
            $search[]  = '[FORM_NAME]';
            $replace[] = $data['form_name'];
        }

        return str_replace($search, $replace, $this->validateMsg);
    }

    /**
     * 获取前端表单验证规则
     * @param array $data
     * @return string|string[]
     */
    public function getFormRule($data = [])
    {
        $search  = [
            '[RULE_NAME]',
        ];
        $replace = [
            $this->name,
        ];

        if (isset($data['field_name'])) {
            $search[]  = '[FIELD_NAME]';
            $replace[] = $data['field_name'];
        }
        if (isset($data['form_name'])) {
            $search[]  = '[FORM_NAME]';
            $replace[] = $data['form_name'];
        }

        return str_replace($search, $replace, $this->formRule);
    }

    /**
     * 获取前端验证提示信息
     * @param array $data
     * @return string|string[]
     */
    public function getFormMsg($data = [])
    {
        $search  = [
            '[RULE_NAME]',
            '[ERR_MSG]',
        ];
        $replace = [
            $this->name,
            $this->msg,
        ];
        if (isset($data['field_name'])) {
            $search[]  = '[FIELD_NAME]';
            $replace[] = $data['field_name'];
        }
        if (isset($data['form_name'])) {
            $search[]  = '[FORM_NAME]';
            $replace[] = $data['form_name'];
        }

        return str_replace($search, $replace, $this->formMsg);

    }

    /**
     * @return string
     */
    public function getMsg(): string
    {
        return $this->msg;
    }

    /**
     * @param string $msg
     */
    public function setMsg(string $msg): void
    {
        $this->msg = $msg;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }


}