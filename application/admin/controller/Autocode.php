<?php
/**
 * 代码自动生成
 * @author yupoxiong<i@yufuping.com>
 * @version 1.0
 */

namespace app\admin\controller;

use app\common\model\Sysconfigs;

class Autocode extends Base
{

    public function index()
    {
        return $this->fetch();

    }

    /**
     * 添加设置
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $post   = $this->post;
            $result = $this->validate($post, 'Sysconfig.add');

            if (true !== $result) {
                return $this->do_error($result);
            }

            $bus = Sysconfigs::create($post);
            if ($bus) {
                return $this->do_success();
            }
            return $this->do_error();
        }
        return $this->fetch();
    }


    /**
     * 修改设置
     */
    public function edit()
    {
        if ($this->request->isPost()) {
            $post   = $this->post;
            $result = $this->validate($post, 'Sysconfig.edit');
            if (true !== $result) {
                return $this->do_error($result);
            }

            $info = Sysconfigs::get($this->id);
            if ($info->save($post)) {

                return $this->do_success();
            }
            return $this->do_error();
        }
        $info = Sysconfigs::get($this->id);
        $this->assign([
            'info' => $info,
        ]);
        return $this->fetch('add');
    }


    /**
     * 删除配置
     */
    public function del()
    {
        $protected_ids = range(1,10);
        if(in_array($this->id,$protected_ids)){
            return $this->do_error('系统限制，无法删除');
        }

        if (is_array($this->id) && sizeof($this->id) == 0) {
            return $this->do_error('请选择需要删除的数据');
        }

        $result = Sysconfigs::destroy($this->id);
        if ($result) {
            return $this->do_success();
        }
        return $this->do_error('删除失败');
    }

}