<?php
/**
 * 读取文本日志
 * @author yupoxiong<i@yufuping.com>
 */
namespace app\common\exception;

class LogReader{

    //暂无用处
    public function readlog(){
        $file_path = config('sys_log.path');
        $file = fopen($file_path, "r");
        $logs=array();
        $i=0;
        while(! feof($file))
        {
            $logs[$i]= fgets($file);//fgets()函数从文件指针中读取一行
            $i++;
        }
        fclose($file);
        $user=array_filter($logs);
        return $user;
    }
}