<?php
/**
 * 编辑器server
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\controller;

use think\Request;
use tools\UEditor;

class EditorController extends Controller
{
    protected $authExcept = [
        'admin/editor/server',
    ];

    //编辑器上传等url
    public function server(Request $request)
    {
        $param = $request->param();
        $config = config('ueditor.');
        $action  = $param['action'];
        $editor = new UEditor($config);
        return $editor->server($action);
    }
}