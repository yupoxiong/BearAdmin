<?php
/**
 * 后台登录退出控制器
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\controller;

use Exception;
use think\captcha\Captcha;
use think\Request;
use app\admin\model\AdminUser;
use app\admin\validate\AdminUserValidate;
use think\Validate;
use tools\GeeTest;

class AuthController extends Controller
{

    protected $authExcept = [
        'admin/auth/login',
        'admin/auth/logout',
        'admin/auth/captcha',
        'admin/auth/initgeetest',

    ];

    //登录
    public function login(Request $request, AdminUser $model, AdminUserValidate $validate)
    {
        if ($request->isPost()) {

            $login_config = config('admin.login');

            $param = $request->param();

            //如果需要验证码
            if ($login_config['captcha'] > 0) {

                if ($login_config['captcha'] == 1) {
                    if (!captcha_check($param['captcha'])) {
                        return admin_error('验证码错误');
                    }
                } else if ($login_config['captcha'] == 2) {

                    $config  = config('geetest.');
                    $geeTest = new GeeTest($config['id'], $config['key']);

                    $data = array(
                        'user_id'     => session('gt_uid'),
                        'client_type' => 'web',
                        'ip_address'  => $request->ip(),
                    );
                    if (session('gt_server') == 1) {
                        $gee_test_result = $geeTest->successValidate($param['geetest_challenge'], $param['geetest_validate'], $param['geetest_seccode'], $data);
                        if (!$gee_test_result) {
                            return admin_error('验证失败');
                        }
                    } else {
                        if (!$geeTest->failValidate($param['geetest_challenge'], $param['geetest_validate'])) {
                            return admin_error('验证失败');
                        }
                    }
                }
            }

            $validate_result = $validate->scene('login')->check($param);
            if (!$validate_result) {
                return admin_error($validate->getError());
            }

            //如果需要验证登录token
            if ($login_config['token']) {
                $token_validate        = Validate::make();
                $token_validate_result = $token_validate->rule('__token__', 'token')
                    ->check($param);
                if (!$token_validate_result) {
                    return admin_error($token_validate->getError());
                }
            }

            try {
                $user = $model::login($param);
            } catch (Exception $e) {
                return admin_error($e->getMessage());
            }

            $remember = isset($param['remember']) ? true : false;
            self::authLogin($user, $remember);

            $redirect = session('redirect') ?? url('admin/index/index');

            return admin_success('登录成功', $redirect);
        }
        $this->admin['title'] = '登录';

        $this->assign([
            //登录设置，参考/config/admin/admin.php文件配置
            'login_config' => config('admin.login'),
        ]);


        return $this->fetch();
    }

    //退出
    public function logout()
    {
        self::authLogout();
        return redirect(url('admin/auth/login'));
    }


    //极验初始化
    public function initGeeTest(Request $request)
    {

        $config  = config('geetest.');
        $geeTest = new GeeTest($config['id'], $config['key']);

        $ip = $request->ip();
        $ug = $request->header('user-agent');

        $data = array(

            'gt_uid'      => md5($ip . $ug),
            'client_type' => 'web',
            'ip_address'  => $ip,
        );

        $status = $geeTest->preProcess($data);

        session('gt_server', $status);
        session('gt_uid', $data['gt_uid']);

        return admin_success($status, URL_CURRENT, $geeTest->getResponse());
    }

    //ThinkPHP 图形验证码
    public function captcha()
    {
        $config  = [
            // 验证码字体大小
            'fontSize' => 30,
            // 验证码位数
            'length'   => 4,
            // 关闭验证码杂点
            'useNoise' => false,
        ];
        $captcha = new Captcha($config);
        return $captcha->entry();
    }
}
