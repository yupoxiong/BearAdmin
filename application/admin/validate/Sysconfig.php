<?php
/**
 * 后台设置证类
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\validate;

class Sysconfig extends Admin
{
    protected $rule = [
        'code|代码'    => 'require',
        'content|内容' => 'require',
        'status|是否启用'  => 'require',
    ];

    protected $scene = [
        'add'  => ['code', 'content'],
        'edit' => ['code', 'content'],
    ];
}