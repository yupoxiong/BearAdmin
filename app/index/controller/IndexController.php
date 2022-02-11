<?php
/**
 * 前台首页控制器
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);

namespace app\index\controller;

use Exception;

class IndexController extends IndexBaseController
{
    /**
     * @throws Exception
     */
    public function index(): string
    {
        $this->index['name'] = '演示首页';
        $this->index['title'] = '首页演示，自定义即可';
        return  $this->fetch();
    }

    /**
     * @throws Exception
     */
    public function product(): string
    {
        $this->index['name'] = '演示产品';
        $this->index['title'] = '这是产品的演示页面，啊啊啊啊啊，这个地方可以用富文本，自己合理安排就行。。';

        return  $this->fetch();
    }

    /**
     * @throws Exception
     */
    public function about(): string
    {
        $this->index['name'] = '演示关于';
        $this->index['title'] =  '这是关于我们的演示页面，啊啊啊啊啊，这个地方可以用富文本，自己合理安排就行。。';

        return  $this->fetch();
    }
}
