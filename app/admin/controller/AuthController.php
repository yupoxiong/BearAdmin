<?php
/**
 * 登录退出控制器
 * @author yupoxiong<i@yupoxiong.com>
 */
declare (strict_types=1);

namespace app\admin\controller;

use Exception;
use think\Request;
use think\Response;
use think\response\Json;
use util\geetest\GeeTest;
use think\captcha\facade\Captcha;
use app\admin\service\AuthService;
use think\exception\ValidateException;
use app\admin\validate\AdminUserValidate;
use app\admin\exception\AdminServiceException;

class AuthController extends AdminBaseController
{
    // 无需登录的url
    protected array $loginExcept = [
        'admin/auth/login',
        'admin/auth/captcha',
        'admin/auth/geetest',
        'admin/auth/token',
    ];

    // 无需判断权限的url
    protected array $authExcept = [
        'admin/auth/logout',
    ];

    /**
     * 登录
     * @param Request $request
     * @param AuthService $service
     * @param AdminUserValidate $validate
     * @return string|Json
     * @throws Exception
     */
    public function login(Request $request, AuthService $service, AdminUserValidate $validate)
    {
        $redirect = $request->param('redirect') ?? url('admin/index/index');

        $login_config = setting('admin.login');

        if ($request->isPost()) {
            $param = $request->param();
            try {
                // 验证码形式，0为不验证，1为图形验证码，2为极验
                $captcha = (int)$login_config['captcha'];

                if (($captcha === 1) && !captcha_check($param['captcha'])) {
                    return admin_error('验证码错误');
                }

                $validate->scene('login')->failException(true)->check($param);
                // 检查单设备登录
                $service->checkLoginLimit();

                $username = $param['username'];
                $password = $param['password'];
                $remember = $param['remember'] ?? 0;
                $redirect = $param['redirect'] ?? url('admin/index/index')->build();

                $admin_user = $service->login($username, $password);
                $service->setAdminUserAuthInfo($admin_user, (bool)$remember);
                // 设置当前登录设备标识
                $this->setLoginDeviceId($admin_user);

            } catch (ValidateException$e) {
                $msg = $e->getMessage();
                return admin_error(lang($msg));
            } catch (AdminServiceException $e) {
                // 记录错误次数
                $service->setLoginLimit();
                return admin_error($e->getMessage());
            }

            return admin_success('登录成功', [], $redirect);
        }

        $geetest_config = setting('admin.login');
        $geetest_id     = $geetest_config['geetest_id'] ?? '';

        $this->assign([
            'redirect'     => $redirect,
            'login_config' => $login_config,
            'geetest_id'   => $geetest_id,
        ]);

        return $this->fetch();
    }


    /**
     * 退出
     * @param AuthService $service
     * @return Json
     */
    public function logout(AuthService $service): Json
    {
        $result = $service->logout($this->user);
        $data = [
            'redirect' => url('admin/index/index')->build(),
        ];

        return $result ? admin_success('退出成功', $data) : admin_error();
    }

    /**
     * 图形验证码
     * @return Response
     */
    public function captcha(): Response
    {
        return Captcha::create();
    }

    /**
     * 极验初始化
     * @param Request $request
     * @return Json
     */
    public function geetest(Request $request): Json
    {
        $config  = setting('admin.login');
        $geeTest = new GeeTest($config['geetest_id'], $config['geetest_key']);

        $ip = $request->ip();
        $ug = $request->header('user-agent');
        $data = array(
            'gt_uid'      => md5($ip . $ug),
            'client_type' => 'web',
            'ip_address'  => $ip,
        );

        try {
            $status = $geeTest->preProcess($data);
        } catch (Exception $e) {
            $status = 0;
        }

        session('gt_server', $status);
        session('gt_uid', $data['gt_uid']);

        return admin_success((string)$status, $geeTest->getResponse());
    }

    /**
     * 获取token
     * @param Request $request
     * @return Json
     */
    public function token(Request $request): Json
    {
        return $request->isPost() ? admin_success('', [
            'token' => token()
        ]) : admin_error('非法请求');
    }
}
