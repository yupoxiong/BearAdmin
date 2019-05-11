<?php
/**
 * 用户等级管理
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\controller;

use app\common\model\UserLevels;
use app\common\model\Users;

class UserLevel extends Base
{
    public function index()
    {
        $model = new UserLevels();
        $list  = $model->withCount('user')->paginate($this->webData['list_rows']);

        $this->assign([
            'list'  => $list,
            'total' => $list->total(),
            'page'  => $list->render()
        ]);
        return $this->fetch();
    }


    public function add()
    {
        if ($this->request->isPost()) {
            $param          = $this->param;
            $resultValidate = $this->validate($param, 'UserLevel.admin_add');
            if (true !== $resultValidate) {
                return $this->error($resultValidate);
            }
            $result = UserLevels::create($param);
            if ($result) {
                return $this->success();
            }
            return $this->error();
        }
        return $this->fetch();
    }


    public function edit()
    {
        $info = UserLevels::get($this->id);
        if ($this->request->isPost()) {
            $resultValidate = $this->validate($this->param, 'UserLevel.admin_edit');
            if (true !== $resultValidate) {
                return $this->error($resultValidate);
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

        $id = $this->id;

        $users= new Users;
        $userCount = $users->whereIn('level_id',$id)->count();
        if($userCount>0){
            return $this->error('该等级下有用户，无法删除');
        }

        $result = UserLevels::destroy(function ($query) use ($id) {
            $query->whereIn('id', $id);
        });
        if ($result) {
            return $this->deleteSuccess();
        }
        return $this->error('删除失败');
    }


}