<?php

/**
 * 通用树生成
 * @author 佚名
 */

namespace tools;

class Tree
{
    public $text, $html;
    /**
     * 生成树型结构所需要的2维数组
     * @var array
     */
    public $arr = array();

    /**
     * 生成树型结构所需修饰符号，可以换成图片
     * @var array
     */
    public $icon = array('│', '├', '└');
    public $nbsp = "&nbsp;";
    /**
     * @access private
     */
    public $ret = '';


    /**
     * 构造函数，初始化类
     * @param array 2维数组，例如：
     * array(
     *      1 => array('id'=>'1','parentid'=>0,'name'=>'一级栏目一'),
     *      2 => array('id'=>'2','parentid'=>0,'name'=>'一级栏目二'),
     *      3 => array('id'=>'3','parentid'=>1,'name'=>'二级栏目一'),
     *      4 => array('id'=>'4','parentid'=>1,'name'=>'二级栏目二'),
     *      5 => array('id'=>'5','parentid'=>2,'name'=>'二级栏目三'),
     *      6 => array('id'=>'6','parentid'=>3,'name'=>'三级栏目一'),
     *      7 => array('id'=>'7','parentid'=>3,'name'=>'三级栏目二')
     *      )
     * @return bool
     */
    public function init($arr = array())
    {
        $this->arr = $arr;
        $this->ret = '';
        return is_array($arr);
    }

    /**
     * 得到树型结构
     * @param int $myid ，表示获得这个ID下的所有子级
     * @param string $str 生成树型结构的基本代码，例如："<option value=\$id \$selected>\$spacer\$name</option>"
     * @param int $sid 被选中的ID，比如在做树型下拉框的时候需要用到
     * @param string $adds
     * @param string $str_group
     * @return string
     */
    public function get_tree($myid, $str, $sid = 0, $adds = '', $str_group = '')
    {
        $parent_id = '';
        $nstr      = '';
        $number    = 0;
        $child     = $this->get_child($myid);

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
                extract($value);
                $parent_id == 0 && $str_group ? eval("\$nstr = \"$str_group\";") : eval("\$nstr = \"$str\";");
                $this->ret .= $nstr;
                $nbsp = $this->nbsp;
                $this->get_tree($id, $str, $sid, $adds . $k . $nbsp, $str_group);
                $number++;
            }
        }
        return $this->ret;
    }

    /**
     * 获取后台左侧菜单
     * @param $myid
     * @param $current_id
     * @param $parent_ids
     * @return mixed
     */
    public function get_authTree($myid, $current_id, $parent_ids)
    {
        $nstr  = '';
        $child = $this->get_child($myid);
        if (is_array($child)) {
            $menu = current($child);
            //获取当前等级的html
            $text = isset($this->text[$menu['level']]) ? $this->text[$menu['level']] : end($this->text);

            foreach ($child as $k => $v) {
                @extract($v);

                //如果有下级菜单
                if ($this->get_child($k)) {

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
     * @param int $myid
     * @return array
     */
    public function get_child($myid)
    {
        $a = $newarr = array();
        if (is_array($this->arr)) {
            foreach ($this->arr as $id => $a) {
                if ($a['parent_id'] == $myid)
                    $newarr[$id] = $a;
            }
        }
        return $newarr ? $newarr : false;
    }

    /**
     * 递归获取级别
     * @param int $id ID
     * @param array $array 所有菜单
     * @param int $i 所在级别
     * @return int
     */
    public function get_level($id, $array = array(), $i = 0)
    {
        if ($array[$id]['parent_id'] == 0 || empty($array[$array[$id]['parent_id']]) || $array[$id]['parent_id'] == $id) {
            return $i;
        } else {
            $i++;
            return self::get_level($array[$id]['parent_id'], $array, $i);
        }
    }

    public function get_authTree_access($myid)
    {
        $id    = '';
        $nstr  = '';
        $child = $this->get_child($myid);

        if (is_array($child)) {
            $level = current($child);
            $text  = isset($this->text[$level['level']]) ? $this->text[$level['level']] : end($this->text);

            foreach ($child as $k => $v) {
                @extract($v);

                if ($this->get_child($k)) {
                    eval("\$nstr = \"$text[0]\";");
                    $this->html .= $nstr;

                    self::get_authTree_access($k);

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
}