<?php

use think\Db;
use think\migration\Migrator;
use think\migration\db\Column;

class User extends Migrator
{
    public function change()
    {
        $table = $this->table('user', ['comment' => '用户', 'engine' => 'InnoDB', 'encoding' => 'utf8mb4', 'collation' => 'utf8mb4_unicode_ci']);
        $table
            ->addColumn('avatar', 'string', ['limit' => 255, 'default' => '/static/index/images/avatar.png', 'comment' => '头像'])
            ->addColumn('username', 'string', ['limit' => 30, 'default' => '', 'comment' => '用户名'])
            ->addColumn('nickname', 'string', ['limit' => 30, 'default' => '', 'comment' => '昵称'])
            ->addColumn('mobile', 'string', ['limit' => 11, 'default' => '', 'comment' => '手机号'])
            ->addColumn('user_level_id', 'integer', ['limit' => 10, 'default' => 1, 'comment' => '用户等级'])
            ->addColumn('password', 'string', ['limit' => 255, 'default' => base64_encode(password_hash('_default__password_', 1)), 'comment' => '密码'])
            ->addColumn('status', 'boolean', ['limit' => 1, 'default' => 1, 'comment' => '是否启用'])
            ->addColumn('create_time', 'integer', ['limit' => 10, 'default' => 0, 'comment' => '创建时间'])
            ->addColumn('update_time', 'integer', ['limit' => 10, 'default' => 0, 'comment' => '更新时间'])
            ->addColumn('delete_time', 'integer', ['limit' => 10, 'default' => 0, 'comment' => '删除时间'])
            ->create();
        $this->insertData();
    }

    protected function insertData()
    {
        $data = '[{"id":1,"avatar":"/uploads/attachment/20190822/02fce9aecd6cadf6e019e988ad8703ce.png","user_level_id":2,"username":"test001","mobile":"13000000001","nickname":"测试001","password":"123456","status":1},{"id":2,"avatar":"/uploads/attachment/20190822/56644a9f283c058cd371316e186ef48a.png","user_level_id":3,"username":"test002","mobile":"18328374923","nickname":"测试2号会员","password":"123456","status":1},{"id":3,"avatar":"/uploads/attachment/20190822/f0a7cd43074283b428b0a33ecfca5f9d.png","user_level_id":4,"username":"测试3号","mobile":"18653165683","nickname":"测试3号","password":"123456","status":1},{"id":4,"avatar":"/uploads/attachment/20190822/1771ce624eccb96aad992df540126d3b.png","user_level_id":1,"username":"小女孩","mobile":"13638392923","nickname":"小女孩","password":"123456","status":1}]';

        $msg = '前台测试用户导入成功.' . "\n";
        Db::startTrans();
        $data = json_decode($data, true);
        try {
            foreach ($data as $item) {
                \app\common\model\User::create($item);
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            $msg = $e->getMessage();
        }
        print ($msg);
    }
}
