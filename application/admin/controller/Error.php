<?php
/**
 * 访问空控制器，用于访问不存在的控制器跳转
 * @author yupoxiong <i@yufuping.com>
 * version 1.0
 */
namespace app\admin\controller;

use think\Controller;

class Error extends Controller
{
    public function index(){

        $server = $this->request->server();
        if(isset($server['HTTP_REFERER'])&&$server['HTTP_REFERER']!=null){
            $url = $server['HTTP_REFERER'];
        }else{
            $url = '/admin';
        }
        return $this->redirect($url, [], 302, ['error_message' => '页面不存在!']);
    }

    public function _empty()
    {
        $server = $this->request->server();
        if(isset($server['HTTP_REFERER'])&&$server['HTTP_REFERER']!=null){
            $url = $server['HTTP_REFERER'];
        }else{
            $url = '/admin';
        }
        return $this->redirect($url, [], 302, ['error_message' => '页面不存在!']);
    }
}