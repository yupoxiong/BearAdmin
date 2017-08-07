<?php
/**
 * 角色管理
 * @author yupoxiong <i@yufuping.com>
 * @version 1.0
 */

namespace app\admin\controller;

use app\admin\auth\Tree;
use think\Db;
use app\common\model\AuthGroups;
use app\common\model\AuthRules;
use app\common\model\AuthGroupAccess;

class Role extends Base
{
    public $validate = [
        ['title|角色名称', 'require|max:25|token'],
        ['status|角色状态', 'require'],
    ];

    /**
     * 后台角色列表
     */
    public function index()
    {
        $authgroups = new AuthGroups();
        $roles      = $authgroups->paginate(10);

        $this->assign([
            'lists'    => $roles,
            'total'    => $roles->total(),
            'page'     => $roles->render()
        ]);
        return $this->fetch();
    }


    /**
     * 添加角色
     * @return string|void
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $post   = $this->post;
            $result = $this->validate($post, $this->validate);
            if (true !== $result) {
                return $this->do_error($result);
            }
            //默认写入首页和个人资料权限
            $post['rules'] = '1,35,37';

            $role = new AuthGroups();
            if ($role->create($post)) {
                return $this->do_success();
            }
            return $this->do_error();
        }
        
        return $this->fetch();
    }

    /**
     * 编辑角色
     */
    public function edit()
    {
        if ($this->request->isPost()) {
            $post   = $this->post;
            $result = $this->validate($post, $this->validate);
            if (true !== $result) {
                return $this->do_error($result);
            }

            $info = AuthGroups::get($this->id);
            if ($info->save($post)) {
                return $this->do_success();
            }
            return $this->do_error();

        }

        $info = AuthGroups::get($this->id);
        $this->assign([
            'info'     => $info
        ]);
        return $this->fetch();

    }

    /**
     * 删除角色
     */
    public function del()
    {
        $result = AuthGroups::get($this->id);
        if ($result) {

            //删除用户与角色关联记录
            $auth_groups = new AuthGroupAccess();

            $auth_groups->where(['group_id' => $this->id])->delete();

            if (!$auth_groups) {
                return $this->do_error('角色关联数据删除失败！');
            }
            if ($result->delete()) {
                return $this->do_success();
            }
            return $this->do_error();
        }
        return $this->do_error('没有数据！');
    }

    /**
     * 角色授权
     * @return mixed|void
     * @throws \think\Exception
     */
    public function access()
    {
        if (!$this->id) {
            return $this->do_error('角色不存在');
        }

        if ($this->request->isPost()) {
            $post    = $this->post;
            $menu_id = array();
            if (isset($post['menu_id'])) {
                $menu_id = $post['menu_id'];
            }
            $auth_group          = AuthGroups::get($this->id);
            $rules_data['rules'] = '';
            if (is_array($menu_id) && count($menu_id) > 0) {
                //这个地方有问题，既然数据库里存的是rules的id，那就按照这个来
                //原计划是菜单和动作合并成rules，暂且只用菜单好了
                $auth_rules          = new AuthRules();
                $rules_data['rules'] = implode(',', $auth_rules->whereIn('menu_id', $menu_id)->column('id'));
            }
            if (false !== $auth_group->save($rules_data)) {
                return $this->do_success();
            }
            return $this->do_error();
        } else {

            $role_id = $this->id;
            $role    = AuthGroups::get($role_id);
            $menu    = Db::name('admin_menus')
                ->order(["sort_id" => "asc", 'menu_id' => 'asc'])
                ->column('*', 'menu_id');

            $auth_group  = new AuthGroups();
            $group_rules = explode(',', $auth_group->where('id', $this->id)->value('rules'));
            $auth_rules  = new AuthRules();
            $auth_menus  = $auth_rules->whereIn('id', $group_rules)->column('menu_id');
            $info        = self::authorizeHtml($menu, $auth_menus);

            $this->assign([
                'role_name' => $role->title,
                'info'      => $info,
                'web_data'  => $this->web_data
            ]);
            return $this->fetch();
        }
    }

    /**
     * 生成授权html
     * @param $menu
     * @param array $auth_menus
     * @return mixed
     */
    protected function authorizeHtml($menu, $auth_menus = [])
    {
        $tree = new Tree();
        foreach ($menu as $n => $t) {
            $menu[$n]['checked'] = (in_array($t['menu_id'], $auth_menus)) ? ' checked' : '';
            $menu[$n]['level']   = $tree->get_level($t['menu_id'], $menu);
            $menu[$n]['width']   = 100 - $menu[$n]['level'];
        }

        $tree->init($menu);
        $tree->text   = [
            'other' => "<label class='checkbox'  >
                        <input \$checked  name='menu_id[]' value='\$menu_id' level='\$level'
                        onclick='javascript:checknode(this);' type='checkbox'>
                       \$title
                   </label>",
            '0'     => [
                '0' => "<dl class='checkmod'>
                    <dt class='hd'>
                        <label class='checkbox'>
                            <input \$checked name='menu_id[]' value='\$menu_id' level='\$level'
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
                                <input \$checked  name='menu_id[]' value='\$menu_id' level='\$level'
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