<?php
/**
 * 设置
 */
use think\migration\Migrator;
use think\migration\db\Column;

class Setting extends Migrator
{

    public function change()
    {
        $table = $this->table('setting', ['comment' => '设置', 'engine' => 'InnoDB', 'encoding' => 'utf8mb4', 'collation' => 'utf8mb4_unicode_ci']);
        $table
            ->addColumn('setting_group_id', 'integer', ['limit' => 10, 'default' => 0, 'comment' => '所属分组'])
            ->addColumn('name', 'string', ['limit' => 50, 'default' => '', 'comment' => '名称'])
            ->addColumn('description', 'string', ['limit' => 100, 'default' => '', 'comment' => '描述'])
            ->addColumn('code', 'string', ['limit' => 50, 'default' => '', 'comment' => '代码'])
            ->addColumn('content', 'text', [ 'comment' => '设置配置及内容'])
            ->addColumn('sort_number', 'integer', ['limit' => 10, 'default' => 1000, 'comment' => '排序'])
            ->addColumn('create_time', 'integer', ['limit' => 10, 'default' => 0, 'comment' => '创建时间'])
            ->addColumn('update_time', 'integer', ['limit' => 10, 'default' => 0, 'comment' => '更新时间'])
            ->addColumn('delete_time', 'integer', ['limit' => 10, 'default' => 0, 'comment' => '删除时间'])
            ->create();
    }
}
