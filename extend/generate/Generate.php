<?php
/**
 * 自动生成代码
 * @author yupoxiong<i@yufuping.com>
 */

namespace generate;

use generate\exception\GenerateException;
use app\admin\model\AdminMenu;
use generate\traits\Tools;
use generate\field\Field;
use generate\traits\Tree;
use think\facade\Db;
use Exception;

class Generate
{
    use Tree, Tools;

    // 配置
    protected $config = [];

    // 主数据
    protected $data = [];

    /**
     * 控制器和模型名、验证器名黑名单
     * @var array
     */
    protected array $blacklistName = [
        'User',
        'UserLevel',
        'Auth',
        'Index',
        'AdminUser',
        'AdminRole',
        'AdminMenu',
        'AdminLog',
        'AdminLogData',
        'Attachment',
    ];

    /**
     * 表名黑名单
     * @var array
     */
    protected array $blacklistTable = [
        'user',
        'user_level',
        'admin_user',
        'admin_role',
        'admin_menu',
        'admin_log',
        'admin_log_data',
        'migrations',
        'attachment',
        'setting',
        'setting_group',
    ];

    public function __construct($data = [], $config = null)
    {
        $root_path = app()->getRootPath();
        $app_path  = app()->getBasePath();

        $stub_path = $root_path . 'extend/generate/stub/';

        $admin_controller_path = $stub_path . 'admin_controller/';
        $api_controller_path   = $stub_path . 'api_controller/';
        $api_service_path      = $stub_path . 'api_service/';
        $validate_path         = $stub_path . 'validate/';
        $model_path            = $stub_path . 'model/';
        $admin_view_path       = $stub_path . 'admin_view/';
        $config_tmp            = [
            // 模版目录
            'template' => [

                'admin' => [
                    'controller'         => $admin_controller_path . 'AdminController.stub',
                    'action_index'       => $admin_controller_path . 'action_index.stub',
                    'action_add'         => $admin_controller_path . 'action_add.stub',
                    'action_info'        => $admin_controller_path . 'action_info.stub',
                    'action_edit'        => $admin_controller_path . 'action_edit.stub',
                    'action_del'         => $admin_controller_path . 'action_del.stub',
                    'action_disable'     => $admin_controller_path . 'action_disable.stub',
                    'action_enable'      => $admin_controller_path . 'action_enable.stub',
                    'action_import'      => $admin_controller_path . 'action_import.stub',
                    'action_export'      => $admin_controller_path . 'action_export.stub',
                    'relation_data_list' => $admin_controller_path . 'relation_data_list.stub',
                    'relation_assign_1'  => $admin_controller_path . 'relation_assign_1.stub',
                    'relation_with'      => $admin_controller_path . 'relation_with.stub',
                ],

                'api' => [
                    'controller'     => $api_controller_path . 'ApiController.stub',
                    'action_index'   => $api_controller_path . 'api_index.stub',
                    'action_add'     => $api_controller_path . 'api_add.stub',
                    'action_info'    => $api_controller_path . 'api_info.stub',
                    'action_edit'    => $api_controller_path . 'api_edit.stub',
                    'action_del'     => $api_controller_path . 'api_del.stub',
                    'action_disable' => $api_controller_path . 'api_disable.stub',
                    'action_enable'  => $api_controller_path . 'api_enable.stub',

                    'service'         => $api_service_path . 'ApiService.stub',
                    'service_index'   => $api_service_path . 'api_index.stub',
                    'service_add'     => $api_service_path . 'api_add.stub',
                    'service_info'    => $api_service_path . 'api_info.stub',
                    'service_edit'    => $api_service_path . 'api_edit.stub',
                    'service_del'     => $api_service_path . 'api_del.stub',
                    'service_disable' => $api_service_path . 'api_disable.stub',
                    'service_enable'  => $api_service_path . 'api_enable.stub',
                ],

                'model'      => [
                    'model'                      => $model_path . 'Model.stub',
                    'relation'                   => $model_path . 'relation.stub',
                    'getter_setter_select'       => $model_path . 'getter_setter_select.stub',
                    'getter_setter_multi_select' => $model_path . 'getter_setter_multi_select.stub',
                    'getter_setter_switch'       => $model_path . 'getter_setter_switch.stub',
                    'getter_setter_date'         => $model_path . 'getter_setter_date.stub',
                    'getter_setter_datetime'     => $model_path . 'getter_setter_datetime.stub',
                ],
                'validate'   => [
                    'validate' => $validate_path . 'Validate.stub',
                ],
                'admin_view' => [
                    'index'                          => $admin_view_path . 'index/index.stub',
                    'index_create'                   => $admin_view_path . 'index/create.stub',
                    'index_refresh'                  => $admin_view_path . 'index/refresh.stub',
                    'index_del1'                     => $admin_view_path . 'index/del1.stub',
                    'index_del2'                     => $admin_view_path . 'index/del2.stub',
                    'index_filter'                   => $admin_view_path . 'index/filter.stub',
                    'index_export'                   => $admin_view_path . 'index/export.stub',
                    'index_import'                   => $admin_view_path . 'index/import.stub',
                    'index_select1'                  => $admin_view_path . 'index/select1.stub',
                    'index_select2'                  => $admin_view_path . 'index/select2.stub',
                    'index_sort'                     => $admin_view_path . 'index/sort.stub',
                    'index_enable1'                  => $admin_view_path . 'index/enable1.stub',
                    'index_enable2'                  => $admin_view_path . 'index/enable2.stub',
                    'add'                            => $admin_view_path . 'add/add.stub',
                    'add_relation_select_data'       => $admin_view_path . 'add/relation_select_data.stub',
                    'add_customer_select_data'       => $admin_view_path . 'add/customer_select_data.stub',
                    'add_customer_multi_select_data' => $admin_view_path . 'add/customer_multi_select_data.stub',
                ],
            ],
            // 生成文件目录
            'file_dir' => [
                'admin_controller' => $app_path . 'admin/controller/',
                'api_controller'   => $app_path . 'api/controller/',
                'api_service'      => $app_path . 'api/service/',
                'model'            => $app_path . 'common/model/',
                'validate'         => $app_path . 'common/validate/',
                'view'             => $app_path . 'admin/view/',
            ],
        ];

        $config       = $config ?? $config_tmp;
        $this->config = $config;

        $this->data = $data;
    }

    /**
     * @return string
     * @throws GenerateException
     */
    public function run(): string
    {
        $this->checkName($this->data);
        $this->checkDir();
        $this->createModel();
        $this->createAdminController();
        $this->createAddView();
        $this->createIndexView();
        $this->createValidate();
        $this->createApiController();
        $this->createApiService();
        $this->createMenu();
        return '生成成功';
    }


    /**
     * 获取所有表(除黑名单之外)
     * @return array
     */
    public function getTable(): array
    {
        $table_data = Db::query('SHOW TABLES');
        $table      = [];

        foreach ($table_data as $value) {
            $current = current($value);
            if (!in_array($current, $this->blacklistTable, true)) {
                $table[] = $current;
            }
        }
        return $table;
    }

    /**
     * 获取后台已有菜单，以select形式返回
     * @param int $selected
     * @param int $current_id
     * @return string
     */
    public function getMenu(int $selected = 1, int $current_id = 0): string
    {
        $result = (new AdminMenu)->where('id', '<>', $current_id)
            ->order('sort_number', 'asc')
            ->order('id', 'asc')
            ->column('id,parent_id,name,sort_number', 'id');
        foreach ($result as $r) {
            $r['selected'] = (int)$r['id'] === $selected ? 'selected' : '';
        }
        $str = "<option value='\$id' \$selected >\$spacer \$name</option>";
        $this->initTree($result);
        return $this->getTree(0, $str, $selected);
    }


    /**
     * 检查目录（是否可写）
     * @return bool
     * @throws GenerateException
     */
    protected function checkDir(): bool
    {
        if (!is_dir($this->config['file_dir']['admin_controller'])) {
            $this->mkFolder($this->config['file_dir']['admin_controller']);
        }

        if (!is_dir($this->config['file_dir']['api_controller'])) {
            $this->mkFolder($this->config['file_dir']['api_controller']);
        }

        if (!is_dir($this->config['file_dir']['model'])) {
            $this->mkFolder($this->config['file_dir']['model']);
        }

        if (!is_dir($this->config['file_dir']['validate'])) {
            $this->mkFolder($this->config['file_dir']['validate']);
        }

        if (!is_dir($this->config['file_dir']['view'])) {
            $this->mkFolder($this->config['file_dir']['view']);
        }

        if (!is_dir($this->config['file_dir']['view'] . $this->data['table'])) {
            $this->mkFolder($this->config['file_dir']['view'] . $this->data['table']);
        }

        if (!is_writable($this->config['file_dir']['admin_controller'])) {
            throw new GenerateException('Admin控制器目录不可写');
        }
        if (!is_writable($this->config['file_dir']['api_controller'])) {
            throw new GenerateException('Api控制器目录不可写');
        }

        if (!is_writable($this->config['file_dir']['model'])) {
            throw new GenerateException('模型目录不可写');
        }

        if (!is_writable($this->config['file_dir']['validate'])) {
            throw new GenerateException('验证器目录不可写');
        }

        if (!is_writable($this->config['file_dir']['view'])) {
            throw new GenerateException('视图目录不可写');
        }

        if (!is_writable($this->config['file_dir']['view'] . $this->data['table'])) {
            throw new GenerateException($this->data['cn_name'] . '视图目录不可写');
        }

        return true;
    }

    /**
     * 检查名称是在黑名单,表是否存在
     * @param $data
     * @return bool
     * @throws GenerateException
     */
    protected function checkName($data): bool
    {
        if (in_array($data['admin_controller']['name'], $this->blacklistName, true)) {
            throw new GenerateException('控制器名非法');
        }
        if (in_array($data['admin_controller']['name'], $this->blacklistName, true)) {
            throw new GenerateException('控制器名非法');
        }
        if (in_array($data['model'], $this->blacklistName, true)) {
            throw new GenerateException('模型名非法');
        }
        if (in_array($data['validate'], $this->blacklistName, true)) {
            throw new GenerateException('验证器名非法');
        }
        if (in_array($data['table'], $this->blacklistTable, true)) {
            throw new GenerateException('表名非法');
        }
        return true;
    }

    /**
     * 创建菜单
     * @return bool
     * @throws GenerateException
     */
    protected function createMenu(): bool
    {
        return (new AdminMenuBuild($this->data, $this->config))->run();
    }

    /**
     * 创建后台控制器
     * @return bool
     * @throws GenerateException
     */
    protected function createAdminController(): bool
    {
        return (new AdminControllerBuild($this->data, $this->config))->run();
    }

    /**
     * 创建模型
     * @return bool
     * @throws GenerateException
     */
    protected function createModel(): bool
    {
        return (new ModelBuild($this->data, $this->config))->run();
    }

    /**
     * 创建验证器
     * @return bool
     * @throws GenerateException
     */
    protected function createValidate(): bool
    {
        return (new ValidateBuild($this->data, $this->config))->run();
    }

    /**
     * 创建列表视图
     * @return bool
     * @throws GenerateException
     */
    protected function createIndexView(): bool
    {
        return (new AdminViewBuild($this->data, $this->config))->createIndexView();
    }

    /**
     * 创建添加视图
     * @return bool
     */
    protected function createAddView(): bool
    {
        return (new AdminViewBuild($this->data, $this->config))->createAddView();
    }


    /**
     * 创建API模块控制器
     * @return bool
     * @throws GenerateException
     */
    public function createApiController(): bool
    {
        return (new ApiControllerBuild($this->data, $this->config))->create();
    }

    /**
     * 创建API模块控制器
     * @return bool
     * @throws GenerateException
     */
    public function createApiService(): bool
    {
        return (new ApiServiceBuild($this->data, $this->config))->create();
    }


    /**
     * 创建目录
     * @param $path
     */
    protected function mkFolder($path): void
    {
        if (!is_readable($path)) {
            is_file($path) || mkdir($path, 0755) || is_dir($path);
        }
    }


    /**
     * 获取表内字段列表
     * @param $table_name
     * @return array
     */
    public function getAllField($table_name): array
    {
        $parse_name = parse_name($table_name, 1);
        $data       = [
            // 表
            'table'      => [
                'name'    => $table_name,
                'cn_name' => ''
            ],
            // 控制器
            'controller' => [
                'create'       => 1,
                'name'         => $parse_name,
                'action'       => [
                    'index'   => 1,
                    'add'     => 1,
                    'edit'    => 1,
                    'del'     => 1,
                    'enable'  => 0,
                    'disable' => 0,
                ],
                'del_relation' => [
                    // 关联方法，删除模式，1判断关联，2不做操作，3关联删除
                    'user' => 1,
                ]
            ],
            // 模型
            'model'      => [
                'create'         => 1,
                'name'           => $parse_name,
                'auto_timestamp' => 1,
                'soft_delete'    => 1,
            ],
            // 验证器
            'validate'   => [
                'create' => 1,
                'name'   => $parse_name,
                'scene'  => [
                    'admin_add'  => 1,
                    'admin_edit' => 1,
                    'api_add'    => 1,
                    'api_edit'   => 1,
                    'index_add'  => 1,
                    'index_edit' => 1,
                ]
            ],
            // 字段
            'field'      => [],
        ];

        // 所有字段信息
        $field_list = Db::query('SHOW FULL COLUMNS FROM `' . $table_name . '`');
        // 表信息
        $table_info = Db::query('SHOW TABLE STATUS LIKE ' . "'" . $table_name . "'");
        $table_info = $table_info[0];
        // 表名
        $data['table']['name'] = $table_info['Name'];
        // 表中文名
        $data['table']['cn_name'] = $table_info['Comment'];

        // 定义好以下情况，
        // 90%概率为列表的字段，
        // 90%概率不为列表的字段，
        // 90%为搜索的字段
        // 90%不为搜索的字段
        // 90%为表单的字段
        // 90不为表单的字段
        // 导出字段暂时和显示字段一样

        // 显示为列表的字段名，暂时不用
        // $list_show_field = ['id','name','mobile','description','address','money','price', 'keywords','title','img','create_time','sort_number','user_id','status'];
        // 列表隐藏字段，如果为text字段，大概率隐藏
        $list_hide_field = ['password', 'update_time', 'delete_time'];
        $list_hide_type  = ['tinytext', 'tinyblob', 'text', 'blob', 'longtext', 'longblob'];

        /**
         * @param $field
         * @return mixed
         * 常用类型
         * tinyint,smallint,mediumint,int,bigint,float,double,decimal
         * char,varchar,tinytext/tinyblob,text/blob,longtext/longblob
         * date,datetime,timestamp,time,year
         */

        // 搜索字段，如果为varchar，char字段，大概率需要搜索
        $search_show_field = ['id', 'mobile', 'keywords', 'id_card', 'name', 'title', 'username', 'nickname', 'true_name', 'description'];
        $search_show_type  = ['char', 'varchar'];

        $multi_image_field = ['slide', 'img_list', 'image_list',];
        $multi_image_type  = ['varchar', 'text'];

        // 导出隐藏字段，和列表隐藏字段差不多
        //$export_hide_field = ['update_time', 'delete_time'];
        //$export_hide_type  = ['tinytext', 'tinyblob', 'text', 'blob', 'longtext', 'longblob'];

        // 表单显示字段
        // $form_show_field = ['id','create_time','update_time','delete_time'];
        // 表单隐藏字段
        $form_hide_field = ['id', 'create_time', 'update_time', 'delete_time'];
        $form_hide_type  = ['double'];

        // 列表排序字段
        $list_sort_field = ['id', 'create_time', 'money', 'integral',];
        $list_sort_type  = ['tinyint', 'int', 'smallint', 'mediumint', 'bigint', 'decimal', 'float', 'double', 'date', 'datetime', 'timestamp',];

        foreach ($field_list as $value) {

            // 处理关联展示
            $relation_table = '';
            $relation_type  = 0;
            $relation_show  = 'name';

            // 处理主键ID没备注的情况
            $cn_name = $value['Comment'];
            if ($value['Field'] === 'id') {

                if (empty($cn_name)) {
                    $cn_name = 'ID';
                }
                // 主键关联
                $relation_table = $this->getRelationTable($table_name);
                if (!empty($relation_table)) {
                    $relation_type = 4;
                }
            } else if (strrchr($value['Field'], '_id') === '_id') {
                // 这里判断是否为关联外键
                $union_table = $this->getRelation($value['Field']);
                if ($union_table) {
                    $relation_type = 2;
                    $relation_show = $union_table;
                }
            }

            // 筛选搜索
            $index_search = '0';
            if ($value['Field'] === 'name' || $value['Field'] === 'title' || $value['Field'] === 'description') {
                $index_search = 'search';
            }

            $field_data = [
                // 字段名
                'name'              => $value['Field'],
                // 字段中文名
                'cn_name'           => $cn_name,
                // 字段类型
                'field_type'        => $value['Type'],
                // 字段长度
                'field_length'      => 1,
                // 默认值
                'default'           => $value['Default'],
                // 是否列表显示
                'is_list'           => 1,
                'list_sort'         => 0,
                // 表单类型
                'form_type'         => 'none',
                // 表单验证
                'validate'          => ['required'],
                'validate_html'     => '',
                // 验证场景
                'validate_scene'    => ['admin_add'],
                // 获取器/修改器
                'getter_setter'     => false,
                // 首页筛选
                'index_search'      => $index_search,
                'field_select_data' => '',
                // 关联显示
                'relation_type'     => $relation_type,
                //  关联表
                'relation_table'    => $relation_table,
                // 关联显示字段
                'relation_show'     => $relation_show,
            ];

            $field_info = $this->getFieldInfo($field_data['name'], $field_data['field_type']);

            $field_data['field_length'] = $field_info['length'];

            // 处理是否为列表显示
            if (in_array($field_info['name'], $list_hide_field, true) || in_array($field_info['type'], $list_hide_type, true)) {
                $field_data['is_list'] = 0;
            }

            // 处理是否为列表搜索
            if (in_array($field_info['name'], $search_show_field, true) && in_array($field_info['type'], $search_show_type, true)) {
                $field_data['index_search'] = 'search';
            }

            // 处理字段表单类型
            $form_info                   = $this->getFormInfo($field_info);
            $field_data['form_type']     = $form_info['form_type'];
            $field_data['getter_setter'] = $form_info['getter_setter'];

            // 处理是否不为表单字段
            if (in_array($field_info['name'], $form_hide_field, true) || in_array($field_info['type'], $form_hide_type, true)) {
                $field_data['form_type'] = 'none';
            }

            // 是否为排序字段
            if ($field_data['is_list'] === 1 && in_array($field_info['name'], $list_sort_field, true) && in_array($field_info['type'], $list_sort_type, true)) {
                $field_data['list_sort'] = 1;
            }
            // 多图上传
            if (in_array($field_info['name'], $multi_image_field, true) && in_array($field_info['type'], $multi_image_type, true)) {
                $field_data['form_type'] = 'multi_image';
            }

            // 验证
            $field_data['validate_html'] = $this->getValidateHtml($field_data);

            $data['field'][] = $field_data;
        }

        // 处理地图另一个字段的显示和表单配置
        foreach ($data['field'] as $key => $value) {
            if ($value['form_type'] === 'map') {
                // 列表不展示
                $data['field'][$key]['is_list'] = 0;
                // 查找纬度字段
                $search_field = str_replace(['lng', 'longitude'], ['lat', 'latitude'], $data['field'][$key]['name']);
                $found_key    = array_search($search_field, array_column($data['field'], 'name'), true);
                // 纬度字段不显示及不用表单
                $data['field'][$found_key]['is_list']   = 0;
                $data['field'][$found_key]['form_type'] = 'none';
            }
        }
        return $data;
    }

    /**
     * 获取主键一对多的关联表
     * @param $table_name
     * @return string
     */
    public function getRelationTable($table_name): string
    {
        $relation_table = '';
        $fk             = $table_name . '_id';
        $table_list     = $this->getTable();
        foreach ($table_list as $table) {
            //所有字段信息
            $field_list = Db::query('SHOW FULL COLUMNS FROM `' . $table . '`');
            foreach ($field_list as $field) {
                if ($field['Field'] === $fk) {
                    $relation_table .= empty($relation_table) ? $table : ',' . $table;
                    break;
                }
            }
        }
        return $relation_table;
    }

    /**
     * 获取关联显示字段
     * @param $field
     * @return string
     */
    public function getRelation($field): string
    {
        $result     = '';
        $table_name = str_replace('_id', '', $field);

        $table_info = Db::query('SHOW TABLE STATUS LIKE ' . "'" . $table_name . "'");
        if ($table_info) {

            $fields     = [];
            $field_list = Db::query('SHOW FULL COLUMNS FROM `' . $table_name . '`');

            foreach ($field_list as $item) {
                $fields[] = $item['Field'];
            }

            if (in_array('name', $fields, true)) {
                $result = 'name';
            } elseif (in_array('title', $fields, true)) {
                $result = 'title';
            } else {
                foreach ($fields as $item) {
                    if (strpos($item, 'name') !== false) {
                        $result = $item;
                        break;
                    }
                    if (strpos($item, 'title') !== false) {
                        $result = $item;
                        break;
                    }
                }
            }
        }
        return $result;
    }

    /**
     * 根据表单类型和长度返回相应的验证
     * @param $field_data
     * @return string
     */
    public function getValidateHtml($field_data): string
    {
        $html = '';
        try {
            if ($field_data['form_type'] !== 'none') {
                if ($field_data['form_type'] === 'switch') {
                    $field_data['form_type'] = 'switch_field';
                }
                $class_name = parse_name($field_data['form_type'], 1);
                /** @var Field $class */
                $class = '\\generate\\field\\' . $class_name;
                $html  = $class::rule($field_data['field_length']);
            }

        } catch (Exception $exception) {
            return $exception->getMessage();
        }

        return $html;
    }

    /**
     * 获取字段的表单信息
     * @param $field_info
     * @return array
     */
    public function getFormInfo($field_info): array
    {
        // 结尾：_id为select，_time为datetime，_date为date，
        // 结尾：_count为number，_lng/_longitude为map，img为image，slide为multiImage
        // 开头：is_为switch
        // 字段名：lng/longitude为map,password为password，money,price为number
        // 字段名：status大概率为switch或者其他
        $field_data = [
            'form_type'     => 'text',
            'getter_setter' => false,
        ];
        // id
        if ($field_info['name'] === 'id') {
            $field_data['form_type'] = 'number';
        }
        // _id为下拉列表，大多数为关联
        if (strrchr($field_info['name'], '_id') === '_id') {
            $field_data['form_type'] = 'select';
        }
        // 日期时间
        if (strrchr($field_info['name'], '_time') === '_time') {
            $field_data['form_type'] = 'datetime';

            $ignore_field = ['create_time', 'update_time', 'delete_time'];
            if ($field_info['type'] === 'int' && !in_array($field_info['name'], $ignore_field, true)) {
                $field_data['getter_setter'] = 'datetime';
            }
        }
        // 日期
        if ($field_info['type'] === 'datetime') {
            $field_data['form_type'] = 'date';
        }
        // 日期
        if (strrchr($field_info['name'], '_date') === '_date') {
            $field_data['form_type'] = 'date';
            if ($field_info['type'] === 'int') {
                $field_data['getter_setter'] = 'date';
            }
        }
        // 日期
        if ($field_info['type'] === 'date') {
            $field_data['form_type'] = 'date';
        }
        // 数量
        if (strrchr($field_info['name'], '_count') === '_count') {
            $field_data['form_type'] = 'number';
        }
        // 数量
        if (strrchr($field_info['name'], '_number') === '_number') {
            $field_data['form_type'] = 'number';
        }
        // 经纬度
        if (strrchr($field_info['name'], '_lng') === '_lng' || strrchr($field_info['name'], '_longitude') === '_longitude') {
            $field_data['form_type'] = 'map';
        }
        // 经纬度
        if ($field_info['name'] === 'lng' || $field_info['name'] === 'longitude') {
            $field_data['form_type'] = 'map';
        }
        // 图片
        if (strrchr($field_info['name'], 'img') === 'img') {
            $field_data['form_type'] = 'image';
        }
        // 视频
        if (strrchr($field_info['name'], 'video') === 'video') {
            $field_data['form_type'] = 'video';
        }
        // 轮播
        if (strrchr($field_info['name'], 'slide') === 'slide') {
            $field_data['form_type'] = 'multi_image';
        }
        // 密码
        if (strrchr($field_info['name'], 'password') === 'password') {
            $field_data['form_type'] = 'password';
        }
        // 颜色
        if (strrchr($field_info['name'], 'color') === 'color') {
            $field_data['form_type'] = 'color';
        }
        // 图标
        if (strrchr($field_info['name'], 'icon') === 'icon') {
            $field_data['form_type'] = 'icon';
        }
        // 价格，暂时用number
        if (strrchr($field_info['name'], 'price') === 'price') {
            $field_data['form_type'] = 'number';
        }
        // 金额，暂时用number
        if (strrchr($field_info['name'], 'money') === 'money') {
            $field_data['form_type'] = 'number';
        }
        // 状态
        if ($field_info['name'] === 'status') {
            $field_data['form_type']     = 'switch';
            $field_data['getter_setter'] = 'switch';
        }
        // 手机号
        if ($field_info['name'] === 'mobile' || $field_info['name'] === 'phone') {
            $field_data['form_type'] = 'mobile';
        }
        // 头像
        if ($field_info['name'] === 'avatar') {
            $field_data['form_type'] = 'image';
        }
        // 富文本
        if ($field_info['type'] === 'text' || $field_info['type'] === 'longtext') {
            $field_data['form_type'] = 'editor';
        }
        // switch
        if ($field_info['type'] === 'tinyint' && strpos($field_info['name'], 'is_') === 0) {
            $field_data['form_type']     = 'switch';
            $field_data['getter_setter'] = 'switch';
        }

        return $field_data;
    }
}
