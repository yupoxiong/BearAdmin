<?php
/**
 * 数据库备份
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\controller;

use think\Config;
use tools\DataBackup;

class Databack extends Base
{
    protected $config = [], $back, $filename;

    public function __construct()
    {
        parent::__construct();
        $this->config = Config::get("database");

        $this->config['savepath'] = ROOT_PATH . 'backup/';
        if(!is_dir($this->config['savepath'])){
            @mkdir($this->config['savepath']);
        }
        $this->config['filename'] = "database-backup-" . date("Y-m-d-H-i-s", time()) . ".sql";

        $this->back     = new DataBackup($this->config);
        $this->filename = isset($this->param['name']) ? $this->param['name'] : '';
    }

    //列表
    public function index()
    {
        $lists   = [];
        $result = $this->back->get_filelist();

        if ($result['status'] == 200) {
            $lists = $result['result'];
        }

        $this->assign([
            "lists"  => $lists,
            'total' => sizeof($lists)
        ]);

        return $this->fetch();
    }

    //添加备份
    public function add()
    {
        $result = $this->back->backup();
        if ($result['status'] == 200) {
            return $this->success($result['message']);
        }
        return $this->error($result['message']);
    }


    //下载备份
    public function download()
    {
        $this->back->downloadFile($this->filename);
    }


    //还原
    public function restore()
    {
        $result = $this->back->restore($this->filename);
        if ($result['status'] == 200) {
            return $this->success($result['message']);
        }
        return $this->error($result['message']);
    }


    //删除
    public function del()
    {
        $result = $this->back->deleteFile($this->filename);
        if ($result['status'] == 200) {
            return $this->success($result['message']);
        }
        return $this->error($result['message']);
    }
}