<?php
/**
 * 检查重复提交trait
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);


namespace app\api\traits;

use Redis;
use think\facade\Cache;
use think\exception\HttpResponseException;

trait ApiThrottleTrait
{
    protected string $keyPrefix = 'api_throttle_';

    /**
     * 检查重复提交
     */
    protected function checkThrottle(): void
    {
        // 如果当前方法要限制重复提交
        if (array_key_exists($this->url, $this->throttleAction)) {

            $key = $this->getThrottleKey();

            $cache_type = Cache::getConfig('default');
            $cache_time = $this->throttleAction[$this->url];
            // 如果是redis
            if ($cache_type === 'redis') {
                /** @var Redis $redis */
                $redis  = Cache::store('redis')->handler();
                $number = $redis->incr($key);
                if ($number > 1) {
                    if ($number === 2) {
                        $redis->setex($key, $cache_time, 2);
                    }
                    throw new HttpResponseException(api_error('重复提交'));
                }
            } else {
                $has = Cache::has($key);
                if ($has) {
                    throw new HttpResponseException(api_error('重复提交'));
                }
                Cache::set($key, 1, $cache_time);
            }
        }
    }

    /**
     * 获取key
     * @return string
     */
    protected function getThrottleKey(): string
    {
        if ($this->uid > 0) {
            $key = $this->keyPrefix . sha1($this->uid . $this->url);
        } else {
            $key = $this->keyPrefix . sha1(request()->ip() . request()->header('user-agent'));
        }
        return $key;
    }

}