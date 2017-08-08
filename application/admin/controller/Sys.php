<?php
/**
 * 后台系统相关
 * @author yupoxiong<i@yufuping.com>
 * @version 1.0
 */

namespace app\admin\controller;

class Sys extends Base
{
    
    public function about(){
        $this->assign('web_data',$this->web_data);
        return $this->fetch();
    }

    public function test(){

        $info = ip_get_info('58.59.25.14',6);

        $this->assign([
            'info'=>serialize($info),
            'web_data'=>$this->web_data
        ]);
        return $this->fetch();
    }
    
    public function testimg(){
        return $this->fetch();

    }
    
}