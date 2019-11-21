<?php

namespace app\index\controller;


class IndexController extends Controller
{
    //无需验证登录的方法
    protected $authExcept = [
        'index',
    ];

    //前台模块首页
    public function index()
    {

        $is_login      = 0;
        $is_login_text = '未登录';
        if ($this->isLogin()) {
            $is_login      = 1;
            $is_login_text = '已登录';
        }

        $this->assign([
            'is_login'      => $is_login,
            'is_login_text' => $is_login_text,
        ]);

        return $this->fetch();
    }


}
