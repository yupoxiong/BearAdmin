<?php
/**
 * 登录、退出、记录日志相关
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\traits;

use app\admin\model\AdminLog;
use app\admin\model\AdminUser;
use tools\Crypt;
use tools\SafeCookie;
use think\facade\Session;

trait AdminAuth
{
    public static $admin_user_id = 'admin_user_id';
    public static $admin_user_id_sign = 'admin_user_sign';

    //是否登录
    protected function isLogin()
    {
        //这里要写个判断数据库的才行
        $admin_user_id = Session::get(self::$admin_user_id);
        $user       = false;
        $this->user = &$user;
        if (empty($admin_user_id)) {
            if (SafeCookie::has(self::$admin_user_id) && SafeCookie::has(self::$admin_user_id_sign)) {
                $admin_user_id = SafeCookie::get(self::$admin_user_id);
                $sign          = SafeCookie::get(self::$admin_user_id_sign);
                $user          = AdminUser::get($admin_user_id);
                if ($user && $user->sign_str === $sign) {
                    Session::set(self::$admin_user_id, $admin_user_id);
                    Session::set(self::$admin_user_id_sign, $sign);
                    return true;
                }
            }
            return false;
        }

        $user = AdminUser::get($admin_user_id);
        if(!$user) {
            return false;
        }
        $this->uid = $user->id;

        return Session::get(self::$admin_user_id_sign) === $user->sign_str;
    }

    /**
     * session 与cookie登录
     * @param $user AdminUser
     * @param bool $remember
     * @return bool
     */
    public static function authLogin($user, $remember = false)
    {
        Session::set(self::$admin_user_id, $user->id);
        Session::set(self::$admin_user_id_sign, $user->sign_str);

        //记住登录
        if ($remember === true) {
            SafeCookie::set(self::$admin_user_id, $user->id);
            SafeCookie::set(self::$admin_user_id_sign, $user->sign_str);
        } else if (SafeCookie::has(self::$admin_user_id) || SafeCookie::has(self::$admin_user_id_sign)) {
            SafeCookie::delete(self::$admin_user_id);
            SafeCookie::delete(self::$admin_user_id_sign);
        }
        //记录登录日志
        self::loginLog($user);
        return true;
    }

    //退出
    public static function authLogout()
    {
        Session::delete(self::$admin_user_id);
        Session::delete(self::$admin_user_id_sign);
        if (SafeCookie::has(self::$admin_user_id) || SafeCookie::has(self::$admin_user_id_sign)) {
            SafeCookie::delete(self::$admin_user_id);
            SafeCookie::delete(self::$admin_user_id_sign);
        }
        return true;
    }

    /**
     * 权限检查
     * @param $user AdminUser
     * @return bool
     */
    public function authCheck($user)
    {
        return in_array($this->url, $this->authExcept, true) || in_array($this->url, $user->auth_url, true);
    }

    //登录记录
    public static function loginLog($user)
    {
        $data = AdminLog::create([
            'admin_user_id' => $user->id,
            'name'          => '登录',
            'url'           => 'admin/auth/login',
            'log_method'    => 'POST',
            'log_ip'        => request()->ip()
        ]);

        $crypt_data = Crypt::encrypt(json_encode(request()->param()), config('app.app_key'));
        $log_data   = [
            'data' => $crypt_data
        ];
        $data->adminLogData()->save($log_data);
    }

    //创建操作日志
    public function createAdminLog($user, $menu)
    {
        $data = [
            'admin_user_id' => $user->id,
            'name'          => $menu->name,
            'log_method'    => $menu->log_method,
            'url'           => request()->pathinfo(),
            'log_ip'        => request()->ip()
        ];
        $log  = AdminLog::create($data);

        //加密数据，防脱库
        $crypt_data = Crypt::encrypt(json_encode(request()->param()), config('app.app_key'));
        $log_data   = [
            'data' => $crypt_data
        ];
        $log->adminLogData()->save($log_data);
    }
}