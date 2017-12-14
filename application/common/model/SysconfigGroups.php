<?php
/**
 * 后台系统设置分组模型
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\common\model;

use think\Model;

class SysconfigGroups extends Model
{
    protected $name = 'sysconfig_groups';

    public function config()
    {
        return $this->hasMany('Sysconfigs','group_id','id');
    }
}
