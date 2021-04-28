<?php
/**
 * 用户模型
*/

namespace app\common\model;

use think\model\concern\SoftDelete;

class User extends Model
{
    // 自定义选择数据
    

    use SoftDelete;
    public $softDelete = true;
    protected $name = 'user';
    protected $autoWriteTimestamp = true;

    //可搜索字段
    protected $searchField = [];

    //可作为条件的字段
    protected $whereField = [];

    //可做为时间
    protected $timeField = [];

    //是否启用获取器
public function getStatusTextAttr($value, $data)
{
    return self::BOOLEAN_TEXT[$data['status']];
}

    //关联用户等级
public function userLevel()
{
    return $this->belongsTo(UserLevel::class);
}

    
}
