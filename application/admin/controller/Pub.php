<?php
/**
 * 后台PUb控制器,登录退出
 * @author yupoxiong <i@yufuping.com>
 * @version 1.0
 */

namespace app\admin\controller;

use app\common\model\AdminUsers;
use think\Controller;
use app\admin\auth\Auth;
use think\Db;

class Pub extends Controller
{
    private $get, $post;

    public function __construct(\think\Request $request)
    {
        parent::__construct($request);
        $this->get  = $request->get();
        $this->post = $request->post();
    }

    //登录
    public function login()
    {

        if ($this->request->isPost()) {
            $post = $this->post;

            $validate = [
                ['user_name|帐号', 'require|max:25|token'],
                ['password|密码', 'require']
            ];

            //验证
            $result = $this->validate($post, $validate);

            if (true !== $result) {
                return $this->do_error($result);
            }

            $data        = [
                'user_name' => $post['user_name'],
                'password'  => md5($post['password']),
            ];
            $admin_users = new AdminUsers();
            $admin_user  = $admin_users->get($data);

            if ($admin_user) {

                if ($admin_user->getData('status') != 1) {
                    return $this->do_error('账户被冻结');
                }

                if ($post['is_remember'] == 1) {
                    Auth::login($admin_user['user_id'], $admin_user['user_name'], true);
                } else {
                    Auth::login($admin_user['user_id'], $admin_user['user_name'], false);
                }

                //手动加入日志
                $auth = new Auth();
                $this->request->param('password', '');
                $auth->createLog('登录', 2);
                $redirect_uri = isset($this->get['uri']) ? $this->get['uri'] : 'admin/index/index';

                return $this->do_success('登录成功', $redirect_uri);
            }
            return $this->do_error('账户或密码错误');
        }


        $bg_all = range(1, 5);
        $bg     = array_rand($bg_all, 1);

        $this->assign([
            'title'  => "登录",
            'bg_num' => $bg_all[$bg]
        ]);
        return $this->fetch('pub/login');
    }

    //退出
    public function logout()
    {
        $auth = new Auth();
        $auth->createLog('退出', 2);
        $auth->logout();
        $this->redirect('pub/login');
    }


    /**
     * 登录成功
     * @param string $msg
     * @param null $url
     * @param string $data
     */
    protected function do_success($msg = '', $url = null, $data = '')
    {
        if ($url == null) {
            $url = url($this->do_url . 'index');
        }

        if ($msg == '') {
            $msg = '操作成功！';
        }

        return $this->redirect($url, $data, 302, ['success_message' => $msg]);
    }

    /**
     * 登录错误
     * @param string $msg
     * @param null $url
     * @param string $data
     */
    protected function do_error($msg = '', $url = null, $data = '')
    {
        if ($url == null) {
            $url = $this->request->server('HTTP_REFERER');
        }

        if ($msg == '') {
            $msg = '操作失败！';
        }
        return $this->redirect($url, $data, 302, ['error_message' => $msg]);
    }

}