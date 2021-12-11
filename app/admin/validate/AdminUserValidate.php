<?php
/**
 * 后台用户验证器
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);

namespace app\admin\validate;

class AdminUserValidate extends AdminBaseValidate
{
    protected $rule = [
        'username|帐号'           => 'require|unique:admin_user',
        'password|密码'           => 'require',
        'current_password|当前密码'   => 'require',
        'new_password|新密码'      => 'require',
        're_new_password|确认新密码' => 'require|confirm:new_password',
        'nickname|昵称'           => 'require',
        'role|角色'               => 'require',
        'status|状态'             => 'require',
    ];

    protected $message = [
        'name.require' => '名称必须',
        'name.max'     => '名称最多不能超过25个字符',
        'age.number'   => '年龄必须是数字',
        'age.between'  => '年龄只能在1-120之间',
        'email'        => '邮箱格式错误',
    ];

    protected $scene = [
        'admin_add'      => ['username', 'password'],
        'admin_edit'     => ['username', 'password'],
        'admin_login'    => ['username', 'password'],
        'admin_password' => ['current_password', 'new_password', 're_new_password'],
    ];

    public function sceneLogin(): void
    {
        $this->only(['username', 'password'])
            ->remove('username', 'unique');
    }
}
