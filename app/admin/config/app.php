<?php
/**
 * app配置
 * @author yupoxiong<i@yupoxiong.com>
 */

$config = [

    'default_ajax_return' => 'html',
];
if (!env('app_debug')) {
    // 自定后台义异常页面的模板文件
    $config['exception_tmpl'] = app()->getAppPath() . 'view/error/500.html';
}
return $config;