<?php

/**
 * 前台用户中心
 */

namespace app\index\controller;

use app\common\model\User;
use think\Request;

class UserController extends Controller
{
    protected $authExcept=[
        'login'
    ];

    //个人中心首页
    public function index()
    {

        $this->assign([
            'user'=>$this->user
        ]);

        return $this->fetch();
    }


    //登录
    public function login(Request $request,User $model)
    {
        $param = $request->param();

        //登录逻辑
        if($this->request->isPost()){

            try{
                $user = $model::login($param);
            }catch (\Exception $exception){

                return error($exception->getMessage());
            }

            self::authLogin($user);

            return success('登录成功');
        }


        return $this->fetch();
    }


    //退出
    public function logout()
    {
        self::authLogout();

        return redirect(url('index/index'));
    }
}