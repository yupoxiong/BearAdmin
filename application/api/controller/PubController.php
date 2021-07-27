<?php
/**
 *
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);


namespace app\api\controller;


use app\common\model\Attachment;
use think\response\Json;

class PubController extends ApiBaseController
{

    protected $authExcept = [
        //'upload',
    ];

    /**
     * 上传文件
     * @param Attachment $attachment
     * @return Json
     */
    public function upload( Attachment $attachment): Json
    {
        $result = $attachment->upload('file');
        if ($result !== false) {
            return api_success([
                'url' => $result->url
            ]);
        }
        return api_error($attachment->getError());
    }
}