<?php
/**
 * 后台菜单验证器
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);

namespace app\admin\validate;

class AdminMenuValidate extends AdminBaseValidate
{
    protected $rule = [
        'parent_id|父级菜单'    => 'require|egt:0',
        'name|名称'           => 'require',
        'url|URL'           => 'require|unique:admin_menu',
        'icon|图标'           => 'require',
        'sort_number|排序'    => 'require',
        'is_show|是否显示'      => 'require',
        'log_method|日志记录方式' => 'require',
    ];

    protected $message = [
        'parent_id.egt' => '请选择上级菜单',
    ];

    protected $scene = [
        'admin_add'  => ['parent_id', 'title', 'url', 'icon', 'sort_number', 'is_show', 'log_method'],
        'admin_edit' => ['parent_id', 'title', 'url', 'icon', 'sort_number', 'is_show', 'log_method'],
    ];

}