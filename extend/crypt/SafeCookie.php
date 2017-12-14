<?php
/**
 * 安全设置Cookie
 * @author yupoxiong <i@yufuping.com>
 */

namespace crypt;

use think\Cookie;
use think\Config;

class SafeCookie{

    static $crypt_key;

    function __construct()
    {
        self::$crypt_key = Config::get('app_key')!=null ? Config::get('app_key') : 'beautiful_taoqi';
    }

    //加密后调用Cookie类的set方法
    public static function set($key, $value = '', $option = null)
    {
        //$key = Crypt::encrypt($key, self::$crypt_key);
        if(is_array($value)){
            $value = json_encode($value);
        }
        $value = Crypt::encrypt($value, self::$crypt_key);
        return Cookie::set($key,$value,$option);
    }

    //加密后调用Cookie类的set方法
    public static function get($name, $prefix = null)
    {
        //$name = Crypt::encrypt($name, self::$crypt_key);

        $value = Crypt::decrypt(Cookie::get($name,$prefix),self::$crypt_key);
        if(self::is_json($value)){
            $value = json_decode($value,true);
        }

        return $value;

    }

    //判断是否是json
    static function is_json($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }


} 