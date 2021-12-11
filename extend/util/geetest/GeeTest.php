<?php
/**
 * 对官方提供的代码进行了修改
 */
namespace util\geetest;

use Exception;
use JsonException;

class GeeTest
{
    public const GT_SDK_VERSION = 'php_3.0.0';

    public static int $connectTimeout = 1;
    public static int $socketTimeout = 1;

    private $response;

    protected string $captcha_id;
    protected string $private_key;
    protected string $domain;

    public function __construct($captcha_id, $private_key)
    {
        $this->captcha_id  = $captcha_id;
        $this->private_key = $private_key;
        $this->domain      = 'https://api.geetest.com';
    }

    /**
     * 判断极验服务器是否down机
     * @param $param
     * @param int $new_captcha
     * @return int
     * @throws Exception
     */
    public function preProcess($param, int $new_captcha = 1): int
    {
        $data      = [
            'gt'          => $this->captcha_id,
            'new_captcha' => $new_captcha
        ];
        $data      = array_merge($data, $param);
        $query     = http_build_query($data);
        $url       = $this->domain . '/register.php?' . $query;
        $challenge = $this->sendRequest($url);
        if (strlen($challenge) !== 32) {
            $this->failBackProcess();
            return 0;
        }
        $this->successProcess($challenge);
        return 1;
    }

    /**
     * @param $challenge
     */
    private function successProcess($challenge): void
    {
        $challenge      = md5($challenge . $this->private_key);
        $result         = array(
            'success'     => 1,
            'gt'          => $this->captcha_id,
            'challenge'   => $challenge,
            'new_captcha' => 1
        );
        $this->response = $result;
    }


    /**
     * @throws Exception
     */
    private function failBackProcess(): void
    {
        $rnd1           = md5(random_int(0, 100));
        $rnd2           = md5(random_int(0, 100));
        $challenge      = $rnd1 . substr($rnd2, 0, 2);
        $result         = array(
            'success'     => 0,
            'gt'          => $this->captcha_id,
            'challenge'   => $challenge,
            'new_captcha' => 1
        );
        $this->response = $result;
    }

    /**
     * @return false|string
     * @throws JsonException
     */
    public function getResponseStr()
    {
        return json_encode($this->response, JSON_THROW_ON_ERROR);
    }

    /**
     * 返回数组方便扩展
     *
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * 正常模式获取验证结果
     *
     * @param string $challenge
     * @param string $validate
     * @param string $sec_code
     * @param array $param
     * @param int $json_format
     * @return int
     * @throws JsonException
     */
    public function successValidate(string $challenge, string $validate, string $sec_code, array $param, int $json_format = 1): int
    {
        if (!$this->checkValidate($challenge, $validate)) {
            return 0;
        }
        $query        = array(
            'seccode'     => $sec_code,
            'timestamp'   => time(),
            'challenge'   => $challenge,
            'captchaid'   => $this->captcha_id,
            'json_format' => $json_format,
            'sdk'         => self::GT_SDK_VERSION
        );
        $query        = array_merge($query, $param);
        $url          = $this->domain . '/validate.php';
        $code_validate = $this->postRequest($url, $query);
        $obj          = json_decode($code_validate, true, 512, JSON_THROW_ON_ERROR);
        if ($obj === false) {
            return 0;
        }
        if ($obj['seccode'] === md5($sec_code)) {
            return 1;
        }
        return 0;
    }

    /**
     * 宕机模式获取验证结果
     *
     * @param $challenge
     * @param $validate
     * @return int
     */
    public function failValidate($challenge, $validate): int
    {
        if (md5($challenge) === $validate) {
            return 1;
        }

        return 0;
    }

    /**
     * @param $challenge
     * @param $validate
     * @return bool
     */
    private function checkValidate($challenge, $validate): bool
    {
        if (strlen($validate) !== 32) {
            return false;
        }
        if (md5('geetest' . $this->private_key . $challenge) !== $validate) {
            return false;
        }

        return true;
    }

    /**
     * GET 请求
     * @param $url
     * @return bool|int|string
     */
    private function sendRequest($url)
    {
        if (function_exists('curl_exec')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, self::$connectTimeout);
            curl_setopt($ch, CURLOPT_TIMEOUT, self::$socketTimeout);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $data       = curl_exec($ch);
            $curl_errno = curl_errno($ch);
            curl_close($ch);
            if ($curl_errno > 0) {
                return 0;
            }

            return $data;
        }

        $opts    = array(
            'http' => array(
                'method'  => 'GET',
                'timeout' => self::$connectTimeout + self::$socketTimeout,
            )
        );
        $context = stream_context_create($opts);
        $data    = @file_get_contents($url, false, $context);
        if ($data) {
            return $data;
        }

        return 0;
    }

    /**
     * @param       $url
     * @param array $post_data
     * @return bool|string
     */
    private function postRequest($url, array $post_data = [])
    {
        if (!$post_data) {
            return false;
        }

        $data = http_build_query($post_data);
        if (function_exists('curl_exec')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, self::$connectTimeout);
            curl_setopt($ch, CURLOPT_TIMEOUT, self::$socketTimeout);

            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

            $data = curl_exec($ch);

            if (curl_errno($ch)) {
                $err = sprintf("curl[%s] error[%s]", $url, curl_errno($ch) . ':' . curl_error($ch));
                $this->triggerError($err);
            }

            curl_close($ch);
        } else {
            $opts    = array(
                'http' => array(
                    'method'  => 'POST',
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n" . 'Content-Length: ' . strlen($data) . "\r\n",
                    'content' => $data,
                    'timeout' => self::$connectTimeout + self::$socketTimeout
                )
            );
            $context = stream_context_create($opts);
            $data    = file_get_contents($url, false, $context);
        }

        return $data;
    }

    /**
     * @param $err
     */
    private function triggerError($err): void
    {
        trigger_error($err);
    }
}
