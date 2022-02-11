<?php
/**
 * 验证器生成
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);

namespace generate;

use Exception;
use generate\field\Field;
use generate\validate\Rule;
use generate\exception\GenerateException;

class ValidateBuild extends Build
{
    /**
     * ValidateBuild constructor.
     * @param array $data 数据
     * @param array $config 配置
     */
    public function __construct(array $data, array $config)
    {
        $this->data   = $data;
        $this->config = $config;

        $this->template = $this->config['template']['validate'];
        $this->code = file_get_contents($this->template['validate']);
    }

    /**
     * 生成验证器
     * @return bool
     * @throws GenerateException
     */
    public function run(): bool
    {
        // 不生成验证器
        if (!$this->data['validate']['create']) {
            return true;
        }
        $search = [
            '[NAME]',
            '[VALIDATE_NAME]',
            '[VALIDATE_MODULE]',
        ];

        $replace = [
            $this->data['cn_name'],
            $this->data['validate']['name'],
            $this->data['validate']['module'],
        ];

        $code = str_replace($search, $replace, $this->code);

        $rule_class = new Rule();

        $rule_code      = '';
        $msg_code       = '';
        $scene_code     = Field::$validateSceneCode;
        $scene_code_tmp = '';
        foreach ($this->data['data'] as $value) {

            if ($value['form_type'] !== 'none') {
                $temp_rule_code = '';
                foreach ($value['form_validate'] as $value_name) {
                    $class_name = '\\generate\\validate\\' . parse_name($value_name, 1);
                    if (class_exists($class_name)) {
                        /** @var Rule $class */
                        $class = (new $class_name);

                        $validate_rule_name = $value_name==='required'?'require':$value_name;

                        $temp_rule_code .= $temp_rule_code === '' ? $validate_rule_name : '|' . $validate_rule_name;
                        $msg_code       .= $class->getValidateMsg($value);
                    }
                }

                if($temp_rule_code!==''){
                    $rule_code      .= $rule_class->getValidateRule($value, $temp_rule_code);
                }
                $scene_code_tmp .= "'" . $value['field_name'] . "', ";
            }
        }

        $scene_code = str_replace('[RULE_FIELD]', $scene_code_tmp, $scene_code);

        $search  = [
            '[VALIDATE_RULE]',
            '[VALIDATE_MSG]',
            '[VALIDATE_SCENE]',
        ];
        $replace = [
            $rule_code,
            $msg_code,
            $scene_code,
        ];

        $code = str_replace($search, $replace, $code);

        $out_file = $this->config['file_dir']['validate'] . $this->data['validate']['name'] . 'Validate' . '.php';
        try {
            file_put_contents($out_file, $code);
        } catch (Exception $e) {
            throw new GenerateException($e->getMessage());
        }
        return true;
    }
}
