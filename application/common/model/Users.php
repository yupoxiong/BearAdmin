<?php
/**
 * 网站会员模型
 */

namespace app\common\model;

use think\Model;
use traits\model\SoftDelete;

class Users extends Model
{
    use SoftDelete;
    protected $name = 'users';
    protected $autoWriteTimestamp = true;
    
    public function news(){
        return $this->hasMany('News','user_id','id');
    }
}
