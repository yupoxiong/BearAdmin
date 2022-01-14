<?php
/**
 * 后台菜单模型
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);

namespace app\admin\model;

use think\model\concern\SoftDelete;

/**
 * Class AdminMenu
 * @package app\admin\model
 * @property int $parent_id
 * @property string $name
 * @property int $id
 * @property string $icon
 * @property string $url
 * @property string $log_method
 */
class AdminMenu extends AdminBaseModel
{
    use SoftDelete;
    /**
     * @var array 不允许被删除的菜单ID
     */
    public array $noDeletionIds = [
        1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20
    ];

    /**
     * @var array 日志记录方法列表
     */
    public static array $logMethodList = [
        [
            'id'   => '不记录',
            'name' => '不记录',
        ],
        [
            'id'   => 'GET',
            'name' => 'GET',
        ],
        [
            'id'   => 'POST',
            'name' => 'POST',
        ],
        [
            'id'   => 'PUT',
            'name' => 'PUT',
        ],
        [
            'id'   => 'DELETE',
            'name' => 'DELETE',
        ],
        [
            'id'   => 'PATCH',
            'name' => 'PATCH',
        ],
    ];
}
