<?php
/**
 * 用户等级控制器
 */

namespace app\api\controller;

use think\response\Json;
use app\api\service\UserLevelService;
use app\common\validate\UserLevelValidate;
use app\api\exception\ApiServiceException;

class UserLevelController extends ApiBaseController
{
    // 设置添加用户3秒内无法重复请求
    protected array $throttleAction = [
        'api/user_level/add' => 3,
    ];

    /**
     * 列表
     * @param UserLevelService $service
     * @return Json
     */
    public function index(UserLevelService $service): Json
    {
        try {
            $data   = $service->getList($this->param, $this->page, $this->limit);
            $result = [
                'user_level' => $data,
            ];

            return api_success($result);
        } catch (ApiServiceException $e) {
            return api_error($e->getMessage());
        }
    }

    /**
     * 添加
     *
     * @param UserLevelValidate $validate
     * @param UserLevelService $service
     * @return Json
     */
    public function add(UserLevelValidate $validate, UserLevelService $service): Json
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
     * @param UserLevelValidate $validate
     * @param UserLevelService $service
     * @return Json
     */
    public function info(UserLevelValidate $validate, UserLevelService $service): Json
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
     * @param UserLevelService $service
     * @param UserLevelValidate $validate
     * @return Json
     */
    public function edit(UserLevelService $service, UserLevelValidate $validate): Json
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
     * @param UserLevelService $service
     * @param UserLevelValidate $validate
     * @return Json
     */
    public function del(UserLevelService $service, UserLevelValidate $validate): Json
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

    

    
}
