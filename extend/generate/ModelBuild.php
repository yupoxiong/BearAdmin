<?php
/**
 * 模型生成
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);

namespace generate;

use generate\exception\GenerateException;
use think\facade\Db;
use Exception;

class ModelBuild extends Build
{
    public function __construct($data, $config)
    {
        $this->data   = $data;
        $this->config = $config;

        $this->template = $this->config['template']['model'];
        $this->code     = file_get_contents($this->template['model']);
    }

    /**
     * 创建模型相关代码
     * @throws GenerateException
     */
    public function run(): bool
    {
        // 不生成模型
        if (!$this->data['model']['create']) {
            return true;
        }

        $this->autoTimestamp();
        $this->softDelete();

        $code = $this->code;
        $code = str_replace(array('[NAME]', '[TABLE_NAME]', '[MODEL_NAME]', '[MODEL_MODULE]'), array($this->data['cn_name'], $this->data['table'], $this->data['model']['name'], $this->data['model']['module']), $code);

        // 关联
        $relation_code = '';
        // 获取器/修改器
        $getter_setter_code = '';
        // 自定义选择数据
        $select_data_code = '';
        foreach ($this->data['data'] as $value) {

            if ($value['relation_type'] > 0) {
                $relation_code .= $this->getRelationCode($value);
            } else if ($value['form_type'] === 'select') {
                $code_result        = $this->getSelectCode($value);
                $select_data_code   .= $code_result[0];
                $getter_setter_code .= $code_result[1];
            } else if ($value['form_type'] === 'multi_select') {
                $code_result        = $this->getSelectCode($value, true);
                $select_data_code   .= $code_result[0];
                $getter_setter_code .= $code_result[1];
            } else if ($value['index_search'] === 'select') {
                $code_result        = $this->getSelectCode($value);
                $select_data_code   .= $code_result[0];
                $getter_setter_code .= $code_result[1];
            }
            if ($value['getter_setter']) {
                $getter_setter_code .= $this->getGetterSetterCode($value);
            }
        }

        $code = str_replace(array('[RELATION]', '[GETTER_SETTER]', '[SELECT_DATA_LIST]'), array($relation_code, $getter_setter_code, $select_data_code), $code);

        // 搜索字段
        $search_field = '';
        // 条件字段
        $where_field = '';
        // 多选条件字段
        $multi_where_field = '';
        //日期/时间范围查询字段
        $time_field = '';
        foreach ($this->data['data'] as $value) {
            switch ($value['index_search']) {
                case 'search':
                    $search_field .= "'" . $value['field_name'] . "',";
                    break;

                case 'select':
                    $where_field .= "'" . $value['field_name'] . "',";
                    break;
                case 'multi_select':
                    $multi_where_field .= "'" . $value['field_name'] . "',";
                    break;
                case 'date':
                case 'datetime':
                    $time_field .= "'" . $value['field_name'] . "',";
                    break;
                default:
                    break;
            }
        }

        // 搜索筛选字段替换
        $code = str_replace(array('[SEARCH_FIELD]', '[WHERE_FIELD]', '[MULTI_WHERE_FIELD]', '[TIME_FIELD]'), array($search_field, $multi_where_field, $where_field, $time_field), $code);
        try {
            file_put_contents($this->config['file_dir']['model'] . $this->data['model']['name'] . '.php', $code);
        } catch (Exception $e) {
            throw new GenerateException($e->getMessage());
        }
        return true;
    }


    /**
     * 获取关联代码
     * @param $value
     * @return false|string|string[]
     */
    public function getRelationCode($value)
    {
        $tmp_code = file_get_contents($this->template['relation']);
        if ($value['relation_type'] === 1 || $value['relation_type'] === 2) {
            // 外键
            $relation_type = 'belongsTo';
            $table_name    = $this->getSelectFieldFormat($value['field_name']);
            // 表中文名
            $cn_name    = '';
            $table_info = Db::query('SHOW TABLE STATUS LIKE ' . "'" . $table_name . "'");
            if ($table_info) {
                $cn_name = $table_info[0]['Comment'] ?? '';
            }
            $relation_name  = parse_name($table_name, 1, false);
            $relation_class = parse_name($table_name, 1);
            return str_replace(array('[RELATION_NAME]', '[RELATION_TYPE]', '[CLASS_NAME]', '[CN_NAME]'), array($relation_name, $relation_type, $relation_class, $cn_name), $tmp_code);
        }

        $result = '';
        // 主键
        $relation_type = $value['relation_type'] === 3 ? 'hasOne' : 'hasMany';

        $table_tmp = explode(',', $value['relation_table']);
        foreach ($table_tmp as $item) {
            $table_name     = parse_name($item, 0, false);
            $relation_name  = parse_name($table_name, 1, false);
            $relation_class = parse_name($item, 1);

            //表中文名
            $cn_name    = '';
            $table_info = Db::query('SHOW TABLE STATUS LIKE ' . "'" . $table_name . "'");
            if ($table_info) {
                $cn_name = $table_info[0]['Comment'] ?? '';
            }

            $tmp_code_item = str_replace(array('[RELATION_NAME]', '[RELATION_TYPE]', '[CLASS_NAME]', '[CN_NAME]'), array($relation_name, $relation_type, $relation_class, $cn_name), $tmp_code);
            $result        .= $tmp_code_item;
        }

        return $result;
    }

    /**
     * 获取自定义筛选代码
     * @param $value
     * @param bool $multi
     * @return array
     * @throws GenerateException
     */
    public function getSelectCode($value, bool $multi = false): array
    {
        // 如果是select，同时非关联
        $field_select_data = $value['field_select_data'];

        if (empty($field_select_data)) {
            throw new GenerateException('请完善字段[' . $value['form_name'] . ']的自定义筛选/select数据');
        }

        $const_name = $this->getSelectFieldFormat($value['field_name'], 3);

        $options     = explode("\r\n", $field_select_data);
        $option_code = '// ' . $value['form_name'] . "列表\n" . 'const ' . $const_name . "= [\n";
        foreach ($options as $item) {
            $option_key_value = explode('||', $item);
            if (is_numeric($option_key_value[0])) {
                $option_item_key = $option_key_value[0];
            } else {
                $option_item_key = "'$option_key_value[0]'";
            }

            $option_code .= ($option_item_key . "=>'$option_key_value[1]',\n");
        }
        $option_code .= "];\n";

        // 处理select自定义数据的获取器
        $field5             = $this->getSelectFieldFormat($value['field_name'], 5);
        $field4             = $this->getSelectFieldFormat($value['field_name'], 3);
        $getter_setter_code = file_get_contents($this->template[$multi ? 'getter_setter_multi_select' : 'getter_setter_select']);
        $getter_setter_code = str_replace(array('[FORM_NAME]', '[FIELD_NAME5]', '[FIELD_NAME4]', '[FIELD_NAME]', '[FIELD_NAME2]'), array($value['form_name'], $field5, $field4, $value['field_name'], parse_name($value['field_name'], 1)), $getter_setter_code);

        return [$option_code, $getter_setter_code];

    }

    /**
     * 获取器相关代码生成
     * @param $value
     * @return false|string|string[]
     */
    public function getGetterSetterCode($value)
    {
        switch ($value['getter_setter']) {
            case 'switch':
                $code = file_get_contents($this->template['getter_setter_switch']);
                $code = str_replace(array('[FIELD_NAME]', '[FORM_NAME_LOWER]', '[FORM_NAME]'), array(parse_name($value['field_name'], 1), $value['field_name'], $value['form_name']), $code);
                break;
            case 'date':
                $code = file_get_contents($this->template['getter_setter_date']);
                $code = str_replace(array('[FIELD_NAME]', '[FORM_NAME]'), array(parse_name($value['field_name'], 1), $value['form_name']), $code);
                break;
            case 'datetime':
                $code = file_get_contents($this->template['getter_setter_datetime']);
                $code = str_replace(array('[FIELD_NAME]', '[FORM_NAME]'), array(parse_name($value['field_name'], 1), $value['form_name']), $code);
                break;
            default:
                $code = '';
                break;
        }
        return $code;
    }

    /**
     * 软删除
     */
    protected function softDelete(): bool
    {
        $soft_delete1 = '';
        $soft_delete2 = '';
        // 软删除
        if ($this->data['model']['soft_delete']) {
            $soft_delete1 = 'use think\model\concern\SoftDelete;';
            $soft_delete2 = 'use SoftDelete;';
        }

        $this->code = str_replace(array('[SOFT_DELETE_USE1]', '[SOFT_DELETE_USE2]',), array($soft_delete1, $soft_delete2), $this->code);
        return true;
    }

    /**
     * 自动时间戳
     * @return bool
     */
    protected function autoTimestamp(): bool
    {
        $auto_time = '';
        // 自动时间戳
        if ($this->data['model']['timestamp']) {
            $auto_time = 'protected $autoWriteTimestamp = true;';
        }

        $this->code = str_replace('[AUTO_TIMESTAMP]', $auto_time, $this->code);
        return true;
    }
}
