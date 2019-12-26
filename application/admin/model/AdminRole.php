<?php
/**
 * 后台角色模型
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\model;

class AdminRole extends Model
{
    protected $name = 'admin_role';

    public $softDelete = false;

    protected $searchField = [
        'name',
        'description'
    ];

    public $noDeletionId = [
        1
    ];

    public static function init(): void
    {
        //添加首页，系统管理，个人资料菜单/权限
        self::event('before_insert', static function ($data) {
            $data->url = [1, 2, 18];
        });
    }

    protected function getUrlAttr($value)
    {
        return $value !== '' ? explode(',', $value) : [];
    }

    protected function setUrlAttr($value)
    {
        return $value !== '' ? implode(',', $value) : [];
    }
}
