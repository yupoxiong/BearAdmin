<?php
/**
 * 错误控制器
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);

namespace app\admin\controller;

use Exception;

class ErrorController extends AdminBaseController
{
    /**
     * 403 没有权限访问
     * @throws Exception
     */
    public function err403(): string
    {
        return $this->fetch('error/403');
    }
}
