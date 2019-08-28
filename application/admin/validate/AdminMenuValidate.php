<?php
/**
 * 后台菜单证类
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\validate;

class AdminMenuValidate extends Validate
{
    protected $rule = [
        'parent_id|上级'  => 'require|egt:0',
        'name|名称'       => 'require',
        'url|url'        => 'require|unique:admin_menu',
        'icon|图标'       => 'require',
        'sort_id|排序'    => 'require',
        'is_show|是否显示'  => 'require',
        'log_method|记录类型' => 'require',
    ];

    protected $message = [
        'parent_id.egt' => '请选择上级菜单',
    ];

    protected $scene = [
        'add'  => ['parent_id', 'title', 'url', 'icon', 'sort_id', 'is_show', 'log_method'],
        'edit' => ['parent_id', 'title', 'url', 'icon', 'sort_id', 'is_show', 'log_method'],
    ];
}