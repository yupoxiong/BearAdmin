<?php
/**
 * 设置分组
 */

use think\migration\Migrator;
use think\migration\db\Column;

class SettingGroup extends Migrator
{

    public function change()
    {
        $table = $this->table('setting_group', ['comment' => '设置分组', 'engine' => 'InnoDB', 'encoding' => 'utf8mb4', 'collation' => 'utf8mb4_unicode_ci']);
        $table
            ->addColumn('module', 'string', ['limit' => 30, 'default' => '', 'comment' => '作用模块'])
            ->addColumn('name', 'string', ['limit' => 50, 'default' => '', 'comment' => '名称'])
            ->addColumn('description', 'string', ['limit' => 100, 'default' => '', 'comment' => '描述'])
            ->addColumn('code', 'string', ['limit' => 50, 'default' => '', 'comment' => '代码'])
            ->addColumn('sort_number', 'integer', ['limit' => 10, 'default' => 1000, 'comment' => '排序'])
            ->addColumn('auto_create_menu', 'boolean', ['limit' => 1, 'default' => 0, 'comment' => '自动生成菜单'])
            ->addColumn('auto_create_file', 'boolean', ['limit' => 1, 'default' => 0, 'comment' => '自动生成配置文件'])
            ->addColumn('icon', 'string', ['limit' => 30, 'default' => 'fa-list', 'comment' => '图标'])
            ->addColumn('create_time', 'integer', ['limit' => 10, 'default' => 0, 'comment' => '创建时间'])
            ->addColumn('update_time', 'integer', ['limit' => 10, 'default' => 0, 'comment' => '更新时间'])
            ->addColumn('delete_time', 'integer', ['limit' => 10, 'default' => 0, 'comment' => '删除时间'])
            ->create();
    }
}
