<?php
/**
 *
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);

namespace app\admin\service;

use think\Collection;
use app\admin\model\AdminRole;
use think\db\exception\DbException;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;

class AdminRoleService
{
    protected AdminRole $model;

    public function __construct()
    {
        $this->model = new AdminRole();
    }

    /**
     * 获取所有角色
     * @return AdminRole[]|array|Collection
     */
    public function getAll()
    {
        try {
            return $this->model->select();
        } catch (DataNotFoundException | ModelNotFoundException | DbException $e) {
            return [];
        }
    }
}
