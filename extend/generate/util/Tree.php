<?php
/**
 * 后台tree（左侧菜单，菜单列表，权限操作）相关
 * @author yupoxiong<i@yufuping.com>
 */

namespace generate\util;

use app\admin\model\AdminUser;

trait Tree
{

    public $text, $html;
    /**
     * 生成树型结构所需要的2维数组
     * @var array
     */
    public $array;

    /**
     * 生成树型结构所需修饰符号，可以换成图片
     * @var array
     */
    public $icon = array('│', '├', '└');
    public $space = '&nbsp;&nbsp;';
    /**
     * @access private
     */
    public $ret = '';


    /**
     * 构造函数，初始化类
     * @param array $arr
     * @return bool
     */
    public function initTree($arr=[])
    {
        $this->array = $arr;
        $this->ret   = '';
        $this->html  = '';
        return is_array($arr);
    }

    /**
     * 得到树型结构
     * @param int $my_id ，表示获得这个ID下的所有子级
     * @param string $str 生成树型结构的基本代码，例如："<option value=\$id \$selected>\$spacer\$name</option>"
     * @param int $sid 被选中的ID，比如在做树型下拉框的时候需要用到
     * @param string $adds
     * @param string $str_group
     * @return string
     */
    public function getTree($my_id, $str, $sid = 0, $adds = '', $str_group = '')
    {
        $parent_id = '';
        $nstr      = '';
        $number    = 1;
        $child     = $this->getChild($my_id);

        if (is_array($child)) {
            $total = count($child);
            foreach ($child as $id => $value) {
                $j = $k = '';
                if ($number == $total) {
                    $j .= $this->icon[2];
                } else {
                    $j .= $this->icon[1];
                    $k = $adds ? $this->icon[0] : '';
                }
                $spacer   = $adds ? $adds . $j : '';
                $selected = $id == $sid ? 'selected' : '';
                @extract($value);
                $parent_id == 0 && $str_group ? eval("\$nstr = \"$str_group\";") : eval("\$nstr = \"$str\";");
                $this->ret .= $nstr;
                $space     = $this->space;
                $this->getTree($id, $str, $sid, $adds . $k . $space, $str_group);
                $number++;
            }
        }
        return $this->ret;
    }

    /**
     * 获取后台左侧菜单
     * @param $my_id
     * @param $current_id
     * @param $parent_ids
     * @return mixed
     */
    public function get_authTree($my_id, $current_id, $parent_ids)
    {

        $nstr  = '';
        $child = $this->getChild($my_id);
        if (is_array($child)) {
            $menu = current($child);
            //获取当前等级的html
            $text = isset($this->text[$menu['level']]) ? $this->text[$menu['level']] : end($this->text);

            foreach ($child as $k => $v) {
                @extract($v);

                //如果有下级菜单
                if ($this->getChild($k)) {

                    if (array_search($k, $parent_ids, true) !== false) {
                        //如果下级菜单是当前页面
                        eval("\$nstr = \"$text[1]\";");
                        $this->html .= $nstr;
                    } else {
                        //如果下级菜单不是当前页面
                        eval("\$nstr = \"$text[0]\";");
                        $this->html .= $nstr;
                    }


                    self::get_authTree($k, $current_id, $parent_ids);
                    eval("\$nstr = \"$text[2]\";");
                    $this->html .= $nstr;
                } else {
                    //顶级菜单，只有一级
                    if ($k == $current_id) {
                        $a = $this->text['current'];
                        eval("\$nstr = \"$a\";");

                        $this->html .= $nstr;
                    } else {
                        $a = $this->text['other'];
                        eval("\$nstr = \"$a\";");
                        $this->html .= $nstr;
                    }
                }
            }
        }

        return $this->html;
    }

    /**
     * 得到子级数组
     * @param int $pid
     * @return bool|array
     */
    public function getChild($pid)
    {
        $result = [];
        foreach ($this->array as $key => $value) {
            if ($value['parent_id'] == $pid)
                $result[$key] = $value;
        }
        return $result ?? false;
    }

    /**
     * 递归获取级别
     * @param int $id ID
     * @param array $array 所有菜单
     * @param int $i 所在级别
     * @return int
     */
    public function getLevel($id, $array, $i = 0)
    {
        if ($array[$id]['parent_id'] == 0 || empty($array[$array[$id]['parent_id']]) || $array[$id]['parent_id'] == $id) {
            return $i;
        } else {
            $i++;
            return self::getLevel($array[$id]['parent_id'], $array, $i);
        }
    }

    //获取权限树
    public function getAuthTreeAccess($my_id)
    {
        $id    = '';
        $nstr  = '';
        $child = $this->getChild($my_id);

        if (is_array($child)) {
            $level = current($child);
            $text  = isset($this->text[$level['level']]) ? $this->text[$level['level']] : end($this->text);

            foreach ($child as $k => $v) {
                @extract($v);

                if ($this->getChild($k)) {
                    eval("\$nstr = \"$text[0]\";");
                    $this->html .= $nstr;

                    self::getAuthTreeAccess($k);

                    eval("\$nstr = \"$text[1]\";");
                    $this->html .= $nstr;
                } else {
                    $a = $this->text['other'];
                    eval("\$nstr = \"$a\";");
                    $this->html .= $nstr;
                }
            }
        }
        return $this->html;
    }


    /**
     * 获取左侧菜单
     * @param $user AdminUser
     * @return mixed
     */
    protected function getLeftMenu($user)
    {
        $menu       = $user->getShowMenu();
        $max_level  = 0;
        $current_id = 1;
        $parent_ids = array(0 => 0);


        foreach ($menu as $k => $v) {
            if ($v['url'] == $this->url) {
                $parent_ids = $this->getMenuParent($menu, $v['id']);
                $current_id = $v['id'];
            }
        }
        if ($parent_ids == false) {
            $parent_ids = array(0 => 0);
        }


        foreach ($menu as $k => $v) {
            $url               = url($v['url']);
            $menu[$k]['icon']  = $v['icon'];
            $menu[$k]['level'] = $this->getLevel($v['id'], $menu);
            $max_level         = $max_level <= $menu[$k]['level'] ? $menu[$k]['level'] : $max_level;
            $menu[$k]['url']   = $url;
        }

        $this->initTree($menu);

        $text_base_one   = "<li class='treeview";
        $text_hover      = " active";
        $text_base_two   = "'><a href='javascript:void(0);'>
<i class='fa \$icon'></i>
<span>
\$name
</span>
                             <span class='pull-right-container'><i class='fa fa-angle-left pull-right'></i></span>
                             </a><ul class='treeview-menu";
        $text_open       = " menu-open";
        $text_base_three = "'>";

        $text_base_four = "<li";
        $text_hover_li  = " class='active'";
        $text_base_five = ">
                            <a href='\$url'>
                            <i class='fa \$icon'></i>
                            <span>\$name</span>
                            </a>
                         </li>";

        $text_0       = $text_base_one . $text_base_two . $text_base_three;
        $text_1       = $text_base_one . $text_hover . $text_base_two . $text_open . $text_base_three;
        $text_2       = "</ul></li>";
        $text_current = $text_base_four . $text_hover_li . $text_base_five;
        $text_other   = $text_base_four . $text_base_five;

        for ($i = 0; $i <= $max_level; $i++) {
            $this->text[$i]['0'] = $text_0;
            $this->text[$i]['1'] = $text_1;
            $this->text[$i]['2'] = $text_2;
        }
        $this->text['current'] = $text_current;
        $this->text['other']   = $text_other;

        return $this->get_authTree(0, $current_id, $parent_ids);
    }

    //获取父级菜单
    protected function getMenuParent($arr, $my_id, $parent_ids = array())
    {
        $a = array();
        if (is_array($arr)) {
            foreach ($arr as $id => $a) {
                if ($a['id'] === $my_id) {
                    if ($a['parent_id'] !== 0) {
                        array_push($parent_ids, $a['parent_id']);
                        $parent_ids = $this->getMenuParent($arr, $a['parent_id'], $parent_ids);
                    }
                }
            }
        }
        return !empty($parent_ids) ? $parent_ids : false;
    }

    //获取当前导航
    protected function getCurrentNav($arr, $my_id, $parent_ids = array(), $current_nav = '')
    {
        $a = array();
        if (is_array($arr)) {
            foreach ($arr as $id => $a) {
                if ($a['id'] === $my_id) {
                    if ($a['parent_id'] !== 0) {
                        array_push($parent_ids, $a['parent_id']);
                        $ru          = '<li><a><i class="fa ' . $a['icon'] . '"></i> ' . $a['name'] . '</a></li>';
                        $current_nav = $ru . $current_nav;
                        $temp_result = $this->getCurrentNav($arr, $a['parent_id'], $parent_ids, $current_nav);
                        $parent_ids  = $temp_result[0];
                        $current_nav = $temp_result[1];
                    }
                }
            }
        }
        return !empty([$parent_ids, $current_nav]) ? [$parent_ids, $current_nav] : false;
    }

}

