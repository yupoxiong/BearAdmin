<?php
/**
 * 用户模型
 */

namespace app\common\model;

use Exception;
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

    /**
     * 用户登录
     * @param $param
     * @return mixed
     * @throws Exception
     */
    public static function login($param)
    {
        $username = $param['username'];
        $password = $param['password'];
        $user     = self::get(['username' => $username]);
        if (!$user) {
            exception('用户不存在');
        }

        if (!password_verify($password, base64_decode($user->password))) {
            exception('密码错误');
        }

        if ((int)$user->status !== 1) {
            exception('用户被冻结');
        }
        return $user;
    }
}
