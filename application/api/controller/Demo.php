<?php

/**
 * 接口示例
 * @author yupoxiong <i@yufuping.com>
 */

namespace app\api\controller;

use app\common\model\AdminLogs;
use app\common\model\AdminUsers;
use think\Validate;

class Demo extends Api
{
    protected $user_validate=[
        ['user_name|帐号', 'require', '帐号不能为空'],
        ['password|密码', 'require', '密码不能为空'],

    ];
    //示例 数据取操作日志
    public function index()
    {
        $model = new AdminLogs();

        $data_list = $model
            ->order('id desc')
            ->limit(10)
            ->select();
        return $this->api_success('success', 200, $data_list);
    }

    //检查token
    public function check_token()
    {
        //判断登录
        if ($this->token_uid < 1) {
            return $this->api_error('未登录或登录失效');
        }
        return $this->api_success('目前是登录状态');
    }


    //登录方法示例
    //登录
    public function login()
    {
        if (!$this->request->isPost()) {
            return $this->api_error('请使用post访问本接口');
        }
        $post     = $this->post;
        $validate = new Validate($this->user_validate);
        if (!$validate->check($post)) {
            $msg = $validate->getError();
            return $this->api_error($msg);
        }

        $user_name = $post['user_name'];
        $password  = $post['password'];
        $users     = new AdminUsers();
        $where     = [
            'user_name'=>$user_name,
            'password' => md5($password)
        ];

        $user = $users->where($where)->find();
        if ($user) {

            //关于以下参数网上有介绍
            $token_data = [
                "iss" => "https://bearadmin.yufuping.com",
                "aud" => "bear_admin_user",
                "iat" => time(),
                "nbf" => time(),
                'exp' => 2145888000,
            ];

            //创建token
            $user_info = [
                'uid' => $user['user_id'],
            ];

            $token_data['user'] = $user_info;

            $token = $this->enToken($token_data, $this->app_key);
            $data  = ['token' => $token];
            return $this->api_success('登录成功', 200, $data);
        }

        return $this->api_error('密码错误或用户不存在');
    }

}