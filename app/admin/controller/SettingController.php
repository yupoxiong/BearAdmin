<?php
/**
 * 设置控制器
 * @author yupoxiong<i@yufuping.com>
 */

namespace app\admin\controller;

use Exception;
use think\Request;
use think\db\Query;
use RuntimeException;
use think\response\Json;
use app\common\model\Setting;
use app\common\model\SettingGroup;
use app\admin\traits\SettingContent;
use app\admin\traits\AdminSettingForm;
use app\common\validate\SettingValidate;

class SettingController extends AdminBaseController
{
    // 引入form相关trait
    use AdminSettingForm;
    use SettingContent;

    /**
     * 设置列表
     * @param Request $request
     * @param Setting $model
     * @return string
     * @throws Exception
     */
    public function index(Request $request, Setting $model): string
    {
        $param = $request->param();
        $data  = $model->with('setting_group')
            ->scope('where', $param)
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
     * 添加设置
     * @param Request $request
     * @param Setting $model
     * @param SettingValidate $validate
     * @return string|Json
     * @throws Exception
     */
    public function add(Request $request, Setting $model, SettingValidate $validate)
    {
        if ($request->isPost()) {
            $param           = $request->param();
            $validate_result = $validate->scene('add')->check($param);
            if (!$validate_result) {
                return admin_error($validate->getError());
            }

            try {
                $param['content'] = $this->getContent($param);
            } catch (RuntimeException $exception) {
                return admin_error($exception->getMessage());
            }

            $result = $model::create($param);

            $url = URL_BACK;
            if (isset($param['_create']) && ((int)$param['_create']) === 1) {
                $url = URL_RELOAD;
            }

            $group = (new SettingGroup)->find($result->setting_group_id);
            create_setting_file($group);

            return $result ? admin_success('添加成功', $url) : admin_error();
        }

        $this->assign([
            'setting_group_list' => (new SettingGroup)->select(),
        ]);

        return $this->fetch();
    }

    /**
     * 修改设置
     * @param $id
     * @param Request $request
     * @param Setting $model
     * @param SettingValidate $validate
     * @return string|Json
     * @throws Exception
     */
    public function edit($id, Request $request, Setting $model, SettingValidate $validate)
    {

        $data = $model->findOrEmpty($id);
        if ($request->isPost()) {
            $param           = $request->param();
            $validate_result = $validate->scene('edit')->check($param);
            if (!$validate_result) {
                return admin_error($validate->getError());
            }

            try {
                $param['content'] = $this->getContent($param);
            } catch (RuntimeException $exception) {
                return admin_error($exception->getMessage());
            }

            $result = $data->save($param);

            //自动更新配置文件
            $group = (new SettingGroup())->findOrEmpty($data->setting_group_id);
            create_setting_file($group);

            return $result ? admin_success() : admin_error();
        }

        $this->assign([
            'data'               => $data,
            'setting_group_list' => (new SettingGroup())->select(),

        ]);
        return $this->fetch('add');
    }

    /**
     * 删除设置
     * @param $id
     * @param Setting $model
     * @return Json
     */
    public function del($id, Setting $model): Json
    {
        $check = $model->inNoDeletionIds($id);
        if (false !== $check) {
            return admin_error('ID为' . $check . '的数据不能被删除');
        }

        $result = $model::destroy(static function ($query) use ($id) {
            /** @var Query $query */
            $query->whereIn('id', $id);
        });

        return $result ? admin_success('删除成功', [], URL_RELOAD) : admin_error('删除失败');
    }

    /**
     * @param $id
     * @return string
     * @throws Exception
     */
    protected function show($id): string
    {
        $data = (new Setting)->where('setting_group_id', '=', $id)->select();
        foreach ($data as $value) {
            $content_new = [];
            foreach ($value->content as $content) {

                $content['form'] = $this->getFieldForm($content['type'], $content['name'], $content['field'], $content['content'], $content['option']);
                $content_new[]   = $content;
            }
            $value->content = $content_new;
        }

        //自动更新配置文件
        $group                = (new SettingGroup)->find($id);
        $this->admin['title'] = $group->name;

        $this->assign([
            'data_config' => $data,
        ]);

        return $this->fetch('show');
    }

    /**
     * 更新配置
     * @param Request $request
     * @param Setting $model
     * @return Json
     */
    public function update(Request $request, Setting $model): Json
    {
        $param = $request->param();
        $id = $param['id'];
        $config = $model->findOrEmpty($id);

        $content_data = [];
        foreach ($config->content as $value) {
            if ($value['type'] === 'map' || $value['type'] === 'multi_select') {
                $param[$value['field']] = implode(',', $param[$value['field']]);
            }

            $value['content'] = $param[$value['field']];
            $content_data[]   = $value;
        }

        $config->content = $content_data;
        $result          = $config->save();

        //自动更新配置文件
        $group = (new SettingGroup)->findOrEmpty($config->setting_group_id);
        if (((int)$group->auto_create_file) === 1) {
            create_setting_file($group);
        }

        return $result ? admin_success('修改成功', [], URL_RELOAD) : admin_error();
    }

    /**
     * @param Request $request
     * @param SettingGroup $model
     * @return string
     * @throws Exception
     */
    public function all(Request $request, SettingGroup $model): string
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
     * @param $id
     * @return string
     * @throws Exception
     */
    public function info($id): string
    {
        return $this->show($id);
    }

    /**
     * 后台设置
     * @return string
     * @throws Exception
     */
    public function admin(): string
    {
        return $this->show(1);
    }

}//append_menu
//请勿删除上面的注释，上面注释为自动追加菜单方法标记
