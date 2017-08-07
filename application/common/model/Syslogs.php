<?php
/**
 * 后台管理员操作日志模型
 * @author yupoxiong<i@yufuping.com>
 * @version 1.0
 */

namespace app\common\model;

use think\Model;

class Syslogs extends Model
{
    protected $name = 'syslogs';
    protected $autoWriteTimestamp = true;
    protected $updateTime = false;
    
    public function syslogTrace()
    {
        return $this->hasOne('SyslogTrace','log_id')->field('trace_id,log_id,trace');
    }
    
}
