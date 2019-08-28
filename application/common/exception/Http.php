<?php
/**
 * 接管异常
 */
namespace app\common\exception;

use Exception;
use think\exception\Handle;

class Http extends Handle
{
    public function render(Exception $e)
    {

        //处理api模块异常
        if(request()->module()==='api'){

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
                'code' => 500,
                'msg'  => $e->getMessage(),
                'data' => '',
            ], 500,$header);
        }


        // 其他错误交给系统处理
        return parent::render($e);
    }

}