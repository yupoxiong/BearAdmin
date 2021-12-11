<?php
/**
 * 用户登录事件监听类
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);

namespace app\admin\listener;

use think\facade\Log;
use app\admin\service\AdminLogService;
use app\admin\exception\AdminServiceException;

class AdminUserLogin
{

    public function handle($user): void
    {
        try {
            // 记录日志
            (new AdminLogService())->create($user, '登录');
        } catch (AdminServiceException $e) {
            Log::error('记录登录异常，信息：'.$e->getMessage());
        }
    }
}
