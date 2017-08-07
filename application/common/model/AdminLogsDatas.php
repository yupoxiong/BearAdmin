<?php
/**
 * 后台管理员操作日志模型
 * @author yupoxiong<i@yufuping.com>
 * @version 1.0
 */

namespace app\common\model;

use think\Model;

class AdminLogsDatas extends Model
{
    protected $name = 'admin_logs_datas';
    protected $autoWriteTimestamp = true;
    
    
    public function adminLog()
    {
        return $this->belongsTo('AdminLogs','log_id','data_id');
    }
    
}
