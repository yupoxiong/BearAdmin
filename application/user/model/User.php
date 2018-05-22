<?php
/**
 * 用户模型
 * @author yupoxiong<i@yufuping.com>
 * Date: 2018/5/22
 */

namespace app\user\model;

use app\common\model\Model;
use traits\model\SoftDelete;

class Users extends Model
{
    use SoftDelete;
    protected $name = 'users';
    protected $autoWriteTimestamp = true;

}