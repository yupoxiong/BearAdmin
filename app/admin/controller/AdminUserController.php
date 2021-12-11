<?php
/**
 * 后台用户控制器
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);

namespace app\admin\controller;

use Exception;
use think\Request;
use think\Response;
use think\db\Query;
use think\response\Json;
use app\admin\model\AdminUser;
use app\admin\service\AdminRoleService;
use app\admin\service\AdminUserService;
use app\admin\validate\AdminUserValidate;
use app\admin\exception\AdminServiceException;

class AdminUserController extends AdminBaseController
{

    /**
     * 列表
     * @param Request $request
     * @param AdminUser $model
     * @return string
     * @throws Exception
     */
    public function index(Request $request, AdminUser $model): string
    {
        $param = $request->param();
        $data  = $model->scope('where', $param)
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
        ]);

        return $this->fetch();
    }

    /**
     * 添加
     * @param Request $request
     * @param AdminUserService $service
     * @param AdminUserValidate $validate
     * @return string|Json
     * @throws Exception
     */
    public function add(Request $request, AdminUserService $service, AdminUserValidate $validate)
    {
        if ($request->isPost()) {
            $param = $request->param();
            $check = $validate->scene('admin_add')->check($param);
            if (!$check) {
                return admin_error($validate->getError());
            }

            try {
                $result   = $service->create($param);
                $redirect = isset($param['_create']) && (int)$param['_create'] === 1 ? URL_RELOAD : URL_BACK;

                return $result ? admin_success('添加成功', [], $redirect) : admin_error('添加失败');
            } catch (AdminServiceException $e) {
                return admin_error($e->getMessage());
            }
        }

        $this->assign([
            'role_list' => (new AdminRoleService())->getAll(),
        ]);

        return $this->fetch();
    }

    /**
     * 修改
     * @param $id
     * @param Request $request
     * @param AdminUser $model
     * @param AdminUserService $service
     * @param AdminUserValidate $validate
     * @return string|Json
     * @throws Exception
     */
    public function edit($id, Request $request, AdminUser $model, AdminUserService $service, AdminUserValidate $validate)
    {
        $data = $model->findOrEmpty($id);
        if ($request->isPost()) {
            $param = $request->param();

            $check = $validate->scene('admin_edit')->check($param);
            if (!$check) {
                return admin_error($validate->getError());
            }

            try {
                $result = $service->update($data, $param);
            } catch (AdminServiceException $e) {
                return admin_error($e->getMessage());
            }

            return $result ? admin_success('修改成功', [], URL_BACK) : admin_error('修改失败');
        }

        $this->assign([
            'data'            => $data,
            'role_list'       => (new AdminRoleService())->getAll(),
            'password_config' => $service->getCurrentPasswordLevel()
        ]);

        return $this->fetch('add');
    }

    /**
     * 删除
     * @param mixed $id
     * @param AdminUser $model
     * @return Response
     */
    public function del($id, AdminUser $model): Response
    {
        $check = $model->inNoDeletionIds($id);

        if (false !== $check) {
            return admin_error('ID 为' . $check . '的数据无法删除');
        }

        $result = $model::destroy(static function ($query) use ($id) {
            /** @var Query $query */
            $query->whereIn('id', $id);
        });

        return $result ? admin_success('删除成功', [], URL_RELOAD) : admin_error('删除失败');
    }

    /**
     * 启用
     * @param mixed $id
     * @param AdminUser $model
     * @return Json
     */
    public function enable($id, AdminUser $model): Json
    {
        $result = $model->whereIn('id', $id)->update(['status' => 1]);
        return $result ? admin_success('操作成功', [], URL_RELOAD) : admin_error();
    }

    /**
     * 禁用
     * @param mixed $id
     * @param AdminUser $model
     * @return Json
     */
    public function disable($id, AdminUser $model): Json
    {
        $has_admin = false;
        if (is_array($id)) {
            $id = array_map('intval', $id);
            if (in_array(1, $id, true)) {
                $has_admin = true;
            }
        } else if ((int)$id === 1) {
            $has_admin = true;
        }
        if($has_admin){
            return admin_error('超级管理员不能禁用');
        }

        $result = $model->whereIn('id', $id)->update(['status' => 0]);
        return $result ? admin_success('操作成功', [], URL_RELOAD) : admin_error();
    }

    /**
     * 个人资料
     * @param Request $request
     * @param AdminUserValidate $validate
     * @return string|Json
     * @throws Exception
     */
    public function profile(Request $request, AdminUserValidate $validate)
    {
        if ($request->isPost()) {
            $param = $request->param();

            if ($param['update_type'] === 'password') {

                $validate_result = $validate->scene('admin_password')->check($param);
                if (!$validate_result) {
                    return admin_error($validate->getError());
                }

                if (!password_verify($param['current_password'], base64_decode($this->user->password))) {
                    return admin_error('当前密码不正确');
                }
                $param['password'] = $param['new_password'];
            }

            return $this->user->save($param) ? admin_success('修改成功', [], URL_RELOAD) : admin_error();
        }

        $this->assign([
            'data' => $this->user,
        ]);
        return $this->fetch();
    }
}
