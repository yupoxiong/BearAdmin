<?php
/**
 * Api404
 * @author yupoxiong <i@yufuping.com>
 * @version 1.0
 */

namespace app\api\controller;

class Error extends Api
{
    public function index()
    {
        $this->default_404();
        return $this->api_result;
    }

    public function _empty()
    {
        $this->default_404();
        return $this->api_result;
    }
}