<?php
/**
 * User: yupoxiong
 * Date: 2017/3/8
 * Time: 14:18
 */

namespace app\api\controller;

class Miss extends Api
{
    public function index()
    {
        $this->default_404();
        return $this->api_result;
    }
}