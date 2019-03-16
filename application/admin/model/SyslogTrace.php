<?php
/**
 * 系统错误日志tarce
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\model;

class SyslogTrace extends Admin
{
    protected $name = 'syslog_trace';

    //关联日志
    public function syslog(){
        return $this->belongsTo('Syslogs','log_id','id');
    }

    //trace获取器
    public function getTraceAttr($value){
        return '<pre>'.$value.'</pre>';
    }
    
}
