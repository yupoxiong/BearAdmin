<?php
/**
 * 数据表管理
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\controller;

use think\Db;

class Database extends Base
{
    //列表
    public function index()
    {
        $list = db()->query('SHOW TABLE STATUS');
        $list = array_map('array_change_key_case', $list);
        $this->assign([
            'list' => $list,
            'total' => sizeof($list),
        ]);
        return $this->fetch();
    }
    
    //查看表详情
    public function view($name = null)
    {
        $this->showLeftMenu = false;
        if ($name) {


            $info  = Db::table($name)->getTableInfo($name);
       
            $fields = [];
            $datas = [];
            foreach ($info['fields'] as $field){
                $datas['name']=$field;

                $datas['type'] = str_replace('unsigned','<span class="badge">无符号</span>',$info['type'][$field]);
                $fields[] = $datas;
            }

            if(is_array($info['pk'])){
                $info['pk'] = implode(',',$info['pk']);
            }

            $this->assign([
                'fields'=>$fields,
                'table'=>$name,
                'pk' => $info['pk']
            ]);
            return $this->fetch();
        }
        return $this->error("请指定要查看的表");
    }


    //优化表
    public function optimize($name = null)
    {
        if ($name) {
            $Db = db();
            if (is_array($name)) {
                $name = implode('`,`', $name);
            }
            $list = $Db->query("OPTIMIZE TABLE `{$name}`");

            if ($list) {
                return $this->success("数据表`{$name}`优化成功");
            }
            return $this->error("数据表`{$name}`优化失败");

        }
        return $this->error("请指定要优化的表");
    }


    //修复表
    public function repair($name = null)
    {
        if ($name) {
            $Db = db();
            if (is_array($name)) {
                $name = implode('`,`', $name);
            }
            $list = $Db->query("REPAIR TABLE `{$name}`");

            if ($list) {
                return $this->success("数据表`{$name}`修复成功！");
            }
            return $this->error("数据表`{$name}`修复失败");
        }
        return $this->error("请指定要修复的表");
    }
}