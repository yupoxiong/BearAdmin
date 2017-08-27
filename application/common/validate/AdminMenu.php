<?php
/**
 * 后台菜单证类
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\common\validate;

use think\Validate;

class AdminMenu extends Validate
{
    /* public $validate = [
            ['|上级菜单', 'require|max:25|token'],
            ['|菜单名称', 'require|max:30'],
            ['|url', 'require|max:100'],
            ['|菜单图标', 'require|max:30'],
            ['|菜单排序', 'require|number|max:4'],
            ['|菜单状态', 'require'],
            ['|日志请求方式', 'require'],
        ]
        , $protected_menu;*/


    protected $rule = [
        'parent_id' => ['require','egt:0'],
        'title'     => 'require',
        'url'       => 'require',
        'icon'      => 'require',
        'sort_id'   => 'require',
        'is_show'   => 'require',
        'log_type'  => 'require'
    ];

    protected $message = [
        'email.email'  => '邮箱格式错误',
        'mobile.regex' => '手机格式错误',
    ];

    protected $scene = [
        'backend_add'  => ['parent_id', 'user_name', 'nick_name', 'status', 'mobile', 'email'],
        'backend_edit' => ['parent_id', 'user_name', 'nick_name', 'status', 'mobile', 'email'],
    ];
}