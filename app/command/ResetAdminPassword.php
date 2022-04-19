<?php
/**
 * 重置后台用户密码命令
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);

namespace app\command;

use app\common\exception\CommonServiceException;
use app\common\service\StringService;
use think\console\input\Argument;
use think\console\input\Option;
use app\admin\model\AdminUser;
use think\console\Command;
use think\console\Output;
use think\console\Input;

class ResetAdminPassword extends Command
{
    protected function configure()
    {
        $this->setName('reset:admin_password')
            ->addArgument('password', Argument::OPTIONAL, "新密码，可空")
            ->addOption('uid', null, Option::VALUE_REQUIRED, '需要重置密码的用户ID')
            ->setDescription('重置后台用户密码，默认重置开发管理员密码');
    }

    /**
     * @throws CommonServiceException
     */
    protected function execute(Input $input, Output $output)
    {
        $password = trim((string)$input->getArgument('password'));
        $password = $password ?: StringService::getRandString(10, true, true, true, false);

        $uid = 1;
        if ($input->hasOption('uid')) {
            $uid = $input->getOption('uid');
        }

        $user = (new AdminUser())->where('id', '=', $uid)->findOrEmpty();
        if ($user->isEmpty()) {
            $output->error('用户不存在');
            return;
        }

        $user->password = $password;
        $result         = $user->save();
        $output_text    = '[ID:' . $user->id . ']' . $user->nickname;
        if ($result) {
            $output->info($output_text . '的密码重置成功，新密码为：' . $password);
            return;
        }

        $output->error($output_text . '的密码重置失败');
    }
}
