<?php
/**
 * 用户控制器
 */

namespace app\admin\controller;

use Exception;
use think\Request;
use think\response\Json;
use app\common\model\User;
use app\common\model\UserLevel;

use app\common\validate\UserValidate;

class UserController extends AdminBaseController
{

    /**
     * 列表
     * @param Request $request
     * @param User $model
     * @return string
     * @throws Exception
     */
    public function index(Request $request, User $model): string
    {
        $param = $request->param();
        $data  = $model->with('user_level')->scope('where', $param)
            ->paginate([
                'list_rows' => $this->admin['admin_list_rows'],
                'var_page'  => 'page',
                'query'     => $request->get(),
            ]);
        // 关键词，排序等赋值
        $this->assign($request->get());

        $this->assign([
            'data'            => $data,
            'page'            => $data->render(),
            'total'           => $data->total(),
            'user_level_list' => UserLevel::select(),
            'status_list'     => User::STATUS_LIST,
        ]);
        return $this->fetch();
    }

    /**
     * 添加
     *
     * @param Request $request
     * @param User $model
     * @param UserValidate $validate
     * @return string|Json
     * @throws Exception
     */
    public function add(Request $request, User $model, UserValidate $validate)
    {
        if ($request->isPost()) {
            $param           = $request->param();
            $validate_result = $validate->scene('admin_add')->check($param);
            if (!$validate_result) {
                return admin_error($validate->getError());
            }

            $result = $model::create($param);

            $url = URL_BACK;
            if (isset($param['_create']) && (int)$param['_create'] === 1) {
                $url = URL_RELOAD;
            }
            return $result ? admin_success('添加成功', [], $url) : admin_error();
        }
        $this->assign([
            'user_level_list' => UserLevel::select(),

        ]);


        return $this->fetch();
    }

    /**
     * 修改
     *
     * @param $id
     * @param Request $request
     * @param User $model
     * @param UserValidate $validate
     * @return string|Json
     * @throws Exception
     */
    public function edit($id, Request $request, User $model, UserValidate $validate)
    {
        $data = $model->findOrEmpty($id);
        if ($request->isPost()) {
            $param = $request->param();
            $check = $validate->scene('admin_edit')->check($param);
            if (!$check) {
                return admin_error($validate->getError());
            }

            $result = $data->save($param);

            return $result ? admin_success('修改成功', [], URL_BACK) : admin_error('修改失败');
        }

        $this->assign([
            'data'            => $data,
            'user_level_list' => UserLevel::select(),

        ]);

        return $this->fetch('add');
    }

    /**
     * 删除
     *
     * @param mixed $id
     * @param User $model
     * @return Json
     */
    public function del($id, User $model): Json
    {
        $check = $model->inNoDeletionIds($id);
        if (false !== $check) {
            return admin_error('ID为' . $check . '的数据不能被删除');
        }

        $result = $model::destroy(static function ($query) use ($id) {
            /** @var \think\db\Query $query */
            $query->whereIn('id', $id);
        });

        return $result ? admin_success('删除成功', [], URL_RELOAD) : admin_error('删除失败');
    }

    /**
     * 启用
     * @param mixed $id
     * @param User $model
     * @return Json
     */
    public function enable($id, User $model): Json
    {

        $result = $model->whereIn('id', $id)->update(['status' => 1]);
        return $result ? admin_success('操作成功', [], URL_RELOAD) : admin_error();
    }

    /**
     * 禁用
     * @param mixed $id
     * @param User $model
     * @return Json
     */
    public function disable($id, User $model): Json
    {
        $result = $model->whereIn('id', $id)->update(['status' => 0]);
        return $result ? admin_success('操作成功', [], URL_RELOAD) : admin_error();
    }

    /**
     * 导入
     * @param Request $request
     * @return Json
     */
    public function import(Request $request): Json
    {
        $param           = $request->param();
        $field_name_list = ['用户等级', '账号', '密码', '手机号', '昵称', '头像', '是否启用',];
        if (isset($param['action']) && $param['action'] === 'download_example') {
            $this->downloadExample($field_name_list);
        }

        $field_list = ['user_level_id', 'username', 'password', 'mobile', 'nickname', 'avatar', 'status',];
        $result     = $this->importData('file', 'user', $field_list);

        return true === $result ? admin_success('操作成功', [], URL_RELOAD) : admin_error($result);
    }

    /**
     * 导出
     * @param Request $request
     * @param User $model
     * @throws Exception
     */
    public function export(Request $request, User $model): void
    {
        $param = $request->param();
        $data  = $model->with('user_level')->scope('where', $param)->select();

        $header = ['ID', '用户等级', '账号', '手机号', '昵称', '头像', '是否启用', '创建时间',];
        $body   = [];
        foreach ($data as $item) {
            $record                  = [];
            $record['id']            = $item->id;
            $record['user_level_id'] = $item->user_level->name ?? '';
            $record['username']      = $item->username;
            $record['mobile']        = $item->mobile;
            $record['nickname']      = $item->nickname;
            $record['avatar']        = $item->avatar;
            $record['status']        = $item->status_text;
            $record['create_time']   = $item->create_time;

            $body[] = $record;
        }
         $this->exportData($header, $body, '用户数据-' . date('YmdHis'));
    }

}