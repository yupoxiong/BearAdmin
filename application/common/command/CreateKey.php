<?php
/**
 * 创建app_key
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\common\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

class CreateKey extends Command
{
    protected function configure()
    {
        $this->setName('createkey')->setDescription('Create app key');
    }

    protected function execute(Input $input, Output $output)
    {
        $key_file = fopen(dirname(__FILE__)."/../../../.app_key", "w");
        if($key_file){
            $key = md5(uniqid('bear'));
            fwrite($key_file, $key);
            fclose($key_file);
            $output->writeln("Create key successful!");
        }else{
            $output->writeln("Create key error,please check write permission!");
        }

    }
}