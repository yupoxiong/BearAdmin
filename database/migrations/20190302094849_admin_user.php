<?php
/**
 * 后台用户迁移文件
 * @author yupoxiong<i@yufuping.com>
 */

use think\Db;
use think\migration\Migrator;
use think\migration\db\Column;

class AdminUser extends Migrator
{
    public function change()
    {
        $table = $this->table('admin_user', ['comment' => '后台用户', 'engine' => 'InnoDB', 'encoding' => 'utf8mb4', 'collation' => 'utf8mb4_unicode_ci']);
        $table
            ->addColumn('username', 'string', ['limit' => 30, 'default' => '', 'comment' => '用户名'])
            ->addColumn('password', 'string', ['limit' => 255, 'default' => base64_encode(password_hash('_default__password_', 1)), 'comment' => '密码'])
            ->addColumn('nickname', 'string', ['limit' => 30, 'default' => '', 'comment' => '昵称'])
            ->addColumn('avatar', 'string', ['limit' => 255, 'default' => '/static/admin/images/avatar.png', 'comment' => '头像'])
            ->addColumn('role', 'string', ['limit' => 200, 'default' => '', 'comment' => '角色'])
            ->addColumn('status', 'boolean', ['limit' => 1, 'default' => 1, 'comment' => '是否启用'])
            ->addColumn('delete_time', 'integer', ['limit' => 10, 'default' => 0, 'comment' => '删除时间'])
            ->addIndex(['username'], ['name' => 'index_username'])
            ->create();

        $this->insertData();
    }

    protected function insertData()
    {
        $data = [
            [
                'id'       => 1,
                'username' => 'super_admin',
                'nickname' => '超级管理员',
                'password' => 'super_admin',
                'role'     => [1]
            ]
        ];

        $msg = '超级管理员创建成功.' . "\n" . '用户名:super_admin' . "\n" . '密码:super_admin' . "\n";
        Db::startTrans();
        try {
            foreach ($data as $item) {
                \app\admin\model\AdminUser::create($item);
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            $msg = $e->getMessage();
        }
        print ($msg);
    }
}
