<?php
/**
 * 后台角色控制器
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);

namespace app\admin\controller;

use Exception;
use think\Request;
use think\Response;
use think\db\Query;
use think\response\Json;
use app\admin\model\AdminMenu;
use app\admin\model\AdminRole;
use app\admin\validate\AdminRoleValidate;

class AdminRoleController extends AdminBaseController
{

    /**
     * 列表
     * @param Request $request
     * @param AdminRole $model
     * @return string
     * @throws Exception
     */
    public function index(Request $request, AdminRole $model): string
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
     * @param AdminRole $model
     * @param AdminRoleValidate $validate
     * @return string|Json
     * @throws Exception
     */
    public function add(Request $request, AdminRole $model, AdminRoleValidate $validate)
    {
        if ($request->isPost()) {
            $param = $request->param();
            $check = $validate->scene('admin_add')->check($param);
            if (!$check) {
                return admin_error($validate->getError());
            }

            $result = $model::create($param);

            $redirect = isset($param['_create']) && (int)$param['_create'] === 1 ? URL_RELOAD : URL_BACK;

            return $result ? admin_success('添加成功', [], $redirect) : admin_error('添加失败');
        }
        return $this->fetch();
    }

    /**
     * 修改
     * @param $id
     * @param Request $request
     * @param AdminRole $model
     * @param AdminRoleValidate $validate
     * @return string|Json
     * @throws Exception
     */
    public function edit($id, Request $request, AdminRole $model, AdminRoleValidate $validate)
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
            'data' => $data,
        ]);

        return $this->fetch('add');
    }

    /**
     * 授权
     * @param $id
     * @param Request $request
     * @param AdminRole $model
     * @return string|Json
     * @throws Exception
     */
    public function access($id, Request $request, AdminRole $model)
    {
        $data = $model->findOrEmpty($id);
        if ($request->isPost()) {
            $param = $request->param();
            if (!isset($param['url'])) {
                return admin_error('请至少选择一项权限');
            }
            $param['url'] = array_map('intval', $param['url']);
            if (!in_array(1, $param['url'], true) || !in_array(2, $param['url'], true) || !in_array(18, $param['url'], true)) {
                return admin_error('首页和个人资料权限必选');
            }

            asort( $param['url']);

            if (false !== $data->save($param)) {
                return admin_success('操作成功',[],URL_BACK);
            }
            return admin_error();
        }

        $menu = (new AdminMenu)->order('sort_number', 'asc')
            ->order('id', 'asc')
            ->column('*', 'id');
        $html = $this->authorizeHtml($menu, $data->url);

        $this->assign([
            'data' => $data,
            'html' => $html,
        ]);

        return $this->fetch();
    }

    /**
     * 删除
     * @param mixed $id
     * @param AdminRole $model
     * @return Response
     */
    public function del($id, AdminRole $model): Response
    {
        $result = $model::destroy(static function ($query) use ($id) {
            /** @var Query $query */
            $query->whereIn('id', $id);
        });

        return $result ? admin_success('删除成功', [], URL_RELOAD) : admin_error('删除失败');
    }

    /**
     * 启用
     * @param mixed $id
     * @param AdminRole $model
     * @return Json
     */
    public function enable($id, AdminRole $model): Json
    {
        $result = $model->whereIn('id', $id)->update(['status' => 1]);
        return $result ? admin_success('操作成功', [], URL_RELOAD) : admin_error();
    }

    /**
     * 禁用
     * @param mixed $id
     * @param AdminRole $model
     * @return Json
     */
    public function disable($id, AdminRole $model): Json
    {
        $result = $model->whereIn('id', $id)->update(['status' => 0]);
        return $result ? admin_success('操作成功', [], URL_RELOAD) : admin_error();
    }
}
