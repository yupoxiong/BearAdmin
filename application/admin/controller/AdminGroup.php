<?php
/**
 * 角色管理
 * @author yupoxiong <i@yufuping.com>
 */

namespace app\admin\controller;

use tools\Tree;
use app\admin\model\AdminMenus;
use app\admin\model\AdminGroups;
use app\admin\model\AdminGroupAccess;

class AdminGroup extends Base
{
    //列表
    public function index()
    {
        $model = new AdminGroups();
        $list       = $model->paginate($this->webData['list_rows']);
        $this->assign([
            'list' => $list,
            'total' => $list->total(),
            'page'  => $list->render()
        ]);
        return $this->fetch();
    }


    //添加
    public function add()
    {
        if ($this->request->isPost()) {
            $param  = $this->param;
            $result = $this->validate($param, 'AdminGroup.add');
            if (true !== $result) {
                return $this->error($result);
            }
            //默认写入首页和个人资料权限
            $param['rules'] = '1,2,44';
            if (AdminGroups::create($param)) {
                return $this->success();
            }
            return $this->error();
        }
        return $this->fetch();
    }


    //编辑
    public function edit()
    {
        if ($this->request->isPost()) {
            if ($this->id == 1) {
                if($this->uid != 1){
                    return $this->error('不允许修改管理员角色');
                }
                if(isset($this->param['stataus']) && $this->param['stataus']==0){
                    return $this->error('不允许禁用管理员角色');
                }
            }

            $result_validate = $this->validate($this->param, 'AdminGroup.edit');
            if (true !== $result_validate) {
                return $this->error($result_validate);
            }

            $info = AdminGroups::get($this->id);
            if ($info->save($this->param)) {
                return $this->success();
            }
            return $this->error();
        }

        $info = AdminGroups::get($this->id);
        $this->assign([
            'info' => $info
        ]);
        return $this->fetch('add');
    }


    //删除
    public function del()
    {
        $protected_ids = [1];
        $id = $this->id;
        if (is_array($id)) {
            if (array_intersect($id, $protected_ids)) {
                return $this->error('包含系统数据，无法删除');
            }
        } else if (in_array($id, $protected_ids)) {
            return $this->error('包含系统数据，无法删除');
        }


        $result = AdminGroups::destroy(function ($query) use ($id) {
            $query->whereIn('id', $id);
        });

        if ($result) {
            //删除用户与角色关联记录
            $auth_groups = new AdminGroupAccess();
            $result      = $auth_groups->whereIn('group_id', $this->id)->delete();
            if (false===$result) {
                return $this->error('角色关联数据删除失败！');
            }
            return $this->deleteSuccess();
        }
        return $this->error();
    }

    //授权
    public function access()
    {
        $info = AdminGroups::get($this->id);
        if (!$info) {
            return $this->error('角色不存在');
        }
        if ($this->id == 1 && $this->uid != 1) {
            return $this->error('此角色无法修改授权');
        }
        if ($this->request->isPost()) {
            if (!isset($this->param['menu_id'])) {
                return $this->error('请至少选择一项权限');
            }
            $data = [
                'rules' => implode(',', $this->param['menu_id'])
            ];
            if (false !== $info->save($data)) {
                return $this->success();
            }
            return $this->error();
        }

        $admin_menus = new AdminMenus();
        $menu        = $admin_menus
            ->order(["sort_id" => "asc", 'id' => 'asc'])
            ->column('*', 'id');

        $auth_menus = explode(',', $info->rules);
        $html = self::authorizeHtml($menu, $auth_menus);
        $this->assign([
            'role_name' => $info->name,
            'html'      => $html,
            'webData'   => $this->webData
        ]);
        return $this->fetch();
    }

    
    //生成授权html
    protected function authorizeHtml($menu, $auth_menus = [])
    {
        $tree = new Tree();
        foreach ($menu as $n => $t) {
            $menu[$n]['checked'] = (in_array($t['id'], $auth_menus)) ? ' checked' : '';
            $menu[$n]['level']   = $tree->get_level($t['id'], $menu);
            $menu[$n]['width']   = 100 - $menu[$n]['level'];
        }

        $tree->init($menu);
        $tree->text   = [
            'other' => "<label class='checkbox'  >
                        <input \$checked  name='menu_id[]' value='\$id' level='\$level'
                        onclick='javascript:checknode(this);' type='checkbox'>
                       \$title
                   </label>",
            '0'     => [
                '0' => "<dl class='checkmod'>
                    <dt class='hd'>
                        <label class='checkbox'>
                            <input \$checked name='menu_id[]' value='\$id' level='\$level'
                             onclick='javascript:checknode(this);'
                             type='checkbox'>
                            \$title
                        </label>
                    </dt>
                    <dd class='bd'>",
                '1' => "</dd></dl>",
            ],
            '1'     => [
                '0' => "
                        <div class='menu_parent'>
                            <label class='checkbox'>
                                <input \$checked  name='menu_id[]' value='\$id' level='\$level'
                                onclick='javascript:checknode(this);' type='checkbox'>
                               \$title
                            </label>
                        </div>
                        <div class='rule_check' style='width: \$width%;'>",
                '1' => "</div><span class='child_row'></span>",
            ]
        ];
        $info['html'] = $tree->get_authTree_access(0);
        $info['id']   = $this->id;
        return $info;
    }
}