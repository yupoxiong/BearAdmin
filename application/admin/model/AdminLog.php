<?php
/**
 * 后台操作日志模型
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\model;

class AdminLog extends Model
{
    protected $name = 'admin_log';

    public $softDelete = false;

    public $methodText = [
        1 => 'GET',
        2 => 'POST',
        3 => 'PUT',
        4 => 'DELETE',
    ];

    protected $autoWriteTimestamp = true;
    protected $updateTime = false;

    protected $searchField = [
        'name',
        'url',
        'log_ip'
    ];

    protected $whereField = [
        'admin_user_id'
    ];

    protected $timeField = [
        'create_time'
    ];

    //关联用户
    public function adminUser(): \think\model\relation\BelongsTo
    {
        return $this->belongsTo(AdminUser::class);
    }

    //关联详情
    public function adminLogData(): \think\model\relation\HasOne
    {
        return $this->hasOne(AdminLogData::class);
    }

}
