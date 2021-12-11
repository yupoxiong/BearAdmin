<?php
const URL_CURRENT = 'url://current';
const URL_RELOAD  = 'url://reload';
const URL_BACK    = 'url://back';

use think\Response;

if (!function_exists('index_success')) {

    function index_success($msg = '操作成功', $url = URL_BACK, $data = '', $wait = 0, array $header = [])
    {
        return index_result(200, $msg, $data, $url, $wait, $header);
    }
}


if (!function_exists('index_error')) {
    function index_error($msg = '操作失败', $url = URL_CURRENT, $data = '', $wait = 0, array $header = [])
    {
        return index_result(500, $msg, $data, $url, $wait, $header);
    }
}

if (!function_exists('index_result')) {
    function index_result($code = 200, $msg = 'unknown', $data = '', $url = null, $wait = 3, array $header = [])
    {
        $url = (strpos($url, '://') || 0 === strpos($url, '/')) ? $url : url($url)->build();
        if (request()->isPost() || request()->isAjax()) {
            $result = [
                'code' => $code,
                'msg'  => $msg,
                'data' => $data,
                'url'  => $url,
                'wait' => $wait,
            ];
            return Response::create($result, 'json')->header($header);
        }

        if ($url === null) {
            $url = request()->server('HTTP_REFERER') ?? url('index/index/index')->build();;
        }

        return redirect($url);
    }
}