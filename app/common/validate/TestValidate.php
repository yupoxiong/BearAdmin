<?php
/**
 * 测试验证器
 */

namespace app\common\validate;

class TestValidate extends CommonBaseValidate
{
    protected $rule = [
            'avatar|头像' => 'require',
    'username|用户名' => 'require',
    'nickname|昵称' => 'require',
    'mobile|手机号' => 'require',
    'user_level_id|用户等级' => 'require',
    'password|密码' => 'require',
    'status|是否启用' => 'require',
    'slide|相册' => 'require',
    'content|内容' => 'require',

    ];

    protected $message = [
            'avatar.required' => '头像不能为空',
    'username.required' => '用户名不能为空',
    'nickname.required' => '昵称不能为空',
    'mobile.required' => '手机号不能为空',
    'user_level_id.required' => '用户等级不能为空',
    'password.required' => '密码不能为空',
    'status.required' => '是否启用不能为空',
    'slide.required' => '相册不能为空',
    'content.required' => '内容不能为空',

    ];

    protected $scene = [
        'admin_add'     => ['avatar', 'username', 'nickname', 'mobile', 'user_level_id', 'password', 'status', 'lng', 'slide', 'content', ],
        'admin_edit'    => ['id', 'avatar', 'username', 'nickname', 'mobile', 'user_level_id', 'password', 'status', 'lng', 'slide', 'content', ],
        'admin_del'     => ['id', ],
        'admin_disable' => ['id', ],
        'admin_enable'  => ['id', ],
        'api_add'       => ['avatar', 'username', 'nickname', 'mobile', 'user_level_id', 'password', 'status', 'lng', 'slide', 'content', ],
        'api_info'      => ['id', ],
        'api_edit'      => ['id', 'avatar', 'username', 'nickname', 'mobile', 'user_level_id', 'password', 'status', 'lng', 'slide', 'content', ],
        'api_del'       => ['id', ],
        'api_disable'   => ['id', ],
        'api_enable'    => ['id', ],
    ];

}
