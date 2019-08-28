<?php
/**
 * 用户等级模型
 */

namespace app\common\model;

use think\model\concern\SoftDelete;

class UserLevel extends Model
{
    use SoftDelete;
    public $softDelete = true;
    protected $name = 'user_level';
    protected $autoWriteTimestamp = true;

    //可搜索字段
    protected $searchField = ['name', 'description',];

    //是否启用获取器
    public function getStatusTextAttr($value, $data)
    {
        return self::BOOLEAN_TEXT[$data['status']];
    }

    //关联用户
    public function user()
    {
        return $this->hasMany(User::class);
    }


}
