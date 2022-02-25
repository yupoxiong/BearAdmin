<?php
/**
 * 后台公共函数文件
 * @author yupoxiong<i@yupoxiong.com>
 */

use app\admin\exception\AdminServiceException;
use app\admin\controller\SettingController;
use app\admin\service\AuthService;
use app\common\model\SettingGroup;
use app\admin\model\AdminMenu;
use think\response\Json;

/** 不做任何操作 */
const URL_CURRENT = 'url://current';
/** 刷新页面 */
const URL_RELOAD = 'url://reload';
/** 返回上一个页面 */
const URL_BACK = 'url://back';
/** 关闭当前layer弹窗 */
const URL_CLOSE_LAYER = 'url://close_layer';
/** 关闭当前弹窗并刷新父级 */
const URL_CLOSE_REFRESH = 'url://close_refresh';

if (!function_exists('admin_success')) {

    /**
     * 后台返回成功
     * @param string $msg
     * @param mixed $data ,
     * @param int $code
     * @param string $url
     * @param array $header
     * @param array $options
     * @return Json
     */
    function admin_success(string $msg = '操作成功', $data = [], string $url = URL_CURRENT, int $code = 200, array $header = [], array $options = []): Json
    {
        return admin_result($msg, $data, $url, $code, $header, $options);
    }
}

if (!function_exists('admin_error')) {
    /**
     * 后台返回错误
     * @param string $msg
     * @param mixed $data ,
     * @param string $url
     * @param int $code
     * @param array $header
     * @param array $options
     * @return Json
     */
    function admin_error(string $msg = '操作失败', $data = [], string $url = URL_CURRENT, int $code = 500, array $header = [], array $options = []): Json
    {
        return admin_result($msg, $data, $url, $code, $header, $options);
    }
}

if (!function_exists('admin_result')) {
    /**
     * 后台返回结果
     * @param mixed $data ,
     * @param string $msg
     * @param string $url
     * @param int $code
     * @param array $header
     * @param array $options
     * @return Json
     */
    function admin_result(string $msg = '', $data = [], string $url = URL_CURRENT, int $code = 500, array $header = [], array $options = []): Json
    {
        $data = [
            'msg'  => $msg,
            'code' => $code,
            'data' => empty($data) ? (object)$data : $data,
            'url'  => $url,
        ];

        return json($data, 200, $header, $options);
    }
}

if (!function_exists('create_setting_file')) {
    /**
     * 生成配置文件
     * @param SettingGroup $data
     * @return bool
     */
    function create_setting_file(SettingGroup $data): bool
    {
        $file = 'config/' . $data->code . '.php';
        if ($data->module !== 'app') {
            $file = 'app/' . $data->module . '/' . $file;
        }

        $setting   = $data->setting;
        $path      = app()->getRootPath() . $file;
        $file_code = "<?php\r\n/**\r\n* " .
            $data->name . ':' . $data->description .
            "\r\n* 此配置文件为自动生成，生成时间" . date('Y-m-d H:i:s') .
            "\r\n*/\r\n\r\nreturn [";
        foreach ($setting as $value) {
            $file_code .= "\r\n    // " . $value['name'] . ':' . $value['description'] . "\r\n    '" . $value['code'] . "'=>[";
            foreach ($value->content as $content) {
                if (is_array($content['content'])) {
                    $content['content'] = implode(',', $content['content']);

                }
                $file_code .= "\r\n    // " . $content['name'] . "\r\n    '" .
                    $content['field'] . "'=>'" . $content['content'] . "',";

            }
            $file_code .= "\r\n],";
        }
        $file_code .= "\r\n];";
        $result    = file_put_contents($path, $file_code);

        return (bool)$result;
    }
}

if (!function_exists('create_setting_menu')) {
    /**
     * 生成配置文件
     * @param SettingGroup $data
     * @return bool
     */
    function create_setting_menu(SettingGroup $data): bool
    {
        $function = <<<EOF
    /**
    * [GROUP_NAME]
    * @return string
    * @throws Exception
    */
    public function [GROUP_CODE]()
    {
        return \$this->show([GROUP_ID]);
    }\n
}//append_menu
EOF;

        $url = get_setting_menu_url($data);
        /** @var AdminMenu $menu */
        $menu = (new app\admin\model\AdminMenu)->where('url', $url)->findOrEmpty();
        if ($menu->isEmpty()) {
            $result = AdminMenu::create([
                'parent_id' => 43,
                'name'      => $data->name,
                'icon'      => $data->icon,
                'is_show'   => 1,
                'url'       => $url
            ]);
        } else {
            $menu->name = $data->name;
            $menu->icon = $data->icon;
            $menu->url  = $url;
            $result     = $menu->save();
        }

        if (!method_exists(SettingController::class, $data->code)) {

            $function = str_replace(array('[GROUP_CODE]', '[GROUP_ID]', '[GROUP_NAME]'), array($data->code, $data->id, $data->name), $function);

            $file_path = app()->getAppPath() . 'controller/SettingController.php';
            $file      = file_get_contents($file_path);
            $file      = str_replace('}//append_menu', $function, $file);
            file_put_contents($file_path, $file);
        }

        return (bool)$result;
    }
}

if (!function_exists('get_setting_menu_url')) {
    /**
     * 获取菜单url
     * @param $data
     * @return string
     */
    function get_setting_menu_url($data): string
    {
        return 'admin/setting/' . $data->code;
    }
}


if (!function_exists('view_check_auth')) {
    /**
     * 前端检查权限，，主要用在元素的显示上，使用方法，在需要判断权限的元素上添加class"viewCheckAuth"和data-auth属性
     * 例如：  data-auth="{:view_check_auth('question_library/add')}"，
     * 当有权限的时候会自动显示该元素，没有权限的时候不会显示该元素
     *  url形式参考：user/edit，admin/user/edit，前缀"admin/"可以去掉
     * @param $url
     * @return string
     */
    function view_check_auth($url): string
    {
        try {
            return (new AuthService())->viewCheckAuth($url);
        } catch (AdminServiceException $e) {
            return '0';
        }
    }
}
