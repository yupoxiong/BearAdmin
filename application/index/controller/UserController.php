<?php

/**
 * 前台用户中心
 */

namespace app\index\controller;

use app\common\model\User;
use think\Request;

class UserController extends Controller
{
    protected $authExcept=[
    ];

    //个人中心首页
    public function index()
    {

        $this->assign([
            'user'=>$this->user
        ]);

        return $this->fetch();
    }

}