<?php
/**
 * 后台管理员角色模型
 * @author yupoxiong<i@yufuping.com>
 * @version 1.0
 */

namespace app\common\model;

use think\Model;

class AuthGroups extends Model
{
    protected $name = 'auth_groups';

    public function authGroupAccess(){
        return $this->hasMany('AuthGroupAccess','group_id','id');
    }
}
