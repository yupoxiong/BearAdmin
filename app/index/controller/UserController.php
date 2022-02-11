<?php

/**
 * 前台用户中心
 */

namespace app\index\controller;

use Exception;

class UserController extends IndexBaseController
{
    /**
     * 个人中心首页
     * @throws Exception
     */
    public function index(): string
    {
        return $this->fetch();
    }

}