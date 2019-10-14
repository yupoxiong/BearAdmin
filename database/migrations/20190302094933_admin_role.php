<?php
/**
 * 后台角色迁移文件
 * @author yupoxiong<i@yufuping.com>
 */

use think\Db;
use think\migration\Migrator;
use think\migration\db\Column;

class AdminRole extends Migrator
{
    public function change()
    {
        $table = $this->table('admin_role', ['comment'=>'后台角色','engine' => 'InnoDB', 'encoding' => 'utf8mb4', 'collation' => 'utf8mb4_unicode_ci']);
        $table
            ->addColumn('name', 'string', ['limit' => 50, 'default' => '', 'comment' => '名称'])
            ->addColumn('description', 'string', ['limit' => 100, 'default' => '', 'comment' => '简介'])
            ->addColumn('url', 'string', ['limit' => 1000, 'default' => '', 'comment' => '权限'])
            ->addColumn('status', 'boolean', ['limit' => 1, 'default' => 1, 'comment' => '是否启用'])
            ->create();
        $this->insertData();
    }

    protected function insertData()
    {
        $data = [
            [
                'id'          => 1,
                'name'        => '管理员',
                'description' => '后台管理员角色',
                'url'         => range(1,48),
                'status'      => 1
            ],
        ];

        $msg = '添加管理员角色成功.' . "\n";
        Db::startTrans();
        try {
            foreach ($data as $item) {
                \app\admin\model\AdminRole::create($item);
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            $msg = $e->getMessage();
        }
        print ($msg);
    }
}
