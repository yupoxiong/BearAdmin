<?php
/**
 * 后台菜单证类
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\validate;

class AdminMenu extends Admin
{
    protected $rule = [
        'parent_id|上级菜单' => ['require','egt:0'],
        'title|菜单标题'     => 'require',
        'url|菜单url'       => 'require',
        'icon|图标'      => 'require',
        'sort_id|排序'   => 'require',
    ];

    protected $message = [
        'parent_id.egt'  => '请选择上级菜单',
    ];

    protected $scene = [
        'add'  => ['parent_id', 'title', 'url', 'icon', 'sort_id'],
        'edit' => ['parent_id', 'title', 'url', 'icon', 'sort_id'],
    ];
}