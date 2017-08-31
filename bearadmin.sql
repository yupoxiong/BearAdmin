/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : bearadmin

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2017-08-31 15:54:52
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for bear_admin_files
-- ----------------------------
DROP TABLE IF EXISTS `bear_admin_files`;
CREATE TABLE `bear_admin_files` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上传用户id',
  `original_name` varchar(255) NOT NULL,
  `save_name` varchar(255) NOT NULL,
  `save_path` varchar(255) NOT NULL,
  `extension` varchar(255) NOT NULL,
  `mime` varchar(255) NOT NULL,
  `size` int(11) unsigned NOT NULL DEFAULT '0',
  `md5` char(32) NOT NULL,
  `sha1` char(40) NOT NULL,
  `url` varchar(255) NOT NULL,
  `is_open` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否公开，默认为0不公开只能自己看，1为公开',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `update_time` int(11) unsigned NOT NULL,
  `delete_time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of bear_admin_files
-- ----------------------------
INSERT INTO `bear_admin_files` VALUES ('1', '0', 'startup.png', 'd24cb5b0018c34f668341590247f7f98.png', 'D:\\php\\website\\demi\\public\\uploads\\admin\\admin_file\\1\\20170601\\d24cb5b0018c34f668341590247f7f98.png', 'png', 'image/png', '107980', '4c4264c07a5ff0f5255397d76d5f5676', 'de4d2bf7b036003a21a15d47369ee223cbd646a1', '\\uploads\\admin\\admin_file\\1\\20170601\\d24cb5b0018c34f668341590247f7f98.png', '0', '1496332197', '1503622815', '1503622815');
INSERT INTO `bear_admin_files` VALUES ('2', '0', 'bear_logo.jpg', '754210e38594a8a7475af8ab7d9fb2d7.jpg', 'D:\\php\\website\\demi\\public\\uploads\\admin\\admin_file\\1\\20170602\\754210e38594a8a7475af8ab7d9fb2d7.jpg', 'jpg', 'image/jpeg', '168531', '01b139c08ad1a16fac06971eb8893a7b', 'e0459dc6ba54fd3b731401fd7b9098d4c856e0dc', '\\uploads\\admin\\admin_file\\1\\20170602\\754210e38594a8a7475af8ab7d9fb2d7.jpg', '0', '1496333211', '1503622820', '1503622820');
INSERT INTO `bear_admin_files` VALUES ('3', '0', '略略略.png', '7f6b83b858ba90bc6ee7ae4489ecf7ab.png', 'D:\\php\\website\\demi\\public\\uploads\\admin\\admin_file\\1\\20170602\\7f6b83b858ba90bc6ee7ae4489ecf7ab.png', 'png', 'image/png', '297582', 'b908a1c3a5e291b4e05532ca0de64e22', '8aabb398fa5d5fb50a1aa1ad5ffd3aaf54eff84e', '\\uploads\\admin\\admin_file\\1\\20170602\\7f6b83b858ba90bc6ee7ae4489ecf7ab.png', '0', '1496364034', '1503622824', '1503622824');
INSERT INTO `bear_admin_files` VALUES ('4', '0', '略略略.png', '94d2eb63adb8f7a1f059ce28932aaa7c.png', 'D:\\php\\website\\demi\\public\\uploads\\admin\\admin_file\\1\\20170602\\94d2eb63adb8f7a1f059ce28932aaa7c.png', 'png', 'image/png', '297582', 'b908a1c3a5e291b4e05532ca0de64e22', '8aabb398fa5d5fb50a1aa1ad5ffd3aaf54eff84e', '\\uploads\\admin\\admin_file\\1\\20170602\\94d2eb63adb8f7a1f059ce28932aaa7c.png', '0', '1496364056', '1503627677', '1503627677');
INSERT INTO `bear_admin_files` VALUES ('5', '0', '2017.5.18.7z', '32e8a4365d61a64288fdd834ebb9cb4c.7z', 'D:\\php\\website\\demi\\public\\uploads\\admin\\admin_file\\1\\20170602\\32e8a4365d61a64288fdd834ebb9cb4c.7z', '7z', 'application/octet-stream', '25249', 'c309e5d43ccbec7c92a9d9065646d20f', '7853628c50c180c9b8913a8f1c5933b227e4a5b5', '\\uploads\\admin\\admin_file\\1\\20170602\\32e8a4365d61a64288fdd834ebb9cb4c.7z', '0', '1496365008', '1496365255', '1496365255');
INSERT INTO `bear_admin_files` VALUES ('6', '0', 'B-JUI.1.31 (1).zip', '3e9d71b7d25caa08713985b2699f0330.zip', 'D:\\php\\website\\demi\\public\\uploads\\admin\\admin_file\\1\\20170602\\3e9d71b7d25caa08713985b2699f0330.zip', 'zip', 'application/octet-stream', '3850264', '6ab4032d971234cf36e4f8fdbe3e04c2', 'aeccb8fc930cec2e1a04f6949d47f39c73c4ea09', '\\uploads\\admin\\admin_file\\1\\20170602\\3e9d71b7d25caa08713985b2699f0330.zip', '0', '1496373412', '1496374268', '1496374268');
INSERT INTO `bear_admin_files` VALUES ('7', '0', 'yupoxiong_gmail_com149544710417286591.jpeg', '3d7aae7d3a5d6c774c1fdd37e524eb45.jpeg', '/www/wwwroot/bearadmin.aiqingxiaoji.com/public/uploads/admin/admin_file/1/20170602/3d7aae7d3a5d6c774c1fdd37e524eb45.jpeg', 'jpeg', 'image/jpeg', '30636', '26a912571dfe704440158398aa9a2e9c', '1d246f3678d6b5e338f7d5ece5f1537360003414', '/uploads/admin/admin_file/1/20170602/3d7aae7d3a5d6c774c1fdd37e524eb45.jpeg', '0', '1496374180', '1503627677', '1503627677');
INSERT INTO `bear_admin_files` VALUES ('8', '0', '直营店铺后台.png', 'eb52343fd6cbe5792e65953a7bc9c5e7.png', 'D:\\php\\website\\demi\\public\\uploads\\admin\\admin_file\\1\\20170604\\eb52343fd6cbe5792e65953a7bc9c5e7.png', 'png', 'image/png', '101069', 'be1e1ed6fb33321ee4732050724cff7f', 'a1d70f2fcf9f989172e22b21e41a5ada93c70283', '\\uploads\\admin\\admin_file\\1\\20170604\\eb52343fd6cbe5792e65953a7bc9c5e7.png', '0', '1496588182', '1503627677', '1503627677');
INSERT INTO `bear_admin_files` VALUES ('9', '0', '直营店铺后台.png', 'ff2df01292bfc88b257216b02175e3ad.png', 'D:\\php\\website\\demi\\public\\uploads\\admin\\admin_file\\1\\20170604\\ff2df01292bfc88b257216b02175e3ad.png', 'png', 'image/png', '101069', 'be1e1ed6fb33321ee4732050724cff7f', 'a1d70f2fcf9f989172e22b21e41a5ada93c70283', '\\uploads\\admin\\admin_file\\1\\20170604\\ff2df01292bfc88b257216b02175e3ad.png', '0', '1496588427', '1503627677', '1503627677');
INSERT INTO `bear_admin_files` VALUES ('10', '0', 'web.png', 'ba8c0cd2b49077e437dc5768413d5a33.png', '/www/wwwroot/bearadmin.aiqingxiaoji.com/public/uploads/admin/admin_file/24/20170610/ba8c0cd2b49077e437dc5768413d5a33.png', 'png', 'image/png', '10587', 'f9b42310d23fa0ac383937f066c64ece', '462ae0819c26cb4c7853ee58aa3031640f7d408e', '/uploads/admin/admin_file/24/20170610/ba8c0cd2b49077e437dc5768413d5a33.png', '0', '1497073252', '1503627677', '1503627677');
INSERT INTO `bear_admin_files` VALUES ('11', '0', '130815234706081888.jpg', '727aa7f732f06f46411cbb345d1099c7.jpg', '/www/wwwroot/bearadmin.yufuping.com/public/uploads/admin/admin_file/24/20170824/727aa7f732f06f46411cbb345d1099c7.jpg', 'jpg', 'image/jpeg', '10879', '953ff7c7392eabcd5c271c7a03d885ce', '7c1b73d2d62d0dc1f5e56fd85118e8f85173cdbf', '/uploads/admin/admin_file/24/20170824/727aa7f732f06f46411cbb345d1099c7.jpg', '0', '1503556040', '1503627677', '1503627677');
INSERT INTO `bear_admin_files` VALUES ('12', '0', 'drunk.jpg', '1ac9d56ab25381377b5c89d6307db92a.jpg', 'D:\\php\\website\\BearAdmin\\public\\uploads\\admin\\admin_file\\1\\20170825\\1ac9d56ab25381377b5c89d6307db92a.jpg', 'jpg', 'image/jpeg', '5012', '06da5f2db144446ea3218272a44c0f83', 'de16e2f7aa2e7f0bde459aee390beac8b517e4dc', '\\uploads\\admin\\admin_file\\1\\20170825\\1ac9d56ab25381377b5c89d6307db92a.jpg', '0', '1503622600', '1503627515', '1503627515');
INSERT INTO `bear_admin_files` VALUES ('13', '0', 'drunk.jpg', '8eed15e7564618f928d8d06fc219e13d.jpg', 'D:\\php\\website\\BearAdmin\\public\\uploads\\admin\\admin_file\\1\\20170825\\8eed15e7564618f928d8d06fc219e13d.jpg', 'jpg', 'image/jpeg', '5012', '06da5f2db144446ea3218272a44c0f83', 'de16e2f7aa2e7f0bde459aee390beac8b517e4dc', '\\uploads\\admin\\admin_file\\1\\20170825\\8eed15e7564618f928d8d06fc219e13d.jpg', '0', '1503622767', '1503627515', '1503627515');
INSERT INTO `bear_admin_files` VALUES ('14', '0', 'drunk.jpg', 'e6541955cd6de19fcef6b549fdc3a65b.jpg', '/www/wwwroot/bearadmin.yufuping.com/public/uploads/admin/admin_file/1/20170825/e6541955cd6de19fcef6b549fdc3a65b.jpg', 'jpg', 'image/jpeg', '5012', '06da5f2db144446ea3218272a44c0f83', 'de16e2f7aa2e7f0bde459aee390beac8b517e4dc', '/uploads/admin/admin_file/1/20170825/e6541955cd6de19fcef6b549fdc3a65b.jpg', '0', '1503627709', '1503627709', null);
INSERT INTO `bear_admin_files` VALUES ('15', '0', '2.jpg', '78ec2727b340338e7045f427f51d33c3.jpg', '/www/wwwroot/bearadmin.yufuping.com/public/uploads/admin/admin_file/24/20170825/78ec2727b340338e7045f427f51d33c3.jpg', 'jpg', 'image/jpeg', '19610', 'a7f5557f5000af32a04c649eb890bbce', '51ab04d5bb0668773f09e759d58e14e855320296', '/uploads/admin/admin_file/24/20170825/78ec2727b340338e7045f427f51d33c3.jpg', '0', '1503633430', '1503633430', null);
INSERT INTO `bear_admin_files` VALUES ('16', '0', '00000117_1502777287.png', '59ee56a7b8ef1e72e04ae13a01b44db4.png', '/www/wwwroot/bearadmin.yufuping.com/public/uploads/admin/admin_file/24/20170826/59ee56a7b8ef1e72e04ae13a01b44db4.png', 'png', 'image/png', '266', 'cbd4bc7aee57c26df688f207d3b69578', 'd172d2c3758d073fbc7ae71821c7b2273ab9063d', '/uploads/admin/admin_file/24/20170826/59ee56a7b8ef1e72e04ae13a01b44db4.png', '0', '1503731825', '1503731825', null);

-- ----------------------------
-- Table structure for bear_admin_logs
-- ----------------------------
DROP TABLE IF EXISTS `bear_admin_logs`;
CREATE TABLE `bear_admin_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL COMMENT '用户id',
  `resource_id` int(10) NOT NULL DEFAULT '0' COMMENT '资源id，如果是0证明是添加？',
  `title` varchar(100) NOT NULL,
  `log_type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1get，2post，3put，4deldet',
  `log_url` varchar(255) NOT NULL COMMENT '访问url',
  `log_ip` bigint(15) NOT NULL COMMENT '操作ip',
  `create_time` int(11) unsigned NOT NULL COMMENT '操作时间',
  `delete_time` int(10) unsigned DEFAULT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '默认状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2285 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bear_admin_logs
-- ----------------------------

-- ----------------------------
-- Table structure for bear_admin_logs_datas
-- ----------------------------
DROP TABLE IF EXISTS `bear_admin_logs_datas`;
CREATE TABLE `bear_admin_logs_datas` (
  `data_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_id` int(10) unsigned NOT NULL,
  `data` longtext NOT NULL,
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `delete_time` int(10) unsigned DEFAULT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`data_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2285 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of bear_admin_logs_datas
-- ----------------------------

-- ----------------------------
-- Table structure for bear_admin_mail_logs
-- ----------------------------
DROP TABLE IF EXISTS `bear_admin_mail_logs`;
CREATE TABLE `bear_admin_mail_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL COMMENT '用户id',
  `address` varchar(255) NOT NULL DEFAULT '0' COMMENT '资源id，如果是0证明是添加？',
  `subject` varchar(255) NOT NULL,
  `content` text NOT NULL COMMENT '1get，2post，3put，4deldet',
  `attachment_name` varchar(255) NOT NULL DEFAULT '' COMMENT '附件名称',
  `attachment_path` varchar(255) NOT NULL DEFAULT '' COMMENT '附件地址',
  `attachment_url` varchar(255) NOT NULL DEFAULT '' COMMENT '附件url',
  `is_success` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否成功',
  `error_message` varchar(255) NOT NULL DEFAULT '',
  `create_time` int(11) unsigned NOT NULL COMMENT '操作时间',
  `delete_time` int(10) unsigned DEFAULT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '默认状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bear_admin_mail_logs
-- ----------------------------
INSERT INTO `bear_admin_mail_logs` VALUES ('1', '1', '8553151@qq.com', '测试主题111', '<p>这次发附件试试撒</p><p>嘻嘻</p>', 'lueluelue.png', 'D:\\php\\website\\demi\\public\\uploads\\admin\\email_file\\1\\20170605\\7029fa2c5736662de3f0d332a1ae611b.png', '\\uploads\\admin\\email_file\\1\\20170605\\7029fa2c5736662de3f0d332a1ae611b.png', '1', '', '1496649705', null, '1');
INSERT INTO `bear_admin_mail_logs` VALUES ('2', '1', '8553151@qq.com,yupoxiong@gmail.com', '测试发送到多个邮箱', '<p>测试正文</p>', '', '', '', '1', '', '1496651117', null, '1');
INSERT INTO `bear_admin_mail_logs` VALUES ('3', '1', '1113926746@qq.com', '王兴成你好啊', '<p>这是测试邮件，收到请勿回复</p>', '', '', '', '1', '', '1501062750', null, '1');

-- ----------------------------
-- Table structure for bear_admin_menus
-- ----------------------------
DROP TABLE IF EXISTS `bear_admin_menus`;
CREATE TABLE `bear_admin_menus` (
  `menu_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '菜单id',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父级id',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  `title` varchar(50) NOT NULL COMMENT '菜单名称',
  `url` varchar(100) NOT NULL COMMENT '模块/控制器/方法',
  `param` varchar(100) NOT NULL DEFAULT '',
  `icon` varchar(50) NOT NULL DEFAULT 'fa-circle-o' COMMENT '菜单图标',
  `log_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0不记录日志，1get，2post，3put，4delete，先这些啦',
  `sort_id` smallint(5) unsigned NOT NULL DEFAULT '100' COMMENT '排序id',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态：1默认正常，2禁用',
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bear_admin_menus
-- ----------------------------
INSERT INTO `bear_admin_menus` VALUES ('1', '0', '1', '后台首页', 'admin/index/index', '', 'fa-home', '0', '1', '0', '1489371526', '1');
INSERT INTO `bear_admin_menus` VALUES ('2', '34', '1', '系统设置', 'admin/sysconfig/index', '', 'fa-cogs', '0', '998', '0', '1502187600', '1');
INSERT INTO `bear_admin_menus` VALUES ('3', '10', '0', '添加角色', 'admin/role/add', '', 'fa-plus', '2', '100', '0', '1501157348', '1');
INSERT INTO `bear_admin_menus` VALUES ('4', '10', '0', '删除角色', 'admin/role/del', '', 'fa-close', '1', '100', '0', '1502344725', '1');
INSERT INTO `bear_admin_menus` VALUES ('5', '10', '0', '修改角色', 'admin/role/edit', '', 'fa-edit', '2', '100', '0', '1495007134', '1');
INSERT INTO `bear_admin_menus` VALUES ('6', '2', '0', '添加设置', 'admin/sysconfig/add', '', 'fa-plus', '2', '100', '0', '1502344270', '1');
INSERT INTO `bear_admin_menus` VALUES ('7', '11', '0', '添加菜单', 'admin/admin_menu/add', '', 'fa-plus', '2', '100', '0', '1501157447', '1');
INSERT INTO `bear_admin_menus` VALUES ('8', '11', '0', '删除菜单', 'admin/admin_menu/del', '', 'fa-close', '1', '100', '0', '1502344737', '1');
INSERT INTO `bear_admin_menus` VALUES ('9', '11', '0', '修改菜单', 'admin/admin_menu/edit', '', 'fa-edit', '2', '100', '0', '1495010837', '1');
INSERT INTO `bear_admin_menus` VALUES ('10', '34', '1', '角色管理', 'admin/role/index', '', 'fa-group', '0', '100', '0', '1501157271', '1');
INSERT INTO `bear_admin_menus` VALUES ('11', '34', '1', '菜单管理', 'admin/admin_menu/index', '', 'fa-th-list', '0', '100', '0', '1501157420', '1');
INSERT INTO `bear_admin_menus` VALUES ('12', '10', '0', '授权管理', 'admin/role/access', '', 'fa-key', '2', '100', '0', '1495010813', '1');
INSERT INTO `bear_admin_menus` VALUES ('14', '34', '1', '用户管理', 'admin/admin_user/index', '', 'fa-user-secret', '0', '99', '0', '1501157040', '1');
INSERT INTO `bear_admin_menus` VALUES ('15', '2', '0', '编辑设置', 'admin/sysconfig/edit', '', 'fa-edit', '2', '100', '0', '1502344279', '1');
INSERT INTO `bear_admin_menus` VALUES ('16', '14', '0', '添加用户', 'admin/admin_user/add', '', 'fa-plus', '2', '100', '0', '1501157217', '1');
INSERT INTO `bear_admin_menus` VALUES ('17', '34', '1', '文件管理', 'admin/admin_file/index', '', 'fa-file-o', '0', '101', '0', '1501157504', '1');
INSERT INTO `bear_admin_menus` VALUES ('18', '2', '0', '删除设置', 'admin/sysconfig/del', '', 'fa-close', '1', '100', '0', '1502344288', '1');
INSERT INTO `bear_admin_menus` VALUES ('19', '17', '0', '上传文件', 'admin/admin_file/upload', '', 'fa-upload', '2', '100', '0', '1496373547', '1');
INSERT INTO `bear_admin_menus` VALUES ('20', '34', '1', '扩展功能', 'admin/tools', '', 'fa-plus-circle', '0', '102', '0', '1496371967', '1');
INSERT INTO `bear_admin_menus` VALUES ('21', '20', '1', '支付', 'admin/pay/index', '', 'fa-credit-card', '0', '51', '0', '1496800099', '1');
INSERT INTO `bear_admin_menus` VALUES ('22', '21', '1', '微信支付', 'admin/weixinpay/index', '', 'fa-wechat', '0', '100', '0', '1496802274', '1');
INSERT INTO `bear_admin_menus` VALUES ('23', '20', '1', 'Ueditor', 'admin/ueditor/index', '', 'fa-edit', '2', '100', '0', '1496652277', '1');
INSERT INTO `bear_admin_menus` VALUES ('24', '21', '1', '支付宝支付', 'admin/alipay/index', '', 'fa-rmb', '0', '100', '0', '1496802516', '1');
INSERT INTO `bear_admin_menus` VALUES ('25', '20', '1', '第三方登录', 'admin/third_login/index', '', 'fa-exchange', '1', '55', '0', '1503847993', '1');
INSERT INTO `bear_admin_menus` VALUES ('26', '25', '0', 'QQ登录', 'admin/third_login/qq', '', 'fa-qq', '0', '100', '0', '1503848342', '1');
INSERT INTO `bear_admin_menus` VALUES ('27', '20', '1', 'Excel导入导出', 'admin/excel/index', '', 'fa-close', '2', '110', '0', '1496746818', '1');
INSERT INTO `bear_admin_menus` VALUES ('28', '14', '0', '修改用户', 'admin/admin_user/edit', '', 'fa-edit', '2', '100', '0', '1495006610', '1');
INSERT INTO `bear_admin_menus` VALUES ('29', '14', '0', '删除用户', 'admin/admin_user/del', '', 'fa-close', '1', '100', '0', '1502344303', '1');
INSERT INTO `bear_admin_menus` VALUES ('30', '20', '1', '发送邮件', 'admin/admin_mail/index', '', 'fa-envelope', '2', '100', '0', '1496651424', '1');
INSERT INTO `bear_admin_menus` VALUES ('31', '20', '1', '二维码生成', 'admin/admin_qrcode/index', '', 'fa-qrcode', '2', '100', '0', '1496651897', '1');
INSERT INTO `bear_admin_menus` VALUES ('32', '20', '1', '阿里大于', 'admin/alidayu/index', '', 'fa-comment', '2', '100', '1489335056', '1496652347', '1');
INSERT INTO `bear_admin_menus` VALUES ('33', '20', '0', '七牛云存储', 'admin/qiniucloud/index', '', 'fa-cloud', '0', '100', '1489335136', '1499157737', '1');
INSERT INTO `bear_admin_menus` VALUES ('34', '0', '1', '系统管理', 'admin/sys', '', 'fa-gear', '0', '100', '1489335249', '1496385260', '1');
INSERT INTO `bear_admin_menus` VALUES ('35', '37', '1', '操作日志', 'admin/dolog/index', '', 'fa-keyboard-o', '0', '100', '1489335334', '1497584062', '1');
INSERT INTO `bear_admin_menus` VALUES ('36', '34', '1', '个人资料', 'admin/admin_user/profile', '', 'fa-edit', '0', '110', '1489335383', '1496371996', '1');
INSERT INTO `bear_admin_menus` VALUES ('37', '34', '1', '日志管理', 'admin/logs', '', 'fa-info', '0', '100', '1489394592', '1494931863', '1');
INSERT INTO `bear_admin_menus` VALUES ('38', '0', '1', '统计管理', 'admin/statistics/default', '', 'fa-bar-chart', '0', '55', '1490002219', '1490021667', '1');
INSERT INTO `bear_admin_menus` VALUES ('39', '38', '1', '统计概览', 'admin/statistics/index', '', 'fa-circle-o', '0', '100', '1490021568', '1490021568', '1');
INSERT INTO `bear_admin_menus` VALUES ('48', '20', '0', '阿里云oss', 'admin/alioss', '', 'fa-list', '0', '100', '1494496312', '1499157398', '1');
INSERT INTO `bear_admin_menus` VALUES ('49', '25', '0', '微博登录', 'admin/weibologin/index', '', 'fa-list', '0', '100', '1494496555', '1503848351', '1');
INSERT INTO `bear_admin_menus` VALUES ('50', '37', '1', '系统日志', 'admin/syslog/index', '', 'fa-info-circle', '0', '100', '1494498392', '1497584191', '1');
INSERT INTO `bear_admin_menus` VALUES ('51', '25', '0', 'github登录', 'admin/thirdlogin/github', '', 'fa-pie-chart', '0', '100', '1494498424', '1499157789', '1');
INSERT INTO `bear_admin_menus` VALUES ('57', '35', '0', '查看日志', 'admin/dologs/view', '', 'fa-search-plus', '0', '100', '1495382629', '1495552300', '1');
INSERT INTO `bear_admin_menus` VALUES ('58', '17', '0', '文件下载', 'admin/admin_file/download', '', 'fa-download', '1', '100', '1495536279', '1497262778', '1');
INSERT INTO `bear_admin_menus` VALUES ('59', '34', '0', '后台说明', 'admin/sys/about', '', 'fa-hand-o-right', '0', '123', '1496885512', '1496903189', '1');
INSERT INTO `bear_admin_menus` VALUES ('60', '34', '1', '数据维护', 'admin/databack/index', '', 'fa-database', '0', '100', '1502788380', '1503018106', '1');
INSERT INTO `bear_admin_menus` VALUES ('61', '60', '0', '添加备份', 'admin/databack/add', '', 'fa-plus', '0', '100', '1502789144', '1502789144', '1');
INSERT INTO `bear_admin_menus` VALUES ('62', '60', '0', '还原备份', 'admin/databack/reduction', '', 'fa-circle-o', '0', '100', '1502789201', '1502789201', '1');
INSERT INTO `bear_admin_menus` VALUES ('63', '60', '0', '删除备份', 'admin/databack/del', '', 'fa-close', '1', '100', '1502789239', '1502789239', '1');
INSERT INTO `bear_admin_menus` VALUES ('64', '0', '1', '用户测试', 'admin/user/index', '', 'fa-circle-o', '0', '100', '1502864020', '1502864020', '1');
INSERT INTO `bear_admin_menus` VALUES ('65', '64', '0', '添加用户', 'admin/user/add', '', 'fa-circle-o', '0', '100', '1502864686', '1502864702', '1');
INSERT INTO `bear_admin_menus` VALUES ('66', '64', '0', '编辑用户', 'admin/user/edit', '', 'fa-circle-o', '0', '100', '1502864733', '1502864733', '1');
INSERT INTO `bear_admin_menus` VALUES ('67', '64', '0', '删除用户', 'admin/user/del', '', 'fa-circle-o', '0', '100', '1502864755', '1502864755', '1');
INSERT INTO `bear_admin_menus` VALUES ('68', '0', '1', '啊啊粉嘟嘟', '/admin/test/test', '', 'fa-circle-o', '0', '100', '1503535735', '1503994083', '1');
INSERT INTO `bear_admin_menus` VALUES ('70', '0', '1', 'asdasdasd', '/vasdad', '', 'fa-circle-o', '0', '100', '1503556341', '1503556341', '1');
INSERT INTO `bear_admin_menus` VALUES ('72', '0', '1', '这个菜单', '/', '', 'fa-circle-o', '0', '100', '1503993668', '1503993668', '1');

-- ----------------------------
-- Table structure for bear_admin_users
-- ----------------------------
DROP TABLE IF EXISTS `bear_admin_users`;
CREATE TABLE `bear_admin_users` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `user_name` varchar(50) NOT NULL COMMENT '用户名',
  `password` char(32) NOT NULL COMMENT '密码',
  `nick_name` varchar(30) DEFAULT NULL COMMENT '用户昵称或中文用户名',
  `avatar` varchar(255) DEFAULT 'avatar.png' COMMENT '用户头像',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int(10) unsigned DEFAULT NULL COMMENT '是否被删除',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '用户状态1启用，2禁用',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bear_admin_users
-- ----------------------------
INSERT INTO `bear_admin_users` VALUES ('1', 'admin', '21232f297a57a5a743894a0e4a801fc3', '超级管理员', '1/20170524/aa579d638a236fd9ac06ff419ca88cb1.jpg', '1488189586', '1504165739', null, '1');
INSERT INTO `bear_admin_users` VALUES ('2', 'admin', '21232f297a57a5a743894a0e4a801fc3', '管理员2', 'avatar.png', '1488189586', '1502342521', '1502342521', '1');
INSERT INTO `bear_admin_users` VALUES ('3', 'admin2', 'e10adc3949ba59abbe56e057f20f883e', '管理员2', 'avatar.png', '1488189586', '1488246666', '1495183263', '1');
INSERT INTO `bear_admin_users` VALUES ('11', 'admin55', 'd41d8cd98f00b204e9800998ecf8427e', '用户姓名测试', 'avatar.png', '1493955256', '1495183263', '1495183263', '1');
INSERT INTO `bear_admin_users` VALUES ('18', 'admin545', '5abd06d6f6ef0e022e11b8a41f57ebda', '435435', '18/20170523/69b5600769b1d4e7a97cd0d8e8962fff.png', '1495448379', '1502330153', '1502330153', '1');
INSERT INTO `bear_admin_users` VALUES ('19', 'bear', '03e0704b5690a2dee1861dc3ad3316c9', 'bear', 'avatar.png', '1495603226', '1502330153', '1502330153', '1');
INSERT INTO `bear_admin_users` VALUES ('20', 'laipixiong', 'e10adc3949ba59abbe56e057f20f883e', '赖皮熊', 'avatar.png', '1495603405', '1502330153', '1502330153', '1');
INSERT INTO `bear_admin_users` VALUES ('21', 'taoqixiong', '5abd06d6f6ef0e022e11b8a41f57ebda', '淘气熊', 'avatar.png', '1495643747', '1502330153', '1502330153', '1');
INSERT INTO `bear_admin_users` VALUES ('22', 'poxiong', 'd41d8cd98f00b204e9800998ecf8427e', '破熊', 'avatar.png', '1495688185', '1502330153', '1502330153', '1');
INSERT INTO `bear_admin_users` VALUES ('23', 'qqq111', 'd41d8cd98f00b204e9800998ecf8427e', 'qqq11', 'avatar.png', '1495716820', '1502330153', '1502330153', '1');
INSERT INTO `bear_admin_users` VALUES ('24', 'demo', 'fe01ce2a7fbac8fafaed7c982a04e229', 'Demo1', '24/20170831/ff2fbeccbab4661ba1ebfb47e33d2584.jpg', '1496904301', '1504164854', null, '1');
INSERT INTO `bear_admin_users` VALUES ('28', 'jiejie', '5abd06d6f6ef0e022e11b8a41f57ebda', '姐姐', 'avatar.png', '1498196749', '1502330153', '1502330153', '1');
INSERT INTO `bear_admin_users` VALUES ('29', 'test', '098f6bcd4621d373cade4e832627b4f6', '测试用户', 'avatar.png', '1502337568', '1502342171', '1502342171', '1');
INSERT INTO `bear_admin_users` VALUES ('30', 'root', '63a9f0ea7bb98050796b649e85481845', 'cozy', 'avatar.png', '1502342476', '1502342496', '1502342496', '1');
INSERT INTO `bear_admin_users` VALUES ('31', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin', 'avatar.png', '1502347919', '1503387642', '1503387642', '1');
INSERT INTO `bear_admin_users` VALUES ('32', 'maoniu', 'e10adc3949ba59abbe56e057f20f883e', 'sdf', '20170822/2994d238948d39ee4b80ba361180e740.png', '1503387857', '1503534881', '1503534881', '1');
INSERT INTO `bear_admin_users` VALUES ('33', 'ceshi', '5abd06d6f6ef0e022e11b8a41f57ebda', '测试用户', 'avatar.png', '1503392899', '1503394223', '1503394223', '1');
INSERT INTO `bear_admin_users` VALUES ('34', '1111', 'b59c67bf196a4758191e42f76670ceba', '1111', 'avatar.png', '1503394314', '1503534875', '1503534875', '1');
INSERT INTO `bear_admin_users` VALUES ('35', 'demo13', 'fe01ce2a7fbac8fafaed7c982a04e229', 'qweqwe', 'avatar.png', '1503544350', '1503579033', '1503579033', '1');
INSERT INTO `bear_admin_users` VALUES ('36', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin', 'avatar.png', '1503837089', '1503841748', '1503841748', '1');
INSERT INTO `bear_admin_users` VALUES ('37', '1556', 'e10adc3949ba59abbe56e057f20f883e', '123456', 'avatar.png', '1503910453', '1503910453', null, '1');
INSERT INTO `bear_admin_users` VALUES ('38', 'demo1', 'e368b9938746fa090d6afd3628355133', 'demo1', '3820170831/2b79a74e91bb0eaf281d116eebbb4ed1.jpg', '1504032913', '1504160587', null, '1');

-- ----------------------------
-- Table structure for bear_admin_user_profiles
-- ----------------------------
DROP TABLE IF EXISTS `bear_admin_user_profiles`;
CREATE TABLE `bear_admin_user_profiles` (
  `profile_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL COMMENT '用户id',
  `mobile` char(11) NOT NULL DEFAULT '' COMMENT '用户手机',
  `email` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '默认1，男',
  `qq` varchar(11) NOT NULL DEFAULT '',
  `wechat` varchar(50) NOT NULL DEFAULT '',
  `weibo` varchar(100) NOT NULL DEFAULT '',
  `zhihu` varchar(100) NOT NULL DEFAULT '',
  `alipay` varchar(100) NOT NULL DEFAULT '',
  `education` varchar(100) NOT NULL DEFAULT '',
  `city` varchar(100) NOT NULL DEFAULT '',
  `skill` varchar(255) NOT NULL DEFAULT '',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `delete_time` int(10) unsigned DEFAULT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`profile_id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of bear_admin_user_profiles
-- ----------------------------
INSERT INTO `bear_admin_user_profiles` VALUES ('1', '1', '18363083115', '8553151@qq.com', '我是超级管理员，不管你信不信。22', '1', '8553151', '8553151', 'weibo', 'zhihu', '', '', '济南', '', '0', '1503021449', null, '1');
INSERT INTO `bear_admin_user_profiles` VALUES ('3', '2', '18355552220', '49548@qq.com', '', '1', '', '', '', '', '', '', '', '', '0', '1502337712', null, '1');
INSERT INTO `bear_admin_user_profiles` VALUES ('4', '3', '', '', '', '1', '', '', '', '', '', '', '', '', '0', '0', null, '1');
INSERT INTO `bear_admin_user_profiles` VALUES ('5', '5', '', '', '', '1', '', '', '', '', '', '', '', '', '0', '0', null, '1');
INSERT INTO `bear_admin_user_profiles` VALUES ('6', '6', '', '', '', '1', '', '', '', '', '', '', '', '', '0', '0', null, '1');
INSERT INTO `bear_admin_user_profiles` VALUES ('7', '7', '', '', '', '1', '', '', '', '', '', '', '', '', '0', '0', null, '1');
INSERT INTO `bear_admin_user_profiles` VALUES ('8', '8', '', '', '', '1', '', '', '', '', '', '', '', '', '0', '0', null, '1');
INSERT INTO `bear_admin_user_profiles` VALUES ('9', '9', '', '', '', '1', '', '', '', '', '', '', '', '', '0', '0', null, '1');
INSERT INTO `bear_admin_user_profiles` VALUES ('10', '10', '', '', '', '1', '', '', '', '', '', '', '', '', '0', '0', null, '1');
INSERT INTO `bear_admin_user_profiles` VALUES ('11', '11', '', '', '', '1', '', '', '', '', '', '', '', '', '0', '0', null, '1');
INSERT INTO `bear_admin_user_profiles` VALUES ('12', '15', '', '', '', '1', '', '', '', '', '', '', '', '', '0', '1495438454', null, '1');
INSERT INTO `bear_admin_user_profiles` VALUES ('13', '16', '18363083116', '10016@qq.com', '', '1', '', '', '', '', '', '', '', '', '0', '1495442873', null, '1');
INSERT INTO `bear_admin_user_profiles` VALUES ('18', '17', '', '', '', '1', '', '', '', '', '', '', '', '', '1495448368', '1495448368', null, '1');
INSERT INTO `bear_admin_user_profiles` VALUES ('19', '18', '18500002222', '', '', '1', '', '', '', '', '', '', '', '', '1495448379', '1495529573', null, '1');
INSERT INTO `bear_admin_user_profiles` VALUES ('21', '19', '', '', '', '1', '', '', '', '', '', '', '', '', '1495603226', '1495603226', null, '1');
INSERT INTO `bear_admin_user_profiles` VALUES ('22', '20', '', '', '', '1', '', '', '', '', '', '', '', '', '1495603405', '1495603405', null, '1');
INSERT INTO `bear_admin_user_profiles` VALUES ('23', '21', '', '', '', '1', '', '', '', '', '', '', '', '', '1495643747', '1495643747', null, '1');
INSERT INTO `bear_admin_user_profiles` VALUES ('24', '22', '', '', '', '1', '', '', '', '', '', '', '', '', '1495688185', '1495688185', null, '1');
INSERT INTO `bear_admin_user_profiles` VALUES ('25', '23', '', '', '', '1', '', '', '', '', '', '', '', '', '1495716820', '1495716820', null, '1');
INSERT INTO `bear_admin_user_profiles` VALUES ('26', '24', '18822220000', '123456@qq.com', '123123qweqe', '1', '', '', '', '', '', '', '济南', '', '1496904301', '1503909423', null, '1');
INSERT INTO `bear_admin_user_profiles` VALUES ('27', '28', '', '', '', '1', '', '', '', '', '', '', '', '', '1498196749', '1498196749', null, '1');
INSERT INTO `bear_admin_user_profiles` VALUES ('28', '29', '18355552222', '8888888@qq.com', '', '1', '', '', '', '', '', '', '', '', '1502337568', '1502337568', null, '1');
INSERT INTO `bear_admin_user_profiles` VALUES ('29', '30', '12345678912', '12@qq.com', '', '1', '', '', '', '', '', '', '', '', '1502342476', '1502342476', null, '1');
INSERT INTO `bear_admin_user_profiles` VALUES ('30', '31', '12345678922', 'admin@qq.com', '', '1', '', '', '', '', '', '', '', '', '1502347919', '1502347919', null, '1');
INSERT INTO `bear_admin_user_profiles` VALUES ('31', '32', '13072766591', '190423457@qq.com', '', '1', '', '', '', '', '', '', '', '', '1503387857', '1503387857', null, '1');
INSERT INTO `bear_admin_user_profiles` VALUES ('32', '33', '', '', '', '1', '', '', '', '', '', '', '', '', '1503392899', '1503392899', null, '1');
INSERT INTO `bear_admin_user_profiles` VALUES ('33', '34', '15910394622', '123@qq.com', '', '1', '', '', '', '', '', '', '', '', '1503394314', '1503394314', null, '1');
INSERT INTO `bear_admin_user_profiles` VALUES ('34', '35', '15354451651', '321564@qq.com', '', '1', '', '', '', '', '', '', '', '', '1503544350', '1503544350', null, '1');
INSERT INTO `bear_admin_user_profiles` VALUES ('35', '36', '0', '', '', '1', '', '', '', '', '', '', '', '', '1503837089', '1503837697', null, '1');
INSERT INTO `bear_admin_user_profiles` VALUES ('36', '37', '18566986567', '145525@qq.com', '', '1', '', '', '', '', '', '', '', '', '1503910453', '1503910453', null, '1');
INSERT INTO `bear_admin_user_profiles` VALUES ('37', '38', '', '', '', '1', '', '', '', '', '', '', '', '', '1504032913', '1504032913', null, '1');

-- ----------------------------
-- Table structure for bear_auth_groups
-- ----------------------------
DROP TABLE IF EXISTS `bear_auth_groups`;
CREATE TABLE `bear_auth_groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(200) DEFAULT '' COMMENT '角色描述',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` varchar(350) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bear_auth_groups
-- ----------------------------
INSERT INTO `bear_auth_groups` VALUES ('1', '管理员', '卧草', '1', '1,2,3,4,5,6,7,8,9,10,11,15,14,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,49,50,51,52,57,58,59,60,64,65,66,67,68,70');
INSERT INTO `bear_auth_groups` VALUES ('2', '客服', '你是睡', '1', '1,2,3,4,5,6,7,8,9,10,11,15,14,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,49,50,51,52,57,58,59,60,61,62,63,64,65,66,67,68,70');
INSERT INTO `bear_auth_groups` VALUES ('3', '财务', '测试描述', '1', '2,3,4,5,6,7,8,9,10,11,15,14,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,49,50,51,52,57,58,59,60,61,62,63');
INSERT INTO `bear_auth_groups` VALUES ('13', '演示账号', '牧草', '1', '1,35,37');

-- ----------------------------
-- Table structure for bear_auth_group_access
-- ----------------------------
DROP TABLE IF EXISTS `bear_auth_group_access`;
CREATE TABLE `bear_auth_group_access` (
  `uid` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bear_auth_group_access
-- ----------------------------
INSERT INTO `bear_auth_group_access` VALUES ('1', '1');
INSERT INTO `bear_auth_group_access` VALUES ('2', '1');
INSERT INTO `bear_auth_group_access` VALUES ('4', '1');
INSERT INTO `bear_auth_group_access` VALUES ('6', '2');
INSERT INTO `bear_auth_group_access` VALUES ('8', '2');
INSERT INTO `bear_auth_group_access` VALUES ('8', '3');
INSERT INTO `bear_auth_group_access` VALUES ('9', '3');
INSERT INTO `bear_auth_group_access` VALUES ('10', '1');
INSERT INTO `bear_auth_group_access` VALUES ('10', '2');
INSERT INTO `bear_auth_group_access` VALUES ('10', '3');
INSERT INTO `bear_auth_group_access` VALUES ('11', '1');
INSERT INTO `bear_auth_group_access` VALUES ('11', '3');
INSERT INTO `bear_auth_group_access` VALUES ('15', '3');
INSERT INTO `bear_auth_group_access` VALUES ('16', '2');
INSERT INTO `bear_auth_group_access` VALUES ('17', '1');
INSERT INTO `bear_auth_group_access` VALUES ('19', '1');
INSERT INTO `bear_auth_group_access` VALUES ('20', '1');
INSERT INTO `bear_auth_group_access` VALUES ('21', '1');
INSERT INTO `bear_auth_group_access` VALUES ('22', '1');
INSERT INTO `bear_auth_group_access` VALUES ('23', '1');
INSERT INTO `bear_auth_group_access` VALUES ('24', '1');
INSERT INTO `bear_auth_group_access` VALUES ('29', '1');
INSERT INTO `bear_auth_group_access` VALUES ('30', '1');
INSERT INTO `bear_auth_group_access` VALUES ('31', '1');
INSERT INTO `bear_auth_group_access` VALUES ('31', '3');
INSERT INTO `bear_auth_group_access` VALUES ('32', '1');
INSERT INTO `bear_auth_group_access` VALUES ('35', '1');
INSERT INTO `bear_auth_group_access` VALUES ('35', '2');
INSERT INTO `bear_auth_group_access` VALUES ('35', '3');
INSERT INTO `bear_auth_group_access` VALUES ('36', '1');
INSERT INTO `bear_auth_group_access` VALUES ('37', '1');
INSERT INTO `bear_auth_group_access` VALUES ('38', '1');

-- ----------------------------
-- Table structure for bear_auth_rules
-- ----------------------------
DROP TABLE IF EXISTS `bear_auth_rules`;
CREATE TABLE `bear_auth_rules` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(80) NOT NULL DEFAULT '',
  `title` char(20) NOT NULL DEFAULT '',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `condition` char(100) NOT NULL DEFAULT '',
  `menu_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关联菜单id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=73 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bear_auth_rules
-- ----------------------------
INSERT INTO `bear_auth_rules` VALUES ('1', 'admin/index/index', '后台首页', '1', '1', '', '1');
INSERT INTO `bear_auth_rules` VALUES ('2', 'admin/sysconfig/index', '系统设置', '1', '1', '', '2');
INSERT INTO `bear_auth_rules` VALUES ('3', 'admin/role/add', '添加角色', '1', '1', '', '3');
INSERT INTO `bear_auth_rules` VALUES ('4', 'admin/role/del', '删除角色', '1', '1', '', '4');
INSERT INTO `bear_auth_rules` VALUES ('5', 'admin/role/edit', '修改角色', '1', '1', '', '5');
INSERT INTO `bear_auth_rules` VALUES ('6', 'admin/sysconfig/add', '添加设置', '1', '1', '', '6');
INSERT INTO `bear_auth_rules` VALUES ('7', 'admin/admin_menu/add', '添加菜单', '1', '1', '', '7');
INSERT INTO `bear_auth_rules` VALUES ('8', 'admin/admin_menu/del', '删除菜单', '1', '1', '', '8');
INSERT INTO `bear_auth_rules` VALUES ('9', 'admin/admin_menu/edit', '修改菜单', '1', '1', '', '9');
INSERT INTO `bear_auth_rules` VALUES ('10', 'admin/role/index', '角色管理', '1', '1', '', '10');
INSERT INTO `bear_auth_rules` VALUES ('11', 'admin/admin_menu/index', '菜单管理', '1', '1', '', '11');
INSERT INTO `bear_auth_rules` VALUES ('15', 'admin/sysconfig/edit', '编辑设置', '1', '1', '', '15');
INSERT INTO `bear_auth_rules` VALUES ('14', 'admin/admin_user/index', '用户管理', '1', '1', '', '14');
INSERT INTO `bear_auth_rules` VALUES ('17', 'admin/admin_user/add', '添加用户', '1', '1', '', '16');
INSERT INTO `bear_auth_rules` VALUES ('18', 'admin/admin_file/index', '文件管理', '1', '1', '', '17');
INSERT INTO `bear_auth_rules` VALUES ('19', 'admin/sysconfig/del', '删除设置', '1', '1', '', '18');
INSERT INTO `bear_auth_rules` VALUES ('20', 'admin/admin_file/upload', '上传文件', '1', '1', '', '19');
INSERT INTO `bear_auth_rules` VALUES ('21', 'admin/tools', '扩展功能', '1', '1', '', '20');
INSERT INTO `bear_auth_rules` VALUES ('22', 'admin/pay/index', '支付', '1', '1', '', '21');
INSERT INTO `bear_auth_rules` VALUES ('23', 'admin/weixinpay/index', '微信支付', '1', '1', '', '22');
INSERT INTO `bear_auth_rules` VALUES ('24', 'admin/ueditor/index', 'Ueditor', '1', '1', '', '23');
INSERT INTO `bear_auth_rules` VALUES ('25', 'admin/alipay/index', '支付宝支付', '1', '1', '', '24');
INSERT INTO `bear_auth_rules` VALUES ('26', 'admin/third_login/index', '第三方登录', '1', '1', '', '25');
INSERT INTO `bear_auth_rules` VALUES ('27', 'admin/third_login/qq', 'QQ登录', '1', '1', '', '26');
INSERT INTO `bear_auth_rules` VALUES ('28', 'admin/excel/index', 'Excel导入导出', '1', '1', '', '27');
INSERT INTO `bear_auth_rules` VALUES ('29', 'admin/admin_user/edit', '修改用户', '1', '1', '', '28');
INSERT INTO `bear_auth_rules` VALUES ('30', 'admin/admin_user/del', '删除用户', '1', '1', '', '29');
INSERT INTO `bear_auth_rules` VALUES ('31', 'admin/admin_mail/index', '发送邮件', '1', '1', '', '30');
INSERT INTO `bear_auth_rules` VALUES ('32', 'admin/admin_qrcode/index', '二维码生成', '1', '1', '', '31');
INSERT INTO `bear_auth_rules` VALUES ('33', 'admin/qiniucloud/index', '七牛云存储', '1', '1', '', '33');
INSERT INTO `bear_auth_rules` VALUES ('34', 'admin/alidayu/index', '阿里大于', '1', '1', '', '32');
INSERT INTO `bear_auth_rules` VALUES ('35', 'admin/sys', '系统管理', '1', '1', '', '34');
INSERT INTO `bear_auth_rules` VALUES ('36', 'admin/dolog/index', '操作日志', '1', '1', '', '35');
INSERT INTO `bear_auth_rules` VALUES ('37', 'admin/admin_user/profile', '个人资料', '1', '1', '', '36');
INSERT INTO `bear_auth_rules` VALUES ('38', 'admin/role/access', '授权管理', '1', '1', '', '12');
INSERT INTO `bear_auth_rules` VALUES ('39', 'admin/logs', '日志管理', '1', '1', '', '37');
INSERT INTO `bear_auth_rules` VALUES ('40', 'admin/statistics/default', '统计管理', '1', '1', '', '38');
INSERT INTO `bear_auth_rules` VALUES ('41', 'admin/statistics/index', '统计概览', '1', '1', '', '39');
INSERT INTO `bear_auth_rules` VALUES ('43', 'admin/erer/dkjfd', '测试二', '1', '1', '', '41');
INSERT INTO `bear_auth_rules` VALUES ('44', 'aldkfj/adfa/adf', '测试等i大', '1', '1', '', '42');
INSERT INTO `bear_auth_rules` VALUES ('45', 'afadfasdf', 'test222', '1', '1', '', '44');
INSERT INTO `bear_auth_rules` VALUES ('46', 'dakldfjadf', '阿德法地方', '1', '1', '', '45');
INSERT INTO `bear_auth_rules` VALUES ('47', 'adfadsfadsf', 'sfdgadf', '1', '1', '', '46');
INSERT INTO `bear_auth_rules` VALUES ('48', 'dafaf', 'adsfadf', '1', '1', '', '47');
INSERT INTO `bear_auth_rules` VALUES ('49', 'admin/alioss', '阿里云oss', '1', '1', '', '48');
INSERT INTO `bear_auth_rules` VALUES ('50', 'admin/weibologin/index', '微博登录', '1', '1', '', '49');
INSERT INTO `bear_auth_rules` VALUES ('51', 'admin/syslog/index', '系统日志', '1', '1', '', '50');
INSERT INTO `bear_auth_rules` VALUES ('52', 'admin/thirdlogin/github', 'github登录', '1', '1', '', '51');
INSERT INTO `bear_auth_rules` VALUES ('57', 'admin/dologs/view', '查看日志', '1', '1', '', '57');
INSERT INTO `bear_auth_rules` VALUES ('58', 'admin/admin_file/download', '文件下载', '1', '1', '', '58');
INSERT INTO `bear_auth_rules` VALUES ('59', 'admin/sys/about', '后台说明', '1', '1', '', '59');
INSERT INTO `bear_auth_rules` VALUES ('60', 'admin/databack/index', '数据维护', '1', '1', '', '60');
INSERT INTO `bear_auth_rules` VALUES ('61', 'admin/databack/add', '添加备份', '1', '1', '', '61');
INSERT INTO `bear_auth_rules` VALUES ('62', 'admin/databack/reduction', '还原备份', '1', '1', '', '62');
INSERT INTO `bear_auth_rules` VALUES ('63', 'admin/databack/del', '删除备份', '1', '1', '', '63');
INSERT INTO `bear_auth_rules` VALUES ('64', 'admin/user/index', '用户测试', '1', '1', '', '64');
INSERT INTO `bear_auth_rules` VALUES ('65', 'admin/user/add', '添加用户', '1', '1', '', '65');
INSERT INTO `bear_auth_rules` VALUES ('66', 'admin/user/edit', '编辑用户', '1', '1', '', '66');
INSERT INTO `bear_auth_rules` VALUES ('67', 'admin/user/del', '删除用户', '1', '1', '', '67');
INSERT INTO `bear_auth_rules` VALUES ('68', '/admin/test/test', '啊啊粉嘟嘟', '1', '1', '', '68');
INSERT INTO `bear_auth_rules` VALUES ('70', '/vasdad', 'asdasdasd', '1', '1', '', '70');
INSERT INTO `bear_auth_rules` VALUES ('72', '/', '这个菜单', '1', '1', '', '72');

-- ----------------------------
-- Table structure for bear_excel_examples
-- ----------------------------
DROP TABLE IF EXISTS `bear_excel_examples`;
CREATE TABLE `bear_excel_examples` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `age` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `sex` varchar(8) NOT NULL,
  `city` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=438 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of bear_excel_examples
-- ----------------------------
INSERT INTO `bear_excel_examples` VALUES ('1', '于破熊', '25', '男', '济南');
INSERT INTO `bear_excel_examples` VALUES ('2', '淘气熊', '23', '女', '济南');
INSERT INTO `bear_excel_examples` VALUES ('3', '张三', '18', '男', '上海');
INSERT INTO `bear_excel_examples` VALUES ('4', '李四', '29', '男', '北京');
INSERT INTO `bear_excel_examples` VALUES ('5', '刘飞', '74', '男', '成都');
INSERT INTO `bear_excel_examples` VALUES ('6', '小猫', '1', '公', '喵星');
INSERT INTO `bear_excel_examples` VALUES ('7', '小狗', '2', '母', '汪星');
INSERT INTO `bear_excel_examples` VALUES ('8', '小花', '24', '女', '上海');
INSERT INTO `bear_excel_examples` VALUES ('9', '小张', '39', '男', '重庆');
INSERT INTO `bear_excel_examples` VALUES ('10', '小丽', '24', '女', '广州');
INSERT INTO `bear_excel_examples` VALUES ('11', '小猫', '1', '公', '喵星');
INSERT INTO `bear_excel_examples` VALUES ('12', '小狗', '2', '母', '汪星');
INSERT INTO `bear_excel_examples` VALUES ('13', '小花', '24', '女', '上海');
INSERT INTO `bear_excel_examples` VALUES ('14', '小张', '39', '男', '重庆');
INSERT INTO `bear_excel_examples` VALUES ('15', '小丽', '24', '女', '广州');
INSERT INTO `bear_excel_examples` VALUES ('16', '15', '1', '24', '女');
INSERT INTO `bear_excel_examples` VALUES ('17', '14', '2', '39', '男');
INSERT INTO `bear_excel_examples` VALUES ('18', '13', '3', '24', '女');
INSERT INTO `bear_excel_examples` VALUES ('19', '12', '4', '2', '母');
INSERT INTO `bear_excel_examples` VALUES ('20', '11', '5', '1', '公');
INSERT INTO `bear_excel_examples` VALUES ('21', '10', '6', '24', '女');
INSERT INTO `bear_excel_examples` VALUES ('22', '9', '7', '39', '男');
INSERT INTO `bear_excel_examples` VALUES ('23', '8', '8', '24', '女');
INSERT INTO `bear_excel_examples` VALUES ('24', '7', '9', '2', '母');
INSERT INTO `bear_excel_examples` VALUES ('25', '6', '10', '1', '公');
INSERT INTO `bear_excel_examples` VALUES ('26', '5', '11', '74', '男');
INSERT INTO `bear_excel_examples` VALUES ('27', '4', '12', '29', '男');
INSERT INTO `bear_excel_examples` VALUES ('28', '3', '13', '18', '男');
INSERT INTO `bear_excel_examples` VALUES ('29', '2', '14', '23', '女');
INSERT INTO `bear_excel_examples` VALUES ('30', '1', '15', '25', '男');
INSERT INTO `bear_excel_examples` VALUES ('31', '15', '1', '24', '女');
INSERT INTO `bear_excel_examples` VALUES ('32', '14', '2', '39', '男');
INSERT INTO `bear_excel_examples` VALUES ('33', '13', '3', '24', '女');
INSERT INTO `bear_excel_examples` VALUES ('34', '12', '4', '2', '母');
INSERT INTO `bear_excel_examples` VALUES ('35', '11', '5', '1', '公');
INSERT INTO `bear_excel_examples` VALUES ('36', '10', '6', '24', '女');
INSERT INTO `bear_excel_examples` VALUES ('37', '9', '7', '39', '男');
INSERT INTO `bear_excel_examples` VALUES ('38', '8', '8', '24', '女');
INSERT INTO `bear_excel_examples` VALUES ('39', '7', '9', '2', '母');
INSERT INTO `bear_excel_examples` VALUES ('40', '6', '10', '1', '公');
INSERT INTO `bear_excel_examples` VALUES ('41', '5', '11', '74', '男');
INSERT INTO `bear_excel_examples` VALUES ('42', '4', '12', '29', '男');
INSERT INTO `bear_excel_examples` VALUES ('43', '3', '13', '18', '男');
INSERT INTO `bear_excel_examples` VALUES ('44', '2', '14', '23', '女');
INSERT INTO `bear_excel_examples` VALUES ('45', '1', '15', '25', '男');
INSERT INTO `bear_excel_examples` VALUES ('46', '15', '1', '24', '女');
INSERT INTO `bear_excel_examples` VALUES ('47', '14', '2', '39', '男');
INSERT INTO `bear_excel_examples` VALUES ('48', '13', '3', '24', '女');
INSERT INTO `bear_excel_examples` VALUES ('49', '12', '4', '2', '母');
INSERT INTO `bear_excel_examples` VALUES ('50', '11', '5', '1', '公');
INSERT INTO `bear_excel_examples` VALUES ('51', '10', '6', '24', '女');
INSERT INTO `bear_excel_examples` VALUES ('52', '9', '7', '39', '男');
INSERT INTO `bear_excel_examples` VALUES ('53', '8', '8', '24', '女');
INSERT INTO `bear_excel_examples` VALUES ('54', '7', '9', '2', '母');
INSERT INTO `bear_excel_examples` VALUES ('55', '6', '10', '1', '公');
INSERT INTO `bear_excel_examples` VALUES ('56', '5', '11', '74', '男');
INSERT INTO `bear_excel_examples` VALUES ('57', '4', '12', '29', '男');
INSERT INTO `bear_excel_examples` VALUES ('58', '3', '13', '18', '男');
INSERT INTO `bear_excel_examples` VALUES ('59', '2', '14', '23', '女');
INSERT INTO `bear_excel_examples` VALUES ('60', '1', '15', '25', '男');
INSERT INTO `bear_excel_examples` VALUES ('378', '60', '1', '15', '25');
INSERT INTO `bear_excel_examples` VALUES ('379', '59', '2', '14', '23');
INSERT INTO `bear_excel_examples` VALUES ('380', '58', '3', '13', '18');
INSERT INTO `bear_excel_examples` VALUES ('381', '57', '4', '12', '29');
INSERT INTO `bear_excel_examples` VALUES ('382', '56', '5', '11', '74');
INSERT INTO `bear_excel_examples` VALUES ('383', '55', '6', '10', '1');
INSERT INTO `bear_excel_examples` VALUES ('384', '54', '7', '9', '2');
INSERT INTO `bear_excel_examples` VALUES ('385', '53', '8', '8', '24');
INSERT INTO `bear_excel_examples` VALUES ('386', '52', '9', '7', '39');
INSERT INTO `bear_excel_examples` VALUES ('387', '51', '10', '6', '24');
INSERT INTO `bear_excel_examples` VALUES ('388', '50', '11', '5', '1');
INSERT INTO `bear_excel_examples` VALUES ('389', '49', '12', '4', '2');
INSERT INTO `bear_excel_examples` VALUES ('390', '48', '13', '3', '24');
INSERT INTO `bear_excel_examples` VALUES ('391', '47', '14', '2', '39');
INSERT INTO `bear_excel_examples` VALUES ('392', '46', '15', '1', '24');
INSERT INTO `bear_excel_examples` VALUES ('393', '45', '1', '15', '25');
INSERT INTO `bear_excel_examples` VALUES ('394', '44', '2', '14', '23');
INSERT INTO `bear_excel_examples` VALUES ('395', '43', '3', '13', '18');
INSERT INTO `bear_excel_examples` VALUES ('396', '42', '4', '12', '29');
INSERT INTO `bear_excel_examples` VALUES ('397', '41', '5', '11', '74');
INSERT INTO `bear_excel_examples` VALUES ('398', '40', '6', '10', '1');
INSERT INTO `bear_excel_examples` VALUES ('399', '39', '7', '9', '2');
INSERT INTO `bear_excel_examples` VALUES ('400', '38', '8', '8', '24');
INSERT INTO `bear_excel_examples` VALUES ('401', '37', '9', '7', '39');
INSERT INTO `bear_excel_examples` VALUES ('402', '36', '10', '6', '24');
INSERT INTO `bear_excel_examples` VALUES ('403', '35', '11', '5', '1');
INSERT INTO `bear_excel_examples` VALUES ('404', '34', '12', '4', '2');
INSERT INTO `bear_excel_examples` VALUES ('405', '33', '13', '3', '24');
INSERT INTO `bear_excel_examples` VALUES ('406', '32', '14', '2', '39');
INSERT INTO `bear_excel_examples` VALUES ('407', '31', '15', '1', '24');
INSERT INTO `bear_excel_examples` VALUES ('408', '30', '1', '15', '25');
INSERT INTO `bear_excel_examples` VALUES ('409', '29', '2', '14', '23');
INSERT INTO `bear_excel_examples` VALUES ('410', '28', '3', '13', '18');
INSERT INTO `bear_excel_examples` VALUES ('411', '27', '4', '12', '29');
INSERT INTO `bear_excel_examples` VALUES ('412', '26', '5', '11', '74');
INSERT INTO `bear_excel_examples` VALUES ('413', '25', '6', '10', '1');
INSERT INTO `bear_excel_examples` VALUES ('414', '24', '7', '9', '2');
INSERT INTO `bear_excel_examples` VALUES ('415', '23', '8', '8', '24');
INSERT INTO `bear_excel_examples` VALUES ('416', '22', '9', '7', '39');
INSERT INTO `bear_excel_examples` VALUES ('417', '21', '10', '6', '24');
INSERT INTO `bear_excel_examples` VALUES ('418', '20', '11', '5', '1');
INSERT INTO `bear_excel_examples` VALUES ('419', '19', '12', '4', '2');
INSERT INTO `bear_excel_examples` VALUES ('420', '18', '13', '3', '24');
INSERT INTO `bear_excel_examples` VALUES ('421', '17', '14', '2', '39');
INSERT INTO `bear_excel_examples` VALUES ('422', '16', '15', '1', '24');
INSERT INTO `bear_excel_examples` VALUES ('423', '15', '1', '24', '女');
INSERT INTO `bear_excel_examples` VALUES ('424', '14', '2', '39', '男');
INSERT INTO `bear_excel_examples` VALUES ('425', '13', '3', '24', '女');
INSERT INTO `bear_excel_examples` VALUES ('426', '12', '4', '2', '母');
INSERT INTO `bear_excel_examples` VALUES ('427', '11', '5', '1', '公');
INSERT INTO `bear_excel_examples` VALUES ('428', '10', '6', '24', '女');
INSERT INTO `bear_excel_examples` VALUES ('429', '9', '7', '39', '男');
INSERT INTO `bear_excel_examples` VALUES ('430', '8', '8', '24', '女');
INSERT INTO `bear_excel_examples` VALUES ('431', '7', '9', '2', '母');
INSERT INTO `bear_excel_examples` VALUES ('432', '6', '10', '1', '公');
INSERT INTO `bear_excel_examples` VALUES ('433', '5', '11', '74', '男');
INSERT INTO `bear_excel_examples` VALUES ('434', '4', '12', '29', '男');
INSERT INTO `bear_excel_examples` VALUES ('435', '3', '13', '18', '男');
INSERT INTO `bear_excel_examples` VALUES ('436', '2', '14', '23', '女');
INSERT INTO `bear_excel_examples` VALUES ('437', '1', '15', '25', '男');

-- ----------------------------
-- Table structure for bear_news
-- ----------------------------
DROP TABLE IF EXISTS `bear_news`;
CREATE TABLE `bear_news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL COMMENT '用户id',
  `title` varchar(100) NOT NULL COMMENT '标题',
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `source_id` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '来源id，再创建个来源表，1代表原创，2代表转载',
  `sort_id` int(10) unsigned NOT NULL DEFAULT '100' COMMENT '排序id',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `delete_time` int(10) unsigned DEFAULT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bear_news
-- ----------------------------

-- ----------------------------
-- Table structure for bear_news_types
-- ----------------------------
DROP TABLE IF EXISTS `bear_news_types`;
CREATE TABLE `bear_news_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级id',
  `title` varchar(100) NOT NULL COMMENT '分类名称',
  `description` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL COMMENT '图片地址',
  `sort_id` int(10) unsigned NOT NULL DEFAULT '100' COMMENT '排序id',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `delete_time` int(10) unsigned DEFAULT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bear_news_types
-- ----------------------------
INSERT INTO `bear_news_types` VALUES ('1', '0', '生活资讯', '带你领略每一天！', '20170321/d78af52f78b2e634a97234c6dde9eba9.png', '100', '1490091741', '1490091741', null, '1');

-- ----------------------------
-- Table structure for bear_request_type
-- ----------------------------
DROP TABLE IF EXISTS `bear_request_type`;
CREATE TABLE `bear_request_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(20) NOT NULL COMMENT '请求代码',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '默认状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bear_request_type
-- ----------------------------
INSERT INTO `bear_request_type` VALUES ('1', 'GET', '1');
INSERT INTO `bear_request_type` VALUES ('2', 'POST', '1');
INSERT INTO `bear_request_type` VALUES ('3', 'PUT', '1');
INSERT INTO `bear_request_type` VALUES ('4', 'DELETE', '1');

-- ----------------------------
-- Table structure for bear_sysconfigs
-- ----------------------------
DROP TABLE IF EXISTS `bear_sysconfigs`;
CREATE TABLE `bear_sysconfigs` (
  `sysconfig_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `content` varchar(255) NOT NULL,
  `is_open` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `description` varchar(255) NOT NULL,
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  `delete_time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`sysconfig_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of bear_sysconfigs
-- ----------------------------
INSERT INTO `bear_sysconfigs` VALUES ('1', '后台名称12', 'site_name', 'BearAdminfggfg', '1', '网站后台名称，title和登录界面显示', '1502187289', '0', null);

-- ----------------------------
-- Table structure for bear_syslogs
-- ----------------------------
DROP TABLE IF EXISTS `bear_syslogs`;
CREATE TABLE `bear_syslogs` (
  `log_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `level` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '错误等级',
  `message` varchar(500) NOT NULL COMMENT '错误信息',
  `file` varchar(255) NOT NULL COMMENT '文件',
  `line` int(10) unsigned NOT NULL COMMENT '所在行数',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2270 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of bear_syslogs
-- ----------------------------

-- ----------------------------
-- Table structure for bear_syslog_trace
-- ----------------------------
DROP TABLE IF EXISTS `bear_syslog_trace`;
CREATE TABLE `bear_syslog_trace` (
  `trace_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `log_id` int(11) unsigned NOT NULL COMMENT 'log id',
  `trace` text,
  PRIMARY KEY (`trace_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2270 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of bear_syslog_trace
-- ----------------------------

-- ----------------------------
-- Table structure for bear_users
-- ----------------------------
DROP TABLE IF EXISTS `bear_users`;
CREATE TABLE `bear_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(24) NOT NULL COMMENT '用户名，最大长度目前24',
  `password` char(32) NOT NULL,
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int(10) unsigned DEFAULT NULL COMMENT '软删除时间戳',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bear_users
-- ----------------------------
INSERT INTO `bear_users` VALUES ('1', '', '', '1502864773', '1502866453', '1502866453');
INSERT INTO `bear_users` VALUES ('2', '', '', '1502865095', '1502866438', '1502866438');
INSERT INTO `bear_users` VALUES ('3', '', '', '1502865800', '1502867881', '1502867881');
INSERT INTO `bear_users` VALUES ('4', '阿德法地方', 'afdadfadf', '1502865806', '1503016177', '1503016177');
INSERT INTO `bear_users` VALUES ('5', 'test', 'dfgadf', '1502866663', '1502866663', null);
INSERT INTO `bear_users` VALUES ('6', '测试', '34234', '1502867905', '1502867905', null);
INSERT INTO `bear_users` VALUES ('7', '测试3334444', 'fdafasdf', '1502867920', '1502868866', null);
INSERT INTO `bear_users` VALUES ('8', 'pangzi', '1111', '1502880947', '1502880947', null);
INSERT INTO `bear_users` VALUES ('9', 'ceshi a a a', '2322', '1502880985', '1502880985', null);
INSERT INTO `bear_users` VALUES ('10', '阿萨德发大水', '324234', '1503016146', '1503016146', null);
