<?php
/**
 * 前台基础控制器
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\index\controller;

use app\common\model\User;
use app\index\traits\{IndexAuth, IndexTree};

class Controller extends \think\Controller
{
    use IndexAuth, IndexAuth;

    /**
     * 当前url
     * @var string
     */
    protected $url;

    /**
     * 当前用户ID
     * @var int
     */
    protected $uid = 0;

    /**
     * 当前用户
     * @var User
     */
    protected $user;

    /**
     * 无需验证权限的url
     * @var array
     */
    protected $authExcept = [];

    protected function initialize()
    {
        $request = $this->request;
        if (!in_array($request->action(true), $this->authExcept)) {
            if (!$this->isLogin()) {
                index_error('未登录', 'auth/login');
            } else if ($this->user->id !== 1 && !$this->isLogin()) {
                index_error('无权限');
            }
        }

        if ((int)$request->param('check_auth') === 1) {
            index_success();
        }
    }

}
