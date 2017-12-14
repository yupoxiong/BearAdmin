<?php
/**
 * 自定义助手函数
 * @author yupoxiong<i@yufuping.com>
 */

use crypt\Crypt;
use think\Cache;
use think\Config;
use think\Cookie;
use think\Db;
use think\Debug;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\Lang;
use think\Loader;
use think\Log;
use think\Model;
use think\Request;
use think\Response;
use think\Session;
use think\Url;
use think\View;

if (!function_exists('ip_get_info')) {
    /**
     * 根据ip获取ip地址相关信息,ip所在地天气相关信息
     * * @param $ip
     * @param mixed $option 默认为3，获取ip所在城市
     * ***参数说明***
     * 1|'all' 为获取所有信息;
     * 2|'province' 为获取省份;
     * 3|'city' 为获取城市;
     * 4|'' 为获取城市code;
     * 5|'rectangle' 为获取城市坐标范围;
     * 6|'weather' 为获取ip所在城市天气;
     * @return bool|mixed
     * 原始数据参考结果：
     * //ip
     * {"status":"1","info":"OK","infocode":"10000",
     * "province":"山东省","city":"济南市","adcode":"370100",
     * "rectangle":"116.7172801,36.45453907;117.3654199,36.8701026"}
     * //天气
     * {"status":"1","count":"1","info":"OK","infocode":"10000",
     * "lives":[{"province":"山东","city":"济南市","adcode":"370100",
     * "weather":"多云","temperature":"26","winddirection":"南",
     * "windpower":"9","humidity":"52","reporttime":"2017-06-10 18:00:00"}]}
     */
    function ip_get_info($ip, $option = 3)
    {
        $amap_web_key = Config::get('helper_config.amap_web_key');
        $ipinfo_str   = file_get_contents('http://restapi.amap.com/v3/ip?key=' . $amap_web_key . '&ip=' . $ip);
        $ipinfo       = json_decode($ipinfo_str, true);
        $result       = false;
        if ($option == 1 || $option == 'all') {
            $result = $ipinfo;
        }
        if ($option == 2 || $option == 'province') {
            $result = $ipinfo['status'] == 1 && !is_array($ipinfo['province']) ? $ipinfo['province'] : false;
        }
        if ($option == 3 || $option == 'city') {
            $result = $ipinfo['status'] == 1 && !is_array($ipinfo['city']) ? $ipinfo['city'] : false;
        }
        if ($option == 4 || $option == 'adcode') {
            $result = $ipinfo['status'] == 1 && !is_array($ipinfo['adcode']) ? $ipinfo['adcode'] : false;
        }
        if ($option == 5 || $option == 'rectangle') {
            $result = $ipinfo['status'] == 1 && !is_array($ipinfo['rectangle']) ? $ipinfo['rectangle'] : false;
        }
        if ($option == 6 || $option == 'weather') {
            $adcode = $ipinfo['status'] == 1 && !is_array($ipinfo['adcode']) ? $ipinfo['adcode'] : false;
            if ($adcode) {
                $weather_info_str = file_get_contents('http://restapi.amap.com/v3/weather/weatherInfo?key=' . $amap_web_key . '&city=' . $adcode);
                $weather_info     = json_decode($weather_info_str, true);
                if ($weather_info['status'] == 1 && $weather_info['count'] > 0) {
                    $result = count($weather_info['lives'][0]) > 0 ? $weather_info['lives'][0] : false;
                }
            }
        }
        return $result;
    }
}

//这里准备加根据简要地址和城市获取地址的信息
//http://restapi.amap.com/v3/geocode/geo?key=您的key&address=济南&city=济南

if (!function_exists('encrypt')) {

    /**
     * 字符串加密方法
     * @param string $string 需要加密的文本
     * @param string $key 密钥
     * @param int $expiry 过期时间，单位：秒
     * @return string
     */
    function encrypt($string, $key = '', $expiry = 0)
    {
        return Crypt::authcode($string, 'encode', $key, $expiry);

    }
}

if (!function_exists('decrypt')) {

    /**
     * 字符串解密方法
     * @param string $string 加密文本
     * @param string $key 密钥
     * @return string
     */
    function decrypt($string, $key)
    {
        return Crypt::authcode($string, 'DECODE', $key);
    }
}

if (!function_exists('format_size')) {
    /**
     * 格式化文件大小单位
     * @param $size
     * @param string $delimiter
     * @return string
     */
    function format_size($size, $delimiter = '')
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
        return round($size, 2) . $delimiter . $units[$i];
    }
}


if (!function_exists('get_browser_type')) {

    /**
     * 获取浏览器类型（扫码）京东金融，手机QQ，微信，支付宝
     * @param $user_agent
     * @return string
     */
    function get_browser_type($user_agent)
    {
        $match_qq     = '/.*MQQBrowser.*QQ.*/';
        $match_wechat = '/.*MQQBrowser.*MicroMessenger.*/';
        $match_alipay = '/.*UCBrowser.*AlipayClient.*/';
        $match_jd     = '/.*MQQBrowser.*jdFinance.*/';

        $result = preg_match($match_wechat, $user_agent) ? 'wechat' : (
        preg_match($match_alipay, $user_agent) ? 'alipay' : (
        preg_match($match_jd, $user_agent) ? 'jd' : (
        preg_match($match_qq, $user_agent) ? 'qq' : 'unkown'
        )
        )
        );
        return $result;
    }
}

if(!function_exists('parse_name')){
     function parse_name($name, $type = 0, $ucfirst = true)
    {
        if ($type) {
            $name = preg_replace_callback('/_([a-zA-Z])/', function ($match) {
                return strtoupper($match[1]);
            }, $name);
            return $ucfirst ? ucfirst($name) : lcfirst($name);
        } else {
            return strtolower(trim(preg_replace("/[A-Z]/", "_\\0", $name), "_"));
        }
    }
}



if (!function_exists('get_list_rows')) {
    /**
     * 获取后台分页配置
     */
    function get_list_rows()
    {
        $rows = Config::get('list_rows') ? Config::get('list_rows') : 10;
        if (isset($_COOKIE['backend_list_rows'])) {
            $c_rows = $_COOKIE['backend_list_rows'];
            if (0 < $c_rows && 100 >= $c_rows) {
                $rows = $c_rows;
            }
        }
        return intval($rows);
    }
}




