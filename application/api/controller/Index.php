<?php
/**
 * Api首页
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\api\controller;

class Index extends Api
{
    protected $needAuth= true;
    public function index()
    {
        return $this->success($this->uid);
    }
}
