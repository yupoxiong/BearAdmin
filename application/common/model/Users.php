<?php
/**
 * 前台用户类
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\common\model;

use traits\model\SoftDelete;

class Users extends Model
{
    use SoftDelete;
    protected $name = 'users';
    protected $autoWriteTimestamp = true;

    public function userLevel()
    {
        return $this->belongsTo('UserLevels', 'level_id', 'id');
    }

    protected function getAdminStatusTextAttr($vaule, $data)
    {
        $text = [
            0 => '禁用',
            1 => '正常'
        ];
        return $text[$data['status']];
    }

    protected function getLastLoginTimeAttr($value)
    {
        return $value>0?date('Y-m-d H:i:s',$value):'/';
    }


    protected function getRegTimeAttr($value)
    {
        return $value>0?date('Y-m-d H:i:s',$value):'/';
    }
}
