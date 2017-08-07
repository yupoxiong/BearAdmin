<?php
/**
 * @name Slides
 * @author yupoxiong
 * 获取首页轮播图
 *
 */

namespace app\api\controller;

use app\admin\model\Slides as Slide;

class Slides extends Api
{
    //首页轮播图列表
    public function index()
    {

        $slides = new Slide();

        $this->api_result['result'] = $slides->where();
        return $this->api_result;
    }
}
