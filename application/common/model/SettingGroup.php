<?php
/**
 * 设置分组模型
 */

namespace app\common\model;

use think\model\concern\SoftDelete;

class SettingGroup extends Model
{
    use SoftDelete;
    public $softDelete = true;
    protected $name = 'setting_group';
    protected $autoWriteTimestamp = true;

    public $noDeletionId  =[
        1,2,3,4,5,
    ];

    //可搜索字段
    protected $searchField = ['name', 'description', 'code',];


    //关联设置
    public function setting()
    {
        return $this->hasMany(Setting::class);
    }

    public function getAutoCreateMenuTextAttr($value,$data)
    {
        return self::BOOLEAN_TEXT[$data['auto_create_menu']];
    }

    public function getAutoCreateFileTextAttr($value,$data)
    {
        return self::BOOLEAN_TEXT[$data['auto_create_file']];
    }
}
