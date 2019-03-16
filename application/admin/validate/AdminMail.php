<?php
/**
 * 后台邮件验证类
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\validate;

class AdminMail extends Admin
{
    protected $rule = [
        'address|收信人'      => 'require',
        'subject|邮件主题' => 'require',
        'content|邮件正文'  => 'require',
    ];

    protected $scene = [
        'add'  => ['address', 'subject', 'content'],
    ];
}