<?php
/**
 * 登录退出权限相关等等控制器
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\controller;

use app\admin\model\AdminGroupAccess;
use app\admin\model\AdminUsers;
use think\Session;
use tools\AdminAuth;
use tools\GeetestLib;
use anerg\OAuth2\OAuth;

/**
 * @property mixed id
 * @property mixed name
 */
class Auth extends Base
{
    protected $needAuth = false;

    //登录
    public function login()
    {
        if ($this->request->isPost()) {

            if (!$this->check_geetest()) {
                return $this->error('验证失败');
            }

            $result = $this->validate($this->param, 'AdminUser.login');

            if (true !== $result) {
                return $this->error($result);
            }

            $data = [
                'name'     => $this->param['name'],
                'password' => md5($this->param['password']),
            ];

            $user = AdminUsers::get($data);
            if (!$user) {
                return $this->error('用户名或密码错误');
            }

            if ($user->status != 1) {
                return $this->error('账户被冻结');
            }

            $remember = isset($this->param['remember']) ? true : false;
            AdminAuth::login($user['id'], $user['name'], $remember);

            //手动加入日志
            $auth = new AdminAuth();
            $this->request->param('password', '');
            $auth->createLog('登录', 2);
            $redirect_uri = isset($this->param['uri']) ? $this->param['uri'] : 'admin/index/index';

            return $this->success('登录成功', $redirect_uri);
        }

        $bg_all = range(1, 5);
        $bg     = array_rand($bg_all, 1);

        $this->assign([
            'title'  => '登录',
            'bg_num' => $bg_all[$bg]
        ]);
        return $this->fetch();
    }

    //退出
    public function logout()
    {
        $auth = new AdminAuth();
        $auth->createLog('退出', 2);
        $auth->logout();
        $this->redirect('auth/login');
    }


    public function qqlogin()
    {
        $config = config('qq_login');
        $OAuth  = OAuth::getInstance($config, 'qq');
        if ($this->request->isMobile()) {
            $OAuth->setDisplay('mobile');
        }
        return redirect($OAuth->getAuthorizeURL());
    }

    //QQ登录回调

    /**
     * @throws \think\exception\DbException
     */
    public function qq()
    {
        $config = config('qq_login');
        $OAuth  = OAuth::getInstance($config, 'qq');
        $OAuth->getAccessToken();

        $sns_info = $OAuth->userinfo();
        $user     = AdminUsers::get(function ($query) use ($sns_info) {
            $query->where('qq_openid', '=', $sns_info['openid']);
        });

        //如果用户不存在
        if (!$user) {
            //创建新用户
            $data = [
                'name'      => $sns_info['openid'],
                'qq_openid' => $sns_info['openid'],
                'sex'       => $sns_info['gender'] === 'm' ? 1 : 0,
                'nickname'  => $sns_info['nick'],
                'avatar'    => preg_replace('/http:/', 'https:', $sns_info['avatar'], 1),
                'password'  => md5('123456'),
            ];

            $user = AdminUsers::create($data);
            if (!$user) {
                return $this->error('创建用户失败');
            }

            $user->name = 'user' . $user->id;
            $user->save();

            $access_data = [
                'uid'      => $user->id,
                'group_id' => 2
            ];

            $access = AdminGroupAccess::create($access_data);
            if (!$access) {
                return $this->error('分配用户权限失败');
            }
        }

        AdminAuth::login($user->id, $user->name, true);
        $auth = new AdminAuth();
        $auth->createLog('QQ登录', 2);
        $redirect_uri = isset($this->param['uri']) ? $this->param['uri'] : 'admin/index/index';
        return $this->success('登录成功', $redirect_uri);
    }


    //使用前验证
    public function get_geetest_status()
    {
        $geetest = new GeetestLib(config('geetest.id'), config('geetest.key'));
        $data    = array(
            'user_id'     => '0', # 网站用户id
            'client_type' => 'web', #web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
            'ip_address'  => $this->request->ip() # 请在此处传输用户请求验证时所携带的IP
        );

        $status = $geetest->pre_process($data, 1);
        Session::set('gtserver', $status);
        Session::set('gt_user_id', $data['user_id']);

        return json($geetest->get_response_str());
    }

    protected function check_geetest()
    {
        $geetest = new GeetestLib(config('geetest.id'), config('geetest.key'));
        $data    = array(
            'user_id'     => Session::get('gt_user_id'), # 网站用户id
            'client_type' => 'web', #web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
            'ip_address'  => $this->request->ip()
        );

        if (Session::get('gtserver') == 1) {   //服务器正常
            $result = $geetest->success_validate($this->param['geetest_challenge'], $this->param['geetest_validate'], $this->param['geetest_seccode'], $data);
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
}