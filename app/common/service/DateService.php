<?php
/**
 * 日期相关service
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);

namespace app\common\service;

class DateService
{
    /**
     * 获取当前微秒时间戳
     * @return string
     */
    public static function microTimestamp(): string
    {
        $time  = explode(' ', microtime());
        $micro = substr($time[0], 2, 3);
        return $time[1] . $micro;
    }

    /**
     * 获取指定日期当月第一天
     * @param $date
     * @return false|string
     */
    public static function getCurMonthFirstDay($date) {
        return date('Y-m-01', strtotime($date));
    }

    /**
     * 获取指定日期当月最后一天
     * @param $date
     * @return false|string
     */
    public static function getCurMonthLastDay($date) {
        return date('Y-m-d', strtotime(date('Y-m-01', strtotime($date)) . ' +1 month -1 day'));
    }

    /**
     * 获取月初的时间戳
     * @param string $month 月份，格式2020-10
     * @return false|int
     */
    public static function getMonthStartTimestamp(string $month)
    {
        return strtotime($month);
    }

    /**
     * 获取月末时间戳
     * @param string $month 月份，格式2020-10
     * @return false|int
     */
    public static function getMonthEndTimestamp(string $month)
    {
        return strtotime(date('Y-m-t',strtotime($month)).' 23:59:59');
    }

}
