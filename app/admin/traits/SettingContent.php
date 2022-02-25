<?php

namespace app\admin\traits;

use RuntimeException;

trait SettingContent
{

    protected array $typeList = [
        'text', 'number', 'password', 'mobile', 'email', 'id_card', 'url', 'ip',
        'textarea', 'switch', 'select', 'multi_select', 'image', 'multi_image',
        'date', 'date_range', 'datetime', 'datetime_range', 'year', 'year_range',
        'year_month', 'year_month_range', 'map', 'color', 'icon', 'editor',
    ];

    /**
     * 获取配置内容
     * @param $param
     * @return array
     */
    protected function getContent($param): array
    {
        $content = [];
        foreach ($param['config_name'] as $key => $value) {
            if (($param['config_name'][$key]) === ''
                || ($param['config_field'][$key] === '')
                || ($param['config_type'][$key] === '')
            ) {
                throw new RuntimeException('设置信息不完整');
            }

            if ($param['config_option'][$key] === '' && in_array($param['config_type'][$key], ['select', 'multi_select'])) {
                throw new RuntimeException('设置信息不完整');
            }

            $this->checkName($value);
            $this->checkField($param['config_field'][$key]);
            $this->checkType($param['config_type'][$key]);

            $content[] = [
                'name'    => $value,
                'field'   => $param['config_field'][$key],
                'type'    => $param['config_type'][$key],
                'content' => strip_tags($param['config_content'][$key]),
                'option'  => strip_tags($param['config_option'][$key]),
            ];
        }
        return $content;
    }


    protected function checkName($name): void
    {
        $pattern = '/^[\x{4e00}-\x{9fa5}\x{9fa6}-\x{9fef}\x{3400}-\x{4db5}\x{20000}-\x{2ebe0}a-zA-Z0-9_\-]+$/u';
        if (!preg_match($pattern, $name)) {
            throw new RuntimeException('名称的值只能是汉字、字母、数字和下划线_及破折号-');
        }
    }

    protected function checkField($name): void
    {
        $pattern = '/^[a-zA-z]\w{2,49}$/';
        if (!preg_match($pattern, $name)) {
            throw new RuntimeException('字段的值只能是字母、数字、下划线组成，字母开头，3-50位');
        }
    }

    protected function checkType($name): void
    {
        if (!in_array($name, $this->typeList, true)) {
            throw new RuntimeException('类型非法');
        }
    }


}