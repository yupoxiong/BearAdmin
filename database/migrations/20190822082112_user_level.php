<?php
/**
 * 用户等级
 */

use think\Db;
use think\migration\Migrator;
use think\migration\db\Column;

class UserLevel extends Migrator
{

    public function change()
    {
        $table = $this->table('user_level', ['comment' => '用户等级', 'engine' => 'InnoDB', 'encoding' => 'utf8mb4', 'collation' => 'utf8mb4_unicode_ci']);
        $table
            ->addColumn('name', 'string', ['limit' => 20, 'default' => '', 'comment' => '名称'])
            ->addColumn('description', 'string', ['limit' => 50, 'default' => '', 'comment' => '简介'])
            ->addColumn('img', 'string', ['limit' => 255, 'default' => '/static/index/images/user_level_default.png', 'comment' => '图片'])
            ->addColumn('status', 'boolean', ['limit' => 1, 'default' => 1, 'comment' => '是否启用'])
            ->addColumn('create_time', 'integer', ['limit' => 10, 'default' => 0, 'comment' => '创建时间'])
            ->addColumn('update_time', 'integer', ['limit' => 10, 'default' => 0, 'comment' => '更新时间'])
            ->addColumn('delete_time', 'integer', ['limit' => 10, 'default' => 0, 'comment' => '删除时间'])
            ->create();
        $this->insertData();
    }

    protected function insertData()
    {
        $data = '[{"id":1,"name":"普通用户","description":"普通用户","img":"/uploads/attachment/20190822/65e4ad92ece9fdb7f3822ba4fc322bf6.png","status":1},{"id":2,"name":"青铜会员","description":"青铜会员","img":"/uploads/attachment/20190822/d0b153352b15ea7097403c563e9c3be4.png","status":1},{"id":3,"name":"白银会员","description":"白银会员","img":"/uploads/attachment/20190822/72031bafedeba534d1e862b8d717f8db.png","status":1},{"id":4,"name":"黄金会员","description":"黄金会员","img":"/uploads/attachment/20190822/6dcc15ea1701c449e63e6856f0931e2a.png","status":1}]';

        $msg = '前台测试用户等级导入成功.' . "\n";
        Db::startTrans();
        $data = json_decode($data, true);
        try {
            foreach ($data as $item) {
                \app\common\model\UserLevel::create($item);
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            $msg = $e->getMessage();
        }
        print ($msg);
    }
}
