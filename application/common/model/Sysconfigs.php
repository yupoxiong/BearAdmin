<?php
/**
 * 后台系统设置模型
 * @author yupoxiong<i@yufuping.com>
 * @version 1.0
 */

namespace app\common\model;

use think\Model;

class Sysconfigs extends Model
{
    protected $name = 'sysconfigs';
    protected $autoWriteTimestamp = true;
    protected $updateTime = false;
    
    public function getIsOpenTextAttr($value,$data)
    {
        $text = [0=>'禁用',1=>'启用'];
        return $text[$data['is_open']];
    }
    
}
