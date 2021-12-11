<?php
/**
 * 用户退出事件监听类
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);

namespace app\admin\listener;

use think\facade\Log;
use app\admin\service\AdminLogService;
use app\admin\exception\AdminServiceException;

class AdminUserLogout
{
    public function handle($user): void
    {
        try {
            (new AdminLogService)->create($user, '退出');
        } catch (AdminServiceException $e) {
            Log::error('记录退出异常，信息：'.$e->getMessage());
        }

    }
}
