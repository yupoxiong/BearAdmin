<?php
/**
 * 后台用户邮件记录模型
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\model;

class AdminMailLogs extends Admin
{
    protected $name = 'admin_mail_logs';
    protected $autoWriteTimestamp = true;
    protected $updateTime = false;

    //关联后台用户
    public function adminUser()
    {
        return $this->belongsTo('AdminUsers','user_id')->field('user_id,user_name,nickname');
    }
}
