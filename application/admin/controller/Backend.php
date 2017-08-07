<?php
/**
 *
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
                $map[$key] = $val;
            }
        }

        return $map;
    }
    /*
     * 接下来写获取数据列表
     */
}