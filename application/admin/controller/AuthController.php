<?php
/**
 * 后台登录退出控制器
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\controller;

use Exception;
use think\Request;
use app\admin\model\AdminUser;
use app\admin\validate\AdminUserValidate;

class AuthController extends Controller
{

    //登录
    public function login(Request $request, AdminUser $model, AdminUserValidate $validate)
    {
        if ($request->isPost()) {

            $param           = $request->param();
            $validate_result = $validate->scene('login')->check($param);
            if (!$validate_result) {
                return error($validate->getError());
            }

            try {
                $user = $model::login($param);
            } catch (Exception $e) {
                return error($e->getMessage());
            }

            $remember = isset($param['remember']) ? true : false;
            self::authLogin($user, $remember);

            $redirect = session('redirect') ?? url('admin/index/index');

            return success('登录成功', $redirect);
        }
        $this->admin['title'] = '登录';
        return $this->fetch();
    }

    //退出
    public function logout()
    {
        self::authLogout();
        return redirect(url('admin/auth/login'));
    }
}
