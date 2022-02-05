<?php
/**
 * 测试控制器
 */

namespace app\api\controller;

use think\response\Json;
use app\api\service\TestService;
use app\common\validate\TestValidate;
use app\api\exception\ApiServiceException;
use util\jwt\Example;
use util\safe\SafeCookie;

class TestController extends ApiBaseController
{

    protected array $loginExcept= [
        'api/test/index'
    ];

    /**
     * 列表
     * @param TestService $service
     * @return Json
     */
    public function index(TestService $service): Json
    {
        return api_success();
    }

    /**
     * 添加
     *
     * @param TestValidate $validate
     * @param TestService $service
     * @return Json
     */
    public function add(TestValidate $validate, TestService $service): Json
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
     * @param TestValidate $validate
     * @param TestService $service
     * @return Json
     */
    public function info(TestValidate $validate, TestService $service): Json
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
     * @param TestService $service
     * @param TestValidate $validate
     * @return Json
     */
    public function edit(TestService $service, TestValidate $validate): Json
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
     * @param TestService $service
     * @param TestValidate $validate
     * @return Json
     */
    public function del(TestService $service, TestValidate $validate): Json
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
