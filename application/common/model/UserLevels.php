<?php
/**
 * 前台用户等级类
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\common\model;

use traits\model\SoftDelete;

class UserLevels extends Model
{
    use SoftDelete;
    protected $name = 'user_levels';
    protected $autoWriteTimestamp = true;

    public function user()
    {
        return $this->hasMany('Users','level_id','id');
    }
}
