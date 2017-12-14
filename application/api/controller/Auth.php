<?php
/**
 * Auth控制器
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\api\controller;

use Lcobucci\JWT\Builder as TokenBuilder;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class Auth extends Api
{
    protected $needAuth = false;
    
    //用户登录
    public function login()
    {
        $result = $this->validate($this->param,'User.ApiLogin');
        if(true!==$result){
            return $this->error($result);
        }
        
        $signer = new Sha256();
        $token = (new TokenBuilder())
        ->setIssuedAt(time())
        ->setNotBefore(time())
        ->setExpiration(time() + 3600)
        ->set('uid',3)
        ->sign($signer, config('app_key'))
        ->getToken();
        
        return $this->success($token);
    }

}