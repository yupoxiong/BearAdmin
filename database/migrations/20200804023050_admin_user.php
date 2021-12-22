<?php
/**
 * 后台用户迁移文件
 */
use app\common\exception\CommonServiceException;
use app\common\service\StringService;
use think\facade\Db;
use think\migration\Migrator;
use think\migration\db\Column;

class AdminUser extends Migrator
{

    public function change(): void
    {
        $table = $this->table('admin_user', ['comment' => '后台用户', 'engine' => 'InnoDB', 'encoding' => 'utf8mb4', 'collation' => 'utf8mb4_unicode_ci']);
        $table
            ->addColumn('username', 'string', ['limit' => 30, 'default' => '', 'comment' => '用户名'])
            ->addColumn('password', 'string', ['limit' => 255, 'default' => base64_encode(password_hash('_default__password_', 1)), 'comment' => '密码'])
            ->addColumn('nickname', 'string', ['limit' => 30, 'default' => '', 'comment' => '昵称'])
            ->addColumn('avatar', 'string', ['limit' => 255, 'default' => '/static/admin/images/avatar.png', 'comment' => '头像'])
            ->addColumn('role', 'string', ['limit' => 3210, 'default' => '', 'comment' => '角色'])
            ->addColumn('status', 'boolean', ['signed' => false, 'limit' => 1, 'default' => 1, 'comment' => '是否启用'])
            ->addColumn('create_time', 'integer', ['signed' => false, 'limit' => 10, 'default' => 0, 'comment' => '创建时间'])
            ->addColumn('update_time', 'integer', ['signed' => false, 'limit' => 10, 'default' => 0, 'comment' => '更新时间'])
            ->addColumn('delete_time', 'integer', ['signed' => false, 'limit' => 10, 'default' => 0, 'comment' => '删除时间'])
            ->addIndex(['username'], ['name' => 'index_username'])
            ->create();

        $this->insertData();
    }

    protected function insertData(): void
    {
        try {
            $develop_password = StringService::getRandString(10, true, true, true, false);
            $admin_password   = StringService::getRandString(10, true, true, true, false);
        } catch (CommonServiceException $e) {

            $develop_password = 'develop_admin';
            $admin_password   = 'super_admin';
        }
        $data = [
            [
                'id'       => 1,
                'username' => 'develop_admin',
                'nickname' => '开发管理员',
                'password' => $develop_password,
                'role'     => [1]
            ],
            [
                'id'       => 2,
                'username' => 'super_admin',
                'nickname' => '超级管理员',
                'password' => $admin_password,
                'role'     => [1]
            ]
        ];

        $msg = '开发管理员创建成功.' . "\n" . '用户名:develop_admin' . "\n" . '密码:'.$develop_password . "\n";
        $msg .= '超级管理员创建成功.' . "\n" . '用户名:super_admin' . "\n" . '密码:'.$admin_password . "\n";
        Db::startTrans();
        try {
            foreach ($data as $item) {
                \app\admin\model\AdminUser::create($item);
            }
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            $msg = $e->getMessage();
        }
        print ($msg);
    }
}
