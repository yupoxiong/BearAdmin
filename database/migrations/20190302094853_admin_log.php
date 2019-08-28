<?php
/**
 * 后台操作日志迁移文件
 * @author yupoxiong<i@yufuping.com>
 */

use  think\migration\Migrator;
use think\migration\db\Column;

class AdminLog extends Migrator
{
    public function change()
    {
        $table = $this->table('admin_log', ['comment'=>'后台操作日志','engine' => 'InnoDB', 'encoding' => 'utf8mb4', 'collation' => 'utf8mb4_unicode_ci']);
        $table
            ->addColumn('admin_user_id', 'integer', ['limit' => 10, 'comment' => '用户'])
            ->addColumn('name', 'string', ['limit' => 30, 'default' => '', 'comment' => '操作'])
            ->addColumn('url', 'string', ['limit' => 100, 'default' => '', 'comment' => 'URL'])
            ->addColumn('log_method', 'string', ['limit' => 8, 'default' => '不记录', 'comment' => '记录日志方法'])
            ->addColumn('log_ip', 'string', ['limit' => 20, 'default' => '', 'comment' => '操作IP'])
            ->addColumn('create_time', 'integer', ['limit' => 10, 'comment' => '操作时间'])
            ->create();

    }
}
