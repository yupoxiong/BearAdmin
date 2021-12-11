<?php
/**
 * 设置分组模型
 * @author yupoxiong<i@yupoxiong.com>
 */

namespace app\common\model;

use think\model\Collection;
use think\model\relation\HasMany;
use think\model\concern\SoftDelete;

/**
 * Class SettingGroup
 * @package app\common\model
 * @property int $id
 * @property string $name
 * @property int $auto_create_file
 * @property int $auto_create_menu
 * @property string $code
 * @property string $module
 * @property string $description
 * @property Setting[] $setting
 * @property string $icon
 */
class SettingGroup extends CommonBaseModel
{
    use SoftDelete;
    protected $name = 'setting_group';
    protected $autoWriteTimestamp = true;

    public array $noDeletionIds   =[
        1,2,3,4,5,
    ];

    // 可搜索字段
    public array $searchField = ['name', 'description', 'code',];

    // 关联设置
    public function setting(): HasMany
    {
        return $this->hasMany(Setting::class);
    }

    /**
     * 自动生成菜单字段获取器
     * @param $value
     * @param $data
     * @return string
     */
    public function getAutoCreateMenuTextAttr($value,$data): string
    {
        return self::BOOLEAN_TEXT[$data['auto_create_menu']];
    }

    /**
     * 自动生成配置文件获取器
     * @param $value
     * @param $data
     * @return string
     */
    public function getAutoCreateFileTextAttr($value,$data): string
    {
        return self::BOOLEAN_TEXT[$data['auto_create_file']];
    }
}
