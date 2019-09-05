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
访问`/admin`，默认超级管理员的账号密码都为`super-admin`。