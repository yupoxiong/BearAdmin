<?php
/**
 * @author yupoxiong<i@yufuping.com>
 * @title 首页
 */

namespace app\api\controller;

class IndexController extends ApiBaseController
{

    protected $authExcept = [
        //'index'
    ];

    public function index()
    {
        return api_success('index');
    }

}