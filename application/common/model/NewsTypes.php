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

class NewsTypes extends Model
{
    protected $name = 'news_types';
    protected $autoWriteTimestamp = true;
    use SoftDelete;
    
    public function news(){
        return $this->hasMany('News')->field(
            'id,user_id,title,keywords,description,create_time,update_time'
        );
    }

}