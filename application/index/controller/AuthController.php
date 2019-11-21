<?php

/**
 * 前台登录退出
 */

namespace app\index\controller;

use app\common\model\User;
use think\Request;

class AuthController extends Controller
{
    protected $authExcept=[
        'login'
    ];


    //登录
    public function login(Request $request,User $model)
    {
        $param = $request->param();

        //登录逻辑
        if($this->request->isPost()){

            try{
                $user = $model::login($param);
            }catch (\Exception $exception){


                return $this->error($exception->getMessage());
            }

            self::authLogin($user);

            return $this->success('登录成功','index/user/index');
        }


        return $this->fetch();
    }


    //退出
    public function logout()
    {
        self::authLogout();

        return redirect(url('index/index/index'));
    }
}