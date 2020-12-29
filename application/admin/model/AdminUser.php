<?php
/**
 * 后台用户模型
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\model;

use Exception;
use think\model\concern\SoftDelete;

/**
 * @property int id
 * @property mixed sign_str
 * @property mixed auth_url
 * @property mixed role
 * @property string password
 */
class AdminUser extends Model
{
    use SoftDelete;
    protected $name = 'admin_user';

    protected $searchField = [
        'nickname',
        'username'
    ];

    public $noDeletionId = [
        1, 2
    ];


    public static function init()
    {
        //添加自动加密密码
        self::event('before_insert', static function ($data) {
            $data->password = base64_encode(password_hash($data->password, 1));
        });

        //修改密码自动加密
        self::event('before_update', function ($data) {
            $old = (new static())::get($data->id);
            if ($data->password !== $old->password) {
                $data->password = base64_encode(password_hash($data->password, 1));
            }
        });
    }

    //关联操作日志
    public function adminLog()
    {
        return $this->hasMany(AdminLog::class, 'admin_user_id', 'id');
    }


    //角色获取器
    protected function getRoleAttr($value)
    {
        return explode(',', $value);
    }

    //角色修改器
    protected function setRoleAttr($value)
    {
        return implode(',', $value);
    }

    //用户角色名称
    protected function getRoleTextAttr($value, $data)
    {
        return AdminRole::where('id', 'in', $data['role'])->column('id,name', 'id');
    }


    /**
     * 获取已授权url
     * @param $value
     * @param $data
     * @return array
     */
    protected function getAuthUrlAttr($value, $data)
    {
        $role_urls  = AdminRole::where('id', 'in', $data['role'])->where('status', 1)->column('url');
        $url_id_str = '';
        foreach ($role_urls as $key => $val) {
            $url_id_str .= $key === 0 ? $val : ',' . $val;
        }
        $url_id   = array_unique(explode(',', $url_id_str));
        $auth_url = [];
        if (count($url_id) > 0) {
            $auth_url = AdminMenu::where('id', 'in', $url_id)->column('url');
        }
        return $auth_url;
    }

    //加密字符串，用在登录的时候加密处理
    protected function getSignStrAttr($value, $data)
    {
        $ua = request()->header('user-agent');
        return sha1($data['id'] . $data['username'] . $ua);
    }

    //获取当前用户已授权的显示菜单
    public function getShowMenu()
    {
        if ($this->id === 1) {
            return AdminMenu::where('is_show', 1)->order('sort_id', 'asc')->order('id', 'asc')->column('id,parent_id,name,url,icon,sort_id', 'id');
        }

        $role_urls = AdminRole::where('id', 'in', $this->role)->where('status', 1)->column('url');

        $url_id_str = '';
        foreach ($role_urls as $key => $val) {
            $url_id_str .= $key == 0 ? $val : ',' . $val;
        }

        $url_id = array_unique(explode(',', $url_id_str));
        return AdminMenu::where('id', 'in', $url_id)->where('is_show', 1)->order('sort_id', 'asc')->order('id', 'asc')->column('id,parent_id,name,url,icon,sort_id', 'id');
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
