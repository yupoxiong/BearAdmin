<?php
/**
 * 前台基类
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);

namespace app\index\controller;

use think\exception\HttpResponseException;
use app\index\traits\IndexAuthTrait;
use app\common\model\User;
use think\View;
use Exception;

class IndexBaseController
{
    use IndexAuthTrait;

    protected View $view;

    /**
     * 当前url
     * @var string
     */
    protected string $url;

    /**
     * 当前用户ID
     * @var int
     */
    protected int $uid = 0;

    /**
     * 当前用户
     * @var User
     */
    protected User $user;

    /**
     * 无需验证权限的url
     * @var array
     */

    protected array $index=[];

    protected array $loginExcept = [];

    public function __construct()
    {
        $this->init();
    }

    protected function init(): void
    {
        $request = request();

        // 获取当前访问url,应用名+'/'+控制器名+'/'+方法名
        $this->url = $url = parse_name(app('http')->getName())
            . '/' . parse_name($request->controller())
            . '/' . parse_name($request->action());

        $login_except = !empty($this->loginExcept) ? array_map('parse_name', $this->loginExcept) : $this->loginExcept;


        if (!in_array($url, $login_except, true) && !$this->isLogin()) {
            throw new HttpResponseException(index_error('未登录', 'auth/login'));
        }

        if(isset($this->user)){
            $this->index['user'] = $this->user;
        }

        if ((int)$request->param('check_auth') === 1) {
            throw new HttpResponseException(index_success());
        }

        // 初始化view
        $this->view = app()->make(View::class);
    }


    /**
     * 渲染模板
     * @param string $template
     * @param array $vars
     * @return string
     * @throws Exception
     */
    protected function fetch(string $template = '', array $vars = []): string
    {
        $this->assign([
            'index' => $this->index,
        ]);

        return $this->view->fetch($template, $vars);
    }

    /**
     * 模板赋值
     * @param $name
     * @param null $value
     * @return View
     */
    protected function assign($name, $value = null): View
    {
        return $this->view->assign($name, $value);
    }
}
