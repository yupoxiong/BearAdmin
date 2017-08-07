<?php

namespace app\common\model;

use think\Model;

class AuthRules extends Model
{
    protected $name = 'auth_rules';
    
    public function adminMenu()
    {
        return $this->belongsTo('AdminMenus');
    }
    
    
}
