<?php
const URL_CURRENT = 'url://current';
const URL_RELOAD  = 'url://reload';
const URL_BACK    = 'url://back';

use app\admin\model\AdminMenu;
use app\common\model\SettingGroup;
use think\exception\HttpResponseException;
use think\Response;
use think\response\Redirect;

if (!function_exists('success')) {

    function success($msg = '操作成功', $url = URL_BACK, $data = '', $wait = 0, array $header = [])
    {
        result(1, $msg, $data, $url, $wait, $header);
    }
}


if (!function_exists('error')) {
    function error($msg = '操作失败', $url = URL_CURRENT, $data = '', $wait = 0, array $header = [])
    {
        result(0, $msg, $data, $url, $wait, $header);
    }
}

if (!function_exists('result')) {
    function result($code = 0, $msg = 'unknown', $data = '', $url = null, $wait = 3, array $header = [])
    {
        if (request()->isPost()) {
            $url      = (strpos($url, '://') || 0 === strpos($url, '/')) ? $url : url($url);
            $result   = [
                'code' => $code,
                'msg'  => $msg,
                'data' => $data,
                'url'  => $url,
                'wait' => $wait,
            ];
            $response = Response::create($result, 'json')->header($header);
            throw new HttpResponseException($response);
        }


        if ($url === null) {
            $url = request()->server('HTTP_REFERER') ?? 'admin/index/index';
        }

        $response = new Redirect($url);

        $response->code(302)->params($data)->with([$code ? 'success_message' : 'error_message' => $msg, 'url' => $url]);

        throw new HttpResponseException($response);
    }
}


if (!function_exists('create_setting_file')) {
    /**
     * 生成配置文件
     * @param $data SettingGroup
     * @return bool
     */
    function create_setting_file($data)
    {
        $result = true;
        if ($data->auto_create_file == 1) {
            $file = $data->code . '.php';
            if ($data->module !== 'app') {
                $file = $data->module . '/' . $data->code . '.php';
            }

            $setting   = $data->setting;
            $path      = app()->getConfigPath() . $file;
            $file_code = "<?php\r\n\r\n/**\r\n* " .
                $data->name . ':' . $data->description .
                "\r\n* 此配置文件为自动生成，生成时间" . date('Y-m-d H:i:s') .
                "\r\n*/\r\n\r\nreturn [";
            foreach ($setting as $key => $value) {
                $file_code .= "\r\n    //" . $value['name'] . ':' . $value['description'] . "\r\n    '" . $value['code'] . "'=>[";
                foreach ($value->content as $content) {
                    $file_code .= "\r\n    //" . $content['name'] . "\r\n    '" .
                        $content['field'] . "'=>'" . $content['content'] . "',";
                }
                $file_code .= "\r\n],";
            }
            $file_code .= "\r\n];";
            $result    = file_put_contents($path, $file_code);
        }
        return $result ? true : false;
    }
}


if (!function_exists('create_setting_menu')) {
    /**
     * 生成配置文件
     * @param $data SettingGroup
     * @return bool
     */
    function create_setting_menu($data)
    {

        $function = <<<EOF
    public function [GROUP_COED]()
    {
        return \$this->show([GROUP_ID]);
    }\n
}//append_menu
EOF;

        $result = true;
        if ($data->auto_create_menu == 1) {
            $url  = 'admin/setting/' . $data->code;
            $menu = AdminMenu::get(function ($query) use ($url) {
                $query->where('url', $url);
            });
            if (!$menu) {
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

            $setting = new \app\admin\controller\SettingController();
            if (!method_exists($setting, $data->code)) {

                $function = str_replace(array('[GROUP_COED]', '[GROUP_ID]'), array($data->code, $data->id), $function);

                $file_path = app()->getAppPath() . 'admin/controller/SettingController.php';
                $file      = file_get_contents($file_path);
                $file      = str_replace('}//append_menu', $function, $file);
                file_put_contents($file_path, $file);
            }
        }

        return $result ? true : false;
    }
}