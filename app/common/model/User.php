<?php
/**
 * 用户模型
 */

namespace app\common\model;

use think\model\concern\SoftDelete;
use think\model\relation\BelongsTo;

/**
 * @property int $id
 * @property int $status
 * @property string $password
 * @property string $sign_str
 */
class User extends CommonBaseModel
{
    use SoftDelete;

    // 自定义选择数据
    // 是否启用列表
    public const STATUS_LIST = [
        1 => '是',
        0 => '否',
    ];


    protected $name = 'user';
    protected $autoWriteTimestamp = true;

    // 可搜索字段
    public array $searchField = ['username', 'mobile', 'nickname',];

    // 可作为条件的字段
    public array $whereField = ['user_level_id', 'status',];

    // 可做为时间
    public array $timeField = [];

    /**
     * 插入前进行密码加密
     * @param User $data
     * @return void
     */
    public static function onBeforeInsert($data): void
    {
        $data->password = (new self)->setEncryptPassword($data->password);
    }

    /**
     * 更新前监测密码是否变更
     * @param User $data
     * @return void
     */
    public static function onBeforeUpdate($data): void
    {
        $old = (new self())->where('id', '=', $data->id)->findOrEmpty();
        /**@var User $old */
        if ($data->password !== $old->password) {
            $data->password = (new self)->setEncryptPassword($data->password);
        }
    }

    // 是否启用获取器
    public function getStatusNameAttr($value, $data): string
    {
        return self::STATUS_LIST[$data['status']];
    }


    // 关联用户等级
    public function userLevel(): BelongsTo
    {
        return $this->belongsTo(UserLevel::class);
    }

    /**
     * 设置加密密码
     * @param $password
     * @return string
     */
    protected function setEncryptPassword($password): string
    {
        return base64_encode(password_hash($password, 1));
    }

    /**
     * 加密字符串，用在判断登录的时候加密处理
     * @param $value
     * @param $data
     * @return string
     */
    protected function getSignStrAttr($value, $data): string
    {
        $ua = request()->header('user-agent');
        return sha1('user_'.$data['id'] . $data['username'] . $ua);
    }
}
