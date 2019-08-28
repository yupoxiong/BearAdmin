<?php
/**
 * 后台首页控制器
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\controller;

use app\admin\model\AdminLog;
use app\admin\model\AdminMenu;
use app\admin\model\AdminRole;
use app\admin\model\AdminUser;
use tools\SystemInfo;
use think\Request;

class IndexController extends Controller
{
    public function index(Request $request)
    {

        //默认密码修改检测
        $password_danger = 0;
        if (config('admin.password_warning') && $this->user->id == 1 && password_verify('super-admin', $this->user->password)) {
            $password_danger = 1;
        }

        /**
         * 首页数据展示，可自行替换
         */
        $this->assign([
            //后台用户数量
            'admin_user_count' => AdminUser::count(),
            //后台角色数量
            'admin_role_count' => AdminRole::count(),
            //后台菜单数量
            'admin_menu_count' => AdminMenu::count(),
            //后台日志数量
            'admin_log_count'  => AdminLog::count(),
        ]);

        //是否显示欢迎信息
        $welcome_info = config('admin.welcome_info');
        if ($welcome_info) {
            $this->admin['name'] = config('admin.name');
        }

        $this->assign([
            //系统信息
            'system_info'     => SystemInfo::getSystemInfo(),
            //访问信息
            'visitor_info'    => $request,
            //默认密码警告
            'password_danger' => $password_danger,
            //当前用户
            'user'            => $this->user,
            //欢迎信息
            'welcome_info'    => $welcome_info
        ]);
        return $this->fetch();
    }


}
