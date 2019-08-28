<?php
/**
 * 登录、退出、记录日志相关
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\index\traits;

use app\common\model\User;
use tools\SafeCookie;
use think\facade\Session;

trait IndexAuth
{
    public static $user_id = 'user_id';
    public static $user_id_sign = 'user_sign';

    //是否登录
    protected function isLogin()
    {
        //这里要写个判断数据库的才行
        $user_id = Session::get(self::$user_id);
        $user       = false;
        $this->user = &$user;
        if (empty($user_id)) {
            if (SafeCookie::has(self::$user_id) && SafeCookie::has(self::$user_id_sign)) {
                $user_id = SafeCookie::get(self::$user_id);
                $sign          = SafeCookie::get(self::$user_id_sign);
                $user          = User::get($user_id);
                if ($user && $user->sign_str === $sign) {
                    Session::set(self::$user_id, $user_id);
                    Session::set(self::$user_id_sign, $sign);
                    return true;
                }
            }
            return false;
        }

        $user = User::get($user_id);
        if(!$user) {
            return false;
        }
        $this->uid = $user->id;

        return Session::get(self::$user_id_sign) === $user->sign_str;
    }

    /**
     * session 与cookie登录
     * @param $user User
     * @param bool $remember
     * @return bool
     */
    public static function authLogin($user, $remember = false)
    {
        Session::set(self::$user_id, $user->id);
        Session::set(self::$user_id_sign, $user->sign_str);

        //记住登录
        if ($remember === true) {
            SafeCookie::set(self::$user_id, $user->id);
            SafeCookie::set(self::$user_id_sign, $user->sign_str);
        } else if (SafeCookie::has(self::$user_id) || SafeCookie::has(self::$user_id_sign)) {
            SafeCookie::delete(self::$user_id);
            SafeCookie::delete(self::$user_id_sign);
        }

        return true;
    }

    //退出
    public static function authLogout()
    {
        Session::delete(self::$user_id);
        Session::delete(self::$user_id_sign);
        if (SafeCookie::has(self::$user_id) || SafeCookie::has(self::$user_id_sign)) {
            SafeCookie::delete(self::$user_id);
            SafeCookie::delete(self::$user_id_sign);
        }
        return true;
    }


}