<?php
/**
 * 安全设置Cookie,加密后调用TP自带cookie方法
 * @author yupoxiong <i@yufuping.com>
 */

namespace util\safe;

use think\facade\Cookie;

class SafeCookie
{
    /**
     * 加密key
     * @var mixed|string
     */
    protected static $key;

    public function __construct()
    {
        self::$key = env('app.app_key') ?? md5('This-Is-App-Key!');
    }

    public static function has($name): bool
    {
        return Cookie::has($name);
    }

    /**
     * @param string $name
     * @param string $value
     * @param null $option
     */
    public static function set(string $name, string $value, $option = null): void
    {
        $value = self::encrypt($value, self::$key);
        Cookie::set($name, $value, $option);
    }

    public static function get($name, $prefix = null)
    {
        return self::decrypt(Cookie::get($name, $prefix), self::$key);
    }

    /**
     * @param $name
     */
    public static function delete($name): void
    {
        Cookie::delete($name);
    }

    public static function save()
    {
        Cookie::save();
    }

    public static function forever($name = '', $value = '', $option = null)
    {

        Cookie::forever($name, $value, $option);
    }


    /**
     * AES加密
     * @param $input
     * @param $key
     * @return string
     */
    public static function encrypt($input, $key): string
    {
        $key  = self::sha1prng($key);
        $data = openssl_encrypt($input, 'AES-128-ECB', $key, OPENSSL_RAW_DATA);
        return base64_encode($data);
    }

    /**
     * AES解密
     * @param $sStr
     * @param $sKey
     * @return false|string
     */
    public static function decrypt($sStr, $sKey)
    {
        $sKey = self::sha1prng($sKey);
        return openssl_decrypt(base64_decode($sStr), 'AES-128-ECB', $sKey, OPENSSL_RAW_DATA);
    }

    /**
     * SHA1PRNG算法
     */
    public static function sha1prng($key)
    {
        return substr(openssl_digest(openssl_digest($key, 'sha1', true), 'sha1', true), 0, 16);
    }
}
