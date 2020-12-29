<?php
/**
 * 后台基础控制器
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\controller;

use app\admin\model\AdminMenu;
use app\admin\model\AdminUser;
use app\admin\traits\{AdminAuth, AdminTree, PhpOffice};

class Controller extends \think\Controller
{
    use AdminAuth, AdminTree, PhpOffice;


    /**
     * 当前url
     * @var string
     */
    protected $url;

    /**
     * 当前用户ID
     * @var int
     */
    protected $uid = 0;

    /**
     * 当前用户
     * @var AdminUser
     */
    protected $user;


    /**
     * 后台变量
     * @var array
     */
    protected $admin;

    /**
     * 无需验证权限的url
     * @var array
     */
    protected $authExcept = [];

    //初始化基础控制器
    protected function initialize(): void
    {
        $request = $this->request;
        //获取当前访问url
        $this->url = parse_name($request->module()) . '/' .
            parse_name($request->controller()) . '/' .
            parse_name($request->action());

        //验证权限
        if (!in_array($this->url, $this->authExcept, true)) {
            if (!$this->isLogin()) {
                admin_error('未登录', 'auth/login');
            } else if ($this->user->id !== 1 && !$this->authCheck($this->user)) {
                admin_error('无权限', $this->request->isGet() ? null : URL_CURRENT);
            }
        }

        if ((int)$request->param('check_auth') === 1) {
            admin_success();
        }

        //记录日志
        $menu = AdminMenu::get(['url' => $this->url]);
        if ($menu) {
            $this->admin['title'] = $menu->name;
            if ($menu->log_method === $request->method()) {
                $this->createAdminLog($this->user, $menu);
            }
        }

        $this->admin['per_page'] = cookie('admin_per_page') ?? 10;
        $this->admin['per_page'] = $this->admin['per_page'] < 100 ? $this->admin['per_page'] : 100;

    }


    //重写fetch
    protected function fetch($template = '', $vars = [], $config = [])
    {
        $this->admin['pjax'] = $this->request->isPjax();
        $this->admin['user'] = $this->user;
        $this->setAdminInfo();
        if ('admin/auth/login' !== $this->url && !$this->admin['pjax']) {
            $this->admin['menu'] = $this->getLeftMenu($this->user);
        }

        $this->assign([
            'debug'         => config('app.app_debug') ? 'true' : 'false',
            'cookie_prefix' => config('cookie.prefix') ?? '',
            'admin'         => $this->admin,
        ]);

        return parent::fetch($template, $vars, $config);
    }

    //空方法
    public function _empty()
    {
        $this->admin['title'] = '404';
        return $this->fetch('template/404');
    }


    //设置前台显示的后台信息
    protected function setAdminInfo(): void
    {
        $admin_config = config('admin.base');

        $this->admin['name']       = $admin_config['name'] ?? '';
        $this->admin['author']     = $admin_config['author'] ?? '';
        $this->admin['version']    = $admin_config['version'] ?? '';
        $this->admin['short_name'] = $admin_config['short_name'] ?? '';
    }
}
