<?php
/**
 * 初始化env文件
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);

namespace app\command;

use think\console\input\Option;
use think\console\Command;
use think\facade\Console;
use think\console\Output;
use think\console\Input;

class InitEnv extends Command
{
    protected function configure()
    {
        $this->setName('init:env')
            ->addOption('--force', '-f', Option::VALUE_NONE, '强制初始化env配置文件')
            ->setDescription('初始化项目env配置文件');
    }

    protected function execute(Input $input, Output $output)
    {
        $force = $input->getOption('force');

        $example_file = $this->app->getRootPath() . '.example.env';
        $env_file     = $this->app->getRootPath() . '.env';

        if ($force === true || !file_exists($env_file)) {
            copy($example_file, $env_file);
            $output->info('env配置文件初始化成功');

            $app_key_result = Console::call('generate:app_key');
            $output->info($app_key_result->fetch());
            $jwt_key_result = Console::call('generate:jwt_key');
            $output->info($jwt_key_result->fetch());
        } else {
            $output->info('env配置文件已存在');
        }
    }
}
