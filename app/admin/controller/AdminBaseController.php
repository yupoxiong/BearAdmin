<?php
/**
 * 后台基类
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);

namespace app\admin\controller;

use app\admin\traits\{AdminAuthTrait, AdminPhpOffice, AdminTreeTrait};
use app\admin\model\{AdminMenu, AdminUser};
use think\response\Json;
use think\facade\Env;
use think\View;
use Exception;

class AdminBaseController
{
    // 引入树相关trait
    use AdminTreeTrait;

    // 引入权限判断相关trait
    use AdminAuthTrait;

    // 引入office相关trait
    use AdminPhpOffice;

    /**
     * 后台主变量
     * @var array
     */
    protected array $admin;

    /**
     * 视图变量
     * @var View
     */
    protected View $view;

    /**
     * 当前访问的URL
     * @var string
     */
    protected string $url;

    /**
     * 当前访问的菜单
     * @var mixed
     */
    protected $menu;

    /**
     * 无需验证登录的url
     * @var array
     */
    protected array $loginExcept = [];

    /**
     * 无需验证权限的URL
     * @var array
     */
    protected array $authExcept = [
        'admin/error/err403',
        'admin/error/err404',
        'admin/error/err500',
    ];

    /**
     * 当前后台用户
     * @var AdminUser
     */
    protected AdminUser $user;

    public function __construct()
    {
        // 初始化
        $this->initialize();
    }

    /**
     * 初始化方法
     */
    public function initialize(): void
    {
        // 检查登录
        $this->checkLogin();
        // 检查权限
        $this->checkAuth();
        // 单设备登录检查
        $this->checkOneDeviceLogin();
        // csrfToken检查
        $this->checkToken();

        // 初始化view
        $this->view = app()->make(View::class);

        // 分页每页数量
        $this->admin['admin_list_rows'] = cookie('admin_list_rows') ?? 10;
        // 限制每页数量最多不超过100
        $this->admin['admin_list_rows'] = $this->admin['admin_list_rows'] < 100 ? $this->admin['admin_list_rows'] : 100;
        /** @var AdminMenu $menu */
        $this->menu = (new AdminMenu)->where(['url' => $this->url])->findOrEmpty();

        if (isset($this->user) && !$this->menu->isEmpty() && request()->method() === $this->menu->log_method) {
            // 如果用户登录了而且符合菜单记录日志方式，记录操作日志
            $this->createLog($this->user, $this->menu->name);
        }
    }

    /**
     * 模板赋值
     * @param $name
     * @param null $value
     * @return View
     */
    protected function assign($name, $value = null): View
    {
        return $this->view->assign($name, $value);
    }

    /**
     * 渲染模板
     * @param string $template
     * @param array $vars
     * @return string
     * @throws Exception
     */
    protected function fetch(string $template = '', array $vars = []): string
    {
        // 顶部导航
        $this->admin['top_nav'] = (int)setting('admin.display.top_nav');
        // 后台基本信息配置
        $this->admin['base'] = setting('admin.base');
        // 当前顶部导航ID
        $current_top_id = 0;

        if (!$this->menu->isEmpty()) {
            $menu     = $this->menu;
            $menu_all = (new AdminMenu)->field('id,parent_id,name,icon')->select()->toArray();
            // 当前页面标题
            $this->admin['title']      = $menu->name;
            // 当前面包屑
            $this->admin['breadcrumb'] = $this->getBreadCrumb($menu_all, $menu->id);
            if ($this->admin['top_nav'] === 1) {
                // 顶部导航id
                $current_top_id = $this->getTopParentIdById($menu_all, $menu->id);
            }
        }
        // 当前是否为pjax访问
        $this->admin['is_pjax']    = request()->isPjax();
        // 上传文件url
        $this->admin['upload_url'] = url('admin/file/upload')->build();
        // 退出url
        $this->admin['logout_url'] = url('admin/auth/logout')->build();

        if ('admin/auth/login' !== $this->url && !$this->admin['is_pjax']) {
            // 展示菜单
            $show_menu = $this->user->getShowMenu($this->admin['top_nav']);
            // 顶部导航
            $this->admin['top_menu']  = $show_menu['top'];
            // 左侧菜单
            $this->admin['left_menu'] = $this->getLeftMenu($show_menu['left'][$current_top_id], $menu->id ?? 0);
        }
        // 是否开启debug
        $this->admin['debug'] = Env::get('app_debug') ? 1 : 0;
        // 顶部导航
        $this->admin['top_nav'] = 1;
        // 顶部搜索
        $this->admin['top_search'] = 0;
        // 顶部消息
        $this->admin['top_message'] = 0;
        // 顶部通知
        $this->admin['top_notification'] = 0;
        // 文件删除url
        $this->admin['file_del_url'] = url('admin/file/del');
        // 地图配置
        $this->admin['map']          = config('map');
        // 当前用户
        $this->admin['user'] = $this->user ?? new AdminUser();

        // 赋值后台变量
        $this->assign([
            'admin' => $this->admin,
        ]);
        return $this->view->fetch($template, $vars);
    }

    /**
     * 访问不存在的方法
     * @param $name
     * @param $arguments
     * @return string|Json
     * @throws Exception
     */
    public function __call($name, $arguments)
    {
        if (request()->isPost()) {
            return admin_error('页面未找到');
        }
        return $this->fetch('error/404');
    }
}
