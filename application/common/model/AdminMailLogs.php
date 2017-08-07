<?php
/**
 * 后台管理员操作日志模型
 * @author yupoxiong<i@yufuping.com>
 * @version 1.0
 */

namespace app\common\model;

use think\Model;

class AdminMailLogs extends Model
{
    protected $name = 'admin_mail_logs';
    protected $autoWriteTimestamp = true;
    protected $updateTime = false;

    
    //和后台用户关联
    public function adminUser()
    {
        return $this->belongsTo('AdminUsers','user_id')->field('user_id,user_name,nick_name');
    }
    
}
