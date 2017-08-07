<?php
/**
 * User: yupoxiong
 * Date: 2017/3/8
 * Time: 13:55
 */

namespace app\api\controller;

use app\admin\common\Bests;

class Best extends Api
{
    public function index()
    {
        $this->api_result['result'] = Bests::all();
        return $this->api_result;
    }
}