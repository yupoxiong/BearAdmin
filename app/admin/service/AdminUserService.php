<?php
/**
 *
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);

namespace app\admin\service;

use think\Model;
use app\admin\model\AdminUser;
use app\admin\exception\AdminServiceException;

class AdminUserService
{
    // 密码强度列表
    public const  PASSWORD_LEVEL_LIST = [
        1 => [
            'name' => '低',
            'rule' => '/^(?=.*[a-zA-Z])(?=.*\d).{6,16}$/',
            'desc' => '至少1个字母和1个数字，6-16位',

        ],
        2 => [
            'name' => '中',
            'rule' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,16}$/',
            'desc' => '至少1个大写字母和1个小写字母和1个数字，8-16位',

        ],
        3 => [
            'name' => '高',
            'rule' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.[@$!%#?&]).{8,16}$/',
            'desc' => '至少1个大写字母和1个小写字母和1个数字和1个特殊字符，8-16位',
        ],
    ];

    protected AdminUser $model;

    public function __construct()
    {
        $this->model = new AdminUser();
    }

    /**
     * 创建后台用户
     * @param $param
     * @return AdminUser|Model
     * @throws AdminServiceException
     */
    public function create($param)
    {
        $password_check = (int)setting('admin.safe.password_check');
        if ($password_check) {
            $check_result = $this->checkPasswordLevel($param['password']);
            if ($check_result !== true) {
                throw new AdminServiceException($check_result);
            }
        }
        return $this->model::create($param);
    }

    /**
     * @param $data
     * @param $param
     * @return mixed
     * @throws AdminServiceException
     */
    public function update($data, $param)
    {
        $password_check = (int)setting('admin.safe.password_check');
        if ($password_check) {
            $check_result = $this->checkPasswordLevel($param['password']);
            if ($check_result !== true) {
                throw new AdminServiceException($check_result);
            }
        }
        return $data->save($param);
    }


    /**
     * 检查密码是否符合规则
     * @param $password
     * @param int $level
     * @return bool|string
     */
    public function checkPasswordLevel($password, int $level = 0)
    {
        $level = $level === 0 ? (int)setting('admin.safe.password_level') : $level;

        if (preg_match(self::PASSWORD_LEVEL_LIST[$level]['rule'], $password)) {
            return true;
        }
        return '密码必须符合' . self::PASSWORD_LEVEL_LIST[$level]['desc'];
    }

    /**
     * 获取当前密码参数配置内容
     * @param string $content 可以为all，name,rule,desc
     * @return false|string|string[]
     */
    public function getCurrentPasswordLevel(string $content = 'all')
    {
        $check = (int)setting('admin.safe.password_check');
        if (!$check) {
            return false;
        }
        $level = (int)setting('admin.safe.password_level');

        if ($content === 'all') {
            return self::PASSWORD_LEVEL_LIST[$level];
        }
        return self::PASSWORD_LEVEL_LIST[$level][$content];
    }
}
