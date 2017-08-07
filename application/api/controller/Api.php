<?php
/**
 * Api基础类
 * @author yupoxiong
 * 默认返回数据格式 {status: 404, extend: {}, result: {}, message: "", timestamp: 1488793997}
 * status: 200成功，
 */

namespace app\api\controller;

use think\Response;

class Api
{
    //定义返回的变量
    protected $api_result;

    function __construct()
    {

        $this->api_result = [
            'status'    => 200,
            'extend'    => [],
            'result'    => [],
            'message'   => '',
            'timestamp' => time()
        ];
    }


    public function index(){
        $data = [
            'name'=>'lucy',
            'cc'  => '333'
        ];

        return Response::create($data,'json',403);

    }

    protected function header_200(){
        header("status: 200 OK");
    }

    //用户未认证，请求失败
    protected function header_401(){
        header("status: 401 Unauthorized");
    }

    //用户无权限访问该资源，请求失败
    protected function header_403(){
        header("status: 403 Forbidden");
    }

    //请求的资源不存在
    protected function header_404(){
        header("status: 404 Not Found");
    }


    //请求被服务器正确解析，但是包含无效字段
    protected function header_422(){
        header("status: 404 Unprocessable Entity");
    }

    //因为访问频繁，你已经被限制访问，稍后重试
    protected function header_429(){
        header("status: 429 Too Many Requests");
    }

    //请求的接口不存在
    protected function header_500(){
        header("status: 500 Internal Server Error");
    }
    
    protected function default_404(){
        $this->header_404();
        $this->api_result['status'] = 404;
    }

    /**
     * 空方法
     */
    public function _empty()
    {
        $this->default_404();
        return $this->api_result;
    }
}
