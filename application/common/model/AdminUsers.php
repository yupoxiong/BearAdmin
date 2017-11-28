<?php
/**
 * 后台管理员模型
 * @author yupoxiong<i@yufuping.com>
 * @version 1.0
 */


namespace app\common\model;

use think\Model;
use traits\model\SoftDelete;

class AdminUsers extends Model
{
    use SoftDelete;

    protected $name = 'admin_users';
    protected $autoWriteTimestamp = true;
    
    
    public function adminLogs()
    {
        return $this->hasMany('AdminLogs','user_id','user_id')->field('title,log_type,log_ip,create_time');
    }

    /**
     * 关联 用户关联角色表
     * @return \think\model\relation\HasMany
     */
    public function adminRoles()
    {
        return $this->hasMany('AuthGroupAccess','uid','user_id')->with('authGroup');
    }

    public function profile()
    {
        return $this->hasOne('AdminProfiles','user_id','user_id');
    }

    public function getStatusAttr($value)
    {
        $status = ['0'=>'冻结','1'=>'正常'];
        return $status[$value];
    }
    
}
