<?php
/**
 * 系统错误日志模型
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\model;

class Syslogs extends Admin
{
    protected $name = 'syslogs';
    protected $autoWriteTimestamp = true;
    protected $updateTime = false;
    
    public function syslogTrace()
    {
        return $this->hasOne('SyslogTrace','log_id','id')->field('id,log_id,trace');
    }
}
