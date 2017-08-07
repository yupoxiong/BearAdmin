<?php
/**
 * User: yupoxiong
 * Date: 2017/2/28
 * Time: 16:33
 */
namespace uploads;
use uploads\UploadHandler;
class UploadFile{
    
    protected $uploadhandler;
    
    protected function uploadFile(){
        $this->uploadhandler = new UploadHandler();
        
    } 
}