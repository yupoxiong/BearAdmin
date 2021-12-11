<?php
/**
 * 后台操作日志控制器
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);

namespace app\admin\controller;

use Exception;
use JsonException;
use think\Request;
use think\response\Json;
use app\admin\model\AdminLog;
use app\admin\model\AdminUser;

class AdminLogController extends AdminBaseController
{
    protected array $authExcept=[
        'admin/admin_log/position'
    ];
    /**
     * 列表
     * @param Request $request
     * @param AdminLog $model
     * @return string
     * @throws Exception
     */
    public function index(Request $request, AdminLog $model): string
    {
        $param = $request->param();
        $data  = $model->with('adminUser')->scope('where', $param)
            ->paginate([
                'list_rows' => $this->admin['admin_list_rows'],
                'var_page'  => 'page',
                'query'     => $request->get()
            ]);

        // 关键词，排序等赋值
        $this->assign($request->get());
        $this->assign([
            'data'  => $data,
            'page'  => $data->render(),
            'total' => $data->total(),
            'admin_user_list'=>(new AdminUser())->select(),
        ]);
        return $this->fetch();
    }

    /**
     * @param $id
     * @param AdminLog $model
     * @return string
     * @throws Exception
     */
    public function detail($id, AdminLog $model): string
    {
        $data = $model->with('adminLogData')->findOrEmpty($id);

        $this->assign([
            'data' => $data,
        ]);

        return $this->fetch();
    }

    /**
     * 获取操作IP的城市
     * @param $id
     * @param AdminLog $model
     * @return Json
     * @throws JsonException
     */
    public function position($id, AdminLog $model): Json
    {
        $data = $model->findOrEmpty($id);
        $json = file_get_contents('https://restapi.amap.com/v3/ip?ip=' . $data->log_ip . '&key=' . config('map.amap.web_api_key'));
        $arr  = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        if (isset($arr['status']) && $arr['status'] === '1') {
            return admin_success('', ['city' => !empty($arr['city']) ? $arr : '']);
        }

        return admin_error('获取定位失败');
    }
}
