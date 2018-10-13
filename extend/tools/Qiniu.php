<?php
/**
 *
 * @author yupoxiong<i@yufuping.com>
 */

namespace tools;

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class Qiniu
{

    public function upload($info,$way='',$isunlink=false)
    {
        // 需要填写你的 Access Key 和 Secret Key
        $accessKey = config('qiniu.AccessKey');
        $secretKey = config('qiniu.SecretKey');
        if(!$accessKey || $secretKey){
            throw new \Exception('请先配置完整AccessKey和SecretKey');
        }
        // 构建鉴权对象
        $auth = new Auth($accessKey, $secretKey);

        // 要上传的空间
        $bucket = config('qiniu.Bucket');

        // 生成上传 Token
        $token = $auth->uploadToken($bucket);

        // 要上传文件的本地路径
        $file = $info['path'];

        $way=empty($way)?$bucket:$way;
        $name = $way.'/'.date('Ymd').'/'.$info['name'];//想要保存文件的名称

        // 初始化 UploadManager 对象并进行文件的上传。
        $uploadMgr = new UploadManager();

        // 调用 UploadManager 的 putFile 方法进行文件的上传。
        $reuslt = $uploadMgr->putFile($token, $name, $file);

        if($reuslt[1]==null){
            if ($isunlink==true){
                unlink($file);
            }
        }
       
        return $reuslt;
    }
}