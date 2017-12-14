<?php
/**
 * 系统信息类
 * @author yupoxiong<i@yufuping.com>
 */
namespace tools;

class Sysinfo
{

    function getLang() {
        $Lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 4);
        if (preg_match('/zh-c/i',$Lang)) {
            $Lang = '简体中文';
        }
        elseif (preg_match('/zh/i',$Lang)) {
            $Lang = '繁體中文';
        }
        else {
            $Lang = 'English';
        }
        return $Lang;
    }
    function getBrowser() {
        $Browser = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/MSIE/i',$Browser)) {
            $Browser = 'MSIE';
        }
        elseif (preg_match('/Firefox/i',$Browser)) {
            $Browser = 'Firefox';
        }
        elseif (preg_match('/Chrome/i',$Browser)) {
            $Browser = 'Chrome';
        }
        elseif (preg_match('/Safari/i',$Browser)) {
            $Browser = 'Safari';
        }
        elseif (preg_match('/Opera/i',$Browser)) {
            $Browser = 'Opera';
        }
        else {
            $Browser = 'Other';
        }
        return $Browser;
    }
    function getOS() {
        $OS = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/win/i',$OS)) {
            $OS = 'Windows';
        }
        elseif (preg_match('/mac/i',$OS)) {
            $OS = 'MAC';
        }
        elseif (preg_match('/linux/i',$OS)) {
            $OS = 'Linux';
        }
        elseif (preg_match('/unix/i',$OS)) {
            $OS = 'Unix';
        }
        elseif (preg_match('/bsd/i',$OS)) {
            $OS = 'BSD';
        }
        else {
            $OS = 'Other';
        }
        return $OS;
    }

    //获取当前ip
    function getIp() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {

            $ip = explode(',',$_SERVER['HTTP_CLIENT_IP']);
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = explode(',',$_SERVER['HTTP_X_FORWARDED_FOR']);
        }
        elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = explode(',',$_SERVER['REMOTE_ADDR']);
        }
        else {
            $ip[0] = 'None';
        }
        return $ip[0];
    }

    //获取ip信息{ret: 1, start: -1, end: -1, country: "中国", province: "山东", city: "济南", district: "", isp: "",…}
    //2017年6月9日 23:21:20 新浪没有高德准，现在换成高德
    private function getIpinfo() {
        $ip = $this->getIp();
        $ipinfo_str = @file_get_contents('http://restapi.amap.com/v3/ip?key=cb241360e2b73b5951371c60a1b095ef&ip='.$ip);
        if($ipinfo_str){
            $ipinfo = json_decode($ipinfo_str,true);
            return $ipinfo['status']==1 ? $ipinfo : false;
        }
        return false;
    }


    //http://restapi.amap.com/v3/weather/weatherInfo?city=110101&key=<用户key>
    function getAdcode(){
        $ipinfo = $this->getIpinfo();
        return $ipinfo!=false ? $ipinfo['adcode'] : 'None';
    }

    function getProvince() {
        $ipinfo = $this->getIpinfo();
        return $ipinfo!=false ? $ipinfo['province'] : 'None';
    }

    function getCity() {
        $ipinfo = $this->getIpinfo();
        return $ipinfo!=false&&!is_array($ipinfo['city']) ? $ipinfo['city'] : 'None';
    }

    function getProvinceCity() {
        $ipinfo = $this->getIpinfo();
        return $ipinfo!=false ? $ipinfo['province']." ".$ipinfo['city'] : 'None';
    }
}