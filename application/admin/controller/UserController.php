<?php
/**
 * 用户控制器
 */

namespace app\admin\controller;

use think\Request;
use app\common\model\User;
use app\common\model\UserLevel;

use app\common\validate\UserValidate;

class UserController extends Controller
{

    //列表
    public function index(Request $request, User $model)
    {
        $param = $request->param();
        $model = $model->with('user_level')->scope('where', $param);
        if (isset($param['export_data']) && $param['export_data'] == 1) {
            $header = ['ID', '头像', '用户等级', '用户名', '手机号', '昵称', '是否启用', '创建时间',];
            $body   = [];
            $data   = $model->select();
            foreach ($data as $item) {
                $record                  = [];
                $record['id']            = $item->id;
                $record['avatar']        = $item->avatar;
                $record['user_level_id'] = $item->user_level->name ?? '';
                $record['username']      = $item->username;
                $record['mobile']        = $item->mobile;
                $record['nickname']      = $item->nickname;
                $record['status']        = $item->status_text;
                $record['create_time']   = $item->create_time;

                $body[] = $record;
            }
            return $this->exportData($header, $body, 'user-' . date('Y-m-d-H-i-s'));
        }
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
    public function add(Request $request, User $model, UserValidate $validate)
    {
        if ($request->isPost()) {
            $param           = $request->param();
            $validate_result = $validate->scene('add')->check($param);
            if (!$validate_result) {
                return error($validate->getError());
            }
            //处理头像上传
            $attachment_avatar = new \app\common\model\Attachment;
            $file_avatar       = $attachment_avatar->upload('avatar');
            if ($file_avatar) {
                $param['avatar'] = $file_avatar->url;
            } else {
                return error($attachment_avatar->getError());
            }


            $result = $model::create($param);

            $url = URL_BACK;
            if (isset($param['_create']) && $param['_create'] == 1) {
                $url = URL_RELOAD;
            }

            return $result ? success('添加成功', $url) : error();
        }

        $this->assign([
            'user_level_list' => UserLevel::all(),

        ]);


        return $this->fetch();
    }

    //修改
    public function edit($id, Request $request, User $model, UserValidate $validate)
    {

        $data = $model::get($id);
        if ($request->isPost()) {
            $param           = $request->param();
            $validate_result = $validate->scene('edit')->check($param);
            if (!$validate_result) {
                return error($validate->getError());
            }
            //处理头像上传
            if (!empty($_FILES['avatar']['name'])) {
                $attachment_avatar = new \app\common\model\Attachment;
                $file_avatar       = $attachment_avatar->upload('avatar');
                if ($file_avatar) {
                    $param['avatar'] = $file_avatar->url;
                }
            }


            $result = $data->save($param);
            return $result ? success() : error();
        }

        $this->assign([
            'data'            => $data,
            'user_level_list' => UserLevel::all(),

        ]);
        return $this->fetch('add');

    }

    //删除
    public function del($id, User $model)
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

    //启用
    public function enable($id, User $model)
    {
        $result = $model->whereIn('id', $id)->update(['status' => 1]);
        return $result ? success('操作成功', URL_RELOAD) : error();
    }


//禁用
    public function disable($id, User $model)
    {
        $result = $model->whereIn('id', $id)->update(['status' => 0]);
        return $result ? success('操作成功', URL_RELOAD) : error();
    }
}
