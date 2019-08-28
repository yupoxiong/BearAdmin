<?php

namespace app\api\controller;

use app\common\model\User;
use app\common\validate\UserValidate;
use think\Request;

class UserController extends Controller
{

    //列表
    public function index(User $model)
    {
        $data = $model->field('id,nickname,avatar')->page($this->page, $this->limit)->select();
        return success($data);
    }

    //新增
    public function save(Request $request, User $model, UserValidate $validate)
    {
        $param           = $request->param();
        $validate_result = $validate->scene('add')->check($param);
        if (!$validate_result) {
            return error($validate->getError());
        }
        $result = $model::create($param);
        return $result ? success() : error();
    }


    //查看
    public function read($id, User $model)
    {
        $data = $model::get(function ($query) use ($id) {
            $query->where('id', $id)->field('id,nickname,avatar');
        });

        return success($data);
    }


    //更新
    public function update($id, Request $request, User $model, UserValidate $validate)
    {
        $data            = $model::get($id);
        $param           = $request->param();
        $validate_result = $validate->scene('edit')->check($param);
        if (!$validate_result) {
            return error($validate->getError());
        }

        $result = $data->save($param);
        return $result ? success() : error();
    }


    //删除
    public function delete($id, User $model)
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

        return $result ? success('删除成功') : error('删除失败');
    }
}
