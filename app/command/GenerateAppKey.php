<?php
/**
 * 生成新的APP_KEY
 * @author yupoxiong<i@yupoxiong.com>
 */
declare (strict_types=1);

namespace app\command;

use think\console\Command;
use think\console\Output;
use think\console\Input;

class GenerateAppKey extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('generate:app_key')
            ->setDescription('生成新的APP_KEY');
    }

    protected function execute(Input $input, Output $output)
    {
        $env    = app()->getRootPath() . '.env';
        $search = 'APP_KEY=' . env('app.app_key');
        $key    = md5(uniqid('app_key', true));
        $result = file_put_contents($env, str_replace($search, 'APP_KEY=' . $key, file_get_contents($env)));
        if ($result) {
            $output->writeln('新的APP_KEY生成成功，值为：' . $key);
        }
    }
}
