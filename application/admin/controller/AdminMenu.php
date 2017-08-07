<?php
/**
 * 后台菜单管理
 * @author yupoxiong<i@yufuping.com>
 * @version 1.0
 */

namespace app\admin\controller;

use app\admin\auth\Tree;
use think\Db;
use app\common\model\AdminMenus;

class AdminMenu extends Base
{
    /**
     * 数据验证
     * @var array
     */
    public $validate = [
        ['parent_id|上级菜单', 'require|max:25|token'],
        ['title|菜单名称', 'require|max:30'],
        ['url|url', 'require|max:100'],
        ['icon|菜单图标', 'require|max:30'],
        ['sort_id|菜单排序', 'require|number|max:4'],
        ['is_show|菜单状态', 'require'],
        ['log_type|日志请求方式', 'require'],
    ]
    , $protected_menu;

    /**
     * 不能删除和修改的菜单
     * AdminMenu constructor.
     */
    public function __construct()
    {
        $this->protected_menu = range(1,60);
        parent::__construct();
    }

    /**
     * 菜单列表
     * @return mixed
     */
    public function index()
    {

        $result = Db::name('AdminMenus')
            ->order(["sort_id" => "asc", 'menu_id' => 'asc'])
            ->column('*', 'menu_id');

        $tree       = new Tree();
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';

        foreach ($result as $n => $r) {

            $result[$n]['level']          = $tree->get_level($r['menu_id'], $result);
            $result[$n]['parent_id_node'] = ($r['parent_id']) ? ' class="child-of-node-' . $r['parent_id'] . '"' : '';

            $result[$n]['str_manage'] =
                '<a href="' . url($this->do_url . 'edit', 'id=' . $r['menu_id']) . '" class="btn btn-default btn-sm margin">
                <i class="fa fa-edit"></i>
                编辑
                </a>';

            $result[$n]['str_manage'] .=
                '<a class="btn btn-danger btn-sm margin" data-toggle="modal" data-target="#modal" title="删除" onclick="del(' . $r['menu_id'] . ')">
                <i class="fa fa-close"></i>
                删除
            </a>';
            $result[$n]['is_show'] = $r['is_show'] == 1
                ? '显示'
                : '隐藏';
        }
        $str = "<tr id='node-\$menu_id' data-level='\$level' \$parent_id_node>
                    <td>\$menu_id</td>
                    <td>\$spacer  \$title</td>
                    <td>\$url</td>
                    <td>\$parent_id</td>
                    <td><i class='fa \$icon'></i><sapn>(\$icon)</sapn>
                    </td>
                    <td>\$sort_id</td>
                   <td>\$is_show</td>
                    <td>\$str_manage</td>
                </tr>";

        $tree->init($result);
        $menu_list = $tree->get_tree(0, $str);
        $this->assign([
            'menu_list' => $menu_list
        ]);
        return $this->fetch();
    }


    /**
     * 添加菜单
     * @return mixed|void
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $post = $this->post;

            //添加url数据库唯一规则
            array_push($this->validate, ['url|url', 'unique:AdminMenus,url'], ['url|url', 'unique:AuthRules,name']);

            $result = $this->validate($post, $this->validate);

            if (true !== $result) {
                return $this->do_error($result);
            }

            unset($post['__token__'], $post['save']);
            $this->post['url'] = strtolower($this->post['url']);

            $menu_data = $post;

            $rule_data = [
                'title' => $this->post['title'],
                'name'  => $this->post['url']
            ];

            $menu = AdminMenus::create($menu_data);

            if ($menu) {
                if ($menu->authRule()->save($rule_data)) {
                    return $this->do_success();
                }
                return $this->do_error('关联权限添加失败');
            }
            return $this->do_error('菜单添加失败');

        } else {
            $parent_id = isset($this->param['parent_id']) ? $this->param['parent_id'] : 0;
            $selects   = $this->menu($parent_id);
            $requests  = Db::name('request_type')->order('id asc')->select();
            $this->assign([
                'requests' => $requests,
                'selects'  => $selects
            ]);

            return $this->fetch();
        }
    }

    /**
     * 编辑菜单
     * @return mixed|void
     */
    public function edit()
    {
        //不允许修改的菜单，首页和个人资料页，还是多加点吧
        if (in_array($this->id, $this->protected_menu) && $this->web_data['user_info']['user_id']!=1){
            return $this->do_error('此菜单不允许修改');
        }
        if ($this->request->isPost()) {
            $post = $this->post;

            array_push($this->validate, ['url|url', 'unique:AdminMenus,url^menu_id'], ['url|url', 'unique:AuthRules,name,' . $this->post['menu_id'] . ',menu_id']);
            //验证
            $result = $this->validate($post, $this->validate);

            if (true !== $result) {
                return $this->do_error($result);
            }

            $this->post['url'] = strtolower($this->post['url']);
            $menu_data         = [
                'parent_id' => $this->post['parent_id'],
                'title'     => $this->post['title'],
                'url'       => $this->post['url'],
                'icon'      => $this->post['icon'],
                'sort_id'   => $this->post['sort_id'],
                'is_show'   => $this->post['is_show'],
                'log_type'  => $this->post['log_type']
            ];

            $rule_data = [
                'title' => $this->post['title'],
                'name'  => $this->post['url']
            ];

            $admin_menu = AdminMenus::get($post['menu_id']);

            if (false !== $admin_menu->save($menu_data)) {
                if (false !== $admin_menu->authRule->save($rule_data)) {
                    return $this->do_success();
                }
                return $this->do_error('关联权限修改失败');
            }
            return $this->do_error('菜单修改失败');

        } else {
            $info      = AdminMenus::get($this->id);
            $parent_id = $info['parent_id'];
            $requests  = Db::name('request_type')->order('id asc')->select();
            $selects   = $this->menu($parent_id);
            $this->assign([
                'requests' => $requests,
                'selects'  => $selects,
                'info'     => $info
            ]);

            return $this->fetch();
        }
    }


    /**
     * 删除菜单
     */
    public function del()
    {

        if (in_array($this->id, $this->protected_menu) && $this->web_data['user_info']['user_id']!=1) {
            return $this->do_error('此菜单不允许删除');
        }
        $map_son        = ['parent_id' => $this->id];
        $admin_menu_son = AdminMenus::get($map_son);
        if ($admin_menu_son) {
            return $this->do_error('有子菜单不可删除！');
        }

        $admin_menu = AdminMenus::get($this->id);
        if (!$admin_menu) {
            return $this->do_error('菜单不存在！');
        }

        if ($admin_menu->delete()) {
            if ($admin_menu->authRule->delete()) {
                return $this->do_success();
            }
            return $this->do_error('菜单关联权限删除失败');
        }
        return $this->do_error('菜单删除失败');
    }


    function menu($selected = 1)
    {
        $array  = [];  //未定义会报错
        $result = Db::name('AdminMenus')->order(["sort_id" => "asc", 'menu_id' => 'asc'])->column('*', 'menu_id');

        $tree = new Tree();

        foreach ($result as $r) {
            $r['selected'] = $r['menu_id'] == $selected ? 'selected' : '';
            $array[]       = $r;
        }

        $str = "<option value='\$menu_id' \$selected >\$spacer \$title</option>";

        $tree->init($result);

        $parent_id = isset($where['parent_id']) ? $where['parent_id'] : 0;
        return $tree->get_tree($parent_id, $str, $selected);
    }
}