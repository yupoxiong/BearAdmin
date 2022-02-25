<?php
/**
 * 设置分组控制器
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\controller;

use Exception;
use think\Request;
use think\db\Query;
use think\response\Json;
use app\admin\model\AdminMenu;
use app\common\model\SettingGroup;
use app\common\validate\SettingGroupValidate;

class SettingGroupController extends AdminBaseController
{
    protected array $codeBlacklist = [
        'app', 'cache', 'database', 'console', 'cookie', 'log', 'middleware', 'session', 'template', 'trace',
        'api', 'attachment', 'geetest', 'generate', 'admin', 'paginate', 'abstract', 'and', 'array',
        'as', 'break', 'callable', 'case', 'catch', 'class', 'clone', 'const', 'continue', 'declare', 'default', 'die',
        'do', 'echo', 'else', 'elseif', 'empty', 'enddeclare', 'endfor', 'endforeach', 'endif', 'endswitch', 'endwhile',
        'eval', 'exit', 'extends', 'final', 'finally', 'for', 'foreach', 'function', 'global', 'goto', 'if',
        'include', 'include_once', 'instanceof', 'insteadof', 'interface', 'isset', 'list', 'namespace', 'new',
        'or', 'print', 'private', 'protected', 'public', 'require', 'require_once', 'return', 'static', 'switch',
        'throw', 'trait', 'try', 'unset', 'use', 'var', 'while', 'xor', 'yield', 'int', 'float', 'bool', 'string', 'true',
        'false', 'null', 'index',

    ];

    /**
     * @param Request $request
     * @param SettingGroup $model
     * @return string
     * @throws Exception
     */
    public function index(Request $request, SettingGroup $model): string
    {
        $param = $request->param();
        $data  = $model->scope('where', $param)
            ->paginate([
                'list_rows' => $this->admin['admin_list_rows'],
                'var_page'  => 'page',
                'query'     => $request->get()
            ]);

        // 关键词，排序等赋值
        $this->assign($request->get());

        $this->assign([
            'data'  => $data,
            'page'  => $data->render(),
            'total' => $data->total(),
        ]);
        return $this->fetch();
    }

    /**
     * @param Request $request
     * @param SettingGroup $model
     * @param SettingGroupValidate $validate
     * @return string|Json
     * @throws Exception
     */
    public function add(Request $request, SettingGroup $model, SettingGroupValidate $validate)
    {
        if ($request->isPost()) {
            $param           = $request->param();
            $validate_result = $validate->scene('add')->check($param);
            if (!$validate_result) {
                return admin_error($validate->getError());
            }

            if (in_array($param['code'], $this->codeBlacklist, true)) {
                return admin_error('代码 ' . $param['code'] . ' 在黑名单内，禁止使用');
            }

            $result = $model::create($param);
            $data   = $model->find($result->id);
            if ($data->auto_create_menu === 1) {
                create_setting_menu($data);
            }
            if ($data->auto_create_file === 1) {
                create_setting_file($data);
            }


            $redirect = isset($param['_create']) && (int)$param['_create'] === 1 ? URL_RELOAD : URL_BACK;

            return $result ? admin_success('添加成功', [], $redirect) : admin_error('添加失败');
        }

        $this->assign([
            'module_list' => $this->getModuleList(),
        ]);

        return $this->fetch();
    }

    /**
     * @param $id
     * @param Request $request
     * @param SettingGroup $model
     * @param SettingGroupValidate $validate
     * @return string|Json
     * @throws Exception
     */
    public function edit($id, Request $request, SettingGroup $model, SettingGroupValidate $validate)
    {
        /** @var SettingGroup $data */
        $data = $model->findOrEmpty($id);
        if ($request->isPost()) {
            $param           = $request->param();
            $validate_result = $validate->scene('edit')->check($param);
            if (!$validate_result) {
                return admin_error($validate->getError());
            }

            $result = $data->save($param);
            $data   = $model->find($data->id);
            if ($data->auto_create_menu === 1) {
                create_setting_menu($data);
            }
            if ($data->auto_create_file === 1) {
                create_setting_file($data);
            }

            return $result ? admin_success('修改成功', [], URL_BACK) : admin_error('修改失败');
        }

        $this->assign([
            'data'        => $data,
            'module_list' => $this->getModuleList(),

        ]);
        return $this->fetch('add');

    }

    /**
     * @param $id
     * @param SettingGroup $model
     * @return Json
     * @throws Exception
     */
    public function del($id, SettingGroup $model): Json
    {
        $check = $model->inNoDeletionIds($id);
        if (false !== $check) {
            return admin_error('ID 为' . $check . '的数据无法删除');
        }
        // 删除限制
        $relation_name    = 'setting';
        $relation_cn_name = '设置';
        $tips             = '下有' . $relation_cn_name . '数据，请删除' . $relation_cn_name . '数据后再进行删除操作';
        if (is_array($id)) {
            foreach ($id as $item) {
                /** @var SettingGroup $data */
                $data = $model->find($item);
                if ($data->$relation_name->count() > 0) {
                    return admin_error($data->name . $tips);
                }
            }
        } else {
            /** @var SettingGroup $data */
            $data = $model->find($id);
            if ($data->$relation_name->count() > 0) {
                return admin_error($data->name . $tips);
            }
        }

        $result = $model::destroy(static function ($query) use ($id) {
            /** @var Query $query */
            $query->whereIn('id', $id);
        });

        return $result ? admin_success('删除成功', [], URL_RELOAD) : admin_error('删除失败');
    }


    /**
     * 生成配置文件，配置文件名为模块名
     * @param $id
     * @param SettingGroup $model
     * @return Json
     * @throws Exception
     */
    public function file($id, SettingGroup $model): Json
    {
        /** @var SettingGroup $data */
        $data = $model->find($id);

        $file = $data->code . '.php';
        if ($data->module !== 'app') {
            $file = $data->module . '/' . $data->code . '.php';
        }

        $path    = app()->getConfigPath() . $file;
        $warning = cache('create_setting_file_' . $data->code);
        $have    = file_exists($path);
        if (!$warning && $have) {

            cache('create_setting_file_' . $data->code, '1', 5);
            return admin_error('当前配置文件已存在，如果确认要替换请在5秒内再次点击生成按钮');
        }

        $result = create_setting_file($data);
        return $result ? admin_success('生成成功', URL_RELOAD) : admin_error('生成失败');

    }

    /**
     * @param $id
     * @param SettingGroup $model
     * @return Json
     */
    public function menu($id, SettingGroup $model): Json
    {
        /** @var SettingGroup $data */
        $data = $model->findOrEmpty($id);
        if ($data->isEmpty()) {
            return admin_error('数据不存在');
        }

        $have    = (new AdminMenu)->where('url', get_setting_menu_url($data))->findOrEmpty();
        $warning = cache('create_setting_menu_' . $data->code);
        if (!$warning && !$have->isEmpty()) {

            cache('create_setting_menu_' . $data->code, '1', 5);
            return admin_error('当前配置菜单已存在，如果确认要替换请在5秒内再次点击生成按钮');
        }

        $result = create_setting_menu($data);
        return $result ? admin_success('生成成功', URL_RELOAD) : admin_error('生成失败');
    }

    /**
     * 获取所有项目模块
     * @return array
     */
    protected function getModuleList(): array
    {
        $app_path    = app()->getRootPath() . 'app/';
        $module_list = [];
        $all_list    = scandir($app_path);

        foreach ($all_list as $item) {
            if ($item !== '.' && $item !== '..' && is_dir($app_path . $item)) {
                $module_list[] = $item;
            }
        }
        return $module_list;
    }
}
