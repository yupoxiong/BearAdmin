<?php
/**
 * Markdown
 * @author yupoxiong<i@yufuping.com>
 * @version 1.0
 */
namespace app\admin\controller;

use Flc\Alidayu\Client;
use Flc\Alidayu\App;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;
use Parsedown;


class Markdown extends Base
{
    public function index()
    {
        if ($this->request->isPost()) {

            $post      = $this->request->post(false);
            $Parsedown = new Parsedown();
            $content   = $Parsedown->text($post['content']);
            $this->assign('content', $content);
            return $this->fetch('view');
        }
        return $this->fetch();
    }

}