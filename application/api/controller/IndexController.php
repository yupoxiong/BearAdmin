<?php
/**
 * @author yupoxiong<i@yufuping.com>
 * @title 首页
 */

namespace app\api\controller;

class IndexController extends Controller
{

    protected $authExcept = [
        'index'
    ];

    public function index()
    {
        return api_success('index');
    }

}