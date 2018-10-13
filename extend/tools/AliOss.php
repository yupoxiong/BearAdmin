<?php
/**
 * 阿里云oss
 * @author yupoxiong<i@yufuping.com>
 */

namespace tools;

use OSS\Core\OssException;
use OSS\OssClient;

class AliOss
{

    public static function upload($info,$way='',$isunlink=false){

        $accessKeyId = config('aliyun_oss.KeyID');
        $accessKeySecret =config('aliyun_oss.KeySecret');
        $endpoint = config('aliyun_oss.EndPoint');
        $bucket= config('aliyun_oss.Bucket');

        if(!$accessKeyId || $accessKeySecret){
            throw  new \Exception('请先配置完整AccessKeyId和AaccessKeySecret');
        }
        
        $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
        //判断bucketname是否存在，不存在就去创建
        if( !$ossClient->doesBucketExist($bucket)){
            $ossClient->createBucket($bucket);
        }
        $way=empty($way)?$bucket:$way;

        $object = $way.'/'.date('Ymd').'/'.$info['name'];//想要保存文件的名称
        $file = $info['path'];//文件路径，必须是本地的。
        
        try{
            $ossClient->uploadFile($bucket,$object,$file);
            if ($isunlink==true){
                unlink($file);
            }
        }catch (OssException $e){
            $e->getErrorMessage();
        }
        $url = config('aliyun_oss.url').$object;

        return $url;
    }
    
}