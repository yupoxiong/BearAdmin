<?php
/**
 * Token Service
 * 当前默认用HS256加密，可自行修改为RS256或者其他
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);

namespace app\api\service;

use util\jwt\Jwt;
use think\facade\Cache;
use util\jwt\JwtException;
use app\common\service\DateService;
use app\api\exception\ApiServiceException;


class TokenService extends ApiBaseService
{
    /** @var Jwt */
    protected Jwt $jwt;
    /** @var string */
    protected $key = 'b19a4be117e0d245bb838b3c7f776ade370c88e6';
    /** @var string 颁发者 */
    /** @var int 默认token过期时间 当前为1000天 */
    protected int $exp = 86400000;
    /** @var int 刷新token 默认为15天 */
    protected int $refreshTokenExp = 1296000;
    /** @var bool 开启token刷新 */
    protected bool $enableRefreshToken = false;
    /** @var bool 重复使用检测 */
    protected bool $reuseCheck = false;
    /** @var string 黑名单缓存前缀 */
    protected string $refreshTokenBlacklistKeyPrefix = 'api_access_token_blacklist_';
    protected string $loginAgainKeyPrefix = 'api_user_login_again_';

    public function __construct()
    {
        $this->jwt = new Jwt();

        $config = config('api.auth');

        $this->key                = $config['jwt_key'] ?? $this->key;
        $this->exp                = $config['jwt_exp'] ?? $this->exp;
        $this->enableRefreshToken = $config['enable_refresh_token'] ?? $this->enableRefreshToken;
        $this->reuseCheck         = $config['reuse_check'] ?? $this->reuseCheck;
        $this->refreshTokenExp    = $config['refresh_token_exp'] ?? $this->refreshTokenExp;
    }

    /**
     * 获取token
     * @param int $uid 用户ID
     * @param array $claim 自定义claim
     * @return string
     * @throws ApiServiceException
     */
    public function getAccessToken(int $uid, array $claim = []): string
    {
        $time  = time();
        $jti   = $this->createJti($uid);
        $token = $this->jwt
            ->setKey($this->key)
            ->setIat($time)
            ->setExp($time + $this->exp)// 过期时间
            ->setJti($jti)// tokenID
            ->setUid($uid);// 用户ID
        // 附加参数
        if (count($claim) > 0) {
            foreach ($claim as $c_key => $c_value) {
                $token = $token->setClaim($c_key, $c_value);
            }
        }

        try {
            return $token->getToken();
        } catch (JwtException $e) {
            throw  new ApiServiceException('生成token失败，信息：' . $e->getMessage());
        }
    }

    /**
     * 获取token
     * @param int $uid 用户ID
     * @param array $claim 自定义claim
     * @return string
     * @throws ApiServiceException
     */
    public function getRefreshToken(int $uid, array $claim = []): string
    {
        $time  = time();
        $jti   = $this->createJti($uid);
        $token = (new Jwt())->setKey($this->key)
            ->setIat($time)
            ->setExp($time + $this->refreshTokenExp)// 过期时间
            ->setJti($jti)// tokenID
            ->setUid($uid);// 用户ID
        // 附加参数
        if (count($claim) > 0) {
            foreach ($claim as $c_key => $c_value) {
                $token = $token->setClaim($c_key, $c_value);
            }
        }

        try {
            return $token->getToken();
        } catch (JwtException $e) {
            throw  new ApiServiceException('生成token失败，信息：' . $e->getMessage());
        }
    }

    /**
     * 验证token
     * @param $token
     * @return Jwt
     * @throws ApiServiceException
     */
    public function checkToken($token): Jwt
    {
        try {
            $check = $this->jwt->setKey($this->key)->checkToken($token);
            if (!$check) {
                throw new ApiServiceException($this->jwt->getMessage());
            }
            // 如果开启了刷新token和重复使用检查
            if ($this->enableRefreshToken && $this->reuseCheck && $this->needLoginAgain($this->jwt)) {
                throw  new ApiServiceException('需重新登录');
            }
            return $this->jwt;

        } catch (JwtException $e) {
            throw  new ApiServiceException($e->getMessage());
        }
    }

    /**
     * @param $refresh_token
     * @return array
     * @throws ApiServiceException
     */
    public function refreshToken($refresh_token): array
    {
        // 判断是否开启刷新功能
        if (!$this->enableRefreshToken) {
            throw new ApiServiceException('未开启token刷新功能');
        }

        // 启用刷新的话access_token的有效期应该短，refresh_token的有效期长
        if ($this->exp >= $this->refreshTokenExp) {
            throw new ApiServiceException('access_token有效期配置超过refresh_token');
        }

        // 检查token的合法性
        $jwt = $this->checkToken($refresh_token);
        if (!$jwt) {
            throw new ApiServiceException($this->jwt->getMessage());
        }

        // 如果开启了重复使用检查
        if ($this->reuseCheck) {
            $jti  = $jwt->getJti();
            $used = $this->isRefreshTokenBlacklist($jti);
            if ($used) {
                // 如果此refresh_token已经被使用过了,此用户必须重新登录，
                $this->setLoginAgain($jwt->getUid());
                throw new ApiServiceException('refresh_token被重复使用');
            } else {
                $this->addRefreshBlacklist($jwt);
            }
        }

        return [
            'access_token'  => $this->getAccessToken($jwt->getUid()),
            'refresh_token' => $this->getRefreshToken($jwt->getUid()),
        ];
    }

    /**
     * 创建jwt的ID
     * @param $uid
     * @return string
     */
    public function createJti($uid): string
    {
        return sha1($uid . DateService::microTimestamp() . uniqid('jwt_' . $uid, true));
    }

    /**
     * 检查jti是否在黑名单
     * @param $jti
     * @return bool
     */
    public function isRefreshTokenBlacklist($jti): bool
    {
        $blacklist_key = $this->refreshTokenBlacklistKeyPrefix . $jti;
        return Cache::has($blacklist_key);
    }

    /**
     * 将jti加入黑名单
     * @param Jwt $jwt
     * @return bool
     */
    public function addRefreshBlacklist($jwt): bool
    {
        $time          = time();
        $blacklist_key = $this->refreshTokenBlacklistKeyPrefix . $jwt->getJti();
        $value         = [
            'time' => $time,
            'uid'  => $jwt->getUid(),
        ];
        return Cache::set($blacklist_key, $value, $jwt->getExp() - $time + 1);
    }

    /**
     * 设置用户必须重新登录，添加1秒的防护机制
     * @param $uid
     * @return bool
     */
    public function setLoginAgain($uid): bool
    {
        $login_again_key = $this->loginAgainKeyPrefix . $uid;
        return Cache::set($login_again_key, time() + 1, $this->refreshTokenExp + 1);
    }

    /**
     * 检查是否需要重新登录
     * @param Jwt $jwt
     * @return bool
     */
    public function needLoginAgain(Jwt $jwt): bool
    {
        $login_again_key = $this->loginAgainKeyPrefix . $jwt->getUid();
        if (Cache::has($login_again_key)) {
            $time = Cache::get($login_again_key);
            $iat  = $jwt->getIat();
            // 如果当前token签发时间早于重用记录时间，证明token已失效
            if ($iat <= $time) {
                return true;
            }
        }
        return false;
    }

    /**
     * 是否开启了token刷新
     * @return bool
     */
    public function isEnableRefreshToken(): bool
    {
        return $this->enableRefreshToken;
    }

    /**
     * 从黑名单里删除token
     * @param $jti
     * @return bool
     */
    public function delRefreshBlacklist($jti): bool
    {
        $blacklist_key = $this->refreshTokenBlacklistKeyPrefix . $jti;
        return Cache::delete($blacklist_key);
    }

    /**
     * 清除需要重新登录的标记
     * @param $uid
     * @return bool
     */
    public function clearLoginAgain($uid): bool
    {
        $login_again_key = $this->loginAgainKeyPrefix . $uid;
        return Cache::delete($login_again_key);
    }
}
