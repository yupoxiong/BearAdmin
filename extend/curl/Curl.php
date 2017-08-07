<?php
/**
 * CURL扩展
 * @author yupoxiong <i@yufuping.com>
 * version 1.0
 **/

namespace curl;

use Exception;

class Curl
{
    private $ch;
    private $url = "https://www.yufuping.com";
    private $flag_if_have_run;   //标记exec是否已经运行
    private $set_time_out = 20;  //设置curl超时时间
    private $cookie_file = "";  //cookie_file路径
    private $cookie_mode = 0;    //cookie保存模式 0不使用 1客户端、2服务器文件
    private $show_header = 0;    //是否输出返回头信息
    private $set_useragent = ""; //模拟用户使用的浏览器，默认为模拟

    //构造函数
    public function __construct($url = ""){
        $this->ch = curl_init();
        $this->url = $url ? $url : $this->url;
        //$this->set_useragent = $_SERVER['HTTP_USER_AGENT']; // 模拟用户使用的浏览器
        $this->set_useragent ="Mozilla/5.0 (iPhone; CPU iPhone OS 6_1_4 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/7.0 Mobile/10B350 Safari/9537.53";
        // $this->set_useragent = "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/36.0.1985.143 Safari/537.36";
        $this->cookie_file=dirname(__FILE__)."/cookie_".md5(basename(__FILE__)).".txt";    //初始化cookie文件路径

    }
    //关闭curl
    public function close(){
        curl_close($this->ch);
    }
    //析构函数
    public function __destruct(){
        $this->close();
    }

    //设置超时
    public function set_time_out($timeout=20){
        if(intval($timeout) != 0)
            $this->set_time_out = $timeout;
        return $this;
    }
    //设置来源页面
    public function set_referer($referer = ""){
        if (!empty($referer))
            curl_setopt($this->ch, CURLOPT_REFERER , $referer);
        return $this;
    }
    //设置cookie存放模式 1客户端、2服务器文件
    public function set_cookie_mode($mode = ""){
        $this->cookie_mode = $mode;
        return $this;
    }
    //载入cookie
    public function load_cookie(){

        if($this->cookie_mode == 1 ) {
            if(isset($_COOKIE['curl'])){
                curl_setopt($this->ch,CURLOPT_COOKIE,$_COOKIE['curl']);
            }else{
                $this->exec();
                curl_setopt($this->ch,CURLOPT_COOKIE,$this->cookie_file);
            }

        }
        if($this->cookie_mode == 2 ) {

            curl_setopt($this->ch, CURLOPT_COOKIEFILE , $this->cookie_file);

        }

        return $this;
    }

    //设置保存cookie方式 $cookie_val 模式1为变量 模式2为文件路径
    public function save_cookie($cookie_val = "") {
        //保存在客户端
        if($this->cookie_mode == 1 && $cookie_val){
            setcookie('curl',$cookie_val);
        }
        //保存服务器端
        if($this->cookie_mode == 2){
            if(!empty($cookie_val))
                $this->cookie_file =  $cookie_val;
            curl_setopt($this->ch, CURLOPT_COOKIEJAR , $this->cookie_file);
        }

        return $this;

    }
    //post参数 (array) $post
    public function post ($post = ""){
        if($post && is_array($post)){
            curl_setopt($this->ch, CURLOPT_POST , 1);
            curl_setopt($this->ch, CURLOPT_POSTFIELDS , $post );
        }
        return $this;
    }
    //设置代理 ,例如'68.119.83.81:27977'
    public function set_proxy($proxy = ""){
        if($proxy){
            curl_setopt($this->ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
            curl_setopt($this->ch, CURLOPT_PROXY,$proxy);
        }
        return $this;
    }
    //设置伪造ip
    public function set_ip($ip=""){
        if(!empty($ip))
            curl_setopt($this->ch, CURLOPT_HTTPHEADER, array("X-FORWARDED-FOR:$ip", "CLIENT-IP:$ip"));
        return $ip;
    }
    //设置是否显示返回头信息
    public function show_header($show=0){
        $this->show_header = 0;
        if($show)
            $this->show_header = 1;
        return $this;
    }

    //设置请求头信息
    public function set_useragent($str=""){
        if($str)
            $this->set_useragent = $str;
        return $this;
    }

    //执行
    public function exec ($url = ""){
        if(!$url) $url = $this->url;
        curl_setopt($this->ch, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER , 1 );    //获取的信息以文件流的形式返回
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
        curl_setopt($this->ch, CURLOPT_USERAGENT, $this->set_useragent); // 模拟用户使用的浏览器
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($this->ch, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($this->ch, CURLOPT_TIMEOUT, $this->set_time_out);  //超时设置
        curl_setopt($this->ch, CURLOPT_HEADER, $this->show_header); // 显示返回的Header区域内容
        curl_setopt($this->ch, CURLOPT_NOBODY, 0);//不返回response body内容

        $res = curl_exec($this->ch);
        $this->flag_if_have_run = true;
        if (curl_errno($this->ch)) {
            //echo 'Errno'.curl_error($this->ch);
            return false;
        }
        if($this->show_header == 1){ //数组形式返回头信息和body信息
            list($header, $body) = explode("\r\n\r\n", $res);
            $arr['header'] = $header;
            $arr['body'] = $body;
            if($this->cookie_mode == 1 || $this->cookie_mode == 3){
                preg_match_all("/set\-cookie:([^\r\n]*)/i", $header, $matches);
                //print_r($matches);
                if($matches && isset($matches[1]) ){
                    $val = implode(';',array_unique(explode(';',implode(';',$matches[1])))); //去重处理
                    if($val)
                        $this->save_cookie($val); //设置客户端保存cookie
                }
            }
            if($arr) return $arr;
        }

        return $res;
    }


    //返回  curl_getinfo信息
    public function get_info(){
        if($this->flag_if_have_run == true )
            return curl_getinfo($this->ch);
        else
            throw new Exception("请先执行exec方法");
    }

}