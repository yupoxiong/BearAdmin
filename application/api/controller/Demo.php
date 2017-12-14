<?php
/**
 * Api开发示例
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\api\controller;

use app\common\model\AdminLogs;

class Demo extends Api
{
    protected $needAuth=false;

    //后台最新5条操作日志
    public function log()
    {
        $list = AdminLogs::all(function ($query){
            $query->order('id desc')->limit(5);
        });

        if($list){
            return $this->success($list);
        }
        return $this->error('获取数据失败');
    }
}