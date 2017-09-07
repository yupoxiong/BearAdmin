<?php
/**
 * 数据表管理
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\controller;

use think\Db;

class Database extends Base
{

    public function index()
    {
        $list = db()->query('SHOW TABLE STATUS');
        $list = array_map('array_change_key_case', $list);
        $this->assign([
            'lists' => $list,
            'total' => sizeof($list),
        ]);

        return $this->fetch();
    }


    //查看表详情
    public function view($name = null)
    {
        if ($name) {
            $table = db($name);
            $info  = $table->getTableInfo($name);
            $this->assign([
                'info'=>$info
            ]);
            return $this->fetch();
        }
        return $this->do_error("请指定要查看的表");
    }


    //优化表
    public function optimize($name = null)
    {
        if ($name) {
            $Db = db();
            if (is_array($name)) {
                $tables = implode('`,`', $name);
            }
            $list = $Db->query("OPTIMIZE TABLE `{$name}`");

            if ($list) {
                return $this->do_success("数据表优化成功");
            }
            return $this->do_error("数据表优化失败");

        }
        return $this->do_error("请指定要优化的表");

    }


    //修复表
    public function repair($name = null)
    {
        if ($name) {
            $Db = db();
            if (is_array($name)) {
                $tables = implode('`,`', $name);
            }
            $list = $Db->query("REPAIR TABLE `{$name}`");

            if ($list) {
                return $this->do_success("数据表修复成功！");
            }
            return $this->do_error("数据表修复失败");
        }
        return $this->error("请指定要修复的表");
    }

}