<?php
/**
 * 数据统计
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\controller;

use app\admin\model\AdminLogs;
use app\admin\model\AdminMenus;
use app\admin\model\AdminUsers;
use app\admin\model\Syslogs;

class Statistics extends Base
{

    //统计概览
    public function index(){
        $admin_users = new AdminUsers();
        $admin_user_count = $admin_users->count();
        $syslogs = new Syslogs();
        $syslog_count = $syslogs->count();
        $admin_logs = new AdminLogs();
        $admin_log_count = $admin_logs->count();
        $admin_menus = new AdminMenus();
        $admin_menu_count = $admin_menus->count();

        $this->assign([
            'adminuser_count'=>$admin_user_count,
            'syslog_count'=>$syslog_count,
            'admin_log_count'=>$admin_log_count,
            'admin_menu_count' => $admin_menu_count
        ]);
        return $this->fetch();
    }

    //展示数据
    public function showdata()
    {
        

    }

}