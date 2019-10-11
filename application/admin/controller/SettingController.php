<?php
/**
 * 设置控制器
 */

namespace app\admin\controller;

use think\Request;

use app\common\model\Setting;
use app\common\model\SettingGroup;
use app\common\validate\SettingValidate;
use app\admin\traits\SettingForm;

class SettingController extends Controller
{
    use SettingForm;

    //列表
    public function index(Request $request, Setting $model)
    {
        $param = $request->param();
        $model = $model->with('setting_group')->scope('where', $param);

        $data = $model->paginate($this->admin['per_page'], false, ['query' => $request->get()]);
        //关键词，排序等赋值
        $this->assign($request->get());

        $this->assign([
            'data'  => $data,
            'page'  => $data->render(),
            'total' => $data->total(),
        ]);
        return $this->fetch();
    }

    //添加
    public function add(Request $request, Setting $model, SettingValidate $validate)
    {
        if ($request->isPost()) {
            $param           = $request->param();
            $validate_result = $validate->scene('add')->check($param);
            if (!$validate_result) {
                return error($validate->getError());
            }

            foreach ($param['config_name'] as $key => $value) {
                if (($param['config_name'][$key]) == ''
                    || ($param['config_field'][$key] == '')
                    || ($param['config_type'][$key] == '')
                ) {
                    return error('设置信息不完整');
                }

                if (in_array($param['config_type'][$key], ['select', 'multi_select', 'radio', 'checkbox']) && ($param['config_option'][$key] == '')) {
                    return error('设置信息不完整');
                }

                $content[] = [
                    'name'    => $value,
                    'field'   => $param['config_field'][$key],
                    'type'    => $param['config_type'][$key],
                    'content' => $param['config_content'][$key],
                    'option'  => $param['config_option'][$key],
                ];

            }

            $param['content'] = $content;

            $result = $model::create($param);

            $url = URL_BACK;
            if (isset($param['_create']) && $param['_create'] == 1) {
                $url = URL_RELOAD;
            }

            //自动更新配置文件
            $group = SettingGroup::get($result->setting_group_id);
            create_setting_file($group);

            return $result ? success('添加成功', $url) : error();
        }

        $this->assign([
            'setting_group_list' => SettingGroup::all(),

        ]);

        return $this->fetch();
    }

    //修改
    public function edit($id, Request $request, Setting $model, SettingValidate $validate)
    {

        $data = $model::get($id);
        if ($request->isPost()) {
            $param           = $request->param();
            $validate_result = $validate->scene('edit')->check($param);
            if (!$validate_result) {
                return error($validate->getError());
            }

            foreach ($param['config_name'] as $key => $value) {
                if (($param['config_name'][$key]) == ''
                    || ($param['config_field'][$key] == '')
                    || ($param['config_type'][$key] == '')
                ) {
                    return error('设置信息不完整');
                }

                if (in_array($param['config_type'][$key], ['select', 'multi_select', 'radio', 'checkbox']) && ($param['config_option'][$key] == '')) {
                    return error('设置信息不完整');
                }

                $content[] = [
                    'name'    => $value,
                    'field'   => $param['config_field'][$key],
                    'type'    => $param['config_type'][$key],
                    'content' => $param['config_content'][$key],
                    'option'  => $param['config_option'][$key],
                ];

            }

            $param['content'] = $content;

            $result = $data->save($param);

            //自动更新配置文件
            $group = SettingGroup::get($data->setting_group_id);
            create_setting_file($group);

            return $result ? success() : error();
        }

        $this->assign([
            'data'               => $data,
            'setting_group_list' => SettingGroup::all(),

        ]);
        return $this->fetch('add');

    }

    //删除
    public function del($id, Setting $model)
    {
        if (count($model->noDeletionId) > 0) {
            if (is_array($id)) {
                if (array_intersect($model->noDeletionId, $id)) {
                    return error('ID为' . implode(',', $model->noDeletionId) . '的数据无法删除');
                }
            } else if (in_array($id, $model->noDeletionId)) {
                return error('ID为' . $id . '的数据无法删除');
            }
        }

        if ($model->softDelete) {
            $result = $model->whereIn('id', $id)->useSoftDelete('delete_time', time())->delete();
        } else {
            $result = $model->whereIn('id', $id)->delete();
        }

        return $result ? success('操作成功', URL_RELOAD) : error();
    }


    protected function show($id)
    {
        $data = Setting::where('setting_group_id', $id)->select();

        foreach ($data as $key => $value) {

            $content_new = [];

            foreach ($value->content as $kk => $content) {

                $content['form'] = $this->getFieldForm($content['type'], $content['name'], $content['field'], $content['content'], $content['option']);

                $content_new[] = $content;
            }

            $value->content = $content_new;
        }

        //自动更新配置文件
        $group                = SettingGroup::get($id);
        $this->admin['title'] = $group->name;

        $this->assign([
            'data_config' => $data,
        ]);

        return $this->fetch('show');
    }


    //更新设置
    public function update(Request $request, Setting $model)
    {
        $param = $request->param();

        $id = $param['id'];

        $config = $model::get($id);

        $content_data = [];
        foreach ($config->content as $key => $value) {

            switch ($value['type']) {
                case 'image' :
                case 'file':

                    //处理图片上传
                    if (!empty($_FILES[$value['field']]['name'])) {
                        $attachment = new \app\common\model\Attachment;
                        $file       = $attachment->upload($value['field']);
                        if ($file) {
                            $value['content'] = $param[$value['field']] = $file->url;
                        }
                    }
                    break;

                case 'multi_file':
                case 'multi_image':

                    if (!empty($_FILES[$value['field']]['name'])) {
                        $attachment = new \app\common\model\Attachment;
                        $file       = $attachment->uploadMulti($value['field']);
                        if ($file) {
                            $value['content'] = $param[$value['field']] = json_encode($file);
                        }
                    }
                    break;

                default:
                    $value['content'] = $param[$value['field']];
                    break;
            }

            $content_data[] = $value;
        }

        $config->content = $content_data;
        $result          = $config->save();

        //自动更新配置文件
        $group = SettingGroup::get($config->setting_group_id);
        if ($group->auto_create_file == 1) {
            create_setting_file($group);
        }

        return $result ? success('修改成功', URL_RELOAD) : error();

    }


    //列表
    public function all(Request $request, SettingGroup $model)
    {
        $param = $request->param();
        $model = $model->scope('where', $param);

        $data = $model->paginate($this->admin['per_page'], false, ['query' => $request->get()]);
        //关键词，排序等赋值
        $this->assign($request->get());

        $this->assign([
            'data'  => $data,
            'page'  => $data->render(),
            'total' => $data->total(),
        ]);
        return $this->fetch();
    }


    //单个配置的详情
    public function info($id)
    {

        return $this->show($id);
    }


    public function admin()
    {
        return $this->show(1);
    }

}//append_menu
//请勿删除上面的注释，上面注释为自动追加菜单方法标记
