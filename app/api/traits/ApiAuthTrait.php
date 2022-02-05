<?php
/**
 * api模模块鉴权trait
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);

namespace app\api\traits;

use app\api\exception\ApiServiceException;
use think\exception\HttpResponseException;
use app\api\service\TokenService;
use app\common\model\User;

trait ApiAuthTrait
{
    /**
     * 检查登录
     */
    protected function checkLogin(): void
    {
        $tokenService = new TokenService();

        $request = request();
        // 获取当前访问url,应用名+'/'+控制器名+'/'+方法名
        $this->url = $url = parse_name(app('http')->getName())
            . '/' . parse_name($request->controller())
            . '/' . parse_name($request->action());

        $login_except = !empty($this->loginExcept) ? array_map('parse_name', $this->loginExcept) : $this->loginExcept;

        if (!in_array($url, $login_except, true)) {
            $token_position = config('api.auth.token_position');
            $token_field    = config('api.auth.token_field');
            if ($token_position === 'header') {
                $token = request()->header($token_field, 'token');
            } else {
                $token = request()->param($token_field, 'token');
            }

            // 缺少token
            if (empty($token)) {
                throw new HttpResponseException(api_unauthorized('未登录'));
            }

            // 验证token
            try {
                $result = $tokenService->checkToken($token);
                if (!$result) {
                    // token验证失败
                    throw new HttpResponseException(api_unauthorized('验证token失败，信息：' . $tokenService->jwt->getMessage()));
                }
                // 验证通过赋值用户ID
                $this->uid = (int)$result->getUid();
            } catch (ApiServiceException $e) {
                throw new HttpResponseException(api_unauthorized('验证token失败，信息：' . $e->getMessage()));
            }

            /** @var User $user */
            $user = (new User)->findOrEmpty($this->uid);
            if ($user->isEmpty()) {
                throw new HttpResponseException(api_unauthorized('用户不存在'));
            }

            if ($user->status === 0) {
                throw new HttpResponseException(api_error('账号被冻结'));
            }

            $this->user = $user;
        }
    }

    /**
     * 检查权限
     */
    public function checkAuth(): void
    {
        if (in_array(request()->action(), $this->authExcept, true)) {
            // TODO::这里可以自定义权限检查

        }

    }


}