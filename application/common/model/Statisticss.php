<?php
/**
 * 后台管理员模型
 * @author yupoxiong<i@yufuping.com>
 * @version 1.0
 */


namespace app\common\model;

use think\Model;
use traits\model\SoftDelete;

class Statisticss extends Model
{
    use SoftDelete;

    protected $name = 'admin_users';
    protected $deleteTime = 'is_delete';
    protected $autoWriteTimestamp = true;

    
    
    public function adminlogs()
    {
        return $this->hasMany('AdminLogs','user_id')->field('title,log_type,log_ip,create_time');
    }

    public function adminroles()
    {
        return $this->hasMany('AuthGroupAccess','uid','user_id');
    }
    
}
