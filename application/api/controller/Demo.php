<?php
/**
 * Api开发示例
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\api\controller;

use app\admin\model\AdminLogs;

class Demo extends Api
{
    protected $needAuth = false;

    public function index()
    {
        //当前页数
        $page = isset($this->param['page']) ? $this->param['page'] : 1;
        //每页数量
        $num  = isset($this->param['num']) ? $this->param['num'] : 10;
        //限制每页数量，防止恶意请求数据量过大
        $num = $num>100?100:$num;
        $list = AdminLogs::where('user_id', '<>', 1)
            ->order('id', 'desc')
            ->page($page, $num)
            ->select();
        if ($list) {
            return $this->success($list);
        }
        return $this->error('获取数据失败');
    }
}