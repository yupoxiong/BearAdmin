<?php
/**
 * 网站首页
 *
 */

namespace app\index\controller;


class Index extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

    public function hello()
    {
        return 'hello';
    }
    
}