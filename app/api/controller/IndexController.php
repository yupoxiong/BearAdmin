<?php
/**
 *
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);

namespace app\api\controller;

use think\response\Json;

class IndexController extends ApiBaseController
{
    public function index(): Json
    {
        return api_success();
    }
}
