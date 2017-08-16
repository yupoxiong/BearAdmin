<?php
/**
 * 前台用户
 * @author yupoxiong<i@yufuping.com>
 * @version 1.0
 * Date: 2017/8/16
 */

namespace app\admin\controller;

class User extends Base
{
    use CrudBase;

    public function user(){
        $model = $this->getModel();
        dump($model);
        dump($model->getRelation()) ;
        return;
    }
    
}