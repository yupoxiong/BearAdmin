<?php
/**
 * 用户控制器
 */

namespace app\api\controller;

use think\response\Json;
use app\api\service\UserService;
use app\common\validate\UserValidate;
use app\api\exception\ApiServiceException;

class UserController extends ApiBaseController
{
    // 设置添加用户3秒内无法重复请求
    protected array $throttleAction = [
        'api/user/add' => 3,
    ];

    /**
     * 列表
     * @param UserService $service
     * @return Json
     */
    public function index(UserService $service): Json
    {
        try {
            $data   = $service->getList($this->param, $this->page, $this->limit);
            $result = [
                'user' => $data,
            ];

            return api_success($result);
        } catch (ApiServiceException $e) {
            return api_error($e->getMessage());
        }
    }

    /**
     * 添加
     *
     * @param UserValidate $validate
     * @param UserService $service
     * @return Json
     */
    public function add(UserValidate $validate, UserService $service): Json
    {
        $check = $validate->scene('api_add')->check($this->param);
        if (!$check) {
            return api_error($validate->getError());
        }

        $result = $service->createData($this->param);

        return $result ? api_success() : api_error();
    }

    /**
     * 详情
     *
     * @param UserValidate $validate
     * @param UserService $service
     * @return Json
     */
    public function info(UserValidate $validate, UserService $service): Json
    {
        $check = $validate->scene('api_info')->check($this->param);
        if (!$check) {
            return api_error($validate->getError());
        }

        try {

            $result = $service->getDataInfo($this->id);
            return api_success([
                'user_level' => $result,
            ]);

        } catch (ApiServiceException $e) {
            return api_error($e->getMessage());
        }
    }

    /**
     * 修改
     * @param UserService $service
     * @param UserValidate $validate
     * @return Json
     */
    public function edit(UserService $service, UserValidate $validate): Json
    {
        $check = $validate->scene('api_edit')->check($this->param);
        if (!$check) {
            return api_error($validate->getError());
        }

        try {
            $service->updateData($this->id, $this->param);
            return api_success();
        } catch (ApiServiceException $e) {
            return api_error($e->getMessage());
        }
    }

    /**
     * 删除
     * @param UserService $service
     * @param UserValidate $validate
     * @return Json
     */
    public function del(UserService $service, UserValidate $validate): Json
    {
        $check = $validate->scene('api_del')->check($this->param);
        if (!$check) {
            return api_error($validate->getError());
        }

        try {
            $service->deleteData($this->id);
            return api_success();
        } catch (ApiServiceException $e) {
            return api_error($e->getMessage());
        }
    }

    /**
     * 禁用
     * @param UserService $service
     * @param UserValidate $validate
     * @return Json
     */
    public function disable(UserService $service, UserValidate $validate): Json
    {
        $check = $validate->scene('api_disable')->check($this->param);
        if (!$check) {
            return api_error($validate->getError());
        }

        try {
            $service->disableData($this->id);
            return api_success();
        } catch (ApiServiceException $e) {
            return api_error($e->getMessage());
        }
    }


    /**
     * 启用
     * @param UserService $service
     * @param UserValidate $validate
     * @return Json
     */
    public function enable(UserService $service, UserValidate $validate): Json
    {
        $check = $validate->scene('api_enable')->check($this->param);
        if (!$check) {
            return api_error($validate->getError());
        }

        try {
            $service->enableData($this->id);
            return api_success();
        } catch (ApiServiceException $e) {
            return api_error($e->getMessage());
        }
    }
}
