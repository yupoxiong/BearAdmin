<?php
/**
 * 后台菜单控制器
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
use app\admin\validate\AdminMenuValidate;

class AdminMenuController extends AdminBaseController
{
    /**
     * 列表
     * @param AdminMenu $model
     * @return string
     * @throws Exception
     */
    public function index(AdminMenu $model): string
    {
        $this->assign('data', $this->getMenuTree($model));

        return $this->fetch();
    }

    /**
     * 添加
     * @param Request $request
     * @param AdminMenu $model
     * @param AdminMenuValidate $validate
     * @return string|Json
     * @throws Exception
     */
    public function add(Request $request, AdminMenu $model, AdminMenuValidate $validate)
    {
        if ($request->isPost()) {
            $param = $request->param();
            $check = $validate->scene('admin_add')->check($param);
            if (!$check) {
                return admin_error($validate->getError());
            }

            $result   = $model::create($param);
            $redirect = isset($param['_create']) && (int)$param['_create'] === 1 ? URL_RELOAD : URL_BACK;

            return $result ? admin_success('添加成功', [], $redirect) : admin_error('添加失败');
        }

        $this->assign([
            'parent_list'     => $this->getSelectList($model),
            'log_method_list' => $model::$logMethodList,
        ]);

        return $this->fetch();
    }

    /**
     * 修改
     * @param $id
     * @param Request $request
     * @param AdminMenu $model
     * @param AdminMenuValidate $validate
     * @return string|Json
     * @throws Exception
     */
    public function edit($id, Request $request, AdminMenu $model, AdminMenuValidate $validate)
    {
        /** @var AdminMenu $data */
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
            'log_method_list' => $model::$logMethodList,
            'data'            => $data,
            'parent_list'     => $this->getSelectList($model, $data->parent_id),
        ]);

        return $this->fetch('add');
    }

    /**
     * 删除
     *
     * @param mixed $id
     * @param AdminMenu $model
     * @return Response
     */
    public function del($id, AdminMenu $model): Response
    {
        $check = $model->inNoDeletionIds($id);
        if (false !== $check) {
            return admin_error('ID为' . $check . '的数据不能被删除');
        }

        $result = $model::destroy(static function ($query) use ($id) {
            /** @var Query $query */
            $query->whereIn('id', $id);
        });

        return $result ? admin_success('删除成功', [], URL_RELOAD) : admin_error('删除失败');
    }

    /**
     * @param AdminMenu $model
     * @return string
     */
    protected function getMenuTree(AdminMenu $model): string
    {
        // 查询所有菜单并以树的形式显示
        $result = $model->order('sort_number', 'asc')
            ->order('id', 'asc')
            ->column('*', 'id');

        foreach ($result as $n => $r) {
            $result[$n]['level']          = $this->getLevel($r['id'], $result);
            $result[$n]['parent_id_node'] = $r['parent_id'] ? ' class="child-of-node-' . $r['parent_id'] . '"' : '';
            $result[$n]['str_manage']     = '<a href="' . url('edit', ['id' => $r['id']]) . '" class="btn btn-primary btn-xs" title="修改" data-toggle="tooltip"><i class="fas fa-pen"></i>修改</a> ';
            $result[$n]['str_manage']     .= '<button class="btn btn-danger btn-xs AjaxButton" data-id="' . $r['id'] . '" data-url="del.html"  data-confirm-title="删除确认" data-confirm-content=\'您确定要删除ID为 <span class="text-red"> ' . $r['id'] . ' </span> 的数据吗\'  data-toggle="tooltip" title="删除"><i class="fas fa-trash"></i>删除</button>';
            $result[$n]['is_show']        = (int)$r['is_show'] === 1 ? '显示' : '隐藏';
            $result[$n]['log_method']     = $r['log_method'];
        }

        $str = "<tr id='node-\$id' data-level='\$level' \$parent_id_node><td><input type='checkbox' onclick='checkThis(this)'
                     name='dataCheckbox' data-id='\$id\' class='checkbox dataListCheck' value='\$id' placeholder='选择/取消'>
                    </td><td>\$id</td><td>\$spacer\$name</td><td>\$url</td>
                    <td>\$parent_id</td><td><i class='fa \$icon'></i><span>(\$icon)</span></td>
                    <td>\$sort_number</td><td>\$is_show</td><td>\$log_method</td><td class='td-do'>\$str_manage</td></tr>";

        $this->initTree($result);
        return $this->getTree(0, $str);
    }
}
