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
        $model = new Syslogs();
        $page_param = ['query' => []];
        if (isset($this->param['keywords']) && !empty($this->param['keywords'])) {
            $page_param['query']['keywords'] = $this->param['keywords'];
            $keywords = "%" . $this->param['keywords'] . "%";
            $model->whereLike('message', $keywords);
            $this->assign('keywords', $this->param['keywords']);
        }
        if (isset($this->param['start_date']) && !empty($this->param['start_date'])) {
            $page_param['query']['start_date'] = $this->param['start_date'];
            $start_date                        = $this->param['start_date'];
            $model->whereTime('create_time', '>=', $start_date);
            $this->assign('start_date', $this->param['start_date']);
        }

        if (isset($this->param['end_date']) && !empty($this->param['end_date'])) {
            $page_param['query']['end_date'] = $this->param['end_date'];
            $end_date                        = $this->param['end_date'];
            $model->whereTime('create_time', '<=', strtotime($end_date . '+1 day'));
            $this->assign('end_date', $this->param['end_date']);
        }
        $list = $model
            ->with('syslogTrace')
            ->order('id desc')
            ->paginate($this->webData['list_rows'], false, $page_param);
        $this->assign([
            'list'    => $list,
            'page'     => $list->render(),
            'total'    => $list->total()
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