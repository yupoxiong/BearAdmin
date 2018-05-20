<?php
/**
 * 接管异常处理类
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\common\exception;

use app\admin\model\Syslogs;
use Exception;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\ErrorLogHandler;
use think\exception\Handle;
use think\exception\HttpException;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class LogException extends Handle
{

    /**
     * @param Exception $e
     * @return \think\Response
     */
    public function render(Exception $e)
    {
        //本处理暂无用处
        if ($e instanceof HttpException) {
            $statusCode = $e->getStatusCode();
        }

        $e_file    = $e->getFile();
        $e_line    = $e->getLine();
        $e_message = $e->getMessage();
        $trace = $e->getTraceAsString();

        $log_data = [
            'file'=>$e_file,
            'line'=>$e_line,
            'message'=>$e_message
        ];

        $trace_data = ['trace'=>$trace];
        $syslog = Syslogs::create($log_data);
        if($syslog){
            $syslog->syslogTrace()->save($trace_data);
        }

        $e_content = sprintf('%s:%s', $e_file, $e_line);
        $e_title = nl2br(htmlentities($e_message));
        $data = $e_title ." in ". $e_content;
        $logger = new Logger('errorlog');
        $stream_handler = new StreamHandler(config('sys_log.path'), Logger::INFO);
        $stream_handler->setFormatter(new JsonFormatter());
        $logger->pushHandler($stream_handler);
        $logger->pushHandler(new ErrorLogHandler(ErrorLogHandler::OPERATING_SYSTEM, Logger::INFO));
        $logger->error($data);

        //TODO::开发者对异常的操作
        //可以在此交由系统处理
        return parent::render($e);
    }
}