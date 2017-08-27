<?php

/**
 * 第三方登录
 * @author yupoxiong <i@yufuping.com>
 */

namespace app\admin\controller;

use anerg\OAuth2\OAuth;
use think\Config;
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

}
