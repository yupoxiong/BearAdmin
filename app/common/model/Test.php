<?php
/**
 * 测试模型
*/

namespace app\common\model;

use think\model\concern\SoftDelete;

class Test extends CommonBaseModel
{
    use SoftDelete;
    // 自定义选择数据
    

    protected $name = 'test';
    protected $autoWriteTimestamp = true;

    // 可搜索字段
    public array $searchField = ['username','nickname','mobile',];

    // 可作为条件的字段
    public array $whereField = [];

    // 可做为时间
    public array $timeField = [];

    

    // 关联用户等级
    public function userLevel()
    {
        return $this->belongsTo(UserLevel::class);
    }

}
