<?php
/**
 * 后台管理员模型
 * @author yupoxiong<i@yufuping.com>
 * @version 1.0
 */


namespace app\common\model;

use think\Model;
use traits\model\SoftDelete;

class AdminFiles extends Model
{
    use SoftDelete;

    protected $name = 'admin_files';
    protected $autoWriteTimestamp = true;

    public function adminUser()
    {
        return $this->belongsTo('AdminUsers', 'user_id', 'user_id');
    }

    //
    public function getSizeAttr($value)
    {
        $units = array(' B', ' KB', ' MB', ' GB', ' TB');
        for ($i = 0; $value >= 1024 && $i < 4; $i++) $value /= 1024;
        return round($value, 2) . $units[$i];
    }

}
