<?php
/**
 * 后台用户验类
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\validate;

class AdminUserValidate extends Validate
{
    protected $rule = [
        'username|帐号'          => 'require|unique:admin_user',
        'password|密码'          => 'require',
        'new_password|新密码'     => 'require',
        'renew_password|确认新密码' => 'require|confirm:new_password',
        'nickname|昵称'          => 'require',
        'role|角色'              => 'require',
        'status|状态'            => 'require',
    ];

    protected $message = [
        'role.require' => '请选择角色',
    ];

    protected $scene = [
        'add'   => ['username', 'password', 'nickname', 'role'],
        'edit'   => ['username', 'nickname', 'role'],
        'password' => ['password', 'new_password', 'renew_password'],
        'nickname' => ['nickname'],
    ];

    public function sceneLogin()
    {
        $this->only(['username', 'password'])
            ->remove('username', 'unique');
    }

}
