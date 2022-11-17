<?php
/**
* 后台设置:后台管理方面的设置
* 此配置文件为自动生成，生成时间2022-11-17 15:37:13
*/

return [
    // 基本设置:后台的基本信息设置
    'base'=>[
    // 后台名称
    'name'=>'XX后台系统',
    // 后台简称
    'short_name'=>'后台',
    // 后台作者
    'author'=>'xx科技',
    // 作者网站
    'website'=>'#',
    // 后台版本
    'version'=>'0.1',
    // 后台LOGO
    'logo'=>'/static/admin/images/logo.png',
],
    // 登录设置:后台登录相关设置
    'login'=>[
    // 登录token验证
    'token'=>'0',
    // 验证码
    'captcha'=>'1',
    // 登录背景
    'background'=>'/static/admin/images/login-default-bg.jpg',
    // 极验ID
    'geetest_id'=>'66cfc0f309e368364b753dad7d2f67f2',
    // 极验KEY
    'geetest_key'=>'99750f86ec232c997efaff56c7b30cd3',
    // 登录重试限制
    'login_limit'=>'1',
    // 限制最大次数
    'login_max_count'=>'5',
    // 禁止登录时长(小时)
    'login_limit_hour'=>'2',
],
    // 安全设置:安全相关配置
    'safe'=>[
    // 加密key
    'admin_key'=>'89ce3272dc949fc3698fe7108d1dbe37',
    // SessionKeyUid
    'store_uid_key'=>'admin_user_id',
    // SessionKeySign
    'store_sign_key'=>'admin_user_sign',
    // 后台用户密码强度检测
    'password_check'=>'0',
    // 密码安全强度等级
    'password_level'=>'2',
    // 单设备登录
    'one_device_login'=>'1',
    // CSRFToken检测
    'check_token'=>'1',
    // CSRFToken验证方法
    'check_token_action_list'=>'add,edit,del,import,profile,update',
],
];