<?php
/**
 * 后台的增删改查
 * 列表页已经测试可用，暂时不用；因为虽然方便，但确实很乱。
 * @author yupoxiong<i@yufuping.com>
 * @version 1.0
 * Date: 2017/8/7
 */

namespace app\admin\controller;

trait ControllerCrud
{

    /**
     * 列表
     */
    public function index(){

        $model = $this->getModel();

        $map = $this->getMap($model);
        $order = $this->getOrder($model);
        $linage= $this->getLinage();

        $data_list = $this->getList($model, $map,$order,$linage);

       // return $this->fetch();

    }

    /**
     * 添加
     */
    public function add(){

    }


    /**
     * 编辑
     */
    public function edit(){

    }


    /**
     * 删除
     */
    public function del(){
        $model = $this->getModel();
        $data_id = $this->getDataId();
        return $this->deleteData($model,$data_id);
    }

}