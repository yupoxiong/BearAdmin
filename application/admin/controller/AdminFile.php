<?php

/**
 * 后台文件管理控制器
 * @author yupoxiong <i@yufuping.com>
 */

namespace app\admin\controller;

use net\Http;
use think\Db;
use app\common\model\AdminFiles;
use think\Session;

class AdminFile extends Base
{
    /**
     * 文件列表
     */
    public function index()
    {

        $files      = new AdminFiles();
        $page_param = ['query' => []];
        if (isset($this->param['keywords']) && !empty($this->param['keywords'])) {
            $page_param['query']['keywords'] = $this->param['keywords'];
            $keywords                        = "%" . $this->param['keywords'] . "%";
            $files->whereLike('original_name', $keywords);
            $this->assign('keywords', $this->param['keywords']);
        }

        $lists = $files->order('id desc')
            ->paginate(10, false, $page_param);

        $this->assign([
                'lists'    => $lists,
                'page'     => $lists->render()
            ]);
        return $this->fetch();
    }

    /**
     * 删除文件
     */
    public function del()
    {
        $result = AdminFiles::destroy($this->id);
        if ($result) {
            return $this->do_success();
        }
        return $this->do_error();
    }

    /**
     * 上传文件
     */
    public function upload()
    {
        if ($this->request->isPost()) {
            $user_id = Session::get('user.user_id');
            if ($user_id > 0) {

                $file      = request()->file('file');
                $info      = $file->validate(
                    [
                        'size' => config('file_upload_max_size'),
                        'ext'  => config('file_upload_ext')
                    ]
                )->move(config('file_upload_path') . $user_id);
                $file_info = [];
                if ($info) {

                    $file_info['original_name'] = $info->getInfo('name');
                    $file_info['save_name']     = $info->getFilename();
                    $file_info['save_path']     = config('file_upload_path') . $user_id . DS . $info->getSaveName();
                    $file_info['extension']     = $info->getExtension();
                    $file_info['mime']          = $info->getInfo('type');
                    $file_info['size']          = $info->getSize();
                    $file_info['md5']           = $info->hash('md5');
                    $file_info['sha1']          = $info->hash();
                    $file_info['url']           = config('file_upload_url')
                        . $user_id
                        . DS
                        . $info->getSaveName();
                    AdminFiles::create($file_info);
                    $this->api_result['status']  = 200;
                    $this->api_result['message'] = '上传成功';
                    $this->api_result['result']  = $file_info;
                    return $this->ajaxReturnData($this->api_result);
                }
                return $this->ajaxReturnError($file->getError());
            }
            return $this->ajaxReturnError('未登录或登录失效');
        }
        return $this->ajaxReturnError('请用post访问');
    }

    /**
     * 下载文件
     */
    public function download()
    {
        $admin_file = AdminFiles::get($this->id);
        if ($admin_file) {
            $path  = $admin_file->save_path;
            $name = $admin_file->original_name;

            if (file_exists($path)) {
                return   Http::download($path, $name);
            }
            return $this->do_error('文件不存在');
        }
        return $this->do_error('文件不存在');
    }
}
