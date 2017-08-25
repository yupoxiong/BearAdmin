<?php

/**
 * 第三方登录
 * @author yupoxiong <i@yufuping.com>
 */

namespace app\admin\controller;

use anerg\OAuth2\OAuth;
use think\Db;

class ThirdLogin extends Base
{
    /**
     * @description 暂未完善
     * @return mixed
     */
    public function index()
    {
        
        
        return $this->fetch();
    }



    public function qq() {
        $config = [
            'app_key'    => '101413486',
            'app_secret' => '0811a83649ebb7ce7613ea36ed7cd79f',
            'scope'      => 'get_user_info',
            'callback'   => [
                'default' => 'https://bearadmin.yufuping.com/admin/third_login/callback',
                'mobile'  => 'https://bearadmin.yufuping.com/admin/third_login/callback',
            ]
        ];
        $OAuth  = OAuth::getInstance($config, 'qq');
        $OAuth->setDisplay('mobile'); //此处为可选,若没有设置为mobile,则跳转的授权页面可能不适合手机浏览器访问
        return redirect($OAuth->getAuthorizeURL());
    }

    public function callback() {
        $config   = [
            'app_key'    => '101413486',
            'app_secret' => '0811a83649ebb7ce7613ea36ed7cd79f',
            'scope'      => 'get_user_info',
            'callback'   => [
                'default' => 'https://bearadmin.yufuping.com/admin/third_login/callback',
                'mobile'  => 'https://bearadmin.yufuping.com/admin/third_login/callback',
            ]
        ];
        $OAuth    = OAuth::getInstance($config, 'qq');
        $OAuth->getAccessToken();
        /**
         * 在获取access_token的时候可以考虑忽略你传递的state参数
         * 此参数使用cookie保存并验证
         */
//        $ignore_stat = true;
//        $OAuth->getAccessToken(true);
        $sns_info = $OAuth->userinfo();
        /**
         * 此处获取了sns提供的用户数据
         * 你可以进行其他操作
         */

        dump($sns_info);
        exit();
    }




}
