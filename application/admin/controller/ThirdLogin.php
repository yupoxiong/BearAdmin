<?php

/**
 * 第三方登录
 * @author yupoxiong <i@yufuping.com>
 */

namespace app\admin\controller;

use net\Http;
use think\Db;
use app\common\model\AdminFiles;
use think\Session;

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


    //QQ登录
    public function qq(){

    }

}
