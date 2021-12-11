<?php
/**
 * 设置
 */

use think\facade\Db;
use think\migration\Migrator;
use think\migration\db\Column;

class Setting extends Migrator
{

    public function change(): void
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


    protected function insertData(): void
    {
        $data = '[{"id":1,"setting_group_id":1,"name":"\u57fa\u672c\u8bbe\u7f6e","description":"\u540e\u53f0\u7684\u57fa\u672c\u4fe1\u606f\u8bbe\u7f6e","code":"base","content":[{"name":"\u540e\u53f0\u540d\u79f0","field":"name","type":"text","content":"XX\u540e\u53f0\u7cfb\u7edf","option":""},{"name":"\u540e\u53f0\u7b80\u79f0","field":"short_name","type":"text","content":"\u540e\u53f0","option":""},{"name":"\u540e\u53f0\u4f5c\u8005","field":"author","type":"text","content":"xx\u79d1\u6280","option":""},{"name":"\u4f5c\u8005\u7f51\u7ad9","field":"website","type":"text","content":"#","option":""},{"name":"\u540e\u53f0\u7248\u672c","field":"version","type":"text","content":"0.1","option":""},{"name":"\u540e\u53f0LOGO","field":"logo","type":"image","content":"\/static\/admin\/images\/logo.png","option":""}],"sort_number":1000},{"id":2,"setting_group_id":1,"name":"\u767b\u5f55\u8bbe\u7f6e","description":"\u540e\u53f0\u767b\u5f55\u76f8\u5173\u8bbe\u7f6e","code":"login","content":[{"name":"\u767b\u5f55token\u9a8c\u8bc1","field":"token","type":"switch","content":"1","option":""},{"name":"\u9a8c\u8bc1\u7801","field":"captcha","type":"select","content":"1","option":"0||\u4e0d\u5f00\u542f\r\n1||\u56fe\u5f62\u9a8c\u8bc1\u7801\r\n2||\u6ed1\u52a8\u9a8c\u8bc1"},{"name":"\u767b\u5f55\u80cc\u666f","field":"background","type":"image","content":"\/static\/admin\/images\/login-default-bg.jpg","option":""},{"name":"\u6781\u9a8cID","field":"geetest_id","type":"text","content":"66cfc0f309e368364b753dad7d2f67f2","option":""},{"name":"\u6781\u9a8cKEY","field":"geetest_key","type":"text","content":"99750f86ec232c997efaff56c7b30cd3","option":""},{"name":"\u767b\u5f55\u91cd\u8bd5\u9650\u5236","field":"login_limit","type":"switch","content":"0","option":"0||\u5426\r\n1||\u662f"},{"name":"\u9650\u5236\u6700\u5927\u6b21\u6570","field":"login_max_count","type":"number","content":"5","option":""},{"name":"\u7981\u6b62\u767b\u5f55\u65f6\u957f(\u5c0f\u65f6)","field":"login_limit_hour","type":"number","content":"2","option":""}],"sort_number":1000},{"id":3,"setting_group_id":1,"name":"\u5b89\u5168\u8bbe\u7f6e","description":"\u5b89\u5168\u76f8\u5173\u914d\u7f6e","code":"safe","content":[{"name":"\u52a0\u5bc6key","field":"admin_key","type":"text","content":"89ce3272dc949fc3698fe7108d1dbe37","option":""},{"name":"SessionKeyUid","field":"store_uid_key","type":"text","content":"admin_user_id","option":""},{"name":"SessionKeySign","field":"store_sign_key","type":"text","content":"admin_user_sign","option":""},{"name":"\u540e\u53f0\u7528\u6237\u5bc6\u7801\u5f3a\u5ea6\u68c0\u6d4b","field":"password_check","type":"switch","content":"0","option":"0||\u5173\u95ed\r\n1||\u5f00\u542f"},{"name":"\u5bc6\u7801\u5b89\u5168\u5f3a\u5ea6\u7b49\u7ea7","field":"password_level","type":"select","content":"2","option":"1||\u7b80\u5355\u5bc6\u7801\r\n2||\u4e2d\u7b49\u5bc6\u7801\r\n3||\u590d\u6742\u5bc6\u7801"},{"name":"\u5355\u8bbe\u5907\u767b\u5f55","field":"one_device_login","type":"switch","content":"0","option":"0||\u5173\u95ed\r\n1||\u5f00\u542f"},{"name":"CSRFToken\u68c0\u6d4b","field":"check_token","type":"switch","content":"1","option":""},{"name":"CSRFToken\u9a8c\u8bc1\u65b9\u6cd5","field":"check_token_action_list","type":"multi_select","content":"add,edit,del,import,profile,update","option":"add||\u6dfb\u52a0\r\nedit||\u4fee\u6539\r\ndel||\u5220\u9664\r\nimport||\u5bfc\u5165\r\nprofile||\u4fee\u6539\u8d44\u6599\r\nupdate||\u66f4\u65b0"}],"sort_number":1000}]';


        $msg = '配置导入成功.' . "\n";
        Db::startTrans();
        try {
            $data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            print ('配置数据解析错误，信息：'.$e->getMessage());
        }
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
