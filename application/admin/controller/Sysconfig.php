<?php
/**
 * 后台系统设置
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\controller;

use app\common\model\Sysconfigs;

class Sysconfig extends Base
{
    //列表
    public function index()
    {
        $sysconfigs = new Sysconfigs();
        $configs = $sysconfigs->paginate($this->webData['list_rows']);

        $this->assign([
            'lists' => $configs,
            'total' => $configs->total(),
            'page'  => $configs->render()
        ]);
        return $this->fetch();
    }


    //添加设置
    public function add()
    {
        if ($this->request->isPost()) {
            $param   = $this->param;
            $result = $this->validate($param, 'Sysconfig.add');
            if (true !== $result) {
                return $this->error($result);
            }
            $result = Sysconfigs::create($param);
            if ($result) {
                return $this->success();
            }
            return $this->error();
        }
        return $this->fetch();
    }


    //修改设置
    public function edit()
    {
        $info = Sysconfigs::get($this->id);
        if ($this->request->isPost()) {
            $result = $this->validate($this->param, 'Sysconfig.edit');
            if (true !== $result) {
                return $this->error($result);
            }
            if ($info->save($this->param)) {
                return $this->success();
            }
            return $this->error();
        }

        $this->assign([
            'info' => $info,
        ]);
        return $this->fetch('add');
    }


    //删除设置
    public function del()
    {
        $protected_ids = range(1,100);
        if(in_array($this->id,$protected_ids)){
            return $this->error('系统限制，无法删除');
        }
        if (empty($this->id)) {
            return $this->error('请选择需要删除的数据');
        }
        $result = Sysconfigs::destroy($this->id);
        if ($result) {
            return $this->success();
        }
        return $this->error('删除失败');
    }
}