<?php
/**
 * api相关
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);

namespace generate;

use Exception;
use generate\exception\GenerateException;

class ApiServiceBuild
{
    /**
     * @var array 数据
     */
    protected array $data;
    /**
     * @var array 配置
     */
    protected array $config;

    protected array $template;

    protected string $code;

    protected array $actionList = [
        'index', 'add', 'info', 'edit', 'del', 'disable', 'enable'
    ];

    public function __construct($data, $config)
    {
        $this->data   = $data;
        $this->config = $config;

        $this->template = $this->config['template']['api'];
        $this->code = file_get_contents($this->template['service']);
    }

    /**
     * 创建API模块Service相关代码
     * @return bool
     * @throws GenerateException
     */
    public function create(): bool
    {
        // 不生成api控制器的时候也不生成api模块的service
        if (!$this->data['api_controller']['create']) {
            return true;
        }
        $this->createFunc();

        $out_file = $this->config['file_dir']['api_service'] . $this->data['api_controller']['name'] . 'Service' . '.php';

        $replace_content = [
            '[NAME]'            => $this->data['cn_name'],
            '[TABLE_NAME]'      => $this->data['table'],
            '[MODEL_NAME]'      => $this->data['model']['name'],
            '[MODEL_MODULE]'    => $this->data['model']['module'],
            '[VALIDATE_NAME]'   => $this->data['validate']['name'],
            '[VALIDATE_MODULE]' => $this->data['validate']['module'],
            '[SERVICE_NAME]'    => $this->data['api_controller']['name'],
            '[SERVICE_MODULE]'  => $this->data['api_controller']['module'],
            '[FIELD_LIST]'      => $this->getShowFieldList(),
        ];

        foreach ($replace_content as $key => $value) {
            $this->code = str_replace($key, $value, $this->code);
        }

        try {
            file_put_contents($out_file, $this->code);
        } catch (Exception $e) {
            throw new GenerateException($e->getMessage());
        }
        return true;
    }

    /**
     * 生成方法
     */
    public function createFunc(): void
    {
        foreach ($this->actionList as $action) {
            $code  = '';
            $upper = strtoupper($action);
            if (in_array($action, $this->data['api_controller']['action'], true)) {
                $code = file_get_contents($this->template['service_' . $action]);
            }
            $this->code = str_replace('[SERVICE_' . $upper . ']', $code, $this->code);
        }
    }

    public function getShowFieldList(): string
    {
        $field_list = '';
        foreach ($this->data['data'] as $value) {
            if ((int)$value['is_list'] === 1) {
                $field_list .= empty($field_list) ? $value['field_name'] : ',' . $value['field_name'];
            }
        }
        return $field_list;
    }
}
