<?php

namespace app\common\model;

use think\Model;

class AuthGroupAccess extends Model
{
    //

    public function adminUser()
    {
        return $this->belongsTo('AdminUsers');
    }

    public function authGroup()
    {
        return $this->belongsTo('AuthGroups','group_id','id')->field('id,title');
    }
}
