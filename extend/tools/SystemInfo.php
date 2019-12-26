<?php
/**
 * 获取系统信息
 * @author yupoxiong<i@yufuping.com>
 */

namespace tools;

use think\Db;

class SystemInfo
{

    public static function getSystemInfo(): array
    {
        $composer = json_decode(file_get_contents(app()->getRootPath() . 'composer.json'), true);

        $require_list     = [];
        $require_dev_list = [];
        if (array_key_exists('require', $composer)) {
            $require_list = $composer['require'];
        }
        if (array_key_exists('require-dev', $composer)) {
            $require_dev_list = $composer['require-dev'];
        }


        $user_agent = request()->header('user-agent');

        if (false !== stripos($user_agent, 'win')) {
            $user_os = 'Windows';
        } elseif (false !== stripos($user_agent, 'mac')) {
            $user_os = 'MAC';
        } elseif (false !== stripos($user_agent, 'linux')) {
            $user_os = 'Linux';
        } elseif (false !== stripos($user_agent, 'unix')) {
            $user_os = 'Unix';
        } elseif (false !== stripos($user_agent, 'bsd')) {
            $user_os = 'BSD';
        } elseif (false !== stripos($user_agent, 'iPad') || false !== stripos($user_agent, 'iPhone')) {
            $user_os = 'IOS';
        } elseif (false !== stripos($user_agent, 'android')) {
            $user_os = 'Android';
        } else {
            $user_os = 'Other';
        }


        if (false !== stripos($user_agent, 'MSIE')) {
            $user_browser = 'MSIE';
        } elseif (false !== stripos($user_agent, 'Firefox')) {
            $user_browser = 'Firefox';
        } elseif (false !== stripos($user_agent, 'Chrome')) {
            $user_browser = 'Chrome';
        } elseif (false !== stripos($user_agent, 'Safari')) {
            $user_browser = 'Safari';
        } elseif (false !== stripos($user_agent, 'Opera')) {
            $user_browser = 'Opera';
        } else {
            $user_browser = 'Other';
        }

        $user_ip_address = '--';
        $user_ip         = request()->ip();
        $ip_json         = @file_get_contents('http://restapi.amap.com/v3/ip?key=36764b698753cbde3ffccda82d040d14&ip=' . $user_ip);
        $ip_arr          = json_decode($ip_json, true);
        if (((int)$ip_arr['status'] === 1) && is_string($ip_arr['province']) && is_string($ip_arr['city'])) {
            $user_ip_address = $ip_arr['province'] . ' ' . $ip_arr['city'];
        }
        $info = [
            //服务器系统
            'server_os'           => PHP_OS,
            //服务器ip
            'server_ip'           => GetHostByName($_SERVER['SERVER_NAME']),
            //php版本
            'php_version'         => PHP_VERSION,
            //运行内存限制
            'memory_limit'        => ini_get('memory_limit'),
            //最大文件上传限制
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            //单次上传数量限制
            'max_file_uploads'    => ini_get('max_file_uploads'),
            //最大post限制
            'post_max_size'       => ini_get('post_max_size'),
            //磁盘剩余容量
            'disk_free'           => format_size(disk_free_space(app()->getRootPath())),
            //ThinkPHP版本
            'think_version'       => app()->version(),
            //运行模式
            'php_sapi_name'       => PHP_SAPI,
            //当前后台版本
            'admin_version'       => config('admin.version'),
            //mysql版本
            'db_version'          => Db::query('select VERSION() as db_version')[0]['db_version'],
            //php时区
            'timezone'            => date_default_timezone_get(),
            //当前时间
            'date_time'           => date('Y-m-d H:i:s'),
            //依赖包
            'require_list'        => $require_list,
            //依赖包（dev）
            'require_dev_list'    => $require_dev_list,

            //用户IP
            'user_ip'             => $user_ip,
            //用户系统
            'user_os'             => $user_os,
            //IP所在城市
            'user_ip_address'     => $user_ip_address,
            //用户浏览器
            'user_browser'        => $user_browser,

        ];
        return $info;
    }

}