<?php
const URL_CURRENT = 'url://current';
const URL_RELOAD = 'url://reload';
const URL_BACK = 'url://back';

use think\exception\HttpResponseException;
use think\Response;
use think\response\Redirect;


if (!function_exists('index_success')) {

    function index_success($msg = '操作成功', $url = URL_BACK, $data = '', $wait = 0, array $header = [])
    {
        index_result(1, $msg, $data, $url, $wait, $header);
    }
}


if (!function_exists('index_error')) {
    function index_error($msg = '操作失败', $url = URL_CURRENT, $data = '', $wait = 0, array $header = [])
    {
        index_result(0, $msg, $data, $url, $wait, $header);
    }
}

if (!function_exists('index_result')) {
    function index_result($code = 0, $msg = 'unknown', $data = '', $url = null, $wait = 3, array $header = [])
    {
        if (request()->isPost()) {
            $url = (strpos($url, '://') || 0 === strpos($url, '/')) ? $url : url($url);
            $result   = [
                'code' => $code,
                'msg'  => $msg,
                'data' => $data,
                'url'  => $url,
                'wait' => $wait,
            ];
            $response = Response::create($result, 'json')->header($header);
            throw new HttpResponseException($response);
        }


        if ($url === null) {
            $url = request()->server('HTTP_REFERER') ?? 'index/index/index';
        }

        $response = new Redirect($url);

        $response->code(302)->params($data)->with([$code ? 'success_message' : 'error_message' => $msg, 'url' => $url]);

        throw new HttpResponseException($response);
    }
}