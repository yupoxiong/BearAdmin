<?php
/**
 * 后台管理员角色模型
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\common\model;

use think\Model;

class AdminGroups extends Model
{
    protected $name = 'admin_groups';

    //关联用户&用户组表
    public function adminGroupAccess(){
        return $this->hasMany('AdminGroupAccess','group_id','id');
    }

    //状态获取器
    public function getStatusTextAttr($value,$data)
    {
        $text = [0=>'禁用',1=>'正常'];
        return $text[$data['status']];
    }
    
}
