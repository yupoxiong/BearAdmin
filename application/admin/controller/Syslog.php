<?php
/**
 * 后台系统日志
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\controller;

use app\common\model\AdminLogs;
use app\common\model\AdminUsers;
use app\common\model\Syslogs;
use think\Log;

class Syslog extends Base
{
    //系统日志列表
    public function index(){
        $syslogs = new Syslogs();
        $page_param = ['query' => []];
        if (isset($this->get['keywords']) && !empty($this->get['keywords'])) {
            $page_param['query']['keywords'] = $this->get['keywords'];
            $keywords = "%" . $this->get['keywords'] . "%";
            $syslogs->whereLike('message', $keywords);
            $this->assign('keywords', $this->get['keywords']);
        }
        $lists = $syslogs
            ->with('syslogTrace')
            ->order('id desc')
            ->paginate($this->webData['list_rows'], false, $page_param);
        $this->assign([
            'lists'    => $lists,
            'page'     => $lists->render(),
            'total'    => $lists->total()
        ]);
        return $this->fetch();
    }
    
    //读取日志，暂未用到
    function readlog(){
        $file_path = config('sys_log.path');
        $file = fopen($file_path, "r");
        $logs=array();
        $i=0;
        while(! feof($file))
        {
            $logs[$i]= fgets($file);//fgets()函数从文件指针中读取一行
            $i++;
        }
        fclose($file);
        $user=array_filter($logs);
        return $user;
    }
}