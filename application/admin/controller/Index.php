<?php
/**
 * 后台首页
 * @author yupoxiong <i@yufuping.com>
 * @version 1.0
 */

namespace app\admin\controller;

use tools\Sysinfo;

class Index extends Base
{
    public function index()
    {
        $sysinfo  = new Sysinfo();
        $sys_info = [
            'lang'    => $sysinfo->getLang(),
            'browser' => $sysinfo->getBrowser(),
            'ip'      => $sysinfo->getIp(),
            'city'    => $sysinfo->getCity(),
            'os'      => $sysinfo->getOS(),
            'date'    => date('Y-m-d')
        ];

        $this->assign([
            'sys'      => $sys_info,
        ]);
        return $this->fetch();
    }
}