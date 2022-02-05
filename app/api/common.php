<?php
/**
 * api模块公共函数
 * @author yupoxiong<i@yupoxiong.com>
 */

use think\response\Json;

if (!function_exists('api_unauthorized')) {
    /**
     * 未认证（未登录）
     * @param string $msg
     * @param mixed $data
     * @param int $code
     * @return Json
     */
    function api_unauthorized(string $msg = 'unauthorized', $data = [], int $code = 401): Json
    {
        return api_result($msg, $data, $code);
    }
}

if (!function_exists('api_forbidden')) {
    /**
     * 无权限
     * @param string $msg
     * @param mixed $data
     * @param int $code
     * @return Json
     */
    function api_forbidden(string $msg = 'forbidden', $data = [], int $code = 403): Json
    {
        return api_result($msg, $data, $code);
    }
}


if (!function_exists('api_success')) {
    /**
     * 操作成功
     * @param mixed $data
     * @param string $msg
     * @param int $code
     * @return Json
     */
    function api_success(array $data = [], string $msg = 'success', int $code = 200): Json
    {
        return api_result($msg, $data, $code);
    }
}

if (!function_exists('api_error')) {
    /**
     * 操作失败
     * @param string $msg
     * @param mixed $data
     * @param int $code
     * @return Json
     */
    function api_error(string $msg = 'fail', $data = [], int $code = 500): Json
    {
        return api_result($msg, $data, $code);
    }
}

if (!function_exists('api_result')) {
    /**
     * 返回json结果
     * @param string $msg
     * @param mixed $data
     * @param int $code
     * @return Json
     */
    function api_result(string $msg = 'fail', $data = [], int $code = 500): Json
    {
        if (is_array($data) && empty($data)) {
            $data = (object)$data;
        }
        $header = [];
        // http code是否同步业务code
        $http_code = config('api.response.http_code_sync') ? $code : 200;

        return json([
            'code' => $code,
            'msg'  => $msg,
            'data' => $data,
        ], $http_code, $header);
    }
}


if (!function_exists('api_service_unavailable')) {
    /**
     * 系统维护中
     * @param string $msg
     * @param mixed $data
     * @param int $code
     * @return Json
     */
    function api_service_unavailable(string $msg = 'service unavailable', $data = [], int $code = 503): Json
    {
        return api_result($msg, $data, $code);
    }
}


if (!function_exists('api_error_client')) {
    /**
     * 客户端错误 例如提交表单的时候验证不通过，是因为客户填写端错误引起的
     * @param string $msg
     * @param mixed $data
     * @param int $code
     * @return Json
     */
    function api_error_client(string $msg = 'client error', $data = [], int $code = 400): Json
    {
        return api_result($msg, $data, $code);
    }
}

if (!function_exists('api_error_server')) {
    /**
     * 服务端错误
     * @param string $msg
     * @param mixed $data
     * @param int $code
     * @return Json
     */
    function api_error_server(string $msg = 'server error', $data = [], int $code = 500): Json
    {
        return api_result($msg, $data, $code);
    }
}

if (!function_exists('api_error_404')) {
    /**
     * 资源或接口不存在
     * @param string $msg
     * @param mixed $data
     * @param int $code
     * @return Json
     */
    function api_error_404(string $msg = '404 not found', $data = [], int $code = 404): Json
    {
        return api_result($msg, $data, $code);
    }
}
