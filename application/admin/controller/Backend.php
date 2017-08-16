<?php
/**
 * 用作封装index add edit del方法，其中index已经测试完成，
 * 但是感觉怪怪的，所以没有继续做下去，后期可能会增加代码自动生成功能 
 * @author yupoxiong<i@yufuping.com>
 * @version 1.0
 * Date: 2017/8/7
 */

namespace app\admin\controller;

use think\Controller;
use think\exception\ClassNotFoundException;

class Backend extends Controller
{

    /**
     * 获取当前控制器对应模型
     * 名称为控制器名称+"s"
     */
    protected function getModel()
    {
        $class = 'app\\common\\model\\' . $this->request->controller() . 's';
        if (class_exists($class)) {
            return new $class();
        }

        throw new ClassNotFoundException('class not exists:' . $class, $class);
    }

    /**
     * 条件筛选
     * @param $model
     * @return array
     */
    protected function getMap($model)
    {
        $map   = [];
        $table = $model->getTableInfo();
        $param = $this->request->param();

        foreach ($param as $key => $val) {
            if ($val !== "" && in_array($key, $table['fields'])) {
                if(false!==stripos($table['type'][$key],'text') || false!==stripos($table['type'][$key],'char')) {
                    $map['like'][$key] = "%".$val."%";
                }else{
                    $map['where'][$key] = $val;
                }
            }
        }
        return $map;
    }


    /**
     * 获取排序字段
     * @param $model
     * @return string
     */
    protected function getOrder($model)
    {
        $order = $model->getPk().' desc';
        $param = $this->request->param();
        if (isset($param['_order_name_']) && isset($param['_order_by_'])) {
            $order = $param['_order_name_'].' '.$param['_order_by_'];
        }

        return $order;
    }


    protected function getLinage(){
        return $this->request->param('_linage_') ?: 3;
    }


    /*
     * 获取数据列表
     */
    protected function getList($model, $map, $order = '',$paginate = 3, $field = null, $json = false )
    {

        if ($field) {
            $model->field($field);
        }
        if (sizeof($map) > 0) {
            if(isset($map['like'])){
                foreach ($map['like'] as $key=>$value){
                    $model->whereLike($key,$value);
                }
            }

            if(isset($map['where'])){
                $model->where($map['where']);
            }
        }

        if ($order) {
            $model->order($order);
        }


        $list = $model->paginate($paginate, false, ['query' => $this->request->get()]);
        $data = [
            'list'=>$list,
            'total'=>$list->total(),
            'page'=>$list->render(),
            'linage'=>$list->listRows()
        ];

        $this->assign([
            'lists'    => $list,
            'page'     => $list->render()
        ]);
        $this->assign( $this->request->get());
        //return $data;

    }

    protected function getDataId(){
        return $this->request->param('id');
    }

    protected function deleteData($model,$data_id){
        return $model::destroy($data_id);
    }
    
    
}