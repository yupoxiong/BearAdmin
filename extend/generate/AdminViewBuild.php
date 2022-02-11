<?php
/**
 *
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);

namespace generate;

use generate\exception\GenerateException;
use generate\validate\Rule;
use generate\field\Field;
use Exception;

class AdminViewBuild extends Build
{

    protected $addCode;

    protected $indexCode;

    /**
     * AdminControllerBuild constructor.
     * @param array $data 数据
     * @param array $config 配置
     */
    public function __construct(array $data, array $config)
    {
        $this->data   = $data;
        $this->config = $config;

        $this->template = $this->config['template']['admin_view'];

        $this->indexCode = file_get_contents($this->template['index']);
        $this->addCode   = file_get_contents($this->template['add']);
    }


    /**
     * add视图页面
     * @return bool
     */
    public function createAddView(): bool
    {
        // 如果不需要列表视图，直接返回
        if (!$this->data['view']['create_add']) {
            return true;
        }

        $form_body     = '';
        $form_rules    = '';
        $form_messages = '';

        //日期控件类的字段名
        $date_field = ['date', 'datetime'];

        foreach ($this->data['data'] as $value) {

            if ($value['form_type'] !== 'none') {
                if ($value['form_type'] === 'switch') {
                    $value['form_type'] = 'switch_field';
                } else if ($value['form_type'] === 'select') {
                    $value['option_data'] = '';
                    // 这里是关联的
                    if ($value['relation_type'] === 1 || $value['relation_type'] === 2) {
                        $list_code            = file_get_contents($this->template['add_relation_select_data']);
                        $list_name            = $this->getSelectFieldFormat($value['field_name'], 2);
                        $list_code            = str_replace(array('[DATA_LIST]', '[FIELD_NAME]', '[RELATION_SHOW]'), array($list_name, $value['field_name'], $value['relation_show']), $list_code);
                        $value['option_data'] = $list_code;
                    } else if ($value['relation_type'] === 0) {
                        // 这里是非关联的
                        $list_code            = file_get_contents($this->template['add_customer_select_data']);
                        $list_name            = $this->getSelectFieldFormat($value['field_name'], 2);
                        $list_code            = str_replace(array('[FIELD_LIST]', '[FIELD_NAME]'), array($list_name, $value['field_name']), $list_code);
                        $value['option_data'] = $list_code;
                    }
                } else if (is_numeric($value['field_default']) && in_array($value['form_type'], $date_field, true)) {
                    //如果是日期控件类字段，默认值各式不符的一律修改成''
                    $value['field_default'] = '';
                } else if ($value['form_type'] === 'multi_select') {
                    $value['option_data'] = '';

                    $list_code            = file_get_contents($this->template['add_customer_multi_select_data']);
                    $list_name            = $this->getSelectFieldFormat($value['field_name'], 2);
                    $list_code            = str_replace(array('[FIELD_LIST]', '[FIELD_NAME]'), array($list_name, $value['field_name']), $list_code);
                    $value['option_data'] = $list_code;
                }

                $class_name = parse_name($value['form_type'], 1);
                /** @var Field $class */
                $class      = '\\generate\\field\\' . $class_name;
                $form_body  .= $class::create($value);

                $formRule = new Rule();

                $rule_html = '';
                $msg_html  = '';

                foreach ($value['form_validate'] as $validate_rule) {
                    $validate_class_name = parse_name($validate_rule, 1);
                    $validate_class      = '\\generate\\validate\\' . $validate_class_name;
                    if (class_exists($validate_class)) {
                        /** @var Rule $class */
                        $class     = new $validate_class;
                        $rule_html .= $class->getFormRule($value);
                        $msg_html  .= $class->getFormMsg($value);
                    }
                }

                if ($rule_html !== '') {
                    //如果是多选select，验证字段使用[]后缀
                    $validate_field = $value['field_name'];
                    $multi_field    = ['multi_select', 'multi_image', 'multi_file'];
                    if (in_array($value['form_type'], $multi_field, true)) {
                        $validate_field .= '[]';
                    }

                    $form_rules    .= str_replace(array('[FIELD_NAME]', '[RULE_LIST]'), array($validate_field, $rule_html), $formRule::$ruleList);
                    $form_messages .= str_replace(array('[FIELD_NAME]', '[MSG_LIST]'), array($validate_field, $msg_html), $formRule::$msgList);
                }
            }
        }

        $this->addCode = str_replace(
            array('[FORM_BODY]', '[FORM_RULES]', '[FORM_MESSAGES]'),
            array($form_body, $form_rules, $form_messages),
            $this->addCode);

        $out_file = $this->config['file_dir']['view'] . $this->data['table'] . '/add.html';

        file_put_contents($out_file, $this->addCode);

        return true;
    }


    /**
     * 生成后台列表视图
     * @return bool
     * @throws GenerateException
     */
    public function createIndexView(): bool
    {
        // 如果不需要列表视图，直接返回
        if (!$this->data['view']['create_index']) {
            return true;
        }

        // 列表数据名称
        $name_list = '';
        // 列表数据字段
        $field_list = '';
        // 搜索框显示
        $search_name = '';
        // 其他搜索html
        $search_html = '';
        $file_fields = ['file', 'image', 'video'];
        $sort_code   = '';

        $operation_del_icon = '<i class="fas fa-trash-alt"></i>';
        $operation_del_text = '删除';

        $operation_edit_icon = '<i class="fas fa-pen"></i>';
        $operation_edit_text = '修改';

        $operation_disable_icon = '<i class="fas fa-ban"></i>';
        $operation_disable_text = '禁用';

        $operation_enable_icon = '<i class="far fa-circle"></i>';
        $operation_enable_text = '启用';


        foreach ($this->data['data'] as $value) {

            // 排序处理
            if ($value['list_sort']) {
                $option_code = Field::$listSortOptionHtml;

                $option_code = str_replace(array('[FORM_NAME]', '[FIELD_NAME]'), array($value['form_name'], $value['field_name']), $option_code);
                $sort_code   .= $option_code;
            }

            // 列表处理
            if ($value['is_list']) {
                // 名称显示
                $name_list .= str_replace('[FORM_NAME]', $value['form_name'], Field::$listNameHtml);
                // 字段内容显示
                if (in_array($value['form_type'], $file_fields, true)) {
                    // 图片显示
                    $field_list .= str_replace('[FIELD_NAME]', $value['field_name'], Field::$listImgHtml);
                } else if ($value['form_type'] === 'multi_image') {
                    // 多图显示
                    $field_list .= str_replace('[FIELD_NAME]', $value['field_name'], Field::$listMultiImgHtml);
                } else if ($value['form_type'] === 'multi_file') {
                    // 多文件展示
                    $field_list .= str_replace('[FIELD_NAME]', $value['field_name'], Field::$listMultiFileHtml);
                } else if ($value['form_type'] === 'switch') {
                    // status switch显示
                    if ($value['getter_setter'] === 'switch') {
                        $field_list .= str_replace('[FIELD_NAME]', $value['field_name'], Field::$listSwitchHtml);
                    }
                } else if ($value['form_type'] === 'select') {

                    if ($value['relation_type'] === 1 || $value['relation_type'] === 2) {
                        // 关联字段显示
                        $field_name = $this->getSelectFieldFormat($value['field_name']) . '.' . $value['relation_show'] . '|default=' . "''";
                        $field_list .= str_replace('[FIELD_NAME]', $field_name, Field::$listFieldHtml);
                    } else if ($value['relation_type'] === 0) {
                        $field_name = $this->getSelectFieldFormat($value['field_name'], 4);
                        $field_list .= str_replace('[FIELD_NAME]', $field_name, Field::$listFieldHtml);
                    }
                }else if ($value['form_type'] === 'multi_select') {
                    $field_name = $this->getSelectFieldFormat($value['field_name'], 4);
                    $field_list .= str_replace('[FIELD_NAME]', $field_name, Field::$listFieldHtml);
                }  else {
                    // 普通字段显示
                    $field_list .= str_replace('[FIELD_NAME]', $value['field_name'], Field::$listFieldHtml);
                }

            }

            // 首页列表页搜索
            switch ($value['index_search']) {
                case 'search':
                    if (!empty($search_name)) {
                        $search_name .= '/' . $value['form_name'];
                    } else {
                        $search_name .= $value['form_name'];
                    }
                    break;

                case 'select':
                    if ($value['relation_type'] === 1 || $value['relation_type'] === 2) {
                        //关联字段筛选
                        $field_name  = str_replace('_id', '', $value['field_name']);
                        $search_html .= str_replace(array('[FIELD_NAME]', '[FIELD_NAME1]', '[FORM_NAME]', '[RELATION_SHOW]'), array($value['field_name'], $field_name, $value['form_name'], $value['relation_show']), Field::$listSearchRelationHtml);

                    } else if ($value['relation_type'] === 0) {
                        //自定义select
                        $field_select_data = $value['field_select_data'];
                        if (empty($field_select_data)) {
                            throw new GenerateException('请完善字段[' . $value['form_name'] . ']的自定义筛选/select数据');
                        }

                        $field_name_list = $this->getSelectFieldFormat($value['field_name'], 2);
                        if($value['form_type']==='multi_select'){
                            $search_html .= str_replace(array('[FORM_NAME]', '[FIELD_NAME]', '[FIELD_LIST]'), array($value['form_name'], $value['field_name'], $field_name_list), Field::$listSearchMultiSelectHtml);
                        }else{
                            $search_html .= str_replace(array('[FORM_NAME]', '[FIELD_NAME]', '[FIELD_LIST]'), array($value['form_name'], $value['field_name'], $field_name_list), Field::$listSearchSelectHtml);
                        }
                    }
                    break;
                case 'date':
                    $search_html .= str_replace(array('[FIELD_NAME]', '[FORM_NAME]'), array($value['field_name'], $value['form_name']), Field::$listSearchDate);
                    break;

                case 'datetime':
                    $search_html .= str_replace(array('[FIELD_NAME]', '[FORM_NAME]'), array($value['field_name'], $value['form_name']), Field::$listSearchDatetime);
                    break;
                default:
                    break;
            }
        }

        if ($sort_code !== '') {
            $sort_code = str_replace('[SORT_FIELD_LIST]', $sort_code, file_get_contents($this->template['index_sort']));
        }

        $code = $this->indexCode;

        // 列表删除判断
        $del1 = '';
        $del2 = '';
        // 列表选择
        $select1 = '';
        $select2 = '';

        // 列表添加
        $create = '';

        // 列表刷新
        $refresh = '';

        // 如果有删除或者启用/禁用，开启选择
        if ($this->data['view']['delete'] || $this->data['view']['enable']) {
            $select1 = file_get_contents($this->template['index_select1']);
            $select2 = file_get_contents($this->template['index_select2']);
        }

        // 删除按钮处理
        if ($this->data['view']['delete']) {
            $del1 = file_get_contents($this->template['index_del1']);
            $del2 = file_get_contents($this->template['index_del2']);
            // 操作形式处理
            if ($this->data['view']['index_button'] === 1) {
                $operation_del_text = '';
            } else if ($this->data['view']['index_button'] === 2) {
                $operation_del_icon = '';
            }
            $del2 = str_replace(array('[OPERATION_DEL_ICON]', '[OPERATION_DEL_TEXT]'), array($operation_del_icon, $operation_del_text), $del2);
        }

        // 添加按钮处理
        if ($this->data['view']['create']) {
            $create = file_get_contents($this->template['index_create']);
        }

        // 刷新按钮处理
        if ($this->data['view']['refresh']) {
            $refresh = file_get_contents($this->template['index_refresh']);
        }

        $code = str_replace(array('[INDEX_DEL1]', '[INDEX_DEL2]', '[INDEX_SELECT1]', '[INDEX_SELECT2]', '[INDEX_CREATE]', '[INDEX_REFRESH]'), array($del1, $del2, $select1, $select2, $create, $refresh), $code);


        // 顶部筛选（filter）和导出。筛选功能暂时为必生成

        $filter = file_get_contents($this->template['index_filter']);
        $code   = str_replace('[INDEX_FILTER]', $filter, $code);

        // 导出
        $export = '';
        if ($this->data['view']['export']) {
            $export = file_get_contents($this->template['index_export']);
        }

        // 导入
        $import = '';
        if ($this->data['view']['import']) {
            $import = file_get_contents($this->template['index_import']);
        }

        // 启用/禁用
        $enable1 = '';
        $enable2 = '';
        if ($this->data['view']['enable']) {
            $enable1 = file_get_contents($this->template['index_enable1']);
            $enable2 = file_get_contents($this->template['index_enable2']);
            // 操作形式处理
            if ($this->data['view']['index_button'] === 1) {
                $operation_disable_text = '';
                $operation_enable_text  = '';
            } else if ($this->data['view']['index_button'] === 2) {
                $operation_disable_icon = '';
                $operation_enable_icon  = '';
            }
            $enable2 = str_replace(array('[OPERATION_DISABLE_ICON]', '[OPERATION_DISABLE_TEXT]', '[OPERATION_ENABLE_ICON]', '[OPERATION_ENABLE_TEXT]'), array($operation_disable_icon, $operation_disable_text, $operation_enable_icon, $operation_enable_text), $enable2);
        }

        if ($this->data['view']['index_button'] === 1) {
            $operation_edit_text = '';
        } else if ($this->data['view']['index_button'] === 2) {
            $operation_edit_icon = '';
        }

        $code = str_replace(
            array('[OPERATION_EDIT_ICON]', '[OPERATION_EDIT_TEXT]', '[INDEX_ENABLE1]', '[INDEX_ENABLE2]', '[INDEX_EXPORT]', '[INDEX_IMPORT]', '[NAME_LIST]', '[FIELD_LIST]', '[SEARCH_FIELD]', '[SORT_CODE]', '[SEARCH_HTML]'),
            array($operation_edit_icon, $operation_edit_text, $enable1, $enable2, $export, $import, $name_list, $field_list, $search_name, $sort_code, $search_html),
            $code);

        try {
            file_put_contents($this->config['file_dir']['view'] . $this->data['table'] . '/index.html', $code);
        } catch (Exception $e) {
            throw  new GenerateException($e->getMessage());
        }
        return true;
    }
}
