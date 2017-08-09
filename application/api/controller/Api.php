<?php
/**
 * Api基础类
 * @author yupoxiong<i@yufuping.com>
 * @version 1.0
 * 采用JWT认证
 * header auth字段已经改成appauthorization，因为postman对于头部中的authorization字段偶尔会篡改，导致无法很好的测试
 * 默认返回数据格式 {status: 404, extend: {}, result: {}, message: "", timestamp: 1488793997}
 * status 为状态码，extend 为扩展数据 ，result 为结果数据 ，
 * message 为提示消息，timestamp 为接收请求的时间，客户端可利用这个判断网络传输用时
 *
 */

namespace app\api\controller;

use Exception;
use Firebase\JWT\JWT;
use think\Config;
use think\Request;
use think\Response;

class Api
{
    //定义返回的变量
    protected $api_source_prefix, $domain, $request, $token, $get, $post, $api_result, $app_key, $token_user, $token_uid;

    function __construct()
    {
        $this->request = Request::instance();
        $this->token   = $this->request->header('appauthorization');
        $this->get     = $this->request->get();
        $this->post    = $this->request->post();
        $this->domain  = $this->request->domain();

        $this->api_source_prefix = Config::get('api_source_prefix');

        $this->api_result = [
            'status'    => 404,
            'extend'    => [],
            'result'    => [],
            'message'   => '',
            'timestamp' => time()
        ];

        $this->app_key   = Config::get('app_key');
        $this->token_uid = -1;
        if ($this->token != null) {
            try {
                $result           = JWT::decode($this->token, $this->app_key, array('HS256'));
                $this->token_user = $user_data = $result->user;
                $this->token_uid  = $user_data->uid;
            } catch (Exception $e) {
                $this->token_uid             = 0;
                $this->api_result['status']  = 500;
                $this->api_result['message'] = $e->getMessage();
            }
        }
    }

    /**
     * @param string $message
     * @param int $status $api_result中的status
     * @param string $data $api_result中的result
     * @param int $code http状态码
     * @param array $header
     * @param array $option
     * @return Response|\think\response\Json
     */
    function api_success($message = '',$status = 200, $data = '', $code = 200, array $header = [], $option = [])
    {
        $this->api_result['message'] = $message;
        $this->api_result['status'] = $status;
        if (!empty($data)) {
            $this->api_result['result'] = $data;
        }
        return Response::create($this->api_result, 'json', $code, $header, $option);
    }

    /**
     * @param string $message
     * @param int $status $api_result中的status
     * @param string $data $api_result中的result
     * @param int $code http状态码
     * @param array $header
     * @param array $option
     * @return Response|\think\response\Json
     */
    function api_error($message = '',$status = 500, $data = '', $code = 200, array $header = [], $option = [])
    {
        $this->api_result['message'] = $message;
        $this->api_result['status'] = $status;
        if (!empty($data)) {
            $this->api_result['result'] = $data;
        }
        return Response::create($this->api_result, 'json', $code, $header, $option);
    }

    //api返回带http状态的返回
    function api_success_code($data, $status = 404, array $header = [], $option = [])
    {
        $data['status'] = $status;
        return Response::create($data, 'json', $status, $header, $option);
    }

    //不存在的api
    public function _empty()
    {
        return $this->api_error('',404);
        //return Response::create($this->api_result, 'json', 404);
    }

    /**
     * 解析token
     * @param $token
     * @param $key
     * @return bool|object
     * @internal param $jwt
     */
    public static function deToken($token, $key)
    {
        try {
            $result = JWT::decode($token, $key, array('HS256'));
            return $result;
        } catch (Exception $e) {
            //return $e->getMessage();
            return false;
        }
    }

    /**
     * 生成token
     * @param $body
     * @param $key
     * @return string
     */
    public static function enToken($body, $key)
    {
        $jwt = JWT::encode($body, $key);
        return $jwt;
    }

    /**
     * 普通post方法封装
     * @param $url
     * @param $data
     * @return mixed
     */
    public static function http_post($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //对认证证书来源的检查
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    /**
     * 普通get方法封装
     * @param $url
     * @return mixed
     */
    public static function http_get($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}