<?php
/**
 *
 * @author yupoxiong<i@yufuping.com>
 * @version 1.0
 * Date: 2017/8/15
 */

namespace app\admin\controller;

use think\Config;
use tools\DataBackup;

class Databack extends Base
{
    public function index()
    {

        $type = isset($this->get['type'])?$this->get['type']:'';
        $name = isset($this->get['name'])?$this->get['name']:'';

        $sql=new DataBackup(Config::get("database"));
        switch ($type)
        {
            case "backup": //备份
                $result =  $sql->backup();
                if($result['status']==200) {
                     return $this->do_success($result['message']);
                }
                return $this->do_error($result['message']);
                break;
            case "dowonload": //下载
                return $sql->downloadFile($name);
                break;
            case "restore": //还原
                return $sql->restore($name);
                break;
            case "del": //删除
                $result =  $sql->delfilename($name);
                if($result['status']==200) {
                    return $this->do_success($result['message']);
                }
                return $this->do_error($result['message']);
            default: //获取备份文件列表
                return $this->fetch("index",["list"=>$sql->get_filelist()]);
        }
    }

    public function add()
    {
    }

    
    public function reduction()
    {


    }

    public function del()
    {


    }

}