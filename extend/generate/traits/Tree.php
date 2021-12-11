<?php
/**
 * 前台tree相关
 * @author yupoxiong<i@yufuping.com>
 */
namespace generate\traits;

trait Tree
{
    public string $text;
    public string $html;
    /**
     * 生成树型结构所需要的2维数组
     * @var array
     */
    public array $array;

    /**
     * 生成树型结构所需修饰符号，可以换成图片
     * @var array
     */
    public array $icon = array('│', '├', '└');
    public string $space = '&nbsp;&nbsp;';
    /**
     * @access private
     */
    public string $ret = '';

    /**
     * 构造函数，初始化类
     * @param array $arr
     * @return bool
     */
    public function initTree($arr=[]): bool
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
    public function getTree($my_id, $str, $sid = 0, $adds = '', $str_group = ''): string
    {
        $parent_id = '';
        $n_str      = '';
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
                extract($value);
                0 == $parent_id && $str_group ? eval("\$n_str = \"$str_group\";") : eval("\$n_str = \"$str\";");
                $this->ret .= $n_str;
                $space     = $this->space;
                $this->getTree($id, $str, $sid, $adds . $k . $space, $str_group);
                $number++;
            }
        }
        return $this->ret;
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
            if ($value['parent_id'] == $pid) {
                $result[$key] = $value;
            }
        }
        return $result;
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
        if ($array[$id]['parent_id'] == 0 || empty($array[$array[$id]['parent_id']]) || $id == $array[$id]['parent_id']) {
            return $i;
        }
        $i++;
        return self::getLevel($array[$id]['parent_id'], $array, $i);
    }
}
