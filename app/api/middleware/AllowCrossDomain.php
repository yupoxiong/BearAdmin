<?php
/**
 * 跨域中间件
 */
declare (strict_types=1);

namespace app\api\middleware;

use think\Request;
use Closure;

class AllowCrossDomain
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed|void
     */
    public function handle(Request $request, Closure $next)
    {
        $headers = config('api.cross_domain.header');
        foreach ($headers as $key => $value) {
            header($key . ':' . $value);
        }
        if ($request->isOptions()) {
            return json('');
        }
        return $next($request);
    }
}
