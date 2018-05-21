<?php
/**
 * 后台系统设置模型
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\model;

use traits\model\SoftDelete;

class Sysconfigs extends Admin
{
    use SoftDelete;
    protected $name = 'sysconfigs';
    protected $autoWriteTimestamp = true;
    protected $updateTime = false;

    //启用禁用获取器
    public function getIsOpenTextAttr($value,$data)
    {
        $text = [0=>'禁用',1=>'启用'];
        return $text[$data['is_open']];
    }

    //状态获取器
    public function getStatusTextAttr($value, $data)
    {
        $status = [0 => '否', 1 => '是'];
        return $status[$data['status']];
    }

    
    public function getGroupTextAttr($value,$data)
    {
        $text = [1=>'系统设置',2=>'其他设置'];
        
        return $text[$data['group_id']];
    }
}
