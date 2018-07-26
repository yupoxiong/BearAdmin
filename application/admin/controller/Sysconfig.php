<?php
/**
 * 后台系统设置
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\controller;

use app\admin\model\Sysconfigs;

class Sysconfig extends Base
{
    public function index()
    {
        $model = new Sysconfigs();
        $list    = $model->paginate($this->webData['list_rows']);

        $this->assign([
            'list' => $list,
            'total' => $list->total(),
            'page'  => $list->render()
        ]);
        return $this->fetch();
    }


    public function add()
    {
        if ($this->request->isPost()) {
            $param  = $this->param;
            $result_validate = $this->validate($param, 'Sysconfig.add');
            if (true !== $result_validate) {
                return $this->error($result_validate);
            }
            $result = Sysconfigs::create($param);
            if ($result) {
                return $this->success();
            }
            return $this->error();
        }
        return $this->fetch();
    }


    public function edit()
    {
        $info = Sysconfigs::get($this->id);
        if ($this->request->isPost()) {
            $result_validate = $this->validate($this->param, 'Sysconfig.edit');
            if (true !== $result_validate) {
                return $this->error($result_validate);
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