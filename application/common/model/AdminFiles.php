<?php
/**
 * 后台用户文件模型
 * @author yupoxiong<i@yufuping.com>
 */


namespace app\common\model;

use think\Model;
use traits\model\SoftDelete;

class AdminFiles extends Model
{
    use SoftDelete;

    protected $name = 'admin_files';
    protected $autoWriteTimestamp = true;

    //关联后台用户
    public function adminUser()
    {
        return $this->belongsTo('AdminUsers', 'user_id', 'id');
    }

    //格式化大小
    public function getSizeAttr($value)
    {
        $units = array(' B', ' KB', ' MB', ' GB', ' TB');
        for ($i = 0; $value >= 1024 && $i < 4; $i++) $value /= 1024;
        return round($value, 2) . $units[$i];
    }

}
