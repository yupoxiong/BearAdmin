<?php
/**
 * api基础控制器
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);

namespace app\api\controller;

use think\response\Json;
use app\common\model\User;
use app\api\traits\ApiAuthTrait;
use app\api\traits\ApiThrottleTrait;

class ApiBaseController
{
    use ApiAuthTrait,ApiThrottleTrait;

    /** @var array 无需验证登录的url，禁止在此处修改 */
    protected array $loginExcept = [];

    /** @var array 无需验证权限的url，禁止在此处修改 */
    protected array $authExcept = [];

    /** @var array 需要限制重复提交的action */
    protected array $throttleAction = [];

    /** @var int 当前访问的用户 */
    protected int $uid = 0;

    /** @var User 当前用户 */
    protected User $user;

    /** @var array 当前请求参数 */
    protected array $param;

    /** @var mixed 当前请求数据ID，支持单个数字id，数组形式的多个id，或者英文逗号分割的多个id */
    protected $id;
    /** @var int 当前页数 */
    protected int $page;
    /** @var int 当前每页数量 */
    protected int $limit;
    /** @var string 当前访问的url */
    protected string $url;

    public function __construct()
    {
        // 处理跨域问题
        $this->crossDomain();
        // 检查登录
        $this->checkLogin();
        // 检查登录
        $this->checkAuth();
        // 防重复提交
        $this->checkThrottle();
        // 初始化部分数据
        $this->initData();
    }

    /**
     * 初始化数据
     */
    protected function initData(): void
    {
        $this->param = (array)request()->param();
        // 初始化基本数据
        $this->page  = (int)($this->param['page'] ?? 1);
        $this->limit = (int)($this->param['limit'] ?? 10);
        // 限制每页数量最大为100条
        $this->limit = $this->limit > 100 ? 100 : $this->limit;

        if (isset($this->param['id'])) {
            if (is_numeric($this->param['id'])) {
                $this->id = (int)$this->param['id'];
            } else if (is_string($this->param['id']) && strpos($this->param['id'], ',') > 0) {
                $this->id = explode(',', $this->param['id']);
            } else {
                $this->id = $this->param['id'];
            }
        }
    }

    /** 访问不存在的方法 */
    public function __call($name, $arguments): Json
    {
        return api_error_404();
    }
}
