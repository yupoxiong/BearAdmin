<?php
/**
 * 后台用户管理
 * @author yupoxiong <i@yufuping.com>
 */

namespace app\admin\controller;

use app\common\model\AdminUsers;
use app\common\model\AdminGroups;

class AdminUser extends Base
{
    //列表
    public function index()
    {
        $model = new AdminUsers();
        $page_param = ['query' => []];
        if (isset($this->param['keywords']) && !empty($this->param['keywords'])) {
            $page_param['query']['keywords'] = $this->param['keywords'];

            $model->whereLike('name|nick_name|email|mobile', "%" . $this->param['keywords'] . "%");
            $this->assign('keywords', $this->param['keywords']);
        }

        $lists = $model
            ->where('id', '<>', '1')
            ->relation('adminGroup')
            ->order('id desc')
            ->paginate($this->webData['list_rows'], false, $page_param);

        $this->assign([
            'lists' => $lists,
            'page'  => $lists->render(),
            'total' => $lists->total()
        ]);
        return $this->fetch();
    }


    //增加
    public function add()
    {
        if ($this->request->isPost()) {

            $result = $this->validate($this->param, 'AdminUser.add');
            if (true !== $result) {
                return $this->error($result);
            }

            $save_name = null;
            $file      = request()->file('avatar');
            if ($file != null) {
                $file_info = $file->move(config('admin_avatar.upload_path'));
                if (!$file_info) {
                    return $this->error($file->getError());
                }
                $save_name = $file_info->getSaveName();
            }

            if ($save_name) {
                $this->param['avatar'] = $save_name;
            }
            
            $this->param['password'] = md5($this->param['password']);
            $user = AdminUsers::create($this->param);
            if ($user) {
                $roles = $this->param['parent_id'];
                $group = [];
                foreach ($roles as $key => $value) {
                    array_push($group, ['uid' => $user->id, 'group_id' => $value]);
                }
                $user->adminGroup()->saveAll($group);
                
                return $this->success();
            }
            return $this->error();
        }

        $roles = AdminGroups::all(['status' => 1]);

        foreach ($roles as $key => $value) {
            $value['checked'] = '';
            if (in_array($value['id'], $user_roles = [])) {
                $value['checked'] = 'checked';
            }
        }
        $this->assign([
            'roles' => $roles
        ]);
        return $this->fetch();
    }


    //修改
    public function edit()
    {
        $info = AdminUsers::get($this->id);
        if (!$info) {
            return $this->error('用户不存在');
        }

        if ($this->request->isPost()) {
            $result = $this->validate($this->param, 'AdminUser.edit');
            if (true !== $result) {
                return $this->error($result);
            }

            $file      = request()->file('avatar');
            $save_name = '';
            if ($file != null) {
                $file_info = $file->move(config('admin_avatar.upload_path') . $this->id);

                if (!$file_info) {
                    return $this->error($file->getError());
                }
                $save_name = $this->id . DS . $file_info->getSaveName();
            }

            if ($save_name != '') {
                $this->param['avatar'] = $save_name;
            }

            if (!empty($this->param['password'])) {
                $this->param['password'] = md5($this->param['password']);
            }

            if (false !== $info->save($this->param)) {
                $info->adminGroup()->delete();
                $group = [];
                $roles = $this->param['parent_id'];
                foreach ($roles as $key => $value) {
                    array_push($group, ['uid' => $this->id, 'group_id' => $value]);
                }
                $info->adminGroup()->saveAll($group);

                return $this->success();
            }
            return $this->error();
        }

        $roles      = AdminGroups::all(['status' => 1]);
        $user_roles = $info->adminGroup()->column('group_id');

        foreach ($roles as $key => $value) {
            $value['checked'] = '';
            if (in_array($value['id'], $user_roles)) {
                $value['checked'] = 'checked';
            }
        }
        $this->assign([
            'roles' => $roles,
            'info'  => $info
        ]);
        return $this->fetch('add');
    }


    //删除
    public function del()
    {
        $id     = $this->id;
        $result = AdminUsers::destroy(function ($query) use ($id) {
            $query->whereIn('id', $id);
        });
        if ($result) {
            return $this->success();
        }

        return $this->error('删除失败');
    }

    //用户个人资料页面
    public function profile()
    {
        $user = AdminUsers::get($this->uid);

        if (!$user) {
            return $this->error('无法获取用户信息');
        }

        //更新资料
        if ($this->request->isPost()) {

            if ($this->param['update_type'] == 'password') {

                if ($user->getData('password') != md5($this->param['password'])) {
                    return $this->error('当前密码不正确');
                }
                $this->param['password'] = md5($this->param['newpassword']);
            } else if ($this->param['update_type'] == 'avatar') {
                $save_name = '';
                $file      = request()->file('avatar');
                if ($file != null) {
                    $info = $file->move(config('admin_avatar.upload_path') . DS . $this->uid);
                    if ($info) {
                        $save_name = $this->uid . DS . $info->getSaveName();
                    } else {
                        return $this->error($file->getError());
                    }
                }

                if ($save_name != '') {
                    $this->param['avatar'] = $save_name;
                }
            }
            if (false !== $user->save($this->param)) {
                return $this->success();
            }
            return $this->error();
        }
        $this->assign([
            'user' => $user
        ]);
        return $this->fetch();
    }
}