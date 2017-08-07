<?php
/**
 *
 * @author yupoxiong<i@yufuping.com>
 * @version 1.0
 * Date: 2017/3/20
 */

namespace app\common\model;

use think\Model;
use traits\model\SoftDelete;

class News extends Model
{
    protected $name = 'news';
    protected $autoWriteTimestamp = true;
    use SoftDelete;
    
    public function newstype(){
        return $this->belongsTo('NewsTypes')->field('id,title');
    }

}