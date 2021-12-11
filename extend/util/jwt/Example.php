<?php
/**
 * jwt调用示例
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);


namespace util\jwt;


use app\common\exception\CommonServiceException;
use app\common\service\DateService;
use app\common\service\StringService;
use JsonException;

class Example
{

    /**
     * HS加密方式生成及验证token
     * 当前为HS256，其他同类加密可自行修改
     * @throws JwtException
     * @throws CommonServiceException
     * @throws JsonException
     */
    public function testHSToken(): void
    {
        $time = time();
        echo '<h3>开始生成token</h3>';
        // 用户ID
        $uid = 111;

        // 初始化jwt
        $jwt = new Jwt();
        // 设置加密方式为sha256
        $jwt->setAlg('HS256');
        echo '加密方式：SHA256<br/>';
        // 加密key
        $key = '123456';
        echo '加密key：' . $key . '<br/>';
        // 设置加密key
        $jwt->setKey($key);
        // 设置用户ID
        $jwt->setUid($uid);
        echo '设置用户uid：' . $uid . '<br/>';
        // 生成唯一的jti
        $jti = sha1($uid . StringService::getRandString() . DateService::microTimestamp());
        // 设置jti
        $jwt->setJti($jti);

        // 以下为非必须配置，可自行决定是否添加
        // 添加自定义header
        $jwt->setHeader('haha', '123');
        // 设置签发人
        $jwt->setIss('server');
        // 设置签发时间
        $jwt->setIat($time);
        // 设置使用人
        $jwt->setAud('client');
        // 设置可用时间
        $jwt->setNbf($time);
        // 设置1小时后过期
        $jwt->setExp($time + 3600);
        // 自定义claim
        $jwt->setClaim('hi001','001');

        $token = $jwt->getToken();
        echo '生成的token为：' . ($token) . '<br/><br/>';

        echo '<h3>开始验证token</h3>';
        echo '需要验证的token为：' . $token,'<br/>';

        $jwt1 = new Jwt();
        $result = $jwt1->setKey($key)->checkToken($token);

        if ($result) {
            echo ('验证成功') . '<br/>';
            $uid = $jwt1->getUid();
            echo 'uid：' . $uid . '<br/>';
            $jti = $jwt1->getJti();
            echo 'jwt的ID：'.$jti.'<br/>';
            echo '取出自定义的claim hi001的值：'.$jwt1->getClaim('hi001').'<br/>';

            echo 'header信息：' . json_encode($jwt1->getHeader(), JSON_THROW_ON_ERROR). '<br/>';
            echo 'payload信息：' . json_encode($jwt1->getPayload(), JSON_THROW_ON_ERROR). '<br/>';

            $iss = $jwt1->getIss();
            $aud = $jwt1->getAud();
        } else {

            echo '验证失败,提示信息：' . $jwt1->getMessage() . '<br/>';
        }
        echo '<br/><hr/><br/>';
    }

    /**
     * RS加密方式生成及验证token
     * 当前为RS256，其他同类加密可自行修改
     * @throws JwtException
     * @throws CommonServiceException
     * @throws JsonException
     */
    public function testRSToken(): void
    {
        $time = time();
        echo '<h3>开始生成token</h3>';
        // 用户ID
        $uid = 222;
        // 初始化jwt
        $jwt = new Jwt();
        // 设置加密方式为RSA256
        $jwt->setAlg('RS256');
        echo '加密方式：RSA256<br/>';
        // 私钥
        $private_key = file_get_contents(app()->getRootPath().'extend/util/jwt/private.key');
        echo '私钥：' . $private_key . '<br/>';
        // 设置加密key
        $jwt->setPrivateKey($private_key);
        // 设置用户ID
        $jwt->setUid($uid);
        echo '设置用户uid：' . $uid . '<br/>';
        // 生成唯一的jti
        $jti = sha1($uid . StringService::getRandString() . DateService::microTimestamp());
        // 设置jti
        $jwt->setJti($jti);
        // 以下为非必须配置，可自行决定是否添加
        // 添加自定义header
        $jwt->setHeader('haha', '456');
        // 设置签发人
        $jwt->setIss('server');
        // 设置签发时间
        $jwt->setIat($time);
        // 设置使用人
        $jwt->setAud('client');
        // 设置可用时间
        $jwt->setNbf($time);
        // 设置1小时后过期
        $jwt->setExp($time + 3600);
        // 自定义claim
        $jwt->setClaim('hi002','002');

        $token = $jwt->getToken();
        echo '生成的token为：' . ($token) . '<br/><br/>';


        echo '<h3>开始验证token</h3>';
        echo '需要验证的token为：' . $token;

        $jwt1 = new Jwt();
        $public_key = file_get_contents(app()->getRootPath().'extend/util/jwt/public.key');
        $result = $jwt1->setPublicKey($public_key)->checkToken($token);

        if ($result) {
            echo ('验证成功') . '<br/>';
            $uid = $jwt1->getUid();
            echo 'uid：' . $uid . '<br/>';
            $jti = $jwt1->getJti();
            echo 'jwt的ID：'.$jti.'<br/>';
            echo '取出自定义的claim hi002的值：'.$jwt1->getClaim('hi002').'<br/>';
            echo 'header信息：' . json_encode($jwt1->getHeader(), JSON_THROW_ON_ERROR). '<br/>';
            echo 'payload信息：' . json_encode($jwt1->getPayload(), JSON_THROW_ON_ERROR). '<br/>';
        } else {

            echo '验证失败,提示信息：' . $jwt1->getMessage() . '<br/>';
        }

        echo '<br/><hr/><br/>';
    }

}