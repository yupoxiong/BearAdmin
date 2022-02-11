<?php
/**
 * 生成菜单
 * @author yupoxiong<i@yupoxiong.com>
 */

declare (strict_types=1);

namespace generate;

use Exception;
use think\facade\Db;
use app\admin\model\AdminMenu;
use generate\exception\GenerateException;

class AdminMenuBuild extends Build
{
    // 菜单信息
    protected array $actionDataList = [
        'add'     => [
            'icon' => 'fa fa-plus',
            'name' => '添加',
        ],
        'edit'    => [
            'icon' => 'fa fa-plus',
            'name' => '修改',
        ],
        'del'     => [
            'icon' => 'fa fa-trash',
            'name' => '删除',
        ],
        'enable'  => [
            'icon' => 'far fa-circle',
            'name' => '启用',
        ],
        'disable' => [
            'icon' => 'fas fa-ban',
            'name' => '禁用',
        ],
        'import'  => [
            'icon' => 'fas fa-file-upload',
            'name' => '导入',
        ],
        'export'  => [
            'icon' => 'fas fa-file-export',
            'name' => '导出',
        ],
    ];

    /**
     * @param array $data 数据
     * @param array $config 配置
     */
    public function __construct(array $data, array $config)
    {
        $this->data   = $data;
        $this->config = $config;
    }

    /**
     * 自动添加菜单
     * @return bool
     * @throws GenerateException
     */
    public function run(): bool
    {
        if ($this->data['menu']['create'] < 0) {
            return true;
        }

        // 菜单前缀
        $url_prefix = 'admin/' . $this->data['table'];
        // 显示名称
        $name_show = $this->data['cn_name'];
        Db::startTrans();
        try {

            $parent = (new AdminMenu)->where('url', $url_prefix . '/index')->find();
            if (!$parent) {
                $parent_data = [
                    'parent_id'  => $this->data['menu']['create'],
                    'name'       => $name_show . $this->data['module']['name_suffix'],
                    'url'        => $url_prefix . '/index',
                    'icon'       => $this->data['module']['icon'],
                    'is_show'    => 1,
                    'log_method' => '不记录',
                ];
                $parent      = AdminMenu::create($parent_data);
            }

            $menu_list = $this->data['menu']['menu_list'];
            foreach ($menu_list as $item) {
                if ($item !== 'index') {
                    $have = (new AdminMenu)->where('url', $url_prefix . '/' . $item)->find();
                    if (!$have) {
                        AdminMenu::create([
                            'parent_id'  => $parent->id,
                            'name'       => $this->actionDataList[$item]['name'] . $name_show,
                            'url'        => $url_prefix . '/' . $item,
                            'icon'       => $this->actionDataList[$item]['icon'],
                            'is_show'    => 0,
                            'log_method' => 'POST',
                        ]);
                    }
                }
            }
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            throw new GenerateException($e->getMessage());
        }
        return true;
    }
}
