<?php
/**
 *
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);

namespace app\api\controller;

use app\common\validate\UserValidate;
use think\Request;
use think\response\Json;
use app\api\service\AuthService;
use app\api\exception\ApiServiceException;

class AuthController
{
    /**
     * @param Request $request
     * @param UserValidate $validate
     * @param AuthService $service
     * @return Json
     */
    public function login(Request $request, UserValidate $validate,AuthService $service): Json
    {
        $param = $request->param();

        $check = $validate->scene('api_login')->check($param);
        if (!$check) {
            return api_error($validate->getError());
        }

        try {

            $username = $param['username'];
            $password = $param['password'];
            $result = $service->usernameLogin($username, $password);

            return api_success($result);
        } catch (ApiServiceException $e) {
            return api_error('登录失败，参考信息：'.$e->getMessage());
        }
    }
}