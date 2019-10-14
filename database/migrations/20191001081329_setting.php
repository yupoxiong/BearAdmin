<?php
/**
 * 设置
 */

use think\Db;
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

        $this->insertData();
    }

    protected function insertData()
    {
        $data = '[{"id":1,"setting_group_id":1,"name":"\u57fa\u672c\u8bbe\u7f6e","description":"\u540e\u53f0\u7684\u57fa\u672c\u4fe1\u606f\u8bbe\u7f6e","code":"base","content":[{"name":"\u540e\u53f0\u540d\u79f0","field":"name","type":"text","content":"XX\u540e\u53f0\u7cfb\u7edf","option":""},{"name":"\u540e\u53f0\u7b80\u79f0","field":"short_name","type":"text","content":"\u540e\u53f0","option":""},{"name":"\u540e\u53f0\u4f5c\u8005","field":"author","type":"text","content":"xx\u79d1\u6280","option":""}]},{"id":2,"setting_group_id":1,"name":"\u767b\u5f55\u8bbe\u7f6e","description":"\u540e\u53f0\u767b\u5f55\u76f8\u5173\u8bbe\u7f6e","code":"login","content":[{"name":"\u767b\u5f55token\u9a8c\u8bc1","field":"token","type":"switch","content":"0","option":""},{"name":"\u9a8c\u8bc1\u7801","field":"captcha","type":"select","content":"1","option":"0||\u4e0d\u5f00\u542f\r\n1||\u56fe\u5f62\u9a8c\u8bc1\u7801\r\n2||\u6ed1\u52a8\u9a8c\u8bc1"},{"name":"\u767b\u5f55\u80cc\u666f","field":"background","type":"image","content":"\/static\/admin\/images\/login-default-bg.jpg","option":""}]}]';

        $msg = '配置导入成功.' . "\n";
        Db::startTrans();
        $data = json_decode($data, true);
        try {
            foreach ($data as $item) {
                \app\common\model\Setting::create($item);
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            $msg = $e->getMessage();
        }
        print ($msg);
    }
}
