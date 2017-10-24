<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/7
 * Time: 17:47
 */

use \think\Request;

$basename = Request::instance()->root();

if (pathinfo($basename, PATHINFO_EXTENSION) == 'php') {
    $basename = dirname($basename);
}

return [
    // 模板参数替换
    'view_replace_str' => [
        '__ROOT__'   => $basename,
        '__STATIC__' => $basename . '/static/admin',
        '__AVATAR__' => $basename . '/uploads/admin/avatar/'
    ],

    'template'                   => [

        'layout_on'       => true,
        'layout_name'     => 'template/layout',
        'layout_item'     => '[__REPLACE__]',

        // 模板引擎类型 支持 php think 支持扩展
        'type'            => 'Think',
        // 模板路径
        'view_path'       => '',
        // 模板后缀
        'view_suffix'     => '.html',
        // 预先加载的标签库
        'taglib_pre_load' => '',
        // 默认主题
        'default_theme'   => '',
    ],
    //分页配置
    'paginate'                   => [
        'type'      => '\util\page\Bearpage',
        'var_page'  => 'page',
        'list_rows' => 10,
    ],

    //后台用户头像相关设置
    'admin_avatar'               => [
        'upload_path' => ROOT_PATH . 'public' . DS . 'uploads' . DS . 'admin' . DS . 'avatar' . DS,
    ],
    //后台文件上传路径设置
    'file_upload_path'           => ROOT_PATH . 'public' . DS . 'uploads' . DS . 'admin' . DS . 'admin_file' . DS,
    //上次传文件url显示路径
    'file_upload_url'            => DS . 'uploads' . DS . 'admin' . DS . 'admin_file' . DS,
    'file_upload_max_size'       => 20480,//3145728,
    'file_upload_ext'            => 'jpg,png,gif,doc,docx,xlsx',


    //后台文件上传路径设置
    'email_file_upload_path'     => ROOT_PATH . 'public' . DS . 'uploads' . DS . 'admin' . DS . 'email_file' . DS,
    //上次传文件url显示路径
    'email_file_upload_url'      => DS . 'uploads' . DS . 'admin' . DS . 'email_file' . DS,
    'email_file_upload_max_size' => 20480,//3145728,
    'email_file_upload_ext'      => 'jpg,png,gif,doc,docx,xlsx',


    //后台生成二维码设置
    'qrcode_path'                => ROOT_PATH . 'public' . DS . 'uploads' . DS . 'admin' . DS . 'qrcode' . DS,
    //二维码url显示路径
    'qrcode_url'                 => DS . 'uploads' . DS . 'admin' . DS . 'qrcode' . DS,

    //后台邮件相关参数设置
    'email_from_name'            => '于破熊', // 发件人
    'email_smtp'                 => 'smtp.163.com', // SMTP服务器
    'email_username'             => '', // 账号
    'email_password'             => '', // 密码

    'alidayu_' => [
        'app_key'    => '阿里大于key',
        'app_secret' => '阿里大于secret',
        'sign_name'  =>'短信签名',
        'tpl' =>'SMS_47075011'
    ],

];
