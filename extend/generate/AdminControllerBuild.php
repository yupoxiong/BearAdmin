<?php
/**
 * 后台控制器生成
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);

namespace generate;

use Exception;
use generate\field\Editor;
use generate\exception\GenerateException;

class AdminControllerBuild extends Build
{
    // 可生成的action列表
    protected array $actionList = [
        'index', 'add', 'info', 'edit', 'del', 'disable', 'enable', 'import', 'export'
    ];

    /**
     * AdminControllerBuild constructor.
     * @param array $data 数据
     * @param array $config 配置
     */
    public function __construct(array $data, array $config)
    {
        $this->data   = $data;
        $this->config = $config;

        $this->template = $this->config['template']['admin'];

        $this->code = file_get_contents($this->template['controller']);
    }

    /**
     * 创建admin模块控制器相关代码
     * @return bool
     * @throws GenerateException
     */
    public function run(): bool
    {
        // 不生成后台控制器
        if (!$this->data['admin_controller']['create']) {
            return true;
        }

        // 生成action
        $this->createAction();

        $add_field_code  = '';
        $edit_field_code = '';

        //关联代码
        $relation_1 = '';
        $relation_2 = '';
        $relation_3 = '';

        //导出代码
        $export_header = '';
        $export_body   = '';
        //with代码
        $relation_with = '';
        //with列表
        $relation_with_list = '';

        // 导入代码
        $import_field = '';
        $import_code  = '';
        $import_name  = '';

        // 列表页关联查询
        $index_select = '';

        foreach ($this->data['data'] as $value) {
            if ($value['form_type'] !== 'none') {
                $add_field_code_tmp  = '';
                $edit_field_code_tmp = '';
                if ($value['form_type'] === 'editor') {
                    $add_field_code_tmp  = Editor::$controllerAddCode;
                    $edit_field_code_tmp = Editor::$controllerEditCode;
                }

                $add_field_code_tmp = str_replace(
                    ['[FORM_NAME]', '[FIELD_NAME]'],
                    [$value['form_name'], $value['field_name']],
                    $add_field_code_tmp);

                $add_field_code .= $add_field_code_tmp;

                $edit_field_code_tmp = str_replace(
                    ['[FORM_NAME]', '[FIELD_NAME]'],
                    [$value['form_name'], $value['field_name']],
                    $edit_field_code_tmp);

                $edit_field_code .= $edit_field_code_tmp;

                // 自定义select处理
                if ($value['relation_type'] === 0 && in_array($value['form_type'], ['select', 'multi_select'])) {

                    $list_name  = $this->getSelectFieldFormat($value['field_name'], 2);
                    $const_name = $this->getSelectFieldFormat($value['field_name'], 3);
                    $assign     = "'$list_name'=>" . parse_name($this->data['table'], 1) . '::' . $const_name . ',';
                    $relation_3 .= $assign;
                }

                // 关联处理
                switch ($value['relation_type']) {
                    default:
                        break;
                    case 1:// 外键一对一
                    case 2:// 外键一对多
                        $table_name = $this->getSelectFieldFormat($value['field_name']);
                        $class_name = parse_name($table_name, 1);
                        $relation_1 .= 'use app\\common\\model\\' . $class_name . ";\n";

                        $code_3     = file_get_contents($this->template['relation_data_list']);
                        $list_name  = $this->getSelectFieldFormat($value['field_name'], 2);
                        $code_3     = str_replace(array('[LIST_NAME]', '[CLASS_NAME]'), array($list_name, $class_name), $code_3);
                        $relation_3 .= $code_3;
                        break;
                    case 3:
                    case 4:
                        $list_name  = $this->getSelectFieldFormat($value['field_name'], 2);
                        $const_name = $this->getSelectFieldFormat($value['field_name'], 3);
                        $assign     = "'$list_name'=>" . $this->data['table'] . '::' . $const_name . ',';
                        $relation_3 .= $assign;
                        break;
                }

                // 这里处理导入，表单字段为导入字段
                $import_field .= "'" . $value['field_name'] . "',";
                $import_name  .= "'" . $value['form_name'] . "',";
            }

            if ($value['index_search'] === 'select') {
                if ($value['relation_type'] === 1 || $value['relation_type'] === 2) {
                    $table_name        = $this->getSelectFieldFormat($value['field_name']);
                    $select_class_name = parse_name($table_name, 1);
                    $select_list_name  = $this->getSelectFieldFormat($value['field_name'], 2);
                    $code_select       = file_get_contents($this->template['relation_data_list']);
                    $code_select       = str_replace(array('[LIST_NAME]', '[CLASS_NAME]'), array($select_list_name, $select_class_name), $code_select);

                    $index_select .= $code_select;
                } else if ($value['relation_type'] === 0) {
                    // 这里是处理select字段的选择列表

                    $list_name  = $this->getSelectFieldFormat($value['field_name'], 2);
                    $const_name = $this->getSelectFieldFormat($value['field_name'], 3);

                    $assign = "'$list_name'=>" . parse_name($this->data['table'], 1) . '::' . $const_name . ',';

                    $index_select .= $assign;
                }
            }

            if ($value['is_list'] === 1) {
                //列表关联显示
                if ($value['relation_type'] === 1 || $value['relation_type'] === 2) {
                    $relation_with_name = $this->getSelectFieldFormat($value['field_name']);
                    $relation_with_list .= "'" . $relation_with_name . "',";
                }

                //如果有列表导出
                if (in_array('export', $this->data['admin_controller']['action'], true)) {
                    $export_header .= "'" . $value['form_name'] . "',";
                    if ($value['getter_setter'] === 'switch') {
                        $export_body .= '$record[' . "'" . $value['field_name'] . "'" . '] = $item->' . $value['field_name'] . '_text' . ";\n";
                    } else if ($value['relation_type'] === 1 || $value['relation_type'] === 2) {
                        $relation_name = $this->getSelectFieldFormat($value['field_name']);
                        $export_body   .= '$record[' . "'" . $value['field_name'] . "'" . '] = $item->' . $relation_name . '->' . $value['relation_show'] . '?? ' . "'" . "'" . ";\n";
                    } else {
                        $export_body .= '$record[' . "'" . $value['field_name'] . "'" . '] = $item->' . $value['field_name'] . ";\n";
                    }
                }
            }
        }

        // 导出
        if ($export_header !== '') {
            $export_name = $this->data['cn_name'] . '数据';
            $this->code  = str_replace(array('[HEADER_LIST]', '[BODY_ITEM]', '[FILE_NAME]'), array($export_header, $export_body, $export_name,), $this->code);
        }

        // 导入
        if ($import_field !== '') {
            $table_name = $this->data['table'];
            $this->code = str_replace(array('[TABLE_NAME]', '[FILED_LIST]', '[FILED_NAME_LIST]'), array($table_name, $import_field, $import_name), $this->code);
        }

        if ($relation_3 !== '') {
            $code_2     = file_get_contents($this->template['relation_assign_1']);
            $code_2     = str_replace('[RELATION_LIST]', $relation_3, $code_2);
            $relation_2 = $code_2;
        }

        //如果有列表显示
        if ($relation_with_list !== '') {
            $relation_with = file_get_contents($this->template['relation_with']);
            $relation_with = str_replace('[WITH_LIST]', $relation_with_list, $relation_with);
        }

        //控制器添加方法特殊字段处理
        //控制器修改方法特殊字段处理
        $this->code = str_replace(
            array('[ADD_FIELD_CODE]', '[EDIT_FIELD_CODE]', '[RELATION_1]', '[RELATION_2]', '[RELATION_3]', '[IMPORT_CODE]', '[RELATION_WITH]', '[SEARCH_DATA_LIST]'),
            array($add_field_code, $edit_field_code, $relation_1, $relation_2, $relation_3, $import_code, $relation_with, $index_select),
            $this->code
        );

        $this->createFile();
        return true;
    }

    /**
     * 生成文件
     * @param null $code
     * @param null $path
     * @return bool
     * @throws GenerateException
     */
    public function createFile($code = null, $path = null): bool
    {
        $replace_content = [
            '[NAME]'              => $this->data['cn_name'],
            '[TABLE_NAME]'        => $this->data['table'],
            '[CONTROLLER_NAME]'   => $this->data['admin_controller']['name'],
            '[CONTROLLER_MODULE]' => $this->data['admin_controller']['module'],
            '[MODEL_NAME]'        => $this->data['model']['name'],
            '[MODEL_MODULE]'      => $this->data['model']['module'],
            '[VALIDATE_NAME]'     => $this->data['validate']['name'],
            '[VALIDATE_MODULE]'   => $this->data['validate']['module'],
            '[SERVICE_NAME]'      => $this->data['api_controller']['name'],
            '[SERVICE_MODULE]'    => $this->data['api_controller']['module'],
        ];

        foreach ($replace_content as $key => $value) {
            $this->code = str_replace($key, $value, $this->code);
        }

        if (is_null($code)) {
            $code = $this->code;
        }

        if (is_null($path)) {
            $path = $this->config['file_dir']['admin_controller']
                . $this->data['admin_controller']['name']
                . 'Controller'
                . '.php';
        }
        try {
            file_put_contents($path, $code);
        } catch (Exception $e) {
            throw new GenerateException($e->getMessage());
        }
        return true;
    }

    /**
     * 生成需要生成的action
     */
    protected function createAction(): void
    {
        foreach ($this->actionList as $action) {
            $code  = '';
            $upper = strtoupper($action);
            if (in_array($action, $this->data['admin_controller']['action'], true)) {
                $code = file_get_contents($this->template['action_' . $action]);
            }
            $this->code = str_replace('[ACTION_' . $upper . ']', $code, $this->code);
        }
    }
}
