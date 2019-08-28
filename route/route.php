<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------


use think\facade\Route;

//api模块路由
Route::group('api', function () {

    //自带示例，上线务必删除
    Route::resource('user','api/User') ->only(['index','save', 'read', 'update','delete']);

    //miss路由
    Route::miss(function (){
        return json([
            'code' => 404,
            'msg'  => '接口不存在',
            'data' => '',
        ], 404);
    });
});


return [

];
