<?php
/**
 * 前台用户
 */

use think\facade\Db;
use think\migration\Migrator;
use think\migration\db\Column;

class User extends Migrator
{

    public function change()
    {
        $table = $this->table('user', ['comment' => '用户', 'engine' => 'InnoDB', 'encoding' => 'utf8mb4', 'collation' => 'utf8mb4_unicode_ci']);
        $table
            ->addColumn('user_level_id', 'integer', ['signed' => false, 'limit' => 10, 'default' => 0, 'comment' => '用户等级'])
            ->addColumn('username', 'string', ['limit' => 30, 'default' => '', 'comment' => '账号'])
            ->addColumn('password', 'string', ['limit' => 255, 'default' => '', 'comment' => '密码'])
            ->addColumn('mobile', 'string', ['limit' => 11, 'default' => '', 'comment' => '手机号'])
            ->addColumn('nickname', 'string', ['limit' => 20, 'default' => '', 'comment' => '昵称'])
            ->addColumn('avatar', 'string', ['limit' => 255, 'default' => '/static/index/images/avatar.png', 'comment' => '头像'])
            ->addColumn('status', 'boolean', ['signed' => false, 'limit' => 1, 'default' => 1, 'comment' => '是否启用'])
            ->addColumn('create_time', 'integer', ['signed' => false, 'limit' => 10, 'default' => 0, 'comment' => '创建时间'])
            ->addColumn('update_time', 'integer', ['signed' => false, 'limit' => 10, 'default' => 0, 'comment' => '更新时间'])
            ->addColumn('delete_time', 'integer', ['signed' => false, 'limit' => 10, 'default' => 0, 'comment' => '删除时间'])
            ->create();

        $this->insertData();
    }

    protected function insertData(): void
    {
        $data = '[{"id":1,"user_level_id":1,"username":"putong1","password":"putong1","mobile":"18899990000","nickname":"putong1","avatar":"\/uploads\/uploads\/20211105\/943dafd0517fecce981b6e3c06e4ac87.png","status":1},{"id":2,"user_level_id":1,"username":"putong2","password":"putong2","mobile":"18333333333","nickname":"putong2","avatar":"\/uploads\/uploads\/20211105\/5606eb6dda163840c30eb58ba82bbfca.png","status":1},{"id":3,"user_level_id":2,"username":"baiyin1","password":"baiyin1","mobile":"13200001111","nickname":"baiyin1","avatar":"\/uploads\/uploads\/20211105\/6c0ef20747bac6a8f0b1f75d78327806.jpg","status":0},{"id":4,"user_level_id":2,"username":"baiyin2","password":"baiyin2","mobile":"admin","nickname":"baiyin2","avatar":"\/uploads\/uploads\/20211105\/f5ffbf069f86c0bbeef12754c236c053.png","status":1},{"id":5,"user_level_id":3,"username":"\u9ec4\u91d11","password":"\u9ec4\u91d11","mobile":"\u9ec4\u91d11","nickname":"\u9ec4\u91d11","avatar":"\/uploads\/uploads\/20211105\/09f88c2c15fd9ea2eaebefa94fc82519.png","status":1},{"id":6,"user_level_id":1,"username":"10001","password":"10001","mobile":"13200000000","nickname":"10001","avatar":"\/uploads\/uploads\/20211105\/09f88c2c15fd9ea2eaebefa94fc82519.png","status":1},{"id":7,"user_level_id":2,"username":"10002","password":"10002","mobile":"13200000001","nickname":"10002","avatar":"\/uploads\/uploads\/20211105\/09f88c2c15fd9ea2eaebefa94fc82519.png","status":1},{"id":8,"user_level_id":3,"username":"10003","password":"10003","mobile":"13200000002","nickname":"10003","avatar":"\/uploads\/uploads\/20211105\/09f88c2c15fd9ea2eaebefa94fc82519.png","status":0},{"id":9,"user_level_id":2,"username":"10004","password":"10004","mobile":"13200000003","nickname":"10004","avatar":"\/uploads\/uploads\/20211105\/09f88c2c15fd9ea2eaebefa94fc82519.png","status":0},{"id":10,"user_level_id":1,"username":"10005","password":"10005","mobile":"13200000004","nickname":"10005","avatar":"\/uploads\/uploads\/20211105\/09f88c2c15fd9ea2eaebefa94fc82519.png","status":0}]';

        $msg = '测试用户数据导入成功.' . "\n";
        Db::startTrans();
        try {
            $data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            print ('用户数据解析错误，信息：'.$e->getMessage());
        }
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
