<?php
/**
 * 后台基础控制器
 * @author yupoxiong <i@yufuping.com>
 */

namespace app\admin\controller;

use app\common\model\AdminMenus;
use think\Controller;
use tools\AdminAuth;
use tools\Tree;
use think\Request;
use think\Session;
use app\common\model\AdminUsers;

class Base extends Controller
{
    protected $reuqest;
    protected $requestType;
    protected $param;

    protected $webData;
    protected $id;

    protected $module;
    protected $controller;
    protected $action;
    protected $url;

    protected $uid = 0;
    protected $needAuth = true;

    protected $showDataHeader = true;
    protected $showDataHeaderAddButton = true;
    protected $showDataHeaderDeleteButton = true;
    protected $showLeftMenu = true;
    //ajax提交返回跳转url，1为返回上一页，2重新载入当前页面，3为跳转到指定页面，4为不跳转
    protected $returnDirectUrl = [
        'type'=>1,
        'url'=>'',
    ];

    public function __construct()
    {

        $this->request     = Request::instance();
        $this->requestType = $this->request->isGet() ? 1 : (
        $this->request->isPost() ? 2 : (
        $this->request->isPut() ? 3 : (
        $this->request->isDelete() ? 4 : 0
        )));

        $this->param      = $this->request->param();
        $this->module     = $this->request->module();
        $this->controller = $this->request->controller();
        $this->action     = $this->request->action();

        $this->url = parse_name($this->module) .
            '/' . parse_name($this->controller) .
            "/" . parse_name($this->action);

        $this->id = isset($this->param['id']) ? $this->param['id'] : -1;

        parent::__construct();
    }


    public function _initialize()
    {

        $menu_info              = AdminMenus::get(['url' => $this->url]);
        $this->webData['title'] = $menu_info['title'];
        $log_type               = $menu_info['log_type'];
        //如果需要验证
        if (true == $this->needAuth) {
            $auth = new AdminAuth();
            if (!$auth->is_login()) {
                $this->redirect('auth/login');
            }
            $this->uid = Session::get('user.id');
            if ($this->uid != 1) {
                if (!$auth->check($this->url, $this->uid)) {

                    $redirect_uri = null;
                    $server       = $this->request->server();
                    $current_uri  = $server['REQUEST_SCHEME'] . '://' . $server["SERVER_NAME"];
                    if ($server["SERVER_PORT"] != "80" && $server["SERVER_PORT"] != "443") {
                        $current_uri .= ":" . $server["SERVER_PORT"];
                    }
                    $current_uri .= $server["REQUEST_URI"];

                    if (!isset($server['HTTP_REFERER']) || $current_uri == $server['HTTP_REFERER']) {
                        $redirect_uri = 'index/index';
                    }

                    if ($this->url == 'admin/index/index') {
                        $redirect_uri = 'auth/login';
                        $this->redirect($redirect_uri);
                    }

                    $this->error('无权限', $redirect_uri);
                }
            }

            if ($log_type == $this->requestType && $log_type != 0) {
                $auth->createLog($this->webData['title'], $log_type, $this->id);
            }
        }

        //用户信息
        $user_info = AdminUsers::get($this->uid);

        $this->webData['user_info'] = $user_info;
        //分页记录数处理
        $this->webData['list_rows'] = get_list_rows();

    }

    protected function getMenuChild($arr, $myid)
    {
        $a = $newarr = array();
        if (is_array($arr)) {
            foreach ($arr as $id => $a) {
                if ($a['parent_id'] == $myid)
                    $newarr[$id] = $a;
            }
        }
        return $newarr ? $newarr : false;
    }

    protected function getMenuParent($arr, $myid, $parent_ids = array())
    {
        $a = $newarr = array();
        if (is_array($arr)) {
            foreach ($arr as $id => $a) {
                if ($a['id'] == $myid) {
                    if ($a['parent_id'] != 0) {
                        array_push($parent_ids, $a['parent_id']);
                        $parent_ids = $this->getMenuParent($arr, $a['parent_id'], $parent_ids);
                    }
                }
            }
        }
        return !empty($parent_ids) ? $parent_ids : false;
    }

    protected function getCurrentNav($arr, $myid, $parent_ids = array(), $current_nav = '')
    {
        $a = $newarr = array();
        if (is_array($arr)) {
            foreach ($arr as $id => $a) {
                if ($a['id'] == $myid) {
                    if ($a['parent_id'] != 0) {
                        array_push($parent_ids, $a['parent_id']);
                        $ru          = '<li><a><i class="fa ' . $a['icon'] . '"></i> ' . $a['title'] . '</a></li>';
                        $current_nav = $ru . $current_nav;
                        $temp_result = $this->getCurrentNav($arr, $a['parent_id'], $parent_ids, $current_nav);
                        $parent_ids  = $temp_result[0];
                        $current_nav = $temp_result[1];
                    }
                }
            }
        }
        return !empty([$parent_ids, $current_nav]) ? [$parent_ids, $current_nav] : false;
    }


    protected function success($msg = '操作成功', $url = null, $data = '', $wait = 3, array $header = [])
    {
        if ($this->request->isAjax()) {
            if($url==null){
                $url = $this->returnDirectUrl;
            }
            parent::success($msg, $url, $data, $wait, $header);
        }

        if ($url == null) {
            if ($this->request->server('HTTP_REFERER') != null) {
                $url = $this->request->server('HTTP_REFERER');
            } else {
                $url = 'admin/index/index';
            }
        }

        $this->redirect($url, $data, 302, ['success_message' => $msg]);
    }


    protected function error($msg = '操作失败', $url = null, $data = '', $wait = 3, array $header = [])
    {
        if ($this->request->isAjax()) {
            parent::error($msg, $url, $data, $wait, $header);
        }

        if ($url == null) {
            if ($this->request->server('HTTP_REFERER') != null) {
                $url = $this->request->server('HTTP_REFERER');
            } else {
                $url = 'admin/index/index';
            }
        }

        $this->redirect($url, $data, 302, ['error_message' => $msg, 'form_info' => $this->param]);
    }


    //获取左侧菜单
    protected function getLeftMenu()
    {
        $auth = new AdminAuth();
        $menu = $auth->getMenuList(Session::get('user.id'), 1);

        $max_level  = 0;
        $current_id = 1;
        $parent_ids = array(0 => 0);

        $current_nav = ['', ''];
        foreach ($menu as $k => $v) {
            if ($v['url'] == $this->url) {
                $parent_ids  = $this->getMenuParent($menu, $v['id']);
                $current_id  = $v['id'];
                $current_nav = $this->getCurrentNav($menu, $v['id']);
            }
        }
        if ($parent_ids == false) {
            $parent_ids = array(0 => 0);
        }

        $this->webData['current_nav'] = $current_nav[1];

        $tree = new Tree();

        foreach ($menu as $k => $v) {
            $url               = url($v['url']);
            $menu[$k]['icon']  = !empty($v['icon']) ? $v['icon'] : 'fa fa-list';
            $menu[$k]['level'] = $tree->get_level($v['id'], $menu);
            $max_level         = $max_level <= $menu[$k]['level'] ? $menu[$k]['level'] : $max_level;
            $menu[$k]['url']   = $url;
        }

        $tree->init($menu);

        $text_base_one   = "<li class='treeview";
        $text_hover      = " active";
        $text_base_two   = "'><a href='javascript:void(0);'><i class='fa \$icon'></i><span>\$title</span>
                             <span class='pull-right-container'><i class='fa fa-angle-left pull-right'></i></span>
                             </a><ul class='treeview-menu";
        $text_open       = " menu-open";
        $text_base_three = "'>";

        $text_base_four = "<li";
        $text_hover_li  = " class='active'";
        $text_base_five = ">
                            <a href='\$url'>
                            <i class='fa \$icon'></i>
                            <span>\$title</span>
                            </a>
                         </li>";

        $text_0       = $text_base_one . $text_base_two . $text_base_three;
        $text_1       = $text_base_one . $text_hover . $text_base_two . $text_open . $text_base_three;
        $text_2       = "</ul></li>";
        $text_current = $text_base_four . $text_hover_li . $text_base_five;
        $text_other   = $text_base_four . $text_base_five;

        for ($i = 0; $i <= $max_level; $i++) {
            $tree->text[$i]['0'] = $text_0;
            $tree->text[$i]['1'] = $text_1;
            $tree->text[$i]['2'] = $text_2;
        }

        $tree->text['current'] = $text_current;
        $tree->text['other']   = $text_other;

        return $tree->get_authTree(0, $current_id, $parent_ids);
    }


    //重写fetch
    protected function fetch($template = '', $vars = [], $replace = [], $config = [])
    {
        //左侧菜单
        if ($this->showLeftMenu) {
            $this->webData['left_menu'] = $this->getLeftMenu();
        }

        parent::assign([
            'showDataHeader'             => $this->showDataHeader,
            'showDataHeaderAddButton'    => $this->showDataHeaderAddButton,
            'showDataHeaderDeleteButton' => $this->showDataHeaderDeleteButton,
            'pre_url'                    => $this->request->server('HTTP_REFERER'),
        ]);

        parent::assign(['webData' => $this->webData]);
        return parent::fetch($template, $vars, $replace, $config);
    }


    //空方法
    public function _empty()
    {
        return $this->error('页面不存在');
    }
}