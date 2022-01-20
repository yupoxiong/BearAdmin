<?php
/**
 * 测试命令，可以用这个来写一些日常的测试代码
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

class Test extends Command
{

    protected function configure()
    {
        $this->setName('test')
            ->setDescription('测试命令');
    }

    protected function execute(Input $input, Output $output)
    {
        $output->info('这是测试信息');
    }
}
