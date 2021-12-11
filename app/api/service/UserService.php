<?php
/**
 * 用户Service
 */

declare (strict_types=1);

namespace app\api\service;

use app\common\model\User;
use app\api\exception\ApiServiceException;
use think\db\exception\{DbException, ModelNotFoundException, DataNotFoundException};

class UserService extends ApiBaseService
{
    protected User $model;

    public function __construct()
    {
        $this->model = new User();
    }

        /**
     * 列表
     * @param $param
     * @param $page
     * @param $limit
     * @return array
     * @throws ApiServiceException
     */
    public function getList($param, $page, $limit): array
    {
        try {
            $field = 'id,user_level_id,username,mobile,nickname,avatar,status,create_time';
            $data  = $this->model
                ->scope('ApiWhere', $param)
                ->page($page, $limit)
                ->field($field)
                ->select()
                ->toArray();
        } catch (DataNotFoundException | ModelNotFoundException $e) {
            $data = [];
        } catch (DbException $e) {
            throw new ApiServiceException('查询列表失败，信息' . $e->getMessage());
        }

        return $data;
    }
    
        /**
     * 添加
     * @param $param
     * @return bool
     */
    public function createData($param): bool
    {
        $result = $this->model::create($param);
        return (bool)$result;
    }
    
        /**
     * 数据详情
     * @param $id
     * @return array
     * @throws ApiServiceException
     */
    public function getDataInfo($id): array
    {

        $data = $this->model->where('id', '=', $id)->findOrEmpty();
        if ($data->isEmpty()) {
            throw new ApiServiceException('数据不存在');
        }
        return $data->toArray();
    }
    
        /**
     * 修改
     * @param $id
     * @param $param
     * @return bool
     * @throws ApiServiceException
     */
    public function updateData($id, $param): bool
    {
        $data = $this->model->where('id', '=', $id)->findOrEmpty();
        if ($data->isEmpty()) {
            throw new ApiServiceException('数据不存在');
        }
        $result = $data->save($param);

        if (!$result) {
            throw new ApiServiceException('更新失败');
        }

        return true;
    }
    
        /**
     * 删除
     * @param mixed $id
     * @return bool
     * @throws ApiServiceException
     */
    public function deleteData($id): bool
    {
        $result = $this->model::destroy(function ($query) use ($id) {
            $query->whereIn('id', $id);
        });

        if (!$result) {
            throw new ApiServiceException('更新失败');
        }

        return  true;
    }
    
        /**
     * 禁用
     * @param mixed $id
     * @return bool
     * @throws ApiServiceException
     */
    public function disableData($id): bool
    {
        $result = $this->model
            ->whereIn('id', $id)
            ->save(['status' => 0]);

        if (!$result) {
            throw new ApiServiceException('禁用失败');
        }

        return true;
    }
    
        /**
     * 启用
     * @param mixed $id
     * @return bool
     * @throws ApiServiceException
     */
    public function enableData($id): bool
    {

        $result = $this->model
            ->whereIn('id', $id)
            ->save(['status' => 1]);

        if (!$result) {
            throw new ApiServiceException('启用失败');
        }

        return true;
    }
}
