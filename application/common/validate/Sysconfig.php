<?php
/**
 * 后台设置证类
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\common\validate;

use think\Validate;

class Sysconfig extends Validate
{
    protected $rule = [
        'code'    => 'require',
        'content' => 'require',
        'status'  => 'require',
    ];

    protected $scene = [
        'add'  => ['code', 'content'],
        'edit' => ['code', 'content'],
    ];
}