<?php
/**
 *
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);

namespace app\admin\model;

use JsonException;
use think\model\concern\SoftDelete;
use think\model\relation\BelongsTo;

class AdminLogData extends AdminBaseModel
{
    use SoftDelete;
    /**
     * 关联log
     * @return BelongsTo
     */
    public function adminLog(): BelongsTo
    {
        return $this->belongsTo(AdminLog::class);
    }

    /**
     * @throws JsonException
     */
    public function getDataFormatAttr($value, $data)
    {
        return json_encode(json_decode($data['data'], true, 512, JSON_THROW_ON_ERROR), JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
