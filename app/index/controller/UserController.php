<?php

/**
 * 前台用户中心
 */

namespace app\index\controller;


class UserController extends IndexBaseController
{


    //个人中心首页
    public function index()
    {
        return $this->fetch();
    }

}