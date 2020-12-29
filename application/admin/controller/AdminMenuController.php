<?php
/**
 * 后台菜单控制器
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\controller;

use think\Request;
use app\admin\model\AdminMenu;

use app\admin\validate\AdminMenuValidate;

class AdminMenuController extends Controller
{

    public function index(Request $request, AdminMenu $model)
    {
        //查询所有菜单并以树的形式显示
        $result = $model->order('sort_id asc, id asc')->column('*', 'id');
        foreach ($result as $n => $r) {
            $result[$n]['level']          = $this->getLevel($r['id'], $result);
            $result[$n]['parent_id_node'] = $r['parent_id'] ? ' class="child-of-node-' . $r['parent_id'] . '"' : '';
            $result[$n]['str_manage']     = '<a href="' . url('edit', ['id' => $r['id']]) . '" class="btn btn-primary btn-xs" title="修改" data-toggle="tooltip"><i class="fa fa-pencil"></i></a> ';
            $result[$n]['str_manage']     .= '<a class="btn btn-danger btn-xs AjaxButton" data-id="' . $r['id'] . '" data-url="del.html"  data-confirm-title="删除确认" data-confirm-content=\'您确定要删除ID为 <span class="text-red"> ' . $r['id'] . ' </span> 的数据吗\'  data-toggle="tooltip" title="删除"><i class="fa fa-trash"></i></a>';
            $result[$n]['is_show']        = (int)$r['is_show'] === 1 ? '显示' : '隐藏';
            $result[$n]['log_method']     = $r['log_method'];
        }

        $str = "<tr id='node-\$id' data-level='\$level' \$parent_id_node><td><input type='checkbox' onclick='checkThis(this)'
                     name='data-checkbox' data-id='\$id\' class='checkbox data-list-check' value='\$id' placeholder='选择/取消'>
                    </td><td>\$id</td><td>\$spacer\$name</td><td>\$url</td>
                    <td>\$parent_id</td><td><i class='fa \$icon'></i><span>(\$icon)</span></td>
                    <td>\$sort_id</td><td>\$is_show</td><td>\$log_method</td><td class='td-do'>\$str_manage</td></tr>";

        $this->initTree($result);
        $data = $this->getTree(0, $str);
        $this->assign('data', $data);
        return $this->fetch('index');
    }

    public function add(Request $request, AdminMenu $model, AdminMenuValidate $validate)
    {
        if ($request->isPost()) {
            $param           = $request->param();
            $validate_result = $validate->scene('add')->check($param);
            if (!$validate_result) {
                return admin_error($validate->getError());
            }
            $result = $model::create($param);

            //如果
            if (isset($param['is_more']) && $param['is_more'] == 1) {
                $name = $param['more_name'];
                $url  = explode('/', $param['url']);
                $str  = '/';
                $data = [
                    [
                        'parent_id' => $result->id,
                        'name'      => '添加' . $name,
                        'url'       => $url[0] . $str . $url[1] . $str . 'add',
                        'icon'      => 'fa-plus',
                        'is_show'   => 0,
                        'log_method'  => 'POST',
                    ],
                    [
                        'parent_id' => $result->id,
                        'name'      => '修改' . $name,
                        'url'       => $url[0] . $str . $url[1] . $str . 'edit',
                        'icon'      => 'fa-pencil',
                        'is_show'   => 0,
                        'log_method'  => 'POST',
                    ],
                    [
                        'parent_id' => $result->id,
                        'name'      => '删除' . $name,
                        'url'       => $url[0] . $str . $url[1] . $str . 'del',
                        'icon'      => 'fa-trash',
                        'is_show'   => 0,
                        'log_method'  =>'POST',
                    ]
                ];

                $model->saveAll($data);

            }
            unset($url);
            $url = URL_BACK;
            if (isset($param['_create']) && $param['_create'] == 1) {
                $url = URL_RELOAD;
            }

            return $result ? admin_success('添加成功', $url) : admin_error();
        }

        $parent_id = $request->param('parent_id') ?? 0;
        $parents   = $this->menu($parent_id);
        $this->assign([
            'parents'    => $parents,
            'log_method' => $model->logMethod
        ]);

        return $this->fetch();
    }

    public function edit($id, Request $request, AdminMenu $model, AdminMenuValidate $validate)
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

        $parent_id = $data->parent_id;
        $parents   = $this->menu($parent_id);
        $this->assign([
            'data'       => $data,
            'parents'    => $parents,
            'log_method' => $model->logMethod,
        ]);
        return $this->fetch('add');
    }

    public function del($id, Request $request, AdminMenu $model)
    {
        //判断是否有子菜单
        $have_son = $model->whereIn('parent_id', $id)->find();
        if ($have_son) {
            return admin_error('有子菜单不可删除！');
        }

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

    //菜单选择 select树形选择
    protected function menu($selected = 1, $current_id = 0): string
    {
        $result = AdminMenu::where('id', '<>', $current_id)->order('sort_id', 'asc')->order('id', 'asc')->column('id,parent_id,name,sort_id', 'id');
        foreach ($result as $r) {
            $r['selected'] = (int)$r['id'] === (int)$selected ? 'selected' : '';
        }
        $str = "<option value='\$id' \$selected >\$spacer \$name</option>";
        $this->initTree($result);
        return $this->getTree(0, $str, $selected);
    }

}