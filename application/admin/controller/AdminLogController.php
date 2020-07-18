<?php
/**
 * 后台操作日志控制器
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\controller;

use app\admin\model\AdminLog;
use app\admin\model\AdminUser;
use think\Request;

class AdminLogController extends Controller
{

    public function index(Request $request, AdminLog $model)
    {
        $param = $request->param();
        $data  = $model->scope('where', $param)
            ->paginate($this->admin['per_page'], false, ['query' => $request->get()]);

        // 关键词，排序等赋值
        $this->assign($request->get());

        $this->assign([
            'data'            => $data,
            'page'            => $data->render(),
            'total'           => $data->total(),
            'admin_user_list' => AdminUser::all(),
        ]);

        return $this->fetch();
    }

    /**
     * 日志详情
     * @param $id
     * @param AdminLog $model
     * @return mixed
     */
    public function view($id, AdminLog $model)
    {
        $data = $model::get($id);
        $this->assign([
            'data' => $data
        ]);
        return $this->fetch();
    }

}
