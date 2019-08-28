<?php
/**
 * 后台管理员操作日志数据模型
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\model;

use tools\Crypt;

class AdminLogData extends Model
{
    protected $name = 'admin_log_data';

    public $softDelete = false;

    //关联log
    public function adminLog()
    {
        return $this->belongsTo('app\admin\AdminLog','admin_log_id','id');
    }

    public function getDataAttr($value)
    {
        $data = Crypt::decrypt($value, config('app.app_key'));
        return json_encode(json_decode($data, true), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
    
}
