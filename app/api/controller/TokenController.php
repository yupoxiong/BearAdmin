<?php
/**
 *
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);


namespace app\api\controller;


use app\api\exception\ApiServiceException;
use app\api\service\TokenService;
use think\response\Json;

class TokenController
{

    /**
     * 刷新token，当刷新返回错误的时候需要重新登录
     * @return Json
     */
    public function refresh(): Json
    {
        $param         = request()->param();
        $refresh_token = $param['refresh_token'];
        $service       = new TokenService();

        try {
            $data = $service->refreshToken($refresh_token);
            return api_success($data);
        } catch (ApiServiceException $e) {
            return api_error($e->getMessage());
        }
    }
}