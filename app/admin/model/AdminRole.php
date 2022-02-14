<?php
/**
 * 后台角色模型
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\model;

use think\model\concern\SoftDelete;

/**
 * Class AdminRole
 * @package app\admin\model
 * @property array $url
 */
class AdminRole extends AdminBaseModel
{
    use SoftDelete;

    public array $searchField = [
        'name'
    ];

    /**
     * 角色初始权限
     * @param AdminRole $data
     * @return void
     */
    public static function onBeforeInsert($data): void
    {
        $data->url = empty($data->url) ? [1, 2, 18] : $data->url;
    }

    protected function getUrlAttr($value)
    {
        return !empty($value) ? explode(',', $value) : [];
    }

    protected function setUrlAttr($value): string
    {
        return !empty($value) ? implode(',', $value) : '';
    }
}
