<?php
/**
 * Api404
 * @author yupoxiong <i@yufuping.com>
 * @version 1.0
 */

namespace app\api\controller;

use think\Response;

class Error extends Api
{
    public function index()
    {
        return $this->api_error('',404);
        //return Response::create($this->api_result, 'json',404);
    }

    public function _empty()
    {
        return $this->api_error('',404);
        //return Response::create($this->api_result, 'json',404);
    }
}