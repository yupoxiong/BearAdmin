<?php
/**
 *
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);


namespace app\index\traits;


use app\common\model\User;
use think\facade\Session;
use util\safe\SafeCookie;

trait IndexAuthTrait
{
    public static string $user_id = 'index_user_id';
    public static string $user_id_sign = 'index_user_sign';

    //是否登录
    protected function isLogin(): bool
    {
        //这里要写个判断数据库的才行
        $user_id = Session::get(self::$user_id);
        $user       = new User();
        $this->user = &$user;
        if (empty($user_id)) {
            if (SafeCookie::has(self::$user_id) && SafeCookie::has(self::$user_id_sign)) {
                $user_id = SafeCookie::get(self::$user_id);
                $sign          = SafeCookie::get(self::$user_id_sign);
                $user          = (new User)->where('id','=',$user_id)->findOrEmpty();
                if($user->isEmpty()){
                    return  false;
                }
                if ( $user->sign_str === $sign) {
                    Session::set(self::$user_id, $user_id);
                    Session::set(self::$user_id_sign, $sign);
                    return true;
                }
            }
            return false;
        }

        $user          = (new User)->where('id','=',$user_id)->findOrEmpty();
        if($user->isEmpty()) {
            return false;
        }
        $this->uid = (int)$user->id;

        return Session::get(self::$user_id_sign) === $user->sign_str;
    }

    /**
     * session 与cookie登录
     * @param User $user
     * @param bool $remember
     * @return bool
     */
    public static function authLogin(User $user, bool $remember): bool
    {
        Session::set(self::$user_id, $user->id);
        Session::set(self::$user_id_sign, $user->sign_str);

        //记住登录
        if ($remember === true) {
            SafeCookie::set(self::$user_id, (string)$user->id);
            SafeCookie::set(self::$user_id_sign, $user->sign_str);
        } else if (SafeCookie::has(self::$user_id) || SafeCookie::has(self::$user_id_sign)) {
            SafeCookie::delete(self::$user_id);
            SafeCookie::delete(self::$user_id_sign);
        }

        return true;
    }

    /**
     * 退出
     * @return bool
     */
    public static function authLogout(): bool
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