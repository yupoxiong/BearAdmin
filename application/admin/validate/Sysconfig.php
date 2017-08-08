<?php
/**
 * 系统设置验证
 * @author yupoxiong<i@yufuping.com>
 * @version 1.0
 */
namespace app\common\validate;

use think\Validate;

class Sysconfig extends Validate
{
    protected $rule = [
        ['name|名称', 'require|token'],
        ['code|代码', 'require'],
        ['content|参数', 'require'],
        ['description|说明', 'require']
    ];
    protected $scene = [
        'add'  => ['name', 'code', 'content', 'description'],
        'edit' => ['name', 'code', 'content', 'description']
    ];
}
