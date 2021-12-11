<?php
/**
 * 后台操作日志模型
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);

namespace app\admin\model;

use think\model\relation\HasOne;
use think\model\concern\SoftDelete;
use think\model\relation\BelongsTo;

/**
 * Class AdminLog
 * @package app\admin\model
 * @property string $log_ip
 */
class AdminLog extends AdminBaseModel
{
    use SoftDelete;
    /**
     * @var array 搜索的字段：操作，URL
     */
    public array $searchField = [
        'name',
        'url',
    ];

    public array $whereField = [
        'admin_user_id'
    ];

    /**
     * 关联用户
     * @return BelongsTo
     */
    public function adminUser(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class);
    }

    /**
     * 关联详情
     * @return HasOne
     */
    public function adminLogData(): HasOne
    {
        return $this->hasOne(AdminLogData::class);
    }

}
