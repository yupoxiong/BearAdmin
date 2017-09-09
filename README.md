 # BearAdmin
基于ThinkPHP5+AdminLTE的后台管理系统

 ## 演示地址
 * <https://bearadmin.yufuping.com/admin>
 * 帐号：demo
 * 密码：demo

 ## 开源地址
 * github:<https://github.com/yupoxiong/BearAdmin>
 * 码云:<https://git.oschina.net/yupoxiong/BearAdmin>

 ## 项目安装
 #### 第一步
 安装后台代码
 ```
 git clone https://github.com/yupoxiong/BearAdmin
 ```
 或
```
 git clone https://git.oschina.net/yupoxiong/BearAdmin
```
 #### 第二步
 安装TP框架与项目用到的扩展
 ```
 composer update
 ```
 ### 第三步
 导入数据
 ```
 数据库导入项目目录下的bearadmin.sql文件
 ```
 **注意本项目表数据都是使用`utf8mb4`的**

 ### 第四步
 修改**application/config.php**和**application/database.php**两个配置文件

 ### 第五步
 访问 <em>/admin</em> 登录后台
 帐号：admin 密码：admin

 ## 功能简介

 #### 基本功能
 * 后台用户管理
 * 后台角色管理
 * 基于菜单的权限管理
 * 后台用户操作日志管理
 * 系统错误日志管理
 * 数据表管理
 * 数据库备份管理

 #### 其他功能
 * Ueditor
 * 阿里大于
 * 二维码生成
 * 第三方登录（QQ已经可测试）
 * 文件上传下载
 * Excel导入导出
 * 邮件发送(支持附件)
 * 还有许多待添加然而并没有来得及添加的功能

 #### Api相关
对于刚开始做api开发的同学，可能有些这方面的困惑，恰好本人也做过几个api开发的案例，
在此开源给大家自己做过的一些代码和方法，技术实现上可能并没有大神们处理的好，所以仅供
小白们参考一下。
具体代码在<kbd>application\api\controller</kbd>目录下,
包括基于JWT的登录认证，友好的数据返回格式等等，更多可直接阅读源码。

放一个demo地址：<https://bearadmin.yufuping.com/api/demo>

 #### 其他说明
本项目采用大量的开源代码，包括thinkphp，adminlte等等。
部分代码可能署名已被某些前辈去掉，我也没来得及去查找具体的作者，如果有需要修改的地方，可以与我取得联系，i#yufuping.com(手动替换#即可)。
在此，对所有用到的开源代码作者表示感谢。

交流QQ群：480018279
