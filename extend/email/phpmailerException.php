<?php
/**
 *
 * @author yupoxiong<i@yufuping.com>
 * @version 1.0
 * Date: 2017/6/5
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
