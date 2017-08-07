<?php
/**
 * 系统日志tarce
 * @author yupoxiong<i@yufuping.com>
 * @version 1.0
 */

namespace app\common\model;

use think\Model;

class SyslogTrace extends Model
{
    protected $name = 'syslog_trace';
    
    public function syslog(){
        return $this->belongsTo('Syslogs','log_id');
    }

    public function getTraceAttr($value){
        return '<pre>'.$value.'</pre>';
    }
    
}
