<?php
/**
 * 后台系统设置
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\controller;

use app\admin\model\Sysconfigs;

class Sysconfig extends Base
{
    //列表
    public function index()
    {
        $sysconfigs = new Sysconfigs();
        $configs    = $sysconfigs->paginate($this->webData['list_rows']);

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
            $param  = $this->param;
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
            if (false !== $info->save($this->param)) {
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
        $protected_ids = range(1, 100);
        $id            = $this->id;

        if (is_array($id)) {
            if (array_intersect($id, $protected_ids)) {
                return $this->error('包含系统数据，无法删除');
            }
        } else if (in_array($id, $protected_ids)) {
            return $this->error('包含系统数据，无法删除');
        }
        
        $result = Sysconfigs::destroy(function ($query) use ($id) {
            $query->whereIn('id', $id);
        });
        if ($result) {
            return $this->deleteSuccess();
        }
        return $this->error('删除失败');
    }
}