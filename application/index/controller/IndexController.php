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
        return $this->fetch();
    }


}
