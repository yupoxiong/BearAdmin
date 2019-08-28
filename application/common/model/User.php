<?php
/**
 * 用户模型
 */

namespace app\common\model;

use think\model\concern\SoftDelete;

class User extends Model
{
    use SoftDelete;
    public $softDelete = true;
    protected $name = 'user';
    protected $autoWriteTimestamp = true;

    //可搜索字段
    protected $searchField = ['username', 'mobile', 'nickname',];

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


    //用户登录
    public static function login($param)
    {
        $username = $param['username'];
        $password = $param['password'];
        $user     = self::get(['username' => $username]);
        if (!$user) {
            exception('用户不存在');
        }

        if (!password_verify($password, $user->password)) {
            exception('密码错误');
        }

        if ((int)$user->status !== 1) {
            exception('用户被冻结');
        }
        return $user;
    }
}
