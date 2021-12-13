# BearAdmin
基于ThinkPHP6.0+AdminLTE3.1的后台管理系统。TP5.1版本[点击这里](https://github.com/yupoxiong/BearAdmin/tree/thinkphp5.1), TP5.0版本[点击这里](https://github.com/yupoxiong/BearAdmin/tree/thinkphp5.0)


 [开发文档](https://www.kancloud.cn/codebear/admin_tp6) |
  [在线DEMO](https://demo.bearadmin.com/) |  [DEMO源码](https://github.com/yupoxiong/bearadmin-demo) 

## 安装步骤
### clone 项目到本地
- github地址
```
git clone https://github.com/yupoxiong/BearAdmin.git
```
- 码云地址
```
git clone https://gitee.com/yupoxiong/BearAdmin.git
```
### 安装项目依赖
在项目根目录运行扩展安装命令
```
composer install
```
### 创建数据库
使用navicat工具或命令创建数据库，注意编码必须为`utf8mb4`格式，例如：
~~~sql
create database `数据库名` default character set utf8mb4 collate utf8mb4_unicode_ci;
~~~
### 复制环境变量文件
复制`.example.env`文件为`.env`

### 配置数据库
复制`.example.env`文件为`.env`，更改 `.env` 文件内的数据库配置选项，参考如下：
```ini
[DATABASE]
TYPE = mysql
HOSTNAME = 127.0.0.1
DATABASE = 数据库名称
USERNAME = 数据库用户名
PASSWORD = 数据库密码
HOSTPORT = 3306
CHARSET = utf8mb4
DEBUG = false
```
### 运行数据库迁移命令
```shell
php think migrate:run
``` 
**注意事项**

运行迁移命令的时候会生成2个用户，开发管理员（`develop_admin`），超级管理（`super_admin`），为了防止部分开发者安全意识薄弱，上线后不修改默认超级管理员账号密码，导致后台被入侵，所以当前版本后台密码会随机生成，在运行迁移命令的时候命令行中会显示生成的密码，请自行复制使用。

### 配置Web根目录URL重写
将`public`目录配置为web根目录，然后配置URL重写规则，具体可参考 [ThinkPHP6.0完全开发手册](https://www.kancloud.cn/manual/thinkphp6_0/1037488) URL访问模块

### 访问后台
访问`/admin`，默认开发管理员的账号为`develop_admin`，超级管理员的账号为`super_admin`，对应密码查看迁移命令行输出内容。


## 其他说明
本项目采用大量的开源代码，包括ThinkPHP，AdminLTE等等。
部分代码可能署名已被某些前辈去掉，我也没来得及去查找具体的作者，如果有需要修改的地方，可以与我取得联系，i#yupoxiong.com(手动替换#即可)。
在此，对所有用到的开源代码作者表示由衷的感谢。如果大家需要Laravel版本的后台管理系统，可以使用 [LaravelAdmin](https://github.com/yuxingfei/LaravelAdmin) 。

交流QQ群：[480018279](//shang.qq.com/wpa/qunwpa?idkey=2e8674491df685dab9f634773b72ce8ed7df033aed7cbf194cda95dd4ad45737)

:stuck_out_tongue::bear::heart: