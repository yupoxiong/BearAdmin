# BearAdmin
基于ThinkPHP5.1+AdminLTE的后台管理系统。TP5.0版本[点击这里](https://github.com/yupoxiong/BearAdmin/tree/thinkphp5.0)


 [开发文档](https://www.kancloud.cn/codebear/admin_system) |
  [在线DEMO](https://admindemo.yupoxiong.com/) |  [DEMO源码](https://github.com/yupoxiong/AdminDemo) 
 
 在线 DEMO 账号密码：admin/admindemo
## 安装步骤
#### clone 项目到本地
```
git clone https://github.com/yupoxiong/BearAdmin.git
```
或
```
git clone https://gitee.com/yupoxiong/BearAdmin.git
```

#### 安装项目依赖
```
composer install
```

#### 配置数据库
更改 `/config/database.php` 文件内的数据库配置选项，数据库编码推荐`utf8mb4`。

#### 运行数据库迁移命令
```
php think migrate:run
``` 

#### 配置URL重写
具体可参考[ThinkPHP5.1完全开发手册](https://www.kancloud.cn/manual/thinkphp5_1/353955)

#### 访问后台
访问`/admin`，默认超级管理员的账号密码都为`super_admin`。


#### api模块路由示例
```php
<?php
use think\facade\Route;

/**
 * api模块路由，如果不需要路由的直接忽略
 * 示例的URL为/api/user
 */
Route::group('api', function () {

    //登录接口
    Route::post('auth/login','api/Auth/login');

    //用户操作示例
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

```

## 其他说明
本项目采用大量的开源代码，包括ThinkPHP，AdminLTE等等。
部分代码可能署名已被某些前辈去掉，我也没来得及去查找具体的作者，如果有需要修改的地方，可以与我取得联系，i#yupoxiong.com(手动替换#即可)。
在此，对所有用到的开源代码作者表示由衷的感谢。如果大家需要Laravel版本的后台管理系统，可以使用[LaravelAdmin](https://github.com/yuxingfei/LaravelAdmin)。

交流QQ群：[480018279](//shang.qq.com/wpa/qunwpa?idkey=2e8674491df685dab9f634773b72ce8ed7df033aed7cbf194cda95dd4ad45737)

:stuck_out_tongue::bear::heart: