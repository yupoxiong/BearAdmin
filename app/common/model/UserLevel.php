<?php
/**
 * 用户等级模型
*/

namespace app\common\model;

use think\model\concern\SoftDelete;
use think\model\relation\HasMany;

class UserLevel extends CommonBaseModel
{
    use SoftDelete;
    // 自定义选择数据

    protected $name = 'user_level';
    protected $autoWriteTimestamp = true;

    // 可搜索字段
    public array $searchField = ['name','description',];

    // 可作为条件的字段
    public array $whereField = [];

    // 可做为时间
    public array $timeField = [];

    

    // 关联测试
    public function test(): HasMany
    {
        return $this->hasMany(Test::class);
    }// 关联用户
    public function user(): HasMany
    {
        return $this->hasMany(User::class);
    }

}
