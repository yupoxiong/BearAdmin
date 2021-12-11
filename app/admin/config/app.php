<?php
/**
 * app配置
 * @author yupoxiong<i@yupoxiong.com>
 */

return [
    // 自定后台义异常页面的模板文件
    'exception_tmpl'      => app()->getAppPath() . 'view/error/500.html',
    'default_ajax_return' => 'html',
];
