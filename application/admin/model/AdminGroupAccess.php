<?php
/**
 * 后台管理员与角色关联模型
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\model;

class AdminGroupAccess extends Admin
{
    protected $name = 'admin_group_access';

    public function adminUser()
    {
        return $this->belongsTo('AdminUsers');
    }

    public function adminGroup()
    {
        return $this->belongsTo('AdminGroups','group_id','id')->field('id,name');
    }
}
