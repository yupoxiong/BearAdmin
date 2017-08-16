<?php
/**
 * 后台的增删改查
 * 列表页已经测试可用，暂时不用；因为虽然方便，但确实很乱。
 * @author yupoxiong<i@yufuping.com>
 * @version 1.0
 * Date: 2017/8/7
 */

namespace app\admin\controller;

trait CrudBase
{

    /**
     * 列表
     */
    public function index()
    {

        $model  = $this->getModel();
        $map    = $this->getMap($model);
        $order  = $this->getOrder($model);
        $linage = $this->getLinage();

        return $this->getList($model, $map, $order, $linage);
    }

    /**
     * 添加
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $post = $this->post;

            $result = $this->validate($post, $this->controller . '.add');

            if (true !== $result) {
                return $this->do_error($result);
            }
            $model = $this->getModel();
            $data  = $model::create($post);
            if ($data) {
                return $this->do_success();
            }
            return $this->do_error();
        }
        return $this->fetch();
    }


    /**
     * 编辑
     */
    public function edit()
    {
        $model = $this->getModel();
        $id = $this->getDataId();
        $info = $model::get($id);
        if ($this->request->isPost()) {
            $post = $this->post;

            $result = $this->validate($post, $this->controller . '.add');

            if (true !== $result) {
                return $this->do_error($result);
            }

            $result = $info->save($post);
            if ($result) {
                return $this->do_success();
            }
            return $this->do_error();
        }

        $this->assign('info',$info);
        return $this->fetch('add');
    }


    /**
     * 删除
     * 判断、处理关联删除
     */
    public function del()
    {
        $model   = $this->getModel();
        $data_id = $this->getDataId();
        

        if ($model::destroy($data_id)) {
            return $this->do_success();
        }
        return $this->do_success();
    }
}