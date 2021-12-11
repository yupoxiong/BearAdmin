<?php
/**
 * 字符串相关service
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);

namespace app\common\service;

use Exception;
use app\common\exception\CommonServiceException;

class StringService extends CommonBaseService
{
    public const STR_NUMBER = '0123456789';
    public const STR_LOWER_CASE = 'abcdefghijklmnopqrstuvwxyz';
    public const STR_CAPITAL = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    public const STR_PUNCTUATION = '~!@#$%^&*()_+{}|:"<>?`-=[]\;,./';

    /**
     * 生成随机字符串
     * @param int $length 长度
     * @param bool $number 是否包含数字
     * @param bool $lower_case 是否包含小写字母
     * @param bool $capital 是否包含大写字母
     * @param bool $punctuation 是否包含特殊符号
     * @return string
     * @throws CommonServiceException
     */
    public static function getRandString(int $length = 10, bool $number = true, bool $lower_case = true, bool $capital = true, bool $punctuation = true): string
    {
        $str = '';

        if ($number) {
            $str .= self::STR_NUMBER;
        }
        if ($lower_case) {
            $str .= self::STR_LOWER_CASE;
        }
        if ($capital) {
            $str .= self::STR_CAPITAL;
        }
        if ($punctuation) {
            $str .= self::STR_PUNCTUATION;
        }

        if ($str === '') {
            throw new CommonServiceException('请至少选择一种字符串');
        }

        $max    = strlen($str) - 1;
        $result = '';
        for ($i = 1; $i <= $length; $i++) {
            try {
                $rand = random_int(0, $max);
            } catch (Exception $e) {
                throw new CommonServiceException('rand_int函数执行错误，参考错误信息:' . $e->getMessage());
            }
            $result .= $str[$rand];
        }
        return $result;
    }

    /**
     * 获取两个字符串中间的字符
     * @param $str
     * @param $leftStr
     * @param $rightStr
     * @return string
     */
    public static function getMiddleStr($str, $leftStr, $rightStr): string
    {
        $left  = mb_strpos($str, $leftStr);
        $right = mb_strpos($str, $rightStr, $left + 1);
        if ($left < 0 || $right < $left) {
            return '';
        }
        return mb_substr($str, $left + mb_strlen($leftStr), $right - $left - mb_strlen($leftStr));
    }

}
