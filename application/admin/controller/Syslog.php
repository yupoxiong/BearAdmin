<?php
/**
 * 后台系统日志
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\controller;

use app\admin\model\Syslogs;

class Syslog extends Base
{
    //系统日志列表
    public function index(){
        $syslogs = new Syslogs();
        $page_param = ['query' => []];
        if (isset($this->param['keywords']) && !empty($this->param['keywords'])) {
            $page_param['query']['keywords'] = $this->param['keywords'];
            $keywords = "%" . $this->param['keywords'] . "%";
            $syslogs->whereLike('message', $keywords);
            $this->assign('keywords', $this->param['keywords']);
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


    public function trace()
    {
        $data = Syslogs::get($this->id);
        $this->assign('data',$data);
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