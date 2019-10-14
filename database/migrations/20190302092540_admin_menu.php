<?php
/**
 * 后台菜单迁移文件
 * @author yupoxiong<i@yufuping.com>
 */

use think\Db;
use think\migration\Migrator;
use think\migration\db\Column;

class AdminMenu extends Migrator
{
    public function change()
    {
        $table = $this->table('admin_menu', ['comment'=>'后台菜单','engine' => 'InnoDB', 'encoding' => 'utf8mb4', 'collation' => 'utf8mb4_unicode_ci']);
        $table->addColumn('parent_id', 'integer', ['limit' => 11, 'default' => 0, 'comment' => '父级菜单'])
            ->addColumn('name', 'string', ['limit' => 30, 'default' => '', 'comment' => '名称'])
            ->addColumn('url', 'string', ['limit' => 100, 'default' => '', 'comment' => 'url'])
            ->addColumn('icon', 'string', ['limit' => 30, 'default' => 'fa-list', 'comment' => '图标'])
            ->addColumn('is_show', 'boolean', ['limit' => 1, 'default' => 1, 'comment' => '等级'])
            ->addColumn('sort_id', 'integer', ['limit' => 10, 'default' => '1000', 'comment' => '排序'])
            ->addColumn('log_method', 'string', ['limit' => 8, 'default' => '不记录', 'comment' => '记录日志方法'])
            ->addIndex(['url'], ['name' => 'index_url'])
            ->create();
        $this->insertData();
    }

    protected function insertData()
    {
        $data = '[{"id":1,"parent_id":0,"name":"\u540e\u53f0\u9996\u9875","url":"admin\/index\/index","icon":"fa-home","is_show":1,"sort_id":99,"log_method":"\u4e0d\u8bb0\u5f55"},{"id":2,"parent_id":0,"name":"\u7cfb\u7edf\u7ba1\u7406","url":"admin\/sys","icon":"fa-desktop","is_show":1,"sort_id":1099,"log_method":"\u4e0d\u8bb0\u5f55"},{"id":3,"parent_id":2,"name":"\u7528\u6237\u7ba1\u7406","url":"admin\/admin_user\/index","icon":"fa-user","is_show":1,"sort_id":1000,"log_method":"\u4e0d\u8bb0\u5f55"},{"id":4,"parent_id":3,"name":"\u6dfb\u52a0\u7528\u6237","url":"admin\/admin_user\/add","icon":"fa-plus","is_show":0,"sort_id":1000,"log_method":"POST"},{"id":5,"parent_id":3,"name":"\u4fee\u6539\u7528\u6237","url":"admin\/admin_user\/edit","icon":"fa-edit","is_show":0,"sort_id":1000,"log_method":"POST"},{"id":6,"parent_id":3,"name":"\u5220\u9664\u7528\u6237","url":"admin\/admin_user\/del","icon":"fa-close","is_show":0,"sort_id":1000,"log_method":"POST"},{"id":7,"parent_id":2,"name":"\u89d2\u8272\u7ba1\u7406","url":"admin\/admin_role\/index","icon":"fa-group","is_show":1,"sort_id":1000,"log_method":"\u4e0d\u8bb0\u5f55"},{"id":8,"parent_id":7,"name":"\u6dfb\u52a0\u89d2\u8272","url":"admin\/admin_role\/add","icon":"fa-plus","is_show":0,"sort_id":1000,"log_method":"POST"},{"id":9,"parent_id":7,"name":"\u4fee\u6539\u89d2\u8272","url":"admin\/admin_role\/edit","icon":"fa-edit","is_show":0,"sort_id":1000,"log_method":"POST"},{"id":10,"parent_id":7,"name":"\u5220\u9664\u89d2\u8272","url":"admin\/admin_role\/del","icon":"fa-close","is_show":0,"sort_id":1000,"log_method":"POST"},{"id":11,"parent_id":7,"name":"\u89d2\u8272\u6388\u6743","url":"admin\/admin_role\/access","icon":"fa-key","is_show":0,"sort_id":1000,"log_method":"POST"},{"id":12,"parent_id":2,"name":"\u83dc\u5355\u7ba1\u7406","url":"admin\/admin_menu\/index","icon":"fa-align-justify","is_show":1,"sort_id":1000,"log_method":"\u4e0d\u8bb0\u5f55"},{"id":13,"parent_id":12,"name":"\u6dfb\u52a0\u83dc\u5355","url":"admin\/admin_menu\/add","icon":"fa-plus","is_show":0,"sort_id":1000,"log_method":"POST"},{"id":14,"parent_id":12,"name":"\u4fee\u6539\u83dc\u5355","url":"admin\/admin_menu\/edit","icon":"fa-edit","is_show":0,"sort_id":1000,"log_method":"POST"},{"id":15,"parent_id":12,"name":"\u5220\u9664\u83dc\u5355","url":"admin\/admin_menu\/del","icon":"fa-close","is_show":0,"sort_id":1000,"log_method":"POST"},{"id":16,"parent_id":2,"name":"\u64cd\u4f5c\u65e5\u5fd7","url":"admin\/admin_log\/index","icon":"fa-keyboard-o","is_show":1,"sort_id":1000,"log_method":"\u4e0d\u8bb0\u5f55"},{"id":17,"parent_id":16,"name":"\u67e5\u770b\u64cd\u4f5c\u65e5\u5fd7\u8be6\u60c5","url":"admin\/admin_log\/view","icon":"fa-search-plus","is_show":0,"sort_id":1000,"log_method":"\u4e0d\u8bb0\u5f55"},{"id":18,"parent_id":2,"name":"\u4e2a\u4eba\u8d44\u6599","url":"admin\/admin_user\/profile","icon":"fa-smile-o","is_show":1,"sort_id":1000,"log_method":"POST"},{"id":19,"parent_id":0,"name":"\u7528\u6237\u7ba1\u7406","url":"admin\/user\/mange","icon":"fa-users","is_show":1,"sort_id":1000,"log_method":"\u4e0d\u8bb0\u5f55"},{"id":20,"parent_id":19,"name":"\u7528\u6237\u7ba1\u7406","url":"admin\/user\/index","icon":"fa-user","is_show":1,"sort_id":1000,"log_method":"\u4e0d\u8bb0\u5f55"},{"id":21,"parent_id":20,"name":"\u6dfb\u52a0\u7528\u6237","url":"admin\/user\/add","icon":"fa-plus","is_show":0,"sort_id":1000,"log_method":"POST"},{"id":22,"parent_id":20,"name":"\u4fee\u6539\u7528\u6237","url":"admin\/user\/edit","icon":"fa-pencil","is_show":0,"sort_id":1000,"log_method":"POST"},{"id":23,"parent_id":20,"name":"\u5220\u9664\u7528\u6237","url":"admin\/user\/del","icon":"fa-trash","is_show":0,"sort_id":1000,"log_method":"POST"},{"id":24,"parent_id":20,"name":"\u542f\u7528\u7528\u6237","url":"admin\/user\/enable","icon":"fa-circle","is_show":0,"sort_id":1000,"log_method":"POST"},{"id":25,"parent_id":20,"name":"\u7981\u7528\u7528\u6237","url":"admin\/user\/disable","icon":"fa-circle","is_show":0,"sort_id":1000,"log_method":"POST"},{"id":26,"parent_id":19,"name":"\u7528\u6237\u7b49\u7ea7\u7ba1\u7406","url":"admin\/user_level\/index","icon":"fa-th-list","is_show":1,"sort_id":1000,"log_method":"\u4e0d\u8bb0\u5f55"},{"id":27,"parent_id":26,"name":"\u6dfb\u52a0\u7528\u6237\u7b49\u7ea7","url":"admin\/user_level\/add","icon":"fa-plus","is_show":0,"sort_id":1000,"log_method":"POST"},{"id":28,"parent_id":26,"name":"\u4fee\u6539\u7528\u6237\u7b49\u7ea7","url":"admin\/user_level\/edit","icon":"fa-pencil","is_show":0,"sort_id":1000,"log_method":"POST"},{"id":29,"parent_id":26,"name":"\u5220\u9664\u7528\u6237\u7b49\u7ea7","url":"admin\/user_level\/del","icon":"fa-trash","is_show":0,"sort_id":1000,"log_method":"POST"},{"id":30,"parent_id":26,"name":"\u542f\u7528\u7528\u6237\u7b49\u7ea7","url":"admin\/user_level\/enable","icon":"fa-circle","is_show":0,"sort_id":1000,"log_method":"POST"},{"id":31,"parent_id":26,"name":"\u7981\u7528\u7528\u6237\u7b49\u7ea7","url":"admin\/user_level\/disable","icon":"fa-circle","is_show":0,"sort_id":1000,"log_method":"POST"},{"id":32,"parent_id":2,"name":"\u5f00\u53d1\u7ba1\u7406","url":"admin\/develop\/manager","icon":"fa-code","is_show":1,"sort_id":1000,"log_method":"\u4e0d\u8bb0\u5f55"},{"id":33,"parent_id":32,"name":"\u4ee3\u7801\u751f\u6210","url":"admin\/generate\/index","icon":"fa-file-code-o","is_show":1,"sort_id":1000,"log_method":"\u4e0d\u8bb0\u5f55"},{"id":34,"parent_id":32,"name":"\u8bbe\u7f6e\u914d\u7f6e","url":"admin\/develop\/setting","icon":"fa-cogs","is_show":1,"sort_id":1000,"log_method":"\u4e0d\u8bb0\u5f55"},{"id":35,"parent_id":34,"name":"\u8bbe\u7f6e\u7ba1\u7406","url":"admin\/setting\/index","icon":"fa-cog","is_show":1,"sort_id":1000,"log_method":"\u4e0d\u8bb0\u5f55"},{"id":36,"parent_id":35,"name":"\u6dfb\u52a0\u8bbe\u7f6e","url":"admin\/setting\/add","icon":"fa-plus","is_show":0,"sort_id":1000,"log_method":"POST"},{"id":37,"parent_id":35,"name":"\u4fee\u6539\u8bbe\u7f6e","url":"admin\/setting\/edit","icon":"fa-pencil","is_show":0,"sort_id":1000,"log_method":"POST"},{"id":38,"parent_id":35,"name":"\u5220\u9664\u8bbe\u7f6e","url":"admin\/setting\/del","icon":"fa-trash","is_show":0,"sort_id":1000,"log_method":"POST"},{"id":39,"parent_id":34,"name":"\u8bbe\u7f6e\u5206\u7ec4\u7ba1\u7406","url":"admin\/setting_group\/index","icon":"fa-list","is_show":1,"sort_id":1000,"log_method":"\u4e0d\u8bb0\u5f55"},{"id":40,"parent_id":39,"name":"\u6dfb\u52a0\u8bbe\u7f6e\u5206\u7ec4","url":"admin\/setting_group\/add","icon":"fa-plus","is_show":0,"sort_id":1000,"log_method":"POST"},{"id":41,"parent_id":39,"name":"\u4fee\u6539\u8bbe\u7f6e\u5206\u7ec4","url":"admin\/setting_group\/edit","icon":"fa-pencil","is_show":0,"sort_id":1000,"log_method":"POST"},{"id":42,"parent_id":39,"name":"\u5220\u9664\u8bbe\u7f6e\u5206\u7ec4","url":"admin\/setting_group\/del","icon":"fa-trash","is_show":0,"sort_id":1000,"log_method":"POST"},{"id":43,"parent_id":0,"name":"\u8bbe\u7f6e\u4e2d\u5fc3","url":"admin\/setting\/center","icon":"fa-cogs","is_show":1,"sort_id":1000,"log_method":"\u4e0d\u8bb0\u5f55"},{"id":44,"parent_id":43,"name":"\u6240\u6709\u914d\u7f6e","url":"admin\/setting\/all","icon":"fa-list","is_show":1,"sort_id":1000,"log_method":"\u4e0d\u8bb0\u5f55"},{"id":47,"parent_id":43,"name":"\u540e\u53f0\u8bbe\u7f6e","url":"admin\/setting\/admin","icon":"fa-adjust","is_show":1,"sort_id":1000,"log_method":"\u4e0d\u8bb0\u5f55"},{"id":48,"parent_id":43,"name":"\u66f4\u65b0\u8bbe\u7f6e","url":"admin\/setting\/update","icon":"fa-pencil","is_show":0,"sort_id":1000,"log_method":"POST"}]';

        $msg = '后台管理菜单导入成功.' . "\n";
        Db::startTrans();
        $data = json_decode($data, true);
        try {
            foreach ($data as $item) {
                \app\admin\model\AdminMenu::create($item);
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            $msg = $e->getMessage();
        }
        print ($msg);
    }
}
