<?php
/**
 * Api身份验证
 */

namespace app\api\traits;

use Exception;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser as TokenParser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use think\exception\HttpResponseException;

trait ApiAuth
{

    protected $config = [
        //token在header中的name
        'name'                   => 'token',
        //加密使用的secret
        'secret'                 => '552ac90778a976c72f7f673db174df30',
        //颁发者
        'iss'                    => 'iss',
        //使用者
        'aud'                    => 'aud',
        //过期时间，以秒为单位，默认2小时
        'ttl'                    => 30,
        //刷新时间，以秒为单位，默认14天，以
        'refresh_ttl'            => 1209600,
        //是否自动刷新，开启后可自动刷新token，附在header中返回，name为`Authorization`,字段为`Bearer `+$token
        'auto_refresh'           => true,
        //黑名单宽限期，以秒为单位，首次token刷新之后在此时间内原token可以继续访问
        'blacklist_grace_period' => 60
    ];

    protected $token;

    public function jwtInit()
    {
        $config = config('jwt.');
        if ($config) {
            $this->config = $config;
        }
    }

    /**
     * 检查token
     */
    public function checkToken()
    {
        $config = $this->config;
        if (!in_array($this->request->action(), $this->authExcept, true)) {

            $token = $this->request->header($config['name']);
            //缺少token
            if (empty($token)) {
                throw new HttpResponseException(error('缺少token'));
            }

            $this->token  = $token;
            $token_verify = true;
            $signer       = new Sha256();
            try {
                $jwt = (new TokenParser())->parse((string)$token);

                //验证成功后给当前uid赋值
                if (true === $jwt->verify($signer, $config['secret'])) {
                    $this->uid = $jwt->getClaim('uid');
                    $exp       = $jwt->isExpired();
                    //token已过期
                    if ($exp) {
                        $token_verify     = false;
                        $token_verify_msg = 'token已过期';
                        //如果为自动刷新
                        if ($config['auto_refresh']) {
                            $token        = $this->refreshToken();
                            $token_verify = $token;
                        }
                    }
                } else {
                    //token错误
                    $token_verify     = false;
                    $token_verify_msg = 'token错误';
                }
            } catch (Exception $e) {
                //token验证过程出错
                $token_verify     = false;
                $token_verify_msg = $e->getMessage();
            }

            //统一处理token相关错误，返回401
            if (!$token_verify) {
                throw new HttpResponseException(unauthorized('token验证错误,错误信息:'.$token_verify_msg));
            }

        }
    }


    /**
     * 获取token
     * @param $uid int 用户ID
     * @param array $data 更多数据
     * @return string
     * @throws Exception
     */
    public function getToken($uid, $data = [])
    {
        $config = $this->config;
        //发放token
        $signer = new Sha256();
        $time   = time();
        $jti    = md5($time . $uid . random_int(9999, 999999));
        $jwt    = (new Builder())->issuedBy($config['iss'])//颁发者(iss claim)
        ->permittedFor($config['aud'])//使用者(aud claim)
        ->identifiedBy($jti)//JWT ID (jti claim)
        ->issuedAt($time)//签发时间(iat claim)
        ->canOnlyBeUsedAfter($time)//可使用时间(nbf claim)
        ->expiresAt($time + $config['ttl'])//过期时间(exp claim)
        ->withClaim('uid', $uid);  //用户ID

        if (count($data) > 0) {
            foreach ($data as $key => $value) {
                $jwt = $jwt->withClaim($key, $value);
            }
        }

        $token = $jwt->getToken($signer, new Key($config['secret']));

        return (string)$token;
    }


    /**
     * 刷新token
     * @param $data
     * @return string
     */
    public function refreshToken()
    {
        $result        = false;
        $claim_protect = [
            'iss', 'aud', 'jti', 'iat', 'exp', 'nbf', 'uid'
        ];

        $time         = time();
        $jwt          = (new TokenParser())->parse((string)$this->token);
        $jti          = $jwt->getClaim('jti');
        $nbf_time     = $jwt->getClaim('nbf');
        $refresh_time = $nbf_time + $this->config['refresh_ttl'];

        if ($time >= $nbf_time && $time <= $refresh_time) {
            $blacklist_time = cache('token_blacklist_' . $jti);
            if ($blacklist_time) {
                $grace_period = $blacklist_time + $this->config['blacklist_grace_period'];
                if ($time < $grace_period) {
                    $result = true;
                }

            } else {
                //颁发新的token
                //将过期的token存到缓存中
                $claims = $jwt->getClaims();
                $data   = [];
                foreach ($claims as $key => $value) {
                    $name = $value->getName();
                    if (!in_array($name, $claim_protect)) {
                        $data[$name] = $value->getValue();
                    }
                }

                $token = $this->getToken($this->uid, $data);
                cache('token_blacklist_' . $jti, $time, $refresh_time - $time + 1);
                header('Authorization:Bearer ' . $token);
                $result = true;
            }
        }

        return $result;
    }
}
