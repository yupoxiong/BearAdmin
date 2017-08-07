<?php
/**
 * 访问空控制器，用于访问不存在的控制器跳转
 * @author yupoxiong <i@yufuping.com>
 * version 1.0
 */
namespace app\admin\controller;

use think\Controller;
use think\Request;

class Error extends Controller
{
    public function index(){
        $url = $this->request->server('HTTP_REFERER');
        $server = $this->request->server();
        if(isset($server['HTTP_REFERER'])){
            $url = $server['HTTP_REFERER'];
        }
        return $this->redirect($url, [], 302, ['error_message' => '页面不存在!']);
    }
}