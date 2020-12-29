<?php
/**
 * 安全设置Cookie,加密后调用TP自带cookie方法
 * @author yupoxiong <i@yufuping.com>
 */

namespace tools;

use think\facade\Cookie;

class SafeCookie
{
    protected static $key;

    public function __construct()
    {
        self::$key = config('app.app_key') ?? md5('Tao-qi,I love you.');
    }

    public static function has($name, $prefix = null)
    {
        return Cookie::has($name, $prefix);
    }

    public static function set($name, $value = '', $option = null)
    {
        if (is_array($value)) {
            $value = json_encode($value);
        }
        $value = Crypt::encrypt($value, self::$key);
        return Cookie::set($name, $value, $option);
    }

    public static function get($name, $prefix = null)
    {
        $value = Crypt::decrypt(Cookie::get($name, $prefix), self::$key);
        if (self::isJson($value)) {
            $value = json_decode($value, true);
        }
        return $value;
    }


    public static function delete($name, $prefix = null)
    {
        Cookie::delete($name, $prefix);
    }

    static function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}
