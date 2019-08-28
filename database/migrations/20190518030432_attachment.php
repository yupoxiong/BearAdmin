<?php
/**
 * 附件表
 */

use think\migration\Migrator;
use think\migration\db\Column;

class Attachment extends Migrator
{

    public function change()
    {
        $table = $this->table('attachment', ['comment' => '附件表', 'engine' => 'InnoDB', 'encoding' => 'utf8mb4', 'collation' => 'utf8mb4_unicode_ci']);
        $table
            ->addColumn('admin_user_id', 'integer', ['limit' => 11, 'comment' => '后台用户ID'])
            ->addColumn('user_id', 'integer', ['limit' => 11, 'comment' => '前台用户ID'])
            ->addColumn('original_name', 'string', ['limit' => 200, 'default' => '', 'comment' => '原文件名'])
            ->addColumn('save_name', 'string', ['limit' => 200, 'default' => '', 'comment' => '保存文件名'])
            ->addColumn('save_path', 'string', ['limit' => 255, 'default' => '', 'comment' => '系统完整路径'])
            ->addColumn('url', 'string', ['limit' => 255, 'default' => '', 'comment' => '系统完整路径'])
            ->addColumn('extension', 'string', ['limit' => 100, 'default' => '', 'comment' => '后缀'])
            ->addColumn('mime', 'string', ['limit' => 100, 'default' => '', 'comment' => '类型'])
            ->addColumn('size', 'biginteger', ['limit' => 21, 'default' => '0', 'comment' => '大小'])
            ->addColumn('md5', 'string', ['limit' => 32, 'default' => '', 'comment' => 'MD5'])
            ->addColumn('sha1', 'string', ['limit' => 40, 'default' => '', 'comment' => 'SHA1'])
            ->addColumn('create_time', 'integer', ['limit' => 10, 'default' => 0, 'comment' => '创建时间'])
            ->addColumn('update_time', 'integer', ['limit' => 10, 'default' => 0, 'comment' => '更新时间'])
            ->addColumn('delete_time', 'integer', ['limit' => 10, 'default' => 0, 'comment' => '删除时间'])
            ->create();
    }
}
