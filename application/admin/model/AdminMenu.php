<?php
/**
 * 后台菜单(权限)模型
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\model;

/**
 * Class AdminMenu
 * @package app\admin\model
 * @property int $id
 * @property string $name
 * @property string $log_method
 */
class AdminMenu extends Model
{
    protected $name = 'admin_menu';

    public $softDelete = false;

    public $logMethod = [
        0 => '不记录',
        1 => 'GET',
        2 => 'POST',
        3 => 'PUT',
        4 => 'DELETE'
    ];

    public $noDeletionId = [
        1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20
    ];


}