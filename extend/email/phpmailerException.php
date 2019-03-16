<?php
/**
 * mail异常类，也来自网络
 */

/**
 * PHPMailer exception handler
 * @package PHPMailer
 */

namespace email;
use Exception;

class phpmailerException extends Exception
{
    /**
     * Prettify error message output
     * @return string
     */
    public function errorMessage()
    {
        $errorMsg = '<strong>' . $this->getMessage() . "</strong><br />\n";
        return $errorMsg;
    }
}
