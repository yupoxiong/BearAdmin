<?php
/**
 * 后台角色控制器
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\controller;

use app\admin\model\AdminMenu;
use app\admin\model\AdminRole;
use app\admin\validate\AdminRoleValidate;
use think\Request;

class AdminRoleController extends Controller
{

    public function index(Request $request, AdminRole $model)
    {

        $param = $request->param();
        $data  = $model->scope('where', $param)
            ->paginate($this->admin['per_page'], false, ['query'=>$request->get()]);

        // 关键词，排序等赋值
        $this->assign($request->get());
        $this->assign([
            'data'  => $data,
            'page'  => $data->render(),
            'total' => $data->total(),
        ]);
        return $this->fetch();
    }

    //添加
    public function add(Request $request, AdminRole $model, AdminRoleValidate $validate)
    {
        if ($request->isPost()) {
            $param           = $request->param();
            $validate_result = $validate->scene('add')->check($param);
            if (!$validate_result) {
                return admin_error($validate->getError());
            }
            $result = $model::create($param);

            $url = URL_BACK;
            if (isset($param['_create']) && $param['_create'] == 1) {
                $url = URL_RELOAD;
            }

            return $result ? admin_success('添加成功', $url) : admin_error();
        }

        return $this->fetch();
    }

    //修改
    public function edit($id, Request $request, AdminRole $model, AdminRoleValidate $validate)
    {

        $data = $model::get($id);
        if ($request->isPost()) {
            $param           = $request->param();
            $validate_result = $validate->scene('edit')->check($param);
            if (!$validate_result) {
                return admin_error($validate->getError());
            }

            $result = $data->save($param);
            return $result ? admin_success() : admin_error();
        }

        $this->assign([
            'data' => $data
        ]);
        return $this->fetch('add');

    }

    //删除
    public function del($id, AdminRole $model)
    {
        if (count($model->noDeletionId) > 0) {
            if (is_array($id)) {
                if (array_intersect($model->noDeletionId, $id)) {
                    return admin_error('ID为' . implode(',', $model->noDeletionId) . '的数据无法删除');
                }
            } else if (in_array($id, $model->noDeletionId)) {
                return admin_error('ID为' . $id . '的数据无法删除');
            }
        }

        if ($model->softDelete) {
            $result = $model->whereIn('id', $id)->useSoftDelete('delete_time', time())->delete();
        } else {
            $result = $model->whereIn('id', $id)->delete();
        }

        return $result ? admin_success('操作成功', URL_RELOAD) : admin_error();
    }

    //角色授权
    public function access($id, Request $request, AdminRole $model)
    {
        $data = $model::get($id);
        if ($request->isPost()) {
            $param = $request->param();
            if (!isset($param['url'])) {
                return admin_error('请至少选择一项权限');
            }
            if (!in_array(1, $param['url'])) {
                return admin_error('首页权限必选');
            }

            asort( $param['url']);

            if (false !== $data->save($param)) {
                return admin_success();
            }
            return admin_error();
        }

        $menu = AdminMenu::order('sort_id', 'asc')->order('id', 'asc')->column('*', 'id');
        $html = $this->authorizeHtml($menu, $data->url);

        $this->assign([
            'data' => $data,
            'html' => $html,
        ]);

        return $this->fetch();
    }

    //启用
    public function enable($id, AdminRole $model)
    {
        $result = $model->whereIn('id', $id)->update(['status' => 1]);
        return $result ? admin_success('操作成功', URL_RELOAD) : admin_error();
    }

    //禁用
    public function disable($id, AdminRole $model)
    {
        $result = $model->whereIn('id', $id)->update(['status' => 0]);
        return $result ? admin_success('操作成功', URL_RELOAD) : admin_error();
    }


    //生成授权html
    protected function authorizeHtml($menu, $auth_menus = [])
    {
        foreach ($menu as $n => $t) {
            $menu[$n]['checked'] = in_array($t['id'], $auth_menus) ? ' checked' : '';
            $menu[$n]['level']   = $this->getLevel($t['id'], $menu);
            $menu[$n]['width']   = 100 - $menu[$n]['level'];
        }

        $this->initTree($menu);
        $this->text = [
            'other' => "<label class='checkbox'  >
                        <input \$checked  name='url[]' value='\$id' level='\$level'
                        onclick='javascript:checkNode(this);' type='checkbox'>
                       \$name
                   </label>",
            '0'     => [
                '0' => "<dl class='checkMod'>
                    <dt class='hd'>
                        <label class='checkbox'>
                            <input \$checked name='url[]' value='\$id' level='\$level'
                             onclick='javascript:checkNode(this);'
                             type='checkbox'>
                            \$name
                        </label>
                    </dt>
                    <dd class='bd'>",
                '1' => '</dd></dl>',
            ],
            '1'     => [
                '0' => "
                        <div class='menu_parent'>
                            <label class='checkbox'>
                                <input \$checked  name='url[]' value='\$id' level='\$level'
                                onclick='javascript:checkNode(this);' type='checkbox'>
                               \$name
                            </label>
                        </div>
                        <div class='rule_check' style='width: \$width%;'>",
                '1' => "</div><span class='child_row'></span>",
            ]
        ];
        return $this->getAuthTreeAccess(0);
    }
}
