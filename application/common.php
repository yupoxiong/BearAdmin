<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件


use think\facade\Config;

if (!function_exists('get_middle_str')) {
    /**
     * 获取两个字符串中间的字符
     * @param $str
     * @param $leftStr
     * @param $rightStr
     * @return bool|string
     */
    function get_middle_str($str, $leftStr, $rightStr)
    {
        $left  = strpos($str, $leftStr);
        $right = strpos($str, $rightStr, $left);
        if ($right < $left || $left < 0) {
            return '';
        }
        return substr($str, $left + strlen($leftStr), $right - $left - strlen($leftStr));
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
        for ($i = 0; $size >= 1024 && $i < 5; $i++) {
            $size /= 1024;
        }
        return round($size, 2) . $delimiter . $units[$i];
    }
}

//设置相关助手函数
if (!function_exists('setting')) {
    function setting($name = '', $value = null)
    {
        if ($value === null && is_string($name)) {
            if ('.' === substr($name, -1)) {
                return Config::pull(substr($name, 0, -1));
            }
            return 0 === strpos($name, '?') ? Config::has(substr($name, 1)) : Config::get($name);
        }
        return Config::set($name, $value);
    }

}
