<?php
/**
 * 空控制器
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\api\controller;

use Lcobucci\JWT\Parser as TokenParser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use think\Container;
use think\exception\HttpResponseException;
use think\Response;

class Error extends Api
{
    protected $needAuth = false;

    public function index()
    {
        return $this->error('Api not found');
    }

    //访问空页面
    public function _empty()
    {
        return $this->error('Api not found');
    }
}