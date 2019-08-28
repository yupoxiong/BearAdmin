<?php

use think\response\Json;

if(!function_exists('success')){
    /**
     * 操作成功
     * @param string $msg
     * @param string $data
     * @param int $code
     * @return Json
     */
    function success($data = '', $msg = 'success', $code = 200)
    {
        return result($msg, $data, $code);
    }
}

if(!function_exists('error')){
    /**
     * 操作失败
     * @param string $msg
     * @param string $data
     * @param int $code
     * @return Json
     */
    function error($msg = 'fail', $data = '', $code = 500)
    {
        return result($msg, $data, $code);
    }
}

if(!function_exists('result')){
    /**
     * 返回json结果
     * @param string $msg
     * @param string $data
     * @param int $code
     * @return Json
     */
    function result($msg = 'fail', $data = '', $code = 500)
    {
        $header = [];
        //处理跨域请求问题
        if (config('api.cross_domain.allow')) {
            $header = ['Access-Control-Allow-Origin' => '*'];
            if (request()->isOptions()) {
                $header = config('api.cross_domain.header');
                return json('',200,$header);
            }
        }

        return json([
            'code' => $code,
            'msg'  => $msg,
            'data' => $data,
        ], $code, $header);
    }
}

if(!function_exists('unauthorized')){
    /**
     * 未授权
     * @param string $msg
     * @param string $data
     * @param int $code
     * @return Json
     */
    function unauthorized($msg = 'unauthorized', $data = '', $code = 401)
    {
        return result($msg, $data, $code);
    }
}

if(!function_exists('client_error')){
    /**
     * 客户端错误
     * @param string $msg
     * @param string $data
     * @param int $code
     * @return Json
     */
    function client_error($msg = 'client error', $data = '', $code = 400)
    {
        return result($msg, $data, $code);
    }
}

if(!function_exists('server_error')){
    /**
     * 服务端错误
     * @param string $msg
     * @param string $data
     * @param int $code
     * @return Json
     */
    function server_error($msg = 'server error', $data = '', $code = 500)
    {
        return result($msg, $data, $code);
    }
}

if(!function_exists('error_404')){
    /**
     * 资源或接口不存在
     * @param string $msg
     * @param string $data
     * @param int $code
     * @return Json
     */
    function error_404($msg = '404 not found', $data = '', $code = 404)
    {
        return result($msg, $data, $code);
    }
}
