<?php
/**
 * 前台登录退出权限等控制器
 * @author yupoxiong<i@yufuping.com>
 */
namespace app\index\controller;

use anerg\OAuth2\OAuth;
use think\Config;

class Auth extends Controller
{
    public function qq(){
        $config   = Config::get('qq_login');
        $OAuth  = OAuth::getInstance($config, 'qq');
        /*if($this->request->isMobile()){
            $OAuth->setDisplay('mobile');
        }*/
        $OAuth->setDisplay('mobile');
        return redirect($OAuth->getAuthorizeURL());
    }


    public function qq_callback() {
        $config   = Config::get('qq_login');
        $OAuth    = OAuth::getInstance($config, 'qq');
        $OAuth->getAccessToken();

        $sns_info = $OAuth->userinfo();

        return
            '<h1>'.$sns_info['nick'].'</h1><br>'.
            '<img src="'.$sns_info['avatar'].'">';
    }
}