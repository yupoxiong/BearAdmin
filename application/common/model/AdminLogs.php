<?php
/**
 * 后台管理员操作日志模型
 * @author yupoxiong<i@yufuping.com>
 * @version 1.0
 */

namespace app\common\model;

use think\Model;

class AdminLogs extends Model
{
    protected $name = 'admin_logs';
    protected $autoWriteTimestamp = true;
    protected $updateTime = false;



    public function getLogIpAttr($value)
    {
        return long2ip($value);
    }

    public function getLogTypeAttr($value)
    {
        $logtype=[0=>'NONE',1=>'GET',2=>'POST',3=>'PUT',4=>'DELETE'];
        return $logtype[$value];
    }

    //和后台用户关联
    public function adminUser()
    {
        return $this->belongsTo('AdminUsers','user_id')->field('user_id,user_name,nick_name');
    }


    public function adminLogData()
    {
        return $this->hasOne('AdminLogsDatas','log_id','id')->field('data_id,log_id,data');
    }
    
}
