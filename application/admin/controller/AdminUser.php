<?php
/**
 * 后台用户管理
 * @author yupoxiong <i@yufuping.com>
 * @version 1.0
 */

namespace app\admin\controller;

use think\Db;
use app\common\model\AdminUsers;
use app\common\model\AuthGroups;

class AdminUser extends Base
{
    public $validate = [
        ['parent_id|角色', 'require'],
        ['user_name|用户名', 'require|token'],
        ['nick_name|用户姓名', 'require'],
        ['status|状态', 'require'],
    ];

    //后台用户列表
    public function index()
    {
        $admin_users = new AdminUsers();
        $admin_users->where(['user_id' => ['<>', '1']]);
        $page_param = ['query' => []];
        if (isset($this->get['keywords']) && !empty($this->get['keywords'])) {
            $page_param['query']['keywords'] = $this->get['keywords'];
            $keywords                        = "%" . $this->get['keywords'] . "%";
            //做用户名/昵称/手机/邮箱查询处理
            $temp_key = $this->get['keywords'];
            if (filter_var($temp_key, FILTER_VALIDATE_EMAIL)) {
                $lists = AdminUsers::hasWhere('profile', function ($query) use ($keywords) {
                    $query
                        ->where('email', 'like', $keywords);
                })->order('user_id desc')
                    ->paginate(10, false, $page_param);
            } else if (strlen($temp_key) == 11 && is_numeric($temp_key)) {
                $lists = AdminUsers::hasWhere('profile', function ($query) use ($keywords) {
                    $query
                        ->where('mobile', 'like', $keywords);
                })->order('user_id desc')
                    ->paginate(10, false, $page_param);
            } else {
                $admin_users->whereLike('nick_name|user_name', $keywords);
                $lists = $admin_users->field('user_id,user_name,nick_name,status')
                    ->with('profile')
                    ->relation('adminRoles')
                    ->order('user_id desc')
                    ->paginate(10, false, $page_param);
            }
            $this->assign('keywords', $this->get['keywords']);
        } else {
            $lists = $admin_users->field('user_id,user_name,nick_name,status')
                ->with('profile')
                ->relation('adminRoles')
                ->order('user_id desc')
                ->paginate(10, false, $page_param);
        }
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
            $post = $this->post;

            array_push(
                $this->validate,
                ['user_name|用户名', 'unique:AdminUsers,user_name'],
                ['password|密码', 'require']
            );

            if (isset($post['mobile']) && !empty($post['mobile'])) {
                array_push($this->validate, ['mobile|手机号', 'unique:AdminProfiles,mobile|length:11']);
            }

            if (isset($post['email']) && !empty($post['email'])) {
                array_push($this->validate, ['email|邮箱', 'unique:AdminProfiles,email']);
            }

            $result = $this->validate($post, $this->validate);
            if (true !== $result) {
                return $this->do_error($result);
            }

            $roles = $post['parent_id'];

            $user_data['user_name'] = $post['user_name'];
            $user_data['nick_name'] = $post['nick_name'];
            $user_data['password']  = md5($post['password']);

            $profile_data           = [];
            $profile_data['mobile'] = $post['mobile'];
            $profile_data['email']  = $post['email'];

            $save_name = '';
            $file      = request()->file('avatar');
            if ($file != null) {
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads' . DS . 'admin' . DS . 'avatar');
                if ($info) {
                    $save_name = $info->getSaveName();
                } else {
                    return $this->do_error($file->getError());
                }
            }

            if ($save_name != '') {
                $user_data['avatar'] = $save_name;
            }

            $user = AdminUsers::create($user_data);
            if ($user) {
                //加入角色
                $group = [];
                foreach ($roles as $key => $value) {
                    array_push($group, ['uid' => $user->user_id, 'group_id' => $value]);
                }
                $user->adminroles()->saveAll($group);
                $user->profile()->save($profile_data);
                return $this->do_success();
            }
            return $this->do_error();
        }

        $this->assign('roles', AuthGroups::all(['status' => 1]));

        return $this->fetch();
    }

    //修改
    public function edit()
    {

        if ($this->request->isPost()) {
            if($this->id==24){
                return $this->do_error('测试用户不能修改哦');
            }
            $post = $this->post;
            array_push(
                $this->validate,
                ['user_name|用户名', 'unique:AdminUsers,user_name,' . $post['user_id'] . ',user_id']
            );

            if (isset($post['mobile']) && !empty($post['mobile'])) {
                array_push($this->validate, ['mobile|手机号', 'unique:AdminProfiles,mobile,' . $post['user_id'] . ',user_id|length:11']);
            }

            if (isset($post['email']) && !empty($post['email'])) {
                array_push($this->validate, ['email|邮箱', 'unique:AdminProfiles,email,' . $post['user_id'] . ',user_id']);
            }

            $result = $this->validate($post, $this->validate);
            if (true !== $result) {
                return $this->do_error($result);
            }

            $admin_user = AdminUsers::get($post['user_id']);
            $file       = request()->file('avatar');
            $save_name  = '';
            if ($file != null) {
                $info = $file->move(config('admin_avatar.upload_path') . $post['user_id']);
                if ($info) {
                    $save_name = $post['user_id'] . $info->getSaveName();
                } else {
                    return $this->do_error($file->getError());
                }
            }
            $roles = $post['parent_id'];
            $data  = array(
                'user_name' => $post['user_name'],
                'nick_name' => $post['nick_name'],
                'status'    => $post['status'],
            );
            if ($save_name != '') {
                $data['avatar'] = $save_name;
            }

            $profile_data = array(
                'email'  => $post['email'],
                'mobile' => $post['mobile'],
            );

            $password = $post['password'];
            if (!empty($password)) {
                $data['password'] = md5($password);
            }

            if (false !== $admin_user->save($data)) {
                $admin_user->adminroles()->delete();
                $group = [];
                foreach ($roles as $key => $value) {
                    array_push($group, ['uid' => $this->web_data['user_info']['user_id'], 'group_id' => $value]);
                }
                $admin_user->profile->save($profile_data);
                $admin_user->adminroles()->saveAll($group);
                return $this->do_success();
            }
            return $this->do_error();
        } else {
            $id   = $this->id;
            $info = AdminUsers::get($id);
            if (!$info) {
                return $this->do_error('用户不存在');
            }
            $roles      =  AuthGroups::all(['status' => 1]);
            $user_roles = $info->adminroles()->column('group_id');

            foreach ($roles as $key => $value) {
                if (in_array($value['id'], $user_roles)) {
                    $value['checked'] = 'checked';
                } else {
                    $value['checked'] = '';
                }
            }
            $this->assign([
                'roles' => $roles,
                'info'  => $info
            ]);
            
            return $this->fetch();
        }
    }

    //删除
    public function del()
    {
        if($this->id==24){
            return $this->do_error('测试用户不能删除哦');
        }
        if (is_array($this->id)) {
            if(in_array(24,$this->id)){
                return $this->do_error('当前包含测试用户，无法删除');
            }

            if (sizeof($this->id) == 0) {
                return $this->do_error('请选择需要删除的数据');
            }
            if (in_array(1, $this->id)) {
                return $this->do_error('超级管理员不能删除');
            }

            $result = AdminUsers::destroy($this->id);
            if ($result) {
                return $this->do_success();
            }
            return $this->do_error('删除失败');

        }
        $admin_user = AdminUsers::get($this->id);
        if (!$admin_user) {
            return $this->do_error('用户不存在');
        }
        if ($this->id == 1) {
            return $this->do_error('超级管理员不能删除');
        }
        if ($admin_user->delete()) {
            return $this->do_success();
        }
        return $this->do_error('用户删除失败');
    }

    //用户个人资料页面
    public function profile()
    {
        $user_id = $this->web_data['user_info']['user_id'];
        if (!$user_id) {
            return $this->do_error('无法获取用户信息');
        }
        $user = AdminUsers::get($user_id);
        //更新资料
        if ($this->request->isPost()) {

            $post                = $this->post;
            $user_update_data    = [];
            $profile_update_data = [];
            if ($post['update_type'] == 'profile') {
                $validate = [];

                if (isset($post['mobile']) && !empty($post['mobile'])) {
                    array_push($validate, ['mobile|手机号', 'unique:AdminProfiles,mobile,' . $user_id . ',user_id|length:11']);
                }

                if (isset($post['email']) && !empty($post['email'])) {
                    array_push($validate, ['email|邮箱', 'unique:AdminProfiles,email,' . $user_id . ',user_id' . '|email']);
                }

                $result = $this->validate($post, $validate);
                if (true !== $result) {
                    return $this->do_error($result);
                }
                $user_update_data['nick_name'] = $post['nick_name'];
                $profile_update_data['mobile'] = $post['mobile'];
                $profile_update_data['email']  = $post['email'];
                $profile_update_data['city']   = $post['city'];

                $profile_update_data['description'] = $post['description'];

            } else if ($post['update_type'] == 'social') {
                $profile_update_data['qq']     = $post['qq'];
                $profile_update_data['wechat'] = $post['wechat'];
                $profile_update_data['weibo']  = $post['weibo'];
                $profile_update_data['zhihu']  = $post['zhihu'];

            } else if ($post['update_type'] == 'password') {
                if($user_id==24){
                    return $this->do_error('测试用户不能修改资料哦');
                }
                $validate = [
                    ['password|当前密码', 'require|token'],
                    ['newpassword|新密码', 'require'],
                    ['newpassword_do|确认新密码', 'require|confirm:newpassword'],
                ];
                $result   = $this->validate($post, $validate);
                if (true !== $result) {
                    return $this->do_error($result);
                }

                if ($user->getData('password') != md5($post['password'])) {
                    return $this->do_error('当前密码不正确');
                }
                $user_update_data['password'] = md5($post['newpassword']);
            } else if ($post['update_type'] == 'avatar') {
                $save_name = '';
                $file      = request()->file('avatar');
                if ($file != null) {
                    $info = $file->move(config('admin_avatar.upload_path') . DS . $user_id);
                    if ($info) {
                        $save_name = $user_id . DS . $info->getSaveName();
                    } else {
                        return $this->do_error($file->getError());
                    }
                }

                if ($save_name != '') {
                    $user_update_data['avatar'] = $save_name;
                }
            }
            if (false !== $user->save($user_update_data)) {
                if (false !== $user->profile->save($profile_update_data)) {
                    return $this->do_success('修改成功', 'admin/admin_user/profile');
                }
                return $this->do_error('修改资料失败');
            }
            return $this->do_error('修改密码失败');
        }
        $this->assign([
            'user'     => $user
        ]);
        return $this->fetch();
    }
}