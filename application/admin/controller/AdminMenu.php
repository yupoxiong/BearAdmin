<?php
/**
 * 后台菜单管理
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\controller;

use think\Db;
use tools\Tree;
use app\admin\model\AdminMenus;

class AdminMenu extends Base
{
    protected $logType = [
        0 => '不记录',
        1 => 'GET',
        2 => 'POST',
        3 => 'PUT',
        4 => 'DELETE'
    ];

    //列表
    public function index()
    {
        $model = new AdminMenus();
        $result      = $model
            ->order('sort_id asc, id asc')
            ->column('*', 'id');

        $tree       = new Tree();
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        
        foreach ($result as $n => $r) {

            $result[$n]['level']          = $tree->get_level($r['id'], $result);
            $result[$n]['parent_id_node'] = ($r['parent_id']) ? ' class="child-of-node-' . $r['parent_id'] . '"' : '';

            $result[$n]['str_manage'] =
                '<a href="' . url('admin_menu/edit', 'id=' . $r['id']) .
                '" class="btn btn-primary btn-xs" title="修改" data-toggle="tooltip"><i class="fa fa-pencil"></i></a>
                ';

            $result[$n]['str_manage'] .=
                '<a class="btn btn-danger btn-xs AjaxButton" data-id="'. $r['id'].'" data-url="del.html" data-toggle="tooltip" title="删除"><i class="fa fa-trash"></i></a>';
            $result[$n]['is_show']    = $r['is_show'] == 1
                ? '显示'
                : '隐藏';
            $result[$n]['log_type']   = $this->logType[$r['log_type']];
        }
        
        $str = "<tr id='node-\$id' data-level='\$level' \$parent_id_node>
                    <td><input type='checkbox' onclick='check_this(this)'
                     name='data-checkbox' data-id='\$id\' 
                    class='checkbox data-list-check' value='\$id' placeholder='选择/取消'>
                    </td>
                    <td>\$id</td>
                    <td>\$spacer  \$title</td>
                    <td>\$url</td>
                    <td>\$parent_id</td>
                    <td><i class='fa \$icon'></i><sapn>(\$icon)</sapn>
                    </td>
                    <td>\$sort_id</td>
                   <td>\$is_show</td>
                   <td>\$log_type</td>
                    <td class='td-do'>\$str_manage</td>
                </tr>";

        $tree->init($result);
        $menu_list = $tree->get_tree(0, $str);
        $this->assign([
            'menu_list' => $menu_list
        ]);
        return $this->fetch();
    }


    //添加
    public function add()
    {
        if ($this->request->isPost()) {

            $result = $this->validate($this->param, 'AdminMenu.add');
            if (true !== $result) {
                return $this->error($result);
            }

            $this->param['url'] = parse_name($this->param['url']);

            $menu = AdminMenus::create($this->param);
            if ($menu) {
                return $this->success();
            }
            return $this->error('菜单添加失败');
        }
        $parent_id = isset($this->param['parent_id']) ? $this->param['parent_id'] : 0;
        $selects   = $this->menu($parent_id);
        $this->assign([
            'logtype' => $this->logType,
            'selects'  => $selects
        ]);
        return $this->fetch();
    }


    //编辑
    public function edit()
    {
        $info = AdminMenus::get($this->id);

        //不允许修改的菜单，首页和个人资料页，还是多加点吧
        if ($this->id<60 && $this->uid != 1) {
            return $this->error('此菜单不允许修改');
        }
        if ($this->request->isPost()) {

            $result = $this->validate($this->param, 'AdminMenu.add');
            if (true !== $result) {
                return $this->error($result);
            }

            $this->param['url'] = parse_name($this->param['url']);

            if (false !== $info->save($this->param)) {
                return $this->success();
            }
            return $this->error('菜单修改失败');
        }

        $selects  = $this->menu($info->parent_id, $this->id);
        $this->assign([
            'logtype' => $this->logType,
            'selects'  => $selects,
            'info'     => $info
        ]);
        return $this->fetch('add');
    }


    //删除
    public function del()
    {

        $protected_ids = range(1,50);
        $id = $this->id;
        if (is_array($id)) {
            if (array_intersect($id, $protected_ids)) {
                return $this->error('包含系统数据，无法删除');
            }
        } else if (in_array($id, $protected_ids)) {
            return $this->error('包含系统数据，无法删除');
        }

        $admin_menu_son = AdminMenus::get(function ($query) use ($id) {
            if(is_array($id)){
                $query->whereIn('parent_id',$id);
            }else{
                $query->where('parent_id',$id);
            }
        });
        if ($admin_menu_son) {
            return $this->error('有子菜单不可删除！');
        }

        $id = $this->id;
        $result = AdminMenus::destroy(function ($query) use ($id) {
            $query->whereIn('id', $id);
        });
        if ($result) {
            return $this->deleteSuccess();
        }
        return $this->error('菜单删除失败');
    }


    function menu($selected = 1, $current_id = 0)
    {
        $array       = [];
        $tree        = new Tree();
        $admin_menus = new AdminMenus();
        $result      = $admin_menus->whereNotIn('id', $current_id)->order(["sort_id" => "asc", 'id' => 'asc'])->column('*', 'id');
        foreach ($result as $r) {
            $r['selected'] = $r['id'] == $selected ? 'selected' : '';
            $array[]       = $r;
        }

        $str = "<option value='\$id' \$selected >\$spacer \$title</option>";
        $tree->init($result);
        return $tree->get_tree(0, $str, $selected);
    }
}