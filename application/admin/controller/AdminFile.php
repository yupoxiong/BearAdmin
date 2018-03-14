<?php

/**
 * 后台文件管理控制器
 * @author yupoxiong <i@yufuping.com>
 */

namespace app\admin\controller;

use tools\Http;
use app\common\model\AdminFiles;

class AdminFile extends Base
{
    protected $filetype = [
        1 => ['jpg', 'bmp', 'png', 'jpeg', 'gif', 'svg'],
        2 => ['txt', 'doc', 'docx', 'xls', 'xlsx', 'pdf'],
        3 => ['rar', 'zip', '7z', 'tar'],
        4 => ['mp3', 'ogg', 'flac', 'wma', 'ape'],
        5 => ['mp4', 'wmv', 'avi', 'rmvb', 'mov', 'mpg'],
        6 => '其他'
    ];

    //文件列表
    public function index()
    {
        $model      = new AdminFiles();
        $page_param = ['query' => []];
        if (isset($this->param['keywords']) && !empty($this->param['keywords'])) {
            $page_param['query']['keywords'] = $this->param['keywords'];
            $keywords                        = "%" . $this->param['keywords'] . "%";
            $model->whereLike('original_name', $keywords);
            $this->assign('keywords', $this->param['keywords']);
        }

        if (isset($this->param['file_type']) && ($this->param['file_type'] > 0)) {

            $page_param['query']['file_type'] = $this->param['file_type'];
            $filetype = [];
            foreach ($this->filetype as $key=>$value){
                if($key==$this->param['file_type']){
                    $filetype = $value;
                    break;
                }
            }
            $model->whereIn('extension', $filetype);
            $this->assign('file_type', $this->param['file_type']);
        }

        $lists = $model->order('id desc')
            ->paginate($this->webData['list_rows'], false, $page_param);

        $this->assign([
            'lists' => $lists,
            'page'  => $lists->render(),
            'total' => $lists->total()
        ]);
        return $this->fetch();
    }

    //删除文件
    public function del()
    {
        $id     = $this->id;
        $result = AdminFiles::destroy(function ($query) use ($id) {
            $query->whereIn('id', $id);
        });
        if ($result) {
            return $this->success();
        }
        return $this->error();
    }

    //上传文件
    public function upload()
    {
        if (!$this->request->isPost()) {
            return $this->error('请用post访问');
        }

        $file = request()->file('file');
        $info = $file->validate([
            'size' => config('file_upload_max_size'),
            'ext'  => config('file_upload_ext')
        ])->move(config('file_upload_path') . $this->uid);

        if ($info) {
            $file_info = [
                'user_id'       => $this->uid,
                'original_name' => $info->getInfo('name'),
                'save_name'     => $info->getFilename(),
                'save_path'     => config('file_upload_path') . $this->uid . DS . $info->getSaveName(),
                'extension'     => $info->getExtension(),
                'mime'          => $info->getInfo('type'),
                'size'          => $info->getSize(),
                'md5'           => $info->hash('md5'),
                'sha1'          => $info->hash(),
                'url'           => config('file_upload_url') . $this->uid . DS . $info->getSaveName()
            ];

            $result = AdminFiles::create($file_info);

            return $result ? $this->success('上传成功') : $this->error('上传失败');
        }
        return $this->error($file->getError());
    }


    //下载文件
    public function download()
    {
        $admin_file = AdminFiles::get($this->id);
        if (!$admin_file) {
            return $this->error('文件不存在');
        }

        $path = $admin_file->save_path;
        $name = $admin_file->original_name;

        if (file_exists($path)) {
            return Http::download($path, $name);
        }
        return $this->error('文件不存在');
    }
}