<?php
/**
 * 测试迁移文件，有各种类型字段，可用来测试自动生成代码功能
 */
use think\migration\Migrator;
use think\migration\db\Column;

class Test extends Migrator
{
    public function change()
    {
        $table = $this->table('test', ['comment' => '测试', 'engine' => 'InnoDB', 'encoding' => 'utf8mb4', 'collation' => 'utf8mb4_unicode_ci']);
        $table
            ->addColumn('avatar', 'string', ['limit' => 255, 'default' => '/static/index/images/avatar.png', 'comment' => '头像'])
            ->addColumn('username', 'string', ['limit' => 30, 'default' => '', 'comment' => '用户名'])
            ->addColumn('nickname', 'string', ['limit' => 30, 'default' => '', 'comment' => '昵称'])
            ->addColumn('mobile', 'string', ['limit' => 11, 'default' => '', 'comment' => '手机号'])
            ->addColumn('user_level_id', 'integer', ['limit' => 10, 'default' => 1, 'comment' => '用户等级'])
            ->addColumn('password', 'string', ['limit' => 255, 'default' => base64_encode(password_hash('_default__password_', 1)), 'comment' => '密码'])
            ->addColumn('status', 'boolean', ['limit' => 1, 'default' => 1, 'comment' => '是否启用'])
            ->addColumn('lng', 'decimal', ['precision' => 14, 'scale' => 8, 'default' => 116, 'comment' => '经度'])// 测试地图
            ->addColumn('lat', 'decimal', ['precision' => 14, 'scale' => 8, 'default' => 37, 'comment' => '纬度'])
            ->addColumn('slide', 'text', [ 'comment' => '相册'])// 测试多图上传
            ->addColumn('content', 'text', [ 'comment' => '内容'])// 测试编辑器
            ->addColumn('create_time', 'integer', ['limit' => 10, 'default' => 0, 'comment' => '创建时间'])
            ->addColumn('update_time', 'integer', ['limit' => 10, 'default' => 0, 'comment' => '更新时间'])
            ->addColumn('delete_time', 'integer', ['limit' => 10, 'default' => 0, 'comment' => '删除时间'])
            ->create();
    }
}
