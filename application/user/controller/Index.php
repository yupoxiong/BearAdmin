<?php
/**
 * @author yupoxiong<i@yufuping.com>
 * Date: 2018/5/22
 */
namespace app\user\controller;

class Index extends Admin
{
    public function index()
    {
        return $this->fetch();
    }
}