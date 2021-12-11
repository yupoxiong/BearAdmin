<?php
/**
 * 设置模型
 * @author yupoxiong<i@yupoxiong.com>
 */

namespace app\common\model;

use JsonException;
use think\model\concern\SoftDelete;
use think\model\relation\BelongsTo;

/**
 * Class Setting
 * @package app\common\model
 * @property int $id ID
 * @property int $name 名称
 * @property string $code 代码
 * @property array $content 设置内容
 * @property int $setting_group_id 所属分组ID
 */
class Setting extends CommonBaseModel
{
    use SoftDelete;

    protected $name = 'setting';
    protected $autoWriteTimestamp = true;

    public array $noDeletionIds = [
        1, 2, 3, 4, 5,
    ];

    //可搜索字段
    public array $searchField = ['name', 'description', 'code',];


    //关联设置分组
    public function settingGroup(): BelongsTo
    {
        return $this->belongsTo(SettingGroup::class);
    }


    public function setContentAttr($value)
    {
        try {
            return json_encode($value, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return (object)[];
        }
    }

    public function getContentAttr($value)
    {
        try {
            return json_decode($value, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return [];
        }
    }


}
