<?php
/**
 * 后台管理员操作日志模型
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\model;

class AdminLogs extends Admin
{
    protected $name = 'admin_logs';
    protected $autoWriteTimestamp = true;
    protected $updateTime = false;

    //ip获取器
    public function getLogIpAttr($value)
    {
        return long2ip($value);
    }

    //日志类型获取器
    public function getLogTypeAttr($value)
    {
        $logtype=[0=>'NONE',1=>'GET',2=>'POST',3=>'PUT',4=>'DELETE'];
        return $logtype[$value];
    }

    //关联后台用户
    public function adminUser()
    {
        return $this->belongsTo('AdminUsers','user_id')->field('id,name,nickname');
    }

    //管理日志数据
    public function adminLogData()
    {
        return $this->hasOne('AdminLogDatas','log_id','id')->field('id,log_id,data');
    }
}
