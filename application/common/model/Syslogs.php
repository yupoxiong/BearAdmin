<?php
/**
 * 系统错误日志模型
 * @author yupoxiong<i@yufuping.com>
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
        return $this->hasOne('SyslogTrace','log_id','id')->field('trace_id,log_id,trace');
    }
}
