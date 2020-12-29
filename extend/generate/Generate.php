<?php
/**
 * 自动生成代码
 * @author yupoxiong<i@yufuping.com>
 */

namespace generate;

use app\admin\model\AdminMenu;
use Exception;
use generate\field\Editor;
use generate\field\Field;
use generate\field\File;
use generate\field\Image;
use generate\field\MultiFile;
use generate\field\MultiImage;
use generate\field\Video;
use generate\rule\Required;
use think\Db;
use think\facade\Env;
use generate\traits\Tools;
use generate\traits\Tree;

class Generate
{

    use Tree;
    use Tools;

    protected $config = [];

    //主数据
    protected $data = [];

    protected $error;

    /**
     * 控制器和模型名、验证器名黑名单
     * @var array
     */
    protected $blacklistName = [
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
    protected $blacklistTable = [
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

        $root_path  = Env::get('root_path');
        $app_path   = Env::get('app_path');
        $config_tmp = [
            //模版目录
            'template' => [
                'path'       => $root_path . 'extend/generate/stub/',
                'controller' => $root_path . 'extend/generate/stub/Controller.stub',
                'model'      => $root_path . 'extend/generate/stub/Model.stub',
                'validate'   => $root_path . 'extend/generate/stub/Validate.stub',
                'view'       => [
                    'index'         => $root_path . 'extend/generate/stub/view/index.stub',
                    'index_path'    => $root_path . 'extend/generate/stub/view/index/',
                    'index_del1'    => $root_path . 'extend/generate/stub/view/index/del1.stub',
                    'index_del2'    => $root_path . 'extend/generate/stub/view/index/del2.stub',
                    'index_filter'  => $root_path . 'extend/generate/stub/view/index/filter.stub',
                    'index_export'  => $root_path . 'extend/generate/stub/view/index/export.stub',
                    'index_select1' => $root_path . 'extend/generate/stub/view/index/select1.stub',
                    'index_select2' => $root_path . 'extend/generate/stub/view/index/select2.stub',
                    'add'           => $root_path . 'extend/generate/stub/view/add.stub',
                ],
            ],
            //生成文件目录
            'file_dir' => [
                'controller' => $app_path . 'admin/controller/',
                'model'      => $app_path . 'common/model/',
                'validate'   => $app_path . 'common/validate/',
                'view'       => $app_path . 'admin/view/',
            ],
        ];

        $config       = $config ?? $config_tmp;
        $this->config = $config;

        $this->data = $data;
    }


    public function run()
    {
        $this->checkName($this->data);
        $this->checkDir();

        $this->createAddView();
        $this->createIndexView();
        $this->createController();
        $this->createModel();
        $this->createValidate();
        $this->createMenu();
        return '生成成功';

        //先判断所有目录是否可写，控制器，模型，验证器，视图
        //然后生成表，然后再生成各个代码
        //检查目录

        //检查名称

        //判断是否为

        //$this->createTable($this->data);
    }


    /**
     * 获取所有表(除黑名单之外)
     * @return array
     */
    public function getTable()
    {
        $table_data = Db::query('SHOW TABLES');
        $table      = [];

        foreach ($table_data as $key => $value) {
            $current = current($value);
            if (!in_array($current, $this->blacklistTable)) {
                $table[] = $current;
            }
        }

        return $table;
    }

    /**
     * 获取后台已有菜单，以select形式返回
     */
    public function getMenu($selected = 1, $current_id = 0)
    {
        $result = AdminMenu::where('id', '<>', $current_id)->order('sort_id', 'asc')->order('id', 'asc')->column('id,parent_id,name,sort_id', 'id');
        foreach ($result as $r) {
            $r['selected'] = (int)$r['id'] === $selected ? 'selected' : '';
        }
        $str = "<option value='\$id' \$selected >\$spacer \$name</option>";
        $this->initTree($result);
        return $this->getTree(0, $str, $selected);
    }


    //获取所有模块
    protected function getModule()
    {
        $module = [];
        $path   = Env::get('app_path');
        $dir    = scandir($path);
        foreach ($dir as $item) {
            if ($item !== '.' && $item !== '..' && is_dir($path . $item)) {
                $module[] = $item;
            }
        }
        return count($module) > 0 ? $module : false;
    }

    //检查目录（是否可写）
    protected function checkDir()
    {
        if (!is_dir($this->config['file_dir']['controller'])) {
            $this->mkFolder($this->config['file_dir']['controller']);
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


        if (!is_writable($this->config['file_dir']['controller'])) {
            throw new \Exception('控制器目录不可写');
        }

        if (!is_writable($this->config['file_dir']['model'])) {
            throw new \Exception('模型目录不可写');
        }

        if (!is_writable($this->config['file_dir']['validate'])) {
            throw new \Exception('验证器目录不可写');
        }

        if (!is_writable($this->config['file_dir']['view'])) {
            throw new \Exception('视图目录不可写');
        }

        return true;
    }

    //检查名称是在黑名单,表是否存在
    protected function checkName($data)
    {
        if (in_array($data['controller'], $this->blacklistName)) {
            throw new \Exception('控制器名非法');
        }

        if (in_array($data['model'], $this->blacklistName)) {
            throw new \Exception('模型名非法');
        }

        if (in_array($data['validate'], $this->blacklistName)) {
            throw new \Exception('验证器名非法');
        }

        if (in_array($data['table'], $this->blacklistTable)) {
            throw new \Exception('表名非法');
        }

        return true;
    }


    /**
     * 创建数据表
     * @param array $data
     * @return bool
     */
    protected function createTable($data = [])
    {
        if (count($data) == 0) {
            $data = $this->data;
        }

        //字段
        $fields = [];
        //索引
        $indexes = [];

        $fields[] = ' `id` int(11) unsigned NOT NULL AUTO_INCREMENT';
        foreach ($data['data'] as $item) {
            // 字段
            $fields[] = " `{$item['field_name']}` {$item['field_type']}"
                . ($item['not_null'] ? ' NOT NULL' : ' ')
                . (strtolower($item['default']) == 'null' ? '' : " DEFAULT '{$item['default']}'")
                . ($item['comment'] === '' ? '' : " COMMENT '{$item['comment']}'");

            // 索引
            if (isset($item['key']) && $item['key'] && $item['name'] != 'id') {
                $indexes[] = " KEY `{$item['name']}` (`{$item['name']}`)";
            }
        }

        //自动生成时间戳
        if (isset($data['auto_timestamp']) && $data['auto_timestamp']) {
            $fields[] = " `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间'";
            $fields[] = " `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间'";
        }
        //软删除
        if (isset($data['soft_delete']) && $data['soft_delete']) {
            $fields[] = " `delete_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '删除时间'";
        }

        // 默认主键为id
        $fields[] = ' PRIMARY KEY (`id`) ';
        //表名
        $name = config('database.prefix') . $data['table'];
        $sql  = "CREATE TABLE `{$name}` (\n"
            . implode(",\n", array_merge($fields, $indexes))
            . "\n)ENGINE=" . (isset($this->data['table_engine']) ? $this->data['table_engine'] : 'InnoDB')
            . " DEFAULT CHARSET=utf8mb4 COMMENT '{$this->data['cn_name']}'";
        //记录SQL日志
        //Log::record("AUTO_CREATE_TABLE：\n{$sql};", 'sql');
        $msg = 'error';
        Db::startTrans();
        try {
            Db::query($sql);
            Db::commit();
            $result = true;
        } catch (\Exception $e) {
            Db::rollback();
            $msg    = $e->getMessage();
            $result = false;
        }

        if ($result) {
            return true;
        }
        return $msg;
    }

    //创建控制器
    protected function createController()
    {

        //不生成控制器
        if ($this->data['controller']['create'] == 0) {
            return true;
        }

        $add_field_code  = '';
        $edit_field_code = '';

        //关联代码
        $relation_1 = '';
        $relation_2 = '';
        $relation_3 = '';

        //导出代码
        $export_header = '';
        $export_body   = '';
        $export_name   = '';
        $export_code   = '';
        //with代码
        $relation_with = '';
        //with列表
        $relation_with_list = '';

        //列表页关联查询
        $index_select = '';

        foreach ($this->data['data'] as $key => $value) {

            if ($value['is_form'] == 1) {
                switch ($value['form_type']) {
                    case 'file':
                        $add_field_code_tmp  = File::$controllerAddCode;
                        $edit_field_code_tmp = File::$controllerEditCode;
                        break;
                    case 'image':
                        $add_field_code_tmp  = Image::$controllerAddCode;
                        $edit_field_code_tmp = Image::$controllerEditCode;
                        break;
                    case 'multi_image':
                        $add_field_code_tmp  = MultiImage::$controllerAddCode;
                        $edit_field_code_tmp = MultiImage::$controllerEditCode;
                        break;
                    case 'video':
                        $add_field_code_tmp  = Video::$controllerAddCode;
                        $edit_field_code_tmp = Video::$controllerEditCode;
                        break;
                    case 'multi_file':
                        $add_field_code_tmp  = MultiFile::$controllerAddCode;
                        $edit_field_code_tmp = MultiFile::$controllerEditCode;
                        break;
                    case 'editor':
                        $add_field_code_tmp  = Editor::$controllerAddCode;
                        $edit_field_code_tmp = Editor::$controllerEditCode;
                        break;
                    default:
                        $add_field_code_tmp  = '';
                        $edit_field_code_tmp = '';
                        break;
                }

                $add_field_code_tmp = str_replace(array('[FORM_NAME]', '[FIELD_NAME]'), array($value['form_name'], $value['field_name']), $add_field_code_tmp);
                $add_field_code     .= $add_field_code_tmp;

                $edit_field_code_tmp = str_replace(array('[FORM_NAME]', '[FIELD_NAME]'), array($value['form_name'], $value['field_name']), $edit_field_code_tmp);
                $edit_field_code     .= $edit_field_code_tmp;


                //关联处理
                if ($value['is_relation'] == 1 ) {
                    if( $value['relation_type'] == 1){
                        $table_name = $this->getSelectFieldFormat($value['field_name']);

                        $class_name = parse_name($table_name, 1);
                        $relation_1 .= 'use app\\common\\model\\' . $class_name . ";\n";

                        $code_3     = file_get_contents($this->config['template']['path'] . 'controller/relation_data_list.stub');
                        $list_name  = $this->getSelectFieldFormat($value['field_name'], 2);
                        $code_3     = str_replace(array('[LIST_NAME]', '[CLASS_NAME]'), array($list_name, $class_name), $code_3);
                        $relation_3 .= $code_3;
                    }

                }else if($value['form_type']==='select'){

                    $list_name  = $this->getSelectFieldFormat($value['field_name'], 2);
                    $const_name = $this->getSelectFieldFormat($value['field_name'], 3);
                    $assign     = "'$list_name'=>" . $this->data['table'] . '::' . $const_name . ',';
                    $relation_3 .= $assign;
                }
            }


            //[SEARCH_DATA_LIST]
            if ($value['index_search'] === 'select') {
                if ($value['is_relation'] == 1 && $value['relation_type'] == 1) {
                    $table_name        = $this->getSelectFieldFormat($value['field_name'], 1);
                    $select_class_name = parse_name($table_name, 1);
                    $select_list_name  = $this->getSelectFieldFormat($value['field_name'], 2);
                    $code_select       = file_get_contents($this->config['template']['path'] . 'controller/relation_data_list.stub');
                    $code_select       = str_replace(array('[LIST_NAME]', '[CLASS_NAME]'), array($select_list_name, $select_class_name), $code_select);

                    $index_select .= $code_select;
                } else if ($value['is_relation'] == 0) {
                    // 这里是处理select字段的选择列表

                    $list_name  = $this->getSelectFieldFormat($value['field_name'], 2);
                    $const_name = $this->getSelectFieldFormat($value['field_name'], 3);
                    // '[LIST_NAME]' => [CLASS_NAME]::all(),
                    $assign     = "'$list_name'=>" . $this->data['table'] . '::' . $const_name . ',';

                    $index_select .= $assign;
                }
            }


            if ($value['is_list'] == 1) {

                //列表关联显示
                if ($value['is_relation'] == 1 && $value['relation_type'] == 1) {
                    $relation_with_name = $this->getSelectFieldFormat($value['field_name'], 1);
                    $relation_with_list .= empty($relation_with_list) ? $relation_with_name : ',' . $relation_with_name;

                }

                //如果有列表导出
                if ($this->data['view']['export'] == 1) {
                    $export_header .= "'" . $value['form_name'] . "',";
                    if ($value['getter_setter'] === 'switch') {
                        $export_body .= '$record[' . "'" . $value['field_name'] . "'" . '] = $item->' . $value['field_name'] . '_text' . ";\n";
                    } else if ($value['is_relation'] == 1 && $value['relation_type'] == 1) {

                        $relation_name = $this->getSelectFieldFormat($value['field_name'], 1);
                        $export_body   .= '$record[' . "'" . $value['field_name'] . "'" . '] = $item->' . $relation_name . '->' . $value['relation_show'] . '?? ' . "'" . "'" . ";\n";
                    } else {
                        $export_body .= '$record[' . "'" . $value['field_name'] . "'" . '] = $item->' . $value['field_name'] . ";\n";
                    }
                }

            }

        }


        //启用禁用
        $enable_code = '';
        if (in_array(5, $this->data['controller']['action'])) {
            $enable_tmp  = file_get_contents($this->config['template']['path'] . 'controller/enable.stub');
            $enable_tmp  = str_replace('[MODEL_NAME]', $this->data['model']['name'], $enable_tmp);
            $enable_code = $enable_tmp;
        }

        if (strlen($export_header) > 0) {
            $export_name = $this->data['table'];
            $code_export = file_get_contents($this->config['template']['path'] . 'controller/export.stub');
            $code_export = str_replace(array('[HEADER_LIST]', '[BODY_ITEM]', '[FILE_NAME]'), array($export_header, $export_body, $export_name), $code_export);
            $export_code = $code_export;
        }


        if (strlen($relation_3) > 0) {
            $code_2     = file_get_contents($this->config['template']['path'] . 'controller/relation_assign_1.stub');
            $code_2     = str_replace('[RELATION_LIST]', $relation_3, $code_2);
            $relation_2 = $code_2;
        }


        //如果有列表显示
        if (strlen($relation_with_list) > 0) {
            $relation_with = file_get_contents($this->config['template']['path'] . 'controller/relation_with.stub');
            $relation_with = str_replace('[WITH_LIST]', $relation_with_list, $relation_with);
        }


        $file = $this->config['template']['controller'];
        $code = file_get_contents($file);

        //控制器添加方法特殊字段处理
        //控制器修改方法特殊字段处理
        $code = str_replace(
            array('[NAME]', '[CONTROLLER_NAME]', '[CONTROLLER_MODULE]', '[MODEL_NAME]', '[MODEL_MODULE]', '[VALIDATE_NAME]', '[VALIDATE_MODULE]', '[ADD_FIELD_CODE]', '[EDIT_FIELD_CODE]', '[RELATION_1]', '[RELATION_2]', '[RELATION_3]', '[EXPORT_CODE]', '[ENABLE_CODE]', '[RELATION_WITH]', '[SEARCH_DATA_LIST]'),
            array($this->data['cn_name'], $this->data['controller']['name'], $this->data['controller']['module'], $this->data['model']['name'], $this->data['model']['module'], $this->data['validate']['name'], $this->data['validate']['module'], $add_field_code, $edit_field_code, $relation_1, $relation_2, $relation_3, $export_code, $enable_code, $relation_with, $index_select),
            $code
        );


        $msg = '';
        try {
            file_put_contents($this->config['file_dir']['controller'] . $this->data['controller']['name'] . 'Controller' . '.php', $code);
            $result = true;
        } catch (\Exception $e) {
            $msg    = $e->getMessage();
            $result = false;
        }
        return $result ?? $msg;
    }

    //创建模型

    /**
     * @return bool|string
     */
    protected function createModel()
    {

        //不生成模型
        if ($this->data['model']['create'] == 0) {
            return true;
        }

        $auto_time      = 'protected $autoWriteTimestamp = true;';
        $soft_delete1   = 'use think\model\concern\SoftDelete;';
        $soft_delete2   = 'use SoftDelete;';
        $soft_delete3_1 = 'public $softDelete = true;';
        $soft_delete3_2 = 'public $softDelete = false;';

        $file = $file = $this->config['template']['model'];
        $code = file_get_contents($file);
        $code = str_replace(array('[NAME]', '[TABLE_NAME]', '[MODEL_NAME]', '[MODEL_MODULE]'), array($this->data['cn_name'], $this->data['table'], $this->data['model']['name'], $this->data['model']['module']), $code);

        //软删除
        if ($this->data['model']['soft_delete']) {
            $code = str_replace(array('[SOFT_DELETE_USE1]', '[SOFT_DELETE_USE2]', '[SOFT_DELETE_USE3]'), array($soft_delete1, $soft_delete2, $soft_delete3_1), $code);
        } else {
            $code = str_replace(array("\n" . '[SOFT_DELETE_USE1]' . "\n", "\n    " . '[SOFT_DELETE_USE2]', '[SOFT_DELETE_USE3]'), array('', '', $soft_delete3_2), $code);
        }

        //自动时间戳
        if ($this->data['model']['timestamp']) {
            $code = str_replace('[AUTO_TIMESTAMP]', $auto_time, $code);
        } else {
            $code = str_replace('[AUTO_TIMESTAMP]' . "\n\n", '', $code);
        }

        //关联
        $relation_code = '';
        //获取器/修改器
        $getter_setter_code = '';
        // 自定义选择数据
        $select_data_code = '';

        foreach ($this->data['data'] as $key => $value) {

            if ($value['is_relation']) {

                $tmp_code = file_get_contents($this->config['template']['path'] . 'model/relation.stub');

                if ($value['is_relation'] == 1) {
                    //外键
                    $relation_type = 'belongsTo';
                    $table_name    = $this->getSelectFieldFormat($value['field_name'], 1);
                    //表中文名
                    $cn_name    = '';
                    $table_info = Db::query('SHOW TABLE STATUS LIKE ' . "'" . $table_name . "'");
                    if ($table_info) {
                        $cn_name = $table_info[0]['Comment'] ?? '';
                    }
                    $relation_name  = parse_name($table_name, 1, false);
                    $relation_class = parse_name($table_name, 1);
                    $tmp_code       = str_replace(array('[RELATION_NAME]', '[RELATION_TYPE]', '[CLASS_NAME]', '[CN_NAME]'), array($relation_name, $relation_type, $relation_class, $cn_name), $tmp_code);
                    $relation_code  .= $tmp_code;

                } else if ($value['is_relation'] == 2) {
                    //主键
                    $relation_type = 'hasMany';
                    if ($value['relation_type'] == 2) {
                        $relation_type = 'hasOne';
                    }

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
                        $relation_code .= $tmp_code_item;
                    }
                }

            } else {
                // 如果是select，同时非关联
                if ($value['form_type'] === 'select') {
                    $field_select_data = $value['field_select_data'];

                    if (empty($field_select_data)) {
                        throw new Exception('请完善字段[' . $value['form_name'] . ']的自定义筛选/select数据');
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

                    $select_data_code .= $option_code;

                    // 处理select自定义数据的获取器
                    $field5             = $this->getSelectFieldFormat($value['field_name'], 5);
                    $field4             = $this->getSelectFieldFormat($value['field_name'], 3);
                    $tmp_code           = file_get_contents($this->config['template']['path'] . 'model/getter_setter_select.stub');
                    $tmp_code           = str_replace(array('[FIELD_NAME5]', '[FIELD_NAME4]', '[FIELD_NAME]'), array($field5, $field4, $value['field_name']), $tmp_code);
                    $getter_setter_code .= $tmp_code;

                }
            }


            if ($value['getter_setter']) {
                switch ($value['getter_setter']) {
                    case 'switch':
                        $tmp_code           = file_get_contents($this->config['template']['path'] . 'model/getter_setter_switch.stub');
                        $tmp_code           = str_replace(array('[FIELD_NAME]', '[FORM_NAME_LOWER]', '[FORM_NAME]'), array(parse_name($value['field_name'], 1), $value['field_name'], $value['form_name']), $tmp_code);
                        $getter_setter_code .= $tmp_code;
                        break;
                    case 'datetime':
                        $tmp_code           = file_get_contents($this->config['template']['path'] . 'model/getter_setter_datetime.stub');
                        $tmp_code           = str_replace(array('[FIELD_NAME]', '[FORM_NAME]'), array(parse_name($value['field_name'], 1), $value['form_name']), $tmp_code);
                        $getter_setter_code .= $tmp_code;
                        break;
                    case 'date':
                        $tmp_code           = file_get_contents($this->config['template']['path'] . 'model/getter_setter_date.stub');
                        $tmp_code           = str_replace(array('[FIELD_NAME]', '[FORM_NAME]'), array(parse_name($value['field_name'], 1), $value['form_name']), $tmp_code);
                        $getter_setter_code .= $tmp_code;
                        break;
                    default:
                        break;
                }
            }

        }

        $code = str_replace(array('[RELATION]', '[GETTER_SETTER]', '[SELECT_DATA_LIST]'), array($relation_code, $getter_setter_code, $select_data_code), $code);


        //暂时不用switch，因为基础模型已经有status的获取器
        /*//switch字段
        $switch_field = '';
        foreach ($this->data['data'] as $value) {
            if ($value['form_type'] == 'switch') {
                $switch_field .= "'" . $value['field_name'] . "',";
            }
        }
        //switch字段替换
        $code = str_replace('[SWITCH_FIELD]', $switch_field, $code);*/


        //搜索字段
        $search_field = '';
        //条件字段
        $where_field = '';
        //日期/时间范围查询字段
        $time_field = '';
        //多文件/多图上传获取器，修改器
        $multi_field = '';
        foreach ($this->data['data'] as $value) {
            switch ($value['index_search']) {
                case 'search':
                    $search_field .= "'" . $value['field_name'] . "',";
                    break;

                case 'select':
                    $where_field .= "'" . $value['field_name'] . "',";
                    break;
                case 'date':
                case 'datetime':
                    $time_field .= "'" . $value['field_name'] . "',";
                    break;
                default:
                    break;
            }

            //多图/文件的获取器/修改器处理
            if ($value['form_type'] === 'multi_image' || $value['form_type'] === 'multi_file') {
                $multi_field_tmp = MultiImage::$modelAttrCode;
                $multi_field_tmp = str_replace(array('[FORM_NAME]', '[FIELD_NAME]'), array($value['form_name'], parse_name($value['field_name'], 1)), $multi_field_tmp);
                $multi_field     .= $multi_field_tmp;
            }

        }
        //搜索字段替换
        //替换多图/多文件获取器，修改器
        $code = str_replace(array('[SEARCH_FIELD]', '[MULTI_FIELD]', '[WHERE_FIELD]', '[TIME_FIELD]'), array($search_field, $multi_field, $where_field, $time_field), $code);

        $msg = '';
        try {
            file_put_contents($this->config['file_dir']['model'] . $this->data['model']['name'] . '.php', $code);
            $result = true;
        } catch (\Exception $e) {
            $msg    = $e->getMessage();
            $result = false;
        }
        return $result ?? $msg;
    }

    //创建验证器
    protected function createValidate()
    {
        //不生成验证器
        if ($this->data['validate']['create'] == 0) {
            return true;
        }

        $file = $this->config['template']['validate'];
        $code = file_get_contents($file);
        $code = str_replace(array('[NAME]', '[VALIDATE_NAME]', '[VALIDATE_MODULE]'), array($this->data['cn_name'], $this->data['validate']['name'], $this->data['validate']['module']), $code);


        $rule_code      = '';
        $msg_code       = '';
        $scene_code     = Field::$validateSceneCode;
        $scene_code_tmp = '';
        foreach ($this->data['data'] as $key => $value) {
            if (is_array($value['form_validate']) && $value['is_form'] == 1) {

                if (in_array('required', $value['form_validate']) && !in_array($value['form_type'], ['file', 'multi_file', 'image','video', 'multi_image'])) {

                    $rule_code_tmp = Required::$ruleValidate;
                    $rule_code_tmp = str_replace(array('[FORM_NAME]', '[FIELD_NAME]'), array($value['form_name'], $value['field_name']), $rule_code_tmp);

                    $rule_code .= $rule_code_tmp;

                    $msg_code_tmp = Required::$msgValidate;

                    $msg_code_tmp = str_replace(array('[FIELD_NAME]', '[FORM_NAME]'), array($value['field_name'], $value['form_name']), $msg_code_tmp);
                    $msg_code     .= $msg_code_tmp;

                    $scene_code_tmp .= "'" . $value['field_name'] . "',";


                    //[RULE_FIELD]
                }
            }
        }

        $scene_code = str_replace('[RULE_FIELD]', $scene_code_tmp, $scene_code);

        //规则
        //消息
        //场景
        $code = str_replace(array('[VALIDATE_RULE]', '[VALIDATE_MSG]', '[VALIDATE_SCENE]'), array($rule_code, $msg_code, $scene_code), $code);

        //场景方法
        $code = str_replace('[VALIDATE_SCENE_FUNC]', '', $code);
        /*if ($this->data['validate_scene_func']) {
            $code = str_replace('[VALIDATE_SCENE_FUNC]', '', $code);
        } else {
            $code = str_replace('[VALIDATE_SCENE_FUNC]', '', $code);
        }*/

        $msg = '';
        try {
            file_put_contents($this->config['file_dir']['validate'] . $this->data['validate']['name'] . 'Validate' . '.php', $code);
            $result = true;
        } catch (\Exception $e) {
            $msg    = $e->getMessage();
            $result = false;
        }
        return $result ?? $msg;
    }

    //列表视图
    protected function createIndexView()
    {
        //如果不需要列表视图，直接返回
        if ($this->data['view']['create_index'] == 0) {
            return true;
        }

        //列表数据名称
        $name_list = '';
        //列表数据字段
        $field_list = '';
        //搜索框显示
        $search_name = '';
        //其他搜索html
        $search_html = '';
        $file_fields = ['file', 'image','video'];
        $sort_code   = '';

        //OPERATION_ICON
        //OPERATION_TEXT
        $operation_del_icon = '<i class="fa fa-trash"></i>';
        $operation_del_text = '删除';

        $operation_edit_icon = '<i class="fa fa-pencil"></i>';
        $operation_edit_text = '修改';

        $operation_disable_icon = '<i class="fa fa-circle"></i>';
        $operation_disable_text = '禁用';

        $operation_enable_icon = '<i class="fa fa-circle"></i>';
        $operation_enable_text = '启用';


        foreach ($this->data['data'] as $key => $value) {

            // 排序处理
            if ($value['list_sort'] == 1) {
                if (strlen($sort_code) == 0) {

                    $sort_code .= file_get_contents($this->config['template']['view']['index_path'] . 'sort1.stub');
                }
                $option_code = file_get_contents($this->config['template']['view']['index_path'] . 'sort_option.stub');
                $option_code = str_replace(array('[FORM_NAME]', '[FIELD_NAME]'), array($value['form_name'], $value['field_name']), $option_code);
                $sort_code   .= $option_code;
            }

            // 列表处理
            if ($value['is_list'] == 1) {
                //名称显示
                $name_list .= str_replace('[FORM_NAME]', $value['form_name'], Field::$listNameHtml);
                //字段内容显示
                if (in_array($value['form_type'], $file_fields)) {
                    //图片显示
                    $field_list .= str_replace('[FIELD_NAME]', $value['field_name'], Field::$listImgHtml);
                } else if ($value['form_type'] === 'multi_image') {
                    //多图显示
                    $field_list .= str_replace('[FIELD_NAME]', $value['field_name'], Field::$listMultiImgHtml);
                } else if ($value['form_type'] === 'multi_file') {
                    //多文件展示
                    $field_list .= str_replace('[FIELD_NAME]', $value['field_name'], Field::$listMultiFileHtml);
                } else if ($value['form_type'] === 'switch' ) {
                    //status switch显示
                    if($value['getter_setter'] === 'switch'){
                        $field_list .= str_replace('[FIELD_NAME]', $value['field_name'], Field::$listSwitchHtml);
                    }
                } else if ($value['form_type'] === 'select') {
                    if( $value['is_relation'] == 1 ){
                        if($value['relation_type'] == 1){
                            //关联字段显示
                            $field_name = $this->getSelectFieldFormat($value['field_name'], 1) . '.' . $value['relation_show'] . '|default=' . "''";
                            $field_list .= str_replace('[FIELD_NAME]', $field_name, Field::$listFieldHtml);
                        }
                    }else{
                        $field_name = $this->getSelectFieldFormat($value['field_name'], 4);

                        $field_list .= str_replace('[FIELD_NAME]', $field_name, Field::$listFieldHtml);
                    }
                }else {
                    //普通字段显示
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
                    if ($value['is_relation'] == 1) {
                        if($value['relation_type'] == 1){
                            //关联字段筛选
                            $field_name  = str_replace('_id', '', $value['field_name']);
                            $search_html .= str_replace(array('[FIELD_NAME]', '[FIELD_NAME1]', '[FORM_NAME]', '[RELATION_SHOW]'), array($value['field_name'], $field_name, $value['form_name'], $value['relation_show']), Field::$listSearchRelationHtml);

                        }
                       } else {
                        //自定义select
                        $field_select_data = $value['field_select_data'];
                        if (empty($field_select_data)) {
                            throw new Exception('请完善字段[' . $value['form_name'] . ']的自定义筛选/select数据');
                        }

                        $field_name_list = $this->getSelectFieldFormat($value['field_name'], 2);

                        $search_html .= str_replace(array('[FORM_NAME]', '[FIELD_NAME]', '[FIELD_LIST]'), array($value['form_name'], $value['field_name'], $field_name_list), Field::$listSearchSelectHtml);
                    }
                    break;

                case 'date':
                    $search_html .= str_replace(array('[FIELD_NAME]', '[FORM_NAME]'), array($value['field_name'], $value['form_name']), Field::$listSearchDate);
                    break;

                case 'datetime':
                    $search_html .= str_replace(array('[FIELD_NAME]', '[FORM_NAME]'), array($value['field_name'], $value['form_name']), Field::$listSearchDatatime);
                    break;
                default:
                    break;
            }

        }

        if (strlen($sort_code) > 0) {
            $sort_code .= file_get_contents($this->config['template']['view']['index_path'] . 'sort2.stub');;
        }


        $file = $this->config['template']['view']['index'];
        $code = file_get_contents($file);


        //列表删除判断
        $del1 = '';
        $del2 = '';
        //列表选择
        $select1 = '';
        $select2 = '';

        //列表添加
        $create = '';

        //列表刷新
        $refresh = '';

        //如果有删除或者启用/禁用，开启选择
        if ($this->data['view']['delete'] || $this->data['view']['enable']) {
            $select1 = file_get_contents($this->config['template']['view']['index_select1']);
            $select2 = file_get_contents($this->config['template']['view']['index_select2']);
        }

        //删除按钮处理
        if ($this->data['view']['delete']) {
            $del1 = file_get_contents($this->config['template']['view']['index_del1']);
            $del2 = file_get_contents($this->config['template']['view']['index_del2']);
            //操作形式处理
            if ($this->data['view']['index_button'] == 1) {
                $operation_del_text = '';
            } else if ($this->data['view']['index_button'] == 2) {
                $operation_del_icon = '';
            }
            $del2 = str_replace(array('[OPERATION_DEL_ICON]', '[OPERATION_DEL_TEXT]'), array($operation_del_icon, $operation_del_text), $del2);
        }

        //添加按钮处理
        if ($this->data['view']['create']) {
            $create = file_get_contents($this->config['template']['view']['index_path'] . 'create.stub');
        }

        //刷新按钮处理
        if ($this->data['view']['refresh']) {
            $refresh = file_get_contents($this->config['template']['view']['index_path'] . 'refresh.stub');
        }

        $code = str_replace(array('[INDEX_DEL1]', '[INDEX_DEL2]', '[INDEX_SELECT1]', '[INDEX_SELECT2]', '[INDEX_CREATE]', '[INDEX_REFRESH]'), array($del1, $del2, $select1, $select2, $create, $refresh), $code);


        //顶部筛选（filter）和导出。筛选功能暂时为必生成
        //$filter = '';
        $filter = file_get_contents($this->config['template']['view']['index_filter']);
        $code   = str_replace('[INDEX_FILTER]', $filter, $code);

        //导出
        $export = '';
        if ($this->data['view']['export']) {
            $export = file_get_contents($this->config['template']['view']['index_export']);
        }

        //启用/禁用
        $enable1 = '';
        $enable2 = '';
        if ($this->data['view']['enable']) {
            $enable1 = file_get_contents($this->config['template']['path'] . 'view/index/enable1.stub');
            $enable2 = file_get_contents($this->config['template']['path'] . 'view/index/enable2.stub');
            //操作形式处理
            if ($this->data['view']['index_button'] == 1) {
                $operation_disable_text = '';
                $operation_enable_text  = '';
            } else if ($this->data['view']['index_button'] == 2) {
                $operation_disable_icon = '';
                $operation_enable_icon  = '';
            }
            $enable2 = str_replace(array('[OPERATION_DISABLE_ICON]', '[OPERATION_DISABLE_TEXT]', '[OPERATION_ENABLE_ICON]', '[OPERATION_ENABLE_TEXT]'), array($operation_disable_icon, $operation_disable_text, $operation_enable_icon, $operation_enable_text), $enable2);
        }

        if ($this->data['view']['index_button'] == 1) {
            $operation_edit_text = '';
        } else if ($this->data['view']['index_button'] == 2) {
            $operation_edit_icon = '';
        }

        $code = str_replace(array('[OPERATION_EDIT_ICON]', '[OPERATION_EDIT_TEXT]', '[INDEX_ENABLE1]', '[INDEX_ENABLE2]', '[INDEX_EXPORT]', '[NAME_LIST]', '[FIELD_LIST]', '[SEARCH_FIELD]', '[SORT_CODE]', '[SEARCH_HTML]'), array($operation_edit_icon, $operation_edit_text, $enable1, $enable2, $export, $name_list, $field_list, $search_name, $sort_code, $search_html), $code);

        $msg = '';
        try {
            file_put_contents($this->config['file_dir']['view'] . $this->data['table'] . '/index.html', $code);
            $result = true;
        } catch (\Exception $e) {
            $msg    = $e->getMessage();
            $result = false;
        }
        return $result ?? $msg;
    }

    //add视图页面
    protected function createAddView()
    {
        //如果不需要列表视图，直接返回
        if ($this->data['view']['create_add'] == 0) {
            return true;
        }

        $form_body     = '';
        $form_rules    = '';
        $form_messages = '';

        //日期控件类的字段名
        $date_field = ['date', 'datetime'];

        foreach ($this->data['data'] as $key => $value) {

            if ($value['is_form'] == 1) {
                try {

                    if ($value['form_type'] === 'switch') {
                        $value['form_type'] = 'switch_field';
                    } else if ($value['form_type'] === 'select') {
                        $value['relation_data'] = '';
                        // 这里是关联的
                        if ($value['is_relation'] == 1) {

                            if ($value['relation_type'] == 1) {
                                $list_code              = file_get_contents($this->config['template']['path'] . 'view/add/relation_select_data.stub');
                                $list_name              = $this->getSelectFieldFormat($value['field_name'], 2);
                                $list_code              = str_replace(array('[DATA_LIST]', '[FIELD_NAME]', '[RELATION_SHOW]'), array($list_name, $value['field_name'], $value['relation_show']), $list_code);
                                $value['relation_data'] = $list_code;
                            }
                        } else {
                            // 这里是非关联的
                            $list_code              = file_get_contents($this->config['template']['path'] . 'view/add/customer_select_data.stub');
                            $list_name              = $this->getSelectFieldFormat($value['field_name'], 2);
                            $list_code              = str_replace(array('[FIELD_LIST]', '[FIELD_NAME]'), array($list_name, $value['field_name']), $list_code);
                            $value['relation_data'] = $list_code;
                        }


                    } else if (in_array($value['form_type'], $date_field)) {
                        //如果是日期控件类字段，默认值各式不符的一律修改成''
                        if (is_numeric($value['field_default'])) {
                            $value['field_default'] = '';
                        }
                    }

                    $class_name = parse_name($value['form_type'], 1);
                    $class      = '\\generate\\field\\' . $class_name;
                    $form_body  .= $class::create($value);

                } catch (\Exception $exception) {
                    echo $exception->getMessage();
                    exit();
                } catch (\Error $error) {
                    echo $error->getMessage();
                    exit();
                }

                //验证暂时不处理图片和文件
                if (is_array($value['form_validate'])
                    && $value['form_type'] !== 'image'
                    && $value['form_type'] !== 'video'
                    && $value['form_type'] !== 'file'
                    && $value['form_type'] !== 'multi_image'
                    && $value['form_type'] !== 'multi_file') {

                    if (in_array('required', $value['form_validate'])) {

                        $rule_html = Required::$ruleForm;

                        //如果是多选select，验证字段使用[]后缀
                        if ($value['form_type'] === 'multi_select' || $value['form_type'] === 'multi_image' || $value['form_type'] === 'multi_file') {
                            $value['field_name'] .= '[]';
                        }

                        $form_rules .= str_replace('[FIELD_NAME]', $value['field_name'], $rule_html);

                        $msg_html      = Required::$msgForm;
                        $msg_html      = str_replace(array('[FIELD_NAME]', '[FORM_NAME]'), array($value['field_name'], $value['form_name']), $msg_html);
                        $form_messages .= $msg_html;
                    }
                }
            }
        }


        $file = $this->config['template']['view']['add'];
        $code = file_get_contents($file);
        $code = str_replace(
            array('[FORM_BODY]', '[FORM_RULES]', '[FORM_MESSAGES]'),
            array($form_body, $form_rules, $form_messages),
            $code);

        $msg = '';
        try {
            if (!is_dir($this->config['file_dir']['view'] . $this->data['table'])) {
                $this->mkFolder($this->config['file_dir']['view'] . $this->data['table']);
            }

            file_put_contents($this->config['file_dir']['view'] . $this->data['table'] . '/add.html', $code);
            $result = true;
        } catch (\Exception $e) {
            $msg    = $e->getMessage();
            $result = false;
        }
        return $result ?? $msg;

        /*$code = str_replace('[FORM_RULES]', $this->data['validate'], $code);
        $code = str_replace('[VALIDATE_MODULE]', $this->data['validate_module'], $code);

        //规则
        if ($this->data['validate_rule']) {
            $code = str_replace('[VALIDATE_RULE]', '', $code);
        } else {
            $code = str_replace('[VALIDATE_RULE]', '', $code);
        }

        //消息
        if ($this->data['validate_msg']) {
            $code = str_replace('[VALIDATE_MSG]', '', $code);
        } else {
            $code = str_replace('[VALIDATE_MSG]', '', $code);
        }

        //场景
        if ($this->data['validate_scene']) {
            $code = str_replace('[VALIDATE_SCENE]', '', $code);
        } else {
            $code = str_replace('[VALIDATE_SCENE]', '', $code);
        }

        //场景方法
        if ($this->data['validate_scene_func']) {
            $code = str_replace('[VALIDATE_SCENE_FUNC]', '', $code);
        } else {
            $code = str_replace('[VALIDATE_SCENE_FUNC]', '', $code);
        }

         $msg = '';
        try {
            file_put_contents($this->appPath . $this->data['vali\date_module'] . '/validate/' . $this->data['validate'] . '.php', $code);
            $result = true;
        } catch (\Exception $e) {
            $msg    = $e->getMessage();
            $result = false;
        }
        return $result ?? $msg;

        */

    }

    //自动添加菜单
    protected function createMenu()
    {

        if ($this->data['menu']['create'] < 0) {
            return true;
        }

        //菜单前缀
        $url_prefix = 'admin/' . $this->data['table'];
        //显示名称
        $name_show = $this->data['cn_name'];
        Db::startTrans();
        try {

            if (AdminMenu::where('url', $url_prefix . '/index')->find()) {
                exception('菜单已存在');
            }

            $parent = [
                'parent_id'  => $this->data['menu']['create'],
                'name'       => $name_show . $this->data['module']['name_suffix'],
                'url'        => $url_prefix . '/index',
                'icon'       => $this->data['module']['icon'],
                'is_show'    => 1,
                'log_method' => '不记录',
            ];
            $result = AdminMenu::create($parent);
            if (in_array(2, $this->data['menu']['menu'])) {
                AdminMenu::create([
                    'parent_id'  => $result->id,
                    'name'       => '添加' . $name_show,
                    'url'        => $url_prefix . '/add',
                    'icon'       => 'fa-plus',
                    'is_show'    => 0,
                    'log_method' => 'POST',
                ]);
            }

            if (in_array(3, $this->data['menu']['menu'])) {
                AdminMenu::create([
                    'parent_id'  => $result->id,
                    'name'       => '修改' . $name_show,
                    'url'        => $url_prefix . '/edit',
                    'icon'       => 'fa-pencil',
                    'is_show'    => 0,
                    'log_method' => 'POST',
                ]);
            }

            if (in_array(4, $this->data['menu']['menu'])) {
                AdminMenu::create([
                    'parent_id'  => $result->id,
                    'name'       => '删除' . $name_show,
                    'url'        => $url_prefix . '/del',
                    'icon'       => 'fa-trash',
                    'is_show'    => 0,
                    'log_method' => 'POST',
                ]);
            }

            if (in_array(5, $this->data['menu']['menu'])) {
                AdminMenu::create([
                    'parent_id'  => $result->id,
                    'name'       => '启用' . $name_show,
                    'url'        => $url_prefix . '/enable',
                    'icon'       => 'fa-circle',
                    'is_show'    => 0,
                    'log_method' => 'POST',
                ]);

                AdminMenu::create([
                    'parent_id'  => $result->id,
                    'name'       => '禁用' . $name_show,
                    'url'        => $url_prefix . '/disable',
                    'icon'       => 'fa-circle',
                    'is_show'    => 0,
                    'log_method' => 'POST',
                ]);
            }

            Db::commit();
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            Db::rollback();
        }

        return true;
    }

    //创建目录
    protected function mkFolder($path)
    {
        if (!is_readable($path)) {
            is_file($path) || mkdir($path, 0755) || is_dir($path);
        }
    }

    //获取目录下的所有类名
    public function getClassList($dir, $except = [])
    {
        $handler = opendir($dir);
        while (($filename = readdir($handler)) !== false) {
            if ($filename !== '.' && $filename !== '..') {
                $filename = str_replace('.php', '', $filename);

                if (!in_array($filename, $except)) {
                    $files[] = $filename;
                }
            }
        }
        closedir($handler);

        return $files;
    }


    //获取字段列表
    public function getAll($table_name)
    {
        $parse_name = parse_name($table_name, 1);

        $data = [
            //表
            'table'      => [
                'name'    => $table_name,
                'cn_name' => ''
            ],
            //控制器
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
                    //关联方法，删除模式，1判断关联，2不做操作，3关联删除
                    'user' => 1,
                ]
            ],
            //模型
            'model'      => [
                'create'         => 1,
                'name'           => $parse_name,
                'auto_timestamp' => 1,
                'soft_delete'    => 1,

            ],
            //验证器
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
            //字段
            'field'      => [
                [
                    //字段名
                    'name'              => 'name',
                    //字段中文名
                    'cn_name'           => '名称',
                    //字段类型
                    'field_type'        => 'varchar(30)',
                    //是否列表显示
                    'is_list'           => 1,
                    //是否为表单字段
                    'is_form'           => 1,
                    //表单类型
                    'form_type'         => 'text',
                    //表单验证
                    'validate'          => ['required'],
                    //验证场景
                    'validate_scene'    => ['admin_add'],
                    //获取器
                    'getter'            => 'date_time',
                    //修改器
                    'setter'            => 'date_time',
                    //列表筛选
                    'index_search'      => '',
                    //列表筛选数据
                    'field_select_data' => '',
                    //关联显示
                    'relation_display'  => 1,
                    //关联显示字段
                    'display_field'     => 'name',

                ],
            ],

        ];

        //所有字段信息
        $field_list = Db::query('SHOW FULL COLUMNS FROM `' . $table_name . '`');
        //表信息
        $table_info = Db::query('SHOW TABLE STATUS LIKE ' . "'" . $table_name . "'");
        $table_info = $table_info[0];
        //表名
        $data['table']['name'] = $table_info['Name'];
        //表中文名
        $data['table']['cn_name'] = $table_info['Comment'];


        //定义好以下情况，
        //90%概率为列表的字段，
        //90%概率不为列表的字段，
        //90%为搜索的字段
        //90%不为搜索的字段
        //90%为表单的字段
        //90不为表单的字段
        //导出字段暂时和显示字段一样


        //显示为列表的字段名，暂时不用
        //$list_show_field = ['id','name','mobile','description','address','money','price', 'keywords','title','img','create_time','sort_number','user_id','status'];
        //列表隐藏字段，如果为text字段，大概率隐藏
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


        //搜索字段，如果为varchar，char字段，大概率需要搜索
        $search_show_field = ['id', 'mobile', 'keywords', 'id_card', 'name', 'title', 'username', 'nickname', 'true_name', 'description'];
        $search_show_type  = ['char', 'varchar'];
        //搜索隐藏字段
        //$search_hide_field = ['id','name','description'];

        //导出显示字段
        //$export_show_field = ['id','create_time'];
        //导出隐藏字段，和列表隐藏字段差不多
        $export_hide_field = ['update_time', 'delete_time'];
        $export_hide_type  = ['tinytext', 'tinyblob', 'text', 'blob', 'longtext', 'longblob'];

        //表单显示字段
        //$form_show_field = ['id','create_time','update_time','delete_time'];
        //表单隐藏字段
        $form_hide_field = ['id', 'create_time', 'update_time', 'delete_time'];
        $form_hide_type  = ['double'];

        $data['field'] = [];
        foreach ($field_list as $key => $value) {

            $field_data = [
                //字段名
                'name'              => $value['Field'],
                //字段中文名
                'cn_name'           => $value['Field'] === 'id' ? 'ID' : $value['Comment'],
                //字段类型
                'field_type'        => $value['Type'],
                //字段长度
                'field_length'      => 1,
                //默认值
                'default'           => $value['Default'],
                //是否列表显示
                'is_list'           => 1,
                //是否为表单字段
                'is_form'           => 1,
                //表单类型
                'form_type'         => 'text',
                //表单验证
                'validate'          => ['required'],
                'validate_html'     => '',
                //验证场景
                'validate_scene'    => ['admin_add'],
                //获取器/修改器
                'getter_setter'     => false,
                //首页筛选
                'index_search'      => '',
                'field_select_data' => '',
                //关联显示
                'relation_display'  => 1,
                //关联显示字段
                'display_field'     => 'name',
            ];


            $field_info = $this->getFieldInfo($field_data['name'], $field_data['field_type']);

            $field_data['field_length'] = $field_info['length'];

            //处理是否为列表显示
            if (in_array($field_info['name'], $list_hide_field) || in_array($field_info['type'], $list_hide_type)) {
                $field_data['is_list'] = 0;
            }

            //处理是否为列表搜索
            if (in_array($field_info['name'], $search_show_field) && in_array($field_info['type'], $search_show_type)) {
                $field_data['is_search'] = 1;
            }

            //处理是否不为表单字段
            if (in_array($field_info['name'], $form_hide_field) || in_array($field_info['type'], $form_hide_type)) {
                $field_data['is_form'] = 0;
            }

            //处理字段表单类型
            $form_info                   = $this->getFormInfo($field_info);
            $field_data['form_type']     = $form_info['form_type'];
            $field_data['getter_setter'] = $form_info['getter_setter'];


            //验证
            //$field_data['validate'] = [];
            $field_data['validate_html'] = $this->getValidateHtml($field_data);


            $data['field'][] = $field_data;

        }


        return $data;
    }


    //根据表单类型和长度返回相应的验证
    public function getValidateHtml($field_data)
    {
        $html = '';


        try {

            if ($field_data['form_type'] === 'switch') {
                $field_data['form_type'] = 'switch_field';
            }

            $class_name = parse_name($field_data['form_type'], 1);

            $class = '\\generate\\field\\' . $class_name;
            $html  = $class::rule($field_data['field_length']);

        } catch (\Exception $exception) {
            echo $exception->getMessage();
        } catch (\Error $error) {
            echo $error->getMessage();
        }

        return $html;
    }

    public function getFormInfo($field_info)
    {

        //结尾：_id为select，_time为datetime，_date为date，
        //结尾：_count为number，_lng/_longitude为map，img为image，slide为multiImage
        //开头：is_为switch
        //字段名：lng/longitude为map,password为password，money,price为number
        //字段名：status大概率为switch或者其他
        $field_data = [
            'form_type'     => 'text',
            'getter_setter' => false,
        ];

        //id
        if ($field_info['name'] === 'id') {
            $field_data['form_type'] = 'number';
        }

        //_id为下拉列表，大多数为关联
        if (strrchr($field_info['name'], '_id') === '_id') {
            $field_data['form_type'] = 'select';
        }

        //日期时间
        if (strrchr($field_info['name'], '_time') === '_time') {
            $field_data['form_type'] = 'datetime';

            $ignore_field = ['create_time', 'update_time', 'delete_time'];
            if (!in_array($field_info['name'], $ignore_field) && $field_info['type'] === 'int') {
                $field_data['getter_setter'] = 'datetime';
            }
        }

        //日期
        if ($field_info['type'] === 'datetime') {
            $field_data['form_type'] = 'date';
        }

        //日期
        if (strrchr($field_info['name'], '_date') === '_date') {
            $field_data['form_type'] = 'date';
            if ($field_info['type'] === 'int') {
                $field_data['getter_setter'] = 'date';
            }
        }
        //日期
        if ($field_info['type'] === 'date') {
            $field_data['form_type'] = 'date';
        }

        //数量
        if (strrchr($field_info['name'], '_count') === '_count') {
            $field_data['form_type'] = 'number';
        }

        //数量
        if (strrchr($field_info['name'], '_number') === '_number') {
            $field_data['form_type'] = 'number';
        }

        //经纬度
        if (strrchr($field_info['name'], '_lng') === '_lng') {
            $field_data['form_type'] = 'map';
        }

        //图片
        if (strrchr($field_info['name'], 'img') === 'img') {
            $field_data['form_type'] = 'image';
        }

        //视频
        if (strrchr($field_info['name'], 'video') === 'video') {
            $field_data['form_type'] = 'video';
        }

        //轮播
        if (strrchr($field_info['name'], 'slide') === 'slide') {
            $field_data['form_type'] = 'multi_image';
        }

        //密码
        if (strrchr($field_info['name'], 'password') === 'password') {
            $field_data['form_type'] = 'password';
        }

        //颜色
        if (strrchr($field_info['name'], 'color') === 'color') {
            $field_data['form_type'] = 'color';
        }
        //图标
        if (strrchr($field_info['name'], 'icon') === 'icon') {
            $field_data['form_type'] = 'icon';
        }

        //价格，暂时用number
        if (strrchr($field_info['name'], 'price') === 'price') {
            $field_data['form_type'] = 'number';
        }

        //金额，暂时用number
        if (strrchr($field_info['name'], 'money') === 'money') {
            $field_data['form_type'] = 'number';
        }

        //状态
        if ($field_info['name'] === 'status') {
            $field_data['form_type']     = 'switch';
            $field_data['getter_setter'] = 'switch';
        }

        //手机号
        if ($field_info['name'] === 'mobile') {
            $field_data['form_type'] = 'mobile';
        }


        //头像
        if ($field_info['name'] === 'avatar') {
            $field_data['form_type'] = 'image';
        }

        //富文本
        if ($field_info['type'] === 'text' || $field_info['type'] === 'longtext') {
            $field_data['form_type'] = 'editor';
        }

        if (strpos($field_info['name'], 'is_') === 0 && $field_info['type'] === 'tinyint') {

            $field_data['form_type']     = 'switch';
            $field_data['getter_setter'] = 'switch';
        }

        return $field_data;
    }

    /**
     * @param $field_name
     * @param $type 1返回去掉_id的字段名，如果没有_id的话就返回原字段；
     * 2返回list，例如type字段的type_list，channel_id的channel_list;
     * 3为常量LIST，例如TYPE_LIST，CHANNEL_LIST；
     * 4为显示字段name,例如type_name，channel_name；
     * 这里要注意，如果原字段是_id结尾的，会干掉_id，例如channel_id_list不仅长，而且容易产生歧义，
     * 实际channel_list的话就非常明确，这是渠道列表,是一个二维数组。
     */
    protected function getSelectFieldFormat($field_name, $type = 1)
    {
        $_id_suffix   = '_id';
        $_list_suffix = '_list';
        $_name_suffix = '_name';

        switch ($type) {

            case 1:
            default:
                $result   = $field_name;
                $_id_post = strpos($field_name, $_id_suffix);
                if (strlen($field_name) === ($_id_post + 3)) {
                    $result = substr($result, 0, $_id_post);
                }
                break;
            case 2:

                $result   = $field_name;
                $_id_post = strpos($field_name, $_id_suffix);
                if (strlen($field_name) === ($_id_post + 3)) {
                    $result = substr($result, 0, $_id_post);
                }
                $result .= $_list_suffix;
                break;

            case 3:
                $result   = $field_name;
                $_id_post = strpos($field_name, $_id_suffix);
                if (strlen($field_name) === ($_id_post + 3)) {
                    $result = substr($result, 0, $_id_post);
                }
                $result = strtoupper($result . $_list_suffix);
                break;

            case 4:
                $result   = $field_name;
                $_id_post = strpos($field_name, $_id_suffix);
                if (strlen($field_name) === ($_id_post + 3)) {
                    $result = substr($result, 0, $_id_post);

                }
                $result .= $_name_suffix;
                break;

            case 5:
                $result   = $field_name;
                $_id_post = strpos($field_name, $_id_suffix);
                if (strlen($field_name) === ($_id_post + 3)) {
                    $result = substr($result, 0, $_id_post);
                }
                $result .= $_name_suffix;
                $result = parse_name($result, 1, true);
                break;
        }

        return $result;
    }
}