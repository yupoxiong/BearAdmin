<?php
/**
 * 文件控制器(暂未完成)
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\controller;

use think\Request;

class FileController extends Controller
{

    //文件列表
    public function index(Request $request)
    {

    }

    //文件上传
    public function upload()
    {
        $file = request()->file('file');
    }
}