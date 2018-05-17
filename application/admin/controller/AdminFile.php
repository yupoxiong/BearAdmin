<?php

/**
 * 后台文件管理控制器
 * @author yupoxiong <i@yufuping.com>
 */

namespace app\admin\controller;

use app\common\model\Attachments;
use tools\Attachment;
use tools\Http;

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
        $model      = new Attachments();
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
        $result = Attachments::destroy(function ($query) use ($id) {
            $query->whereIn('id', $id);
        });
        if ($result) {
            return $this->deleteSuccess();
        }
        return $this->error();
    }

    //上传文件
    public function upload()
    {
        if ($this->request->isPost()) {
            $attachment = new Attachment();
            $file_avatar =  $attachment->upload('file');
            if($file_avatar['code']==1){
               return $this->success('上传成功');
            }
        }
        return $this->error();
    }


    //下载文件
    public function download()
    {
        $admin_file = Attachments::get($this->id);
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
    
    //查看
    public function view()
    {
        $info = Attachments::get($this->id);
        $this->assign([
            'info'=>$info
        ]);
        return $this->fetch();
    }

    //回收站
    public function recycle()
    {
        $model      = new Attachments();
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
            ->useSoftDelete('delete_time', ['not null', ''])
            ->paginate($this->webData['list_rows'], false, $page_param);

        $this->assign([
            'lists' => $lists,
            'page'  => $lists->render(),
            'total' => $lists->total()
        ]);
        return $this->fetch();
    }


    //还原
    public function reduction()
    {
        $data = Attachments::onlyTrashed()->whereIn('id', $this->id)->select();
        if ($data) {
            foreach ($data as $d) {
                $d->save(['delete_time' => null]);
            }
            return $this->success('还原成功',self::URL_RELOAD);
        }
        return $this->error('还原失败');
    }

    //永久删除
    public function delete()
    {
        $data = Attachments::onlyTrashed()->whereIn('id', $this->id)->select();
        if ($data) {
            foreach ($data as $d) {
                $d->save(['delete_time' => null]);
            }
            return $this->success('还原成功',self::URL_RELOAD);
        }
        return $this->error('还原失败');
    }
}