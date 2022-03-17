<?php
/**
 * 代码自动生成控制器
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\controller;

use Exception;
use think\Request;
use think\facade\Db;
use think\facade\Log;
use generate\Generate;
use think\response\Json;
use generate\field\Field;

class GenerateController extends AdminBaseController
{
    /**
     * 首页
     * @return string
     * @throws Exception
     */
    public function index(): string
    {
        return $this->fetch();
    }

    /**
     * 自动生成页面
     * @return string
     * @throws Exception
     */
    public function add(): string
    {
        $this->admin['title'] = '代码自动生成';

        $this->assign([
            'table' => (new Generate())->getTable(),
            'menus' => (new Generate())->getMenu(10000),
        ]);
        return $this->fetch();
    }

    /**
     * 获取表数据
     * @return Json
     */
    public function getTable(): Json
    {
        $data = [
            'table_list' => (new Generate())->getTable(),
        ];
        return admin_success('success', $data);
    }

    /**
     * 获取菜单
     * @return Json
     */
    public function getMenu(): Json
    {
        return admin_success('success', (new Generate())->getMenu(10000));
    }

    /**
     * 自动生成代码接口
     * @param Request $request
     * @return Json
     */
    public function create(Request $request): Json
    {
        $param = $request->param();
        $data  = [
            'table'            => $param['table_name'],
            'cn_name'          => $param['cn_name'],
            'menu'             => [
                // 创建菜单：-1为不创建，0为顶级菜单
                'create'    => (int)$param['create_menu'],
                'menu_list' => $param['create_menu_list']
            ],
            // 后台控制器
            'admin_controller' => [
                'module' => 'admin',
                'create' => (int)($param['create_admin_controller'] ?? 0),
                'name'   => $param['admin_controller_name'],
                'action' => $param['admin_controller_action_list'],
            ],
            // api控制器
            'api_controller'   => [
                'module' => 'api',
                'create' => (int)($param['create_api_controller'] ?? 0),
                'name'   => $param['api_controller_name'],
                'action' => $param['api_controller_action_list'],
            ],
            // 模型
            'model'            => [
                'module'      => 'common',
                'create'      => (int)($param['create_model'] ?? 0),
                'name'        => $param['model_name'],
                'timestamp'   => (int)($param['auto_timestamp'] ?? 0),
                'soft_delete' => (int)($param['soft_delete'] ?? 0),
            ],
            // 验证器
            'validate'         => [
                'module' => 'common',
                'create' => (int)($param['create_validate'] ?? 0),
                'name'   => $param['validate_name'],
            ],
            // 视图
            'view'             => [
                'create_index' => (int)($param['create_view_index'] ?? 0),
                'index_button' => (int)($param['index_operation_button'] ?? 1),
                'create_add'   => (int)($param['create_view_add'] ?? 0),
                'enable'       => (int)($param['list_enable'] ?? 0),
                'delete'       => (int)($param['list_delete'] ?? 0),
                'create'       => (int)($param['list_create'] ?? 0),
                'export'       => (int)($param['list_export'] ?? 0),
                'import'       => (int)($param['list_import'] ?? 0),
                'refresh'      => (int)($param['list_refresh'] ?? 0),
            ],
            // 模块
            'module'           => [
                'name_suffix' => $param['module_name_suffix'],
                'icon'        => $param['module_icon'],
            ],
        ];

        /**
         * 字段数据组装
         */
        $field_data = [];
        foreach ($param['field_name'] as $key => $value) {
            $field_data[] = [
                // 字段名
                'field_name'        => $param['field_name'][$key],
                // 字段类型
                'field_type'        => $param['field_type'][$key],

                // 表单/中文名称
                'form_name'         => $param['form_name'][$key] ?? '',
                // 默认值
                'field_default'     => $param['field_default'][$key] ?? '',

                // 是否为列表字段
                'is_list'           => (int)($param['is_list'][$key] ?? 0),
                // 是否参与列表排序
                'list_sort'         => (int)($param['list_sort'][$key] ?? 0),
                // 表单类型
                'form_type'         => $param['form_type'][$key] ?? 'text',

                // 验证规则
                'form_validate'     => $param['form_validate'][$key] ?? [],
                // 验证场景
                'field_scene'       => $param['field_scene'][$key] ?? [],

                // 获取器/修改器
                'getter_setter'     => (string)$param['getter_setter'][$key] === '0' ? 0 : $param['getter_setter'][$key],
                // 关联类型
                'relation_type'     => (int)($param['relation_type'][$key] ?? 0),
                // 关联表
                'relation_table'    => $param['relation_table'][$key] ?? '',
                // 关联显示字段
                'relation_show'     => $param['relation_show'][$key] ?? 'name',
                // 筛选字段
                'index_search'      => $param['index_search'][$key],
                // 筛选自定义select
                'field_select_data' => $param['field_select_data'][$key] ?? '',
            ];
        }

        $data['data'] = $field_data;

        // 已经组装好data,先生成表，再生成模型，验证器，控制器，视图
        $generate = new Generate($data);
        $msg      = '生成成功';
        $result   = false;

        try {
            $generate->run();
            $result = true;
        } catch (Exception $e) {
            $n = "\n";
            Log::write('生成报错：' . $n . '错误信息：' . $e->getMessage()
                . $n . '行数：' . $e->getLine()
                . $n . '文件：' . $e->getFile());
            $msg = '生成报错，信息：'.$e->getMessage().'具体信息请查看日志文件';
        }

        return $result ? admin_success($msg) : admin_error($msg);
    }

    /**
     * 自动生成form表单字段
     * @return string
     * @throws Exception
     */
    public function form(): string
    {
        $this->admin['title'] = '自动生成form表单字段';
        return $this->fetch();
    }

    /**
     * 生成表单字段html接口
     * @param Request $request
     * @return Json
     */
    public function formField(Request $request): Json
    {
        $param = $request->param();
        if (empty($param['form_name']) || empty($param['field_name']) || empty($param['form_type'])) {
            return admin_error('信息不完整');
        }

        $result = false;
        $data   = [];
        try {

            $param['field_default'] = '';

            if ($param['form_type'] === 'switch') {
                $param['form_type'] = 'switch_field';
                // 此处默认为1，可自行修改
                $param['field_default'] = '1';
            }

            $class_name = parse_name($param['form_type'], 1);

            /** @var Field $class */
            $class = '\\generate\\field\\' . $class_name;
            $data  = $class::create($param);

            $result = true;
            $msg    = '生成表单html成功';
        } catch (Exception $exception) {
            $msg = $exception->getMessage();
        }
        return $result ? admin_success($msg, $data) : admin_error($msg);
    }

    /**
     * 根据字段返回相关表单类型和验证
     * @param Request $request
     * @return Json
     */
    public function getField(Request $request): Json
    {
        $param = $request->param();
        $name  = $param['name'];
        $data = (new Generate())->getAllField($name);
        return admin_success('success', $data);
    }

    /**
     * 获取验证规则选择
     * @param Request $request
     * @return Json|void
     */
    public function getValidateSelect(Request $request): Json
    {
        $param  = $request->param();
        $result = false;
        $data = '';
        try {

            if ($param['form_type'] === 'switch') {
                $param['form_type'] = 'switch_field';
            }

            $class_name = parse_name($param['form_type'], 1);
            /** @var Field $class */
            $class = '\\generate\\field\\' . $class_name;
            $data  = $class::rule();

            $result = true;
            $msg    = '获取字段验证规则成功';
        } catch (Exception $exception) {
            $msg = $exception->getMessage();
        }
        return $result ? admin_success($msg, $data) : admin_error($msg);
    }

    /**
     * 获取关联显示字段
     * @param Request $request
     * @return Json
     */
    public function getRelationShowField(Request $request): Json
    {
        $param = $request->param();
        $field = $param['field'];
        $name  = '';
        if (strrchr($field, '_id') === '_id') {
            $table      = str_replace('_id', '', $field);
            $table_info = Db::query('SHOW TABLE STATUS LIKE ' . "'" . $table . "'");
            if ($table_info) {
                $fields = Db::name($table)->getTableFields();
                if (in_array('name', $fields, true)) {
                    $name = 'name';
                } elseif (in_array('title', $fields, true)) {
                    $name = 'title';
                }
            }
        }

        return admin_success('', [
            'name' => $name,
        ]);
    }

    /**
     * 获取关联显示字段
     * @param Request $request
     * @return Json
     */
    public function getRelationTable(Request $request): Json
    {
        $param = $request->param();
        $field = $param['field'];
        $name  = '';
        if (strrchr($field, '_id') === '_id') {
            $table      = str_replace('_id', '', $field);
            $table_info = Db::query('SHOW TABLE STATUS LIKE ' . "'" . $table . "'");
            if ($table_info) {
                $name = $table;
            }
        }
        return admin_success('', [
            'name' => $name,
        ]);
    }
}
