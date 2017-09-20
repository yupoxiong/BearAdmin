<?php
/**
 * 后台PUb控制器,登录退出
 * @author yupoxiong <i@yufuping.com>
 * @version 1.0
 */

namespace app\admin\controller;

use app\common\model\AdminUsers;
use think\Config;
use think\Controller;
use app\admin\auth\Auth;
use think\Db;
use think\Session;
use tools\GeetestLib;

class Pub extends Controller
{
    protected $param;

    public function __construct()
    {
        parent::__construct();
        $this->param = $this->request->param();
    }

    //登录
    public function login()
    {

        if ($this->request->isPost()) {
            if(!$this->check_geetest()){
                return $this->do_error('验证失败');
            }

            $validate = [
                ['user_name|帐号', 'require|max:25|token'],
                ['password|密码', 'require']
            ];

            //验证
            $result = $this->validate($this->param, $validate);

            if (true !== $result) {
                return $this->do_error($result);
            }

            $data        = [
                'user_name' => $this->param['user_name'],
                'password'  => md5($this->param['password']),
            ];
            $admin_users = new AdminUsers();
            $admin_user  = $admin_users->get($data);

            if ($admin_user) {

                if ($admin_user->getData('status') != 1) {
                    return $this->do_error('账户被冻结');
                }

                if ($this->param['is_remember'] == 1) {
                    Auth::login($admin_user['user_id'], $admin_user['user_name'], true);
                } else {
                    Auth::login($admin_user['user_id'], $admin_user['user_name'], false);
                }

                //手动加入日志
                $auth = new Auth();
                $this->request->param('password', '');
                $auth->createLog('登录', 2);
                $redirect_uri = isset($this->param['uri']) ? $this->param['uri'] : 'admin/index/index';

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

    //使用前验证
    public function get_geetest_status()
    {
        $geetest = new GeetestLib(Config::get('geetest.id'), Config::get('geetest.key'));
        $data    = array(
            "user_id"     => "0", # 网站用户id
            "client_type" => "web", #web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
            "ip_address"  => $this->request->ip() # 请在此处传输用户请求验证时所携带的IP
        );

        $status = $geetest->pre_process($data, 1);
        Session::set('gtserver', $status);
        Session::set('gt_user_id', $data['user_id']);

        return json($geetest->get_response_str());
    }

    protected function check_geetest()
    {
        $geetest = new GeetestLib(Config::get('geetest.id'), Config::get('geetest.key'));
        $data    = array(
            "user_id"     => Session::get('gt_user_id'), # 网站用户id
            "client_type" => "web", #web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
            "ip_address"  => $this->request->ip()
        );

        if (Session::get('gtserver') == 1) {   //服务器正常
            $result = $geetest->success_validate($this->param['geetest_challenge'],$this->param['geetest_validate'],$this->param['geetest_seccode'], $data);
            if ($result) {
               return true;
            }
        } else {  //服务器宕机,走failback模式
            if ($geetest->fail_validate($this->param['geetest_challenge'], $this->param['geetest_validate'])) {
               return true;
            }
        }
        return false;
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