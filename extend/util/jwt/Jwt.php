<?php
/**
 * jwt实现
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);

namespace util\jwt;

use JsonException;

class Jwt
{
    public const ALG = [
        'HS256' => 'sha256',
        'HS384' => 'sha384',
        'HS512' => 'sha521',
        'RS256' => OPENSSL_ALGO_SHA256,
        'RS384' => OPENSSL_ALGO_SHA384,
        'RS512' => OPENSSL_ALGO_SHA512,
    ];

    // 头部
    protected array $header = [
        'alg' => 'HS256',// 生成signature的算法
        'typ' => 'JWT',// 类型
    ];

    /**
     * @var string 签名方式
     */
    protected string $alg = 'HS256';
    // 负载
    protected array $payload = [];
    // 加密key
    protected string $key = '';
    // 错误消息
    protected string $message = '';
    // 私钥
    protected string $privateKey = '';
    // 公钥
    protected string $publicKey = '';
    // 当前token
    protected string $token;


    /**
     * 设置key
     * @param $key
     * @return Jwt
     */
    public function setKey($key): Jwt
    {
        $this->key = $key;
        return $this;
    }

    /**
     * 获取header
     * @param string $name 不传为获取header整体内容
     * @return array
     */
    public function getHeader(string $name = ''): array
    {
        if ($name === '') {
            return $this->header;
        }
        return $this->header[$name];
    }

    /**
     * 设置header
     * @param mixed $name 传入array为替换整体header
     * @param string $value
     * @return Jwt
     */
    public function setHeader($name, string $value = ''): Jwt
    {
        if (is_array($name)) {
            $this->header = $name;
            return $this;
        }

        $this->header[$name] = $value;
        return $this;
    }

    /**
     * 获取当前设置的uid
     * @return mixed
     */
    public function getUid()
    {
        return $this->getClaim('uid');
    }

    /**
     * 设置uid
     * @param $uid
     * @return $this
     */
    public function setUid($uid): Jwt
    {
        return $this->setClaim('uid', $uid);
    }

    /**
     * 获取当前设置的jti
     * @return mixed
     */
    public function getJti()
    {
        return $this->getClaim('jti');
    }

    /**
     * 设置jti
     * @param $jti
     * @return $this
     */
    public function setJti($jti): Jwt
    {
        return $this->setClaim('jti', $jti);
    }


    /**
     * 设置claim
     * @param $name
     * @param $value
     * @return Jwt
     */
    public function setClaim($name, $value): Jwt
    {
        $this->payload[$name] = $value;
        return $this;
    }

    /**
     * 获取claim
     * @param $name
     * @return mixed
     */
    public function getClaim($name)
    {
        return $this->payload[$name];
    }

    /**
     * 获取payload
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * @param array $payload
     * @return Jwt
     */
    public function setPayload(array $payload): Jwt
    {
        $this->payload = $payload;

        foreach ($payload as $key => $item) {
            if ($key === 'iat') {
                $this->setIat($item);
            }
            if ($key === 'aud') {
                $this->setAud($item);
            }
            if ($key === 'iss') {
                $this->setIss($item);
            }
            if ($key === 'nbf') {
                $this->setNbf($item);
            }
            if ($key === 'exp') {
                $this->setExp($item);
            }
            if ($key === 'uid') {
                $this->setUid($item);
            }
        }

        return $this;
    }

    /**
     * 获取过期时间
     * @return mixed
     */
    public function getExp()
    {
        return $this->getClaim('exp');
    }

    /**
     * 设置过期时间
     */
    public function setExp($value): Jwt
    {
        return $this->setClaim('exp', $value);
    }

    /**
     * 获取可使用时间
     * @return mixed
     */
    public function getNbf()
    {
        return $this->getClaim('nbf');
    }

    /**
     * @param $value
     * @return $this
     */
    public function setNbf($value): Jwt
    {
        $this->setClaim('nbf', $value);
        return $this;
    }

    /**
     * 获取签发者
     * @return mixed
     */
    public function getIss()
    {
        return $this->getClaim('iss');
    }

    /**
     *
     * @param $value
     * @return $this
     */
    public function setIss($value): Jwt
    {
        $this->setClaim('iss', $value);
        return $this;
    }

    /**
     * 获取使用者
     * @return mixed
     */
    public function getAud()
    {
        return $this->getClaim('aud');
    }

    /**
     * 设置使用者
     * @param $value
     * @return $this
     */
    public function setAud($value): Jwt
    {
        $this->setClaim('aud', $value);
        return $this;
    }

    /**
     * 获取签发时间
     * @return mixed
     */
    public function getIat()
    {
        return $this->getClaim('iat');
    }

    /**
     * 设置签发时间
     * @param $value
     * @return $this
     */
    public function setIat($value): Jwt
    {
        $this->setClaim('iat', $value);
        return $this;
    }


    /**
     * 获取jwt的token
     * @return string
     * @throws JwtException
     */
    public function getToken(): string
    {
        $this->checkSignKey();

        $header_str = $this->base64UrlEncode($this->jsonEncode($this->getHeader()));

        $payload_str = $this->base64UrlEncode($this->jsonEncode($this->getPayload()));

        return $header_str . '.' . $payload_str . '.' . $this->signature($header_str . '.' . $payload_str);
    }

    /**
     * 检查签名key
     * @throws JwtException
     */
    protected function checkSignKey(): bool
    {
        switch ($this->getAlg()) {
            case 'HS256':
            case 'HS384':
            case 'HS512':
            default:
                if (empty($this->getKey())) {
                    throw new JwtException('请设置加密key');
                }
                break;
            case 'RS256':
            case 'RS384':
            case 'RS512':
                if (empty($this->getPrivateKey())) {
                    throw new JwtException('请设置私钥');
                }
                break;
        }

        return true;
    }

    /**
     * 验证token
     * @param $token
     * @return bool
     * @throws JwtException
     */
    public function checkToken($token): bool
    {
        $time  = time();
        $array = explode('.', $token);
        if (count($array) !== 3) {
            throw new JwtException('token格式错误');
        }

        [$header, $payload, $sign] = $array;

        $header_array = $this->jsonDecode($this->base64UrlDecode($header));
        if (!isset($header_array['alg'])) {
            throw new JwtException('未定义签名算法');
        }

        $this->setAlg($header_array['alg']);

        // 签名验证
        $this->checkSign($header, $payload, $sign);

        $this->header = $header_array;

        $payload_array = $this->jsonDecode($this->base64UrlDecode($payload));

        $this->setPayload($payload_array);

        // 签发时间验证
        if (isset($this->payload['iat']) && $this->getIat() > $time) {
            $this->setMessage('签发时间晚于当前时间');
            return false;
        }

        // 未到使用时间验证
        if (isset($this->payload['nbf']) && $this->getNbf() > $time) {
            $this->setMessage('未到使用时间');
            return false;
        }

        // 过期时间验证
        if (isset($this->payload['exp']) && $this->getExp() < $time) {
            $this->setMessage('token已过期');
            return false;
        }

        return true;
    }

    /**
     * 检查签名
     * @param $header
     * @param $payload
     * @param $sign
     * @return bool
     * @throws JwtException
     */
    public function checkSign($header, $payload, $sign): bool
    {
        switch ($this->getAlg()) {
            case 'HS256':
            case 'HS384':
            case 'HS512':
            default:
                // 直接加密，进行对比即可
                if ($this->signature($header . '.' . $payload) !== $sign) {
                    throw new JwtException('签名验证失败');
                }
                break;
            case 'RS256':
            case 'RS384':
            case 'RS512':
                if (empty($this->getPublicKey())) {
                    throw new JwtException('请设置公钥');
                }

                if (!$this->verifyRsaSign($header . '.' . $payload, $sign)) {
                    throw new JwtException('签名验证失败');
                }

                break;
        }

        return true;
    }

    /**
     * 验证签名
     * @param $data
     * @param $sign
     * @return bool
     */
    public function verifyRsaSign($data, $sign): bool
    {
        $signature = $this->base64UrlDecode($sign);
        $publicKey = openssl_get_publickey($this->getPublicKey());
        $res       = openssl_verify($data, $signature, $publicKey, self::ALG[$this->getAlg()]);
        openssl_free_key($publicKey);

        return $res === 1;
    }


    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }


    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }


    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }


    /**
     * 签名
     * @param $input
     * @return string
     */
    protected function signature($input): string
    {
        $alg = $this->getAlg();

        switch ($alg) {
            case 'HS256':
            case 'HS384':
            case 'HS512':
            default:
                $result = $this->base64UrlEncode(hash_hmac(self::ALG[$alg], $input, $this->getKey(), true));
                break;
            case 'RS256':
            case 'RS384':
            case 'RS512':
                $result = openssl_sign($input, $sign, $this->getPrivateKey(), self::ALG[$alg]) ? $this->base64UrlEncode($sign) : '';
                break;
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getPrivateKey(): string
    {
        return $this->privateKey;
    }

    /**
     * @param string $privateKey
     * @return Jwt
     */
    public function setPrivateKey(string $privateKey): Jwt
    {
        $this->privateKey = $privateKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    /**
     * @param string $publicKey
     * @return Jwt
     */
    public function setPublicKey(string $publicKey): Jwt
    {
        $this->publicKey = $publicKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getAlg(): string
    {
        return $this->alg;
    }


    /**
     * @param string $alg
     * @return Jwt
     */
    public function setAlg(string $alg): Jwt
    {
        $this->alg = $alg;

        $this->header['alg'] = $alg;

        return $this;
    }




    /**
     * base64url解码
     * @param string $input
     * @return false|string
     */
    protected function base64UrlDecode(string $input)
    {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $append_length = 4 - $remainder;
            $input         .= str_repeat('=', $append_length);
        }
        return base64_decode(strtr($input, '-_', '+/'));
    }

    /**
     * base64url编码
     * @param string $data
     * @return string
     */
    protected function base64UrlEncode(string $data): string
    {
        return str_replace('=', '', strtr(base64_encode($data), '+/', '-_'));
    }


    /**
     * 转换成json
     * @param mixed $data
     * @return string
     * @throws JwtException
     */
    protected function jsonEncode($data): string
    {
        try {
            return json_encode($data, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new JwtException($e->getMessage());
        }
    }

    /**
     * json转换为数组
     * @param string $data
     * @return mixed
     * @throws JwtException
     */
    protected function jsonDecode(string $data)
    {
        try {
            return json_decode($data, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new JwtException($e->getMessage());
        }
    }
}