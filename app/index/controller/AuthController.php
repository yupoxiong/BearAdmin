<?php
/**
 * 前台登录退出
 */

namespace app\index\controller;

use app\common\model\User;
use app\common\validate\UserValidate;
use app\index\exception\IndexServiceException;
use app\index\service\AuthService;
use think\Request;

class AuthController extends IndexBaseController
{
    protected array $loginExcept=[
        'index/auth/login'
    ];


    //登录
    public function login(Request $request,AuthService $service,UserValidate $validate)
    {
        $param = $request->param();

        //登录逻辑
        if($request->isPost()){

            $check = $validate->scene('index_login')->check($param);
            if (!$check) {
                return index_error($validate->getError());
            }
            try {
                $user = $service->login($param['username'], $param['password']);
                self::authLogin($user,(bool)($param['remember']??false));
                return  index_success('登录成功','index/index',$user);
            } catch (IndexServiceException $e) {
                return  index_error($e->getMessage(),URL_RELOAD);
            }
        }


        return $this->fetch();
    }


    //退出
    public function logout()
    {
        self::authLogout();

        return redirect(url('index/auth/login'));
    }
}