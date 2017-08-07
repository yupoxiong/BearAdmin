<?php
/**
 * 后台管理员模型
 * @author yupoxiong<i@yufuping.com>
 * @version 1.0
 */


namespace app\common\model;

use think\Model;
use traits\model\SoftDelete;

class AdminProfiles extends Model
{
    use SoftDelete;

    protected $name = 'admin_user_profiles';
    protected $autoWriteTimestamp = true;
    
    public function adminUser()
    {
        return $this->belongsTo('AdminUsers','user_id','profile_id')->field('user_id,user_name,nick_name,status');
    }
    
}
