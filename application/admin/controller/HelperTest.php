<?php
/**
 * 助手函数测试参考
 * @author yupoxiong <i@yufuping.com>
 * @version 1.0
 */

namespace app\admin\controller;

use app\common\model\AdminFiles;

class HelperTest extends Base
{

    //文件列表
    public function index()
    {
        $lists = AdminFiles::paginate(10);
        $this->assign([
            'lists'  => $lists,
            'page'  => $lists->render()
        ]);
       
        return $this->fetch();
    }
}
