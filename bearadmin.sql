/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50717
Source Host           : localhost:3306
Source Database       : bearadmin_yufup

Target Server Type    : MYSQL
Target Server Version : 50717
File Encoding         : 65001

Date: 2017-09-27 22:44:56
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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='后台用户文件表';

-- ----------------------------
-- Records of bear_admin_files
-- ----------------------------

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='后台用户操作日志表';

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='后台用户操作日志数据表';

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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='邮件发送记录表';

-- ----------------------------
-- Records of bear_admin_mail_logs
-- ----------------------------

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
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8 COMMENT='后台菜单表';

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
INSERT INTO `bear_admin_menus` VALUES ('59', '20', '1', 'MarkDown编辑器', 'admin/markdown/index', '', 'fa-file-text-o', '1', '100', '1496885512', '1506081778', '1');
INSERT INTO `bear_admin_menus` VALUES ('60', '74', '1', '数据库备份', 'admin/databack/index', '', 'fa-database', '0', '100', '1502788380', '1504764342', '1');
INSERT INTO `bear_admin_menus` VALUES ('61', '60', '0', '添加备份', 'admin/databack/add', '', 'fa-plus', '0', '100', '1502789144', '1502789144', '1');
INSERT INTO `bear_admin_menus` VALUES ('62', '60', '0', '还原备份', 'admin/databack/reduction', '', 'fa-circle-o', '0', '100', '1502789201', '1502789201', '1');
INSERT INTO `bear_admin_menus` VALUES ('63', '60', '0', '删除备份', 'admin/databack/del', '', 'fa-close', '1', '100', '1502789239', '1502789239', '1');
INSERT INTO `bear_admin_menus` VALUES ('64', '0', '1', '用户测试', 'admin/user/index', '', 'fa-circle-o', '0', '100', '1502864020', '1502864020', '1');
INSERT INTO `bear_admin_menus` VALUES ('65', '64', '0', '添加用户', 'admin/user/add', '', 'fa-circle-o', '0', '100', '1502864686', '1502864702', '1');
INSERT INTO `bear_admin_menus` VALUES ('66', '64', '0', '编辑用户', 'admin/user/edit', '', 'fa-circle-o', '0', '100', '1502864733', '1502864733', '1');
INSERT INTO `bear_admin_menus` VALUES ('67', '64', '0', '删除用户', 'admin/user/del', '', 'fa-circle-o', '0', '100', '1502864755', '1502864755', '1');
INSERT INTO `bear_admin_menus` VALUES ('68', '23', '0', '编辑器上传', 'admin/ueditor/server', '', 'fa-server', '2', '100', '1503535735', '1504921345', '1');
INSERT INTO `bear_admin_menus` VALUES ('73', '74', '1', '数据表管理', 'admin/database/index', '', 'fa-list', '0', '100', '1504764209', '1504764438', '1');
INSERT INTO `bear_admin_menus` VALUES ('74', '34', '1', '数据维护', 'admin/database', '', 'fa-database', '0', '100', '1504764318', '1504764318', '1');
INSERT INTO `bear_admin_menus` VALUES ('75', '73', '0', '优化表', 'admin/database/optimize', '', 'fa-refresh', '1', '100', '1504764525', '1504764525', '1');
INSERT INTO `bear_admin_menus` VALUES ('76', '73', '0', '修复表', 'admin/database/repair', '', 'fa-circle-o-notch', '1', '100', '1504764592', '1504764592', '1');
INSERT INTO `bear_admin_menus` VALUES ('77', '73', '0', '查看表详情', 'admin/database/view', '', 'fa-info-circle', '1', '100', '1504764664', '1504764664', '1');

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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='后台用户表';

-- ----------------------------
-- Records of bear_admin_users
-- ----------------------------
INSERT INTO `bear_admin_users` VALUES ('1', 'admin', '21232f297a57a5a743894a0e4a801fc3', '超级管理员', '1/20170524/aa579d638a236fd9ac06ff419ca88cb1.jpg', '1488189586', '1504170129', null, '1');
INSERT INTO `bear_admin_users` VALUES ('2', 'admin2', '21232f297a57a5a743894a0e4a801fc3', '管理员2', 'avatar.png', '1488189586', '1502342521', null, '1');
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='后台用户扩展资料表';

-- ----------------------------
-- Records of bear_admin_user_profiles
-- ----------------------------
INSERT INTO `bear_admin_user_profiles` VALUES ('1', '1', '18363083115', '8553151@qq.com', '我是超级管理员，不管你信不信。22', '1', '8553151', '8553151', 'weibo', 'zhihu', '', '', '济南', '', '0', '1503021449', null, '1');
INSERT INTO `bear_admin_user_profiles` VALUES ('3', '2', '18355552220', '49548@qq.com', '', '1', '', '', '', '', '', '', '', '', '0', '1502337712', null, '1');
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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='角色表';

-- ----------------------------
-- Records of bear_auth_groups
-- ----------------------------
INSERT INTO `bear_auth_groups` VALUES ('1', '管理员', '管理员角色', '1', '1,2,3,4,5,6,7,8,9,10,11,15,14,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,49,50,51,52,57,58,59,60,61,62,63,64,65,66,67,68,70,73,74,75,76,77');
INSERT INTO `bear_auth_groups` VALUES ('2', '普通管理员', '普通管理员', '1', '1,35,37');

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='角色用户关联表';

-- ----------------------------
-- Records of bear_auth_group_access
-- ----------------------------
INSERT INTO `bear_auth_group_access` VALUES ('1', '1');
INSERT INTO `bear_auth_group_access` VALUES ('2', '1');


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
) ENGINE=MyISAM AUTO_INCREMENT=82 DEFAULT CHARSET=utf8 COMMENT='权限规则表';

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
INSERT INTO `bear_auth_rules` VALUES ('59', 'admin/markdown/index', 'MarkDown编辑器', '1', '1', '', '59');
INSERT INTO `bear_auth_rules` VALUES ('60', 'admin/databack/index', '数据库备份', '1', '1', '', '60');
INSERT INTO `bear_auth_rules` VALUES ('61', 'admin/databack/add', '添加备份', '1', '1', '', '61');
INSERT INTO `bear_auth_rules` VALUES ('62', 'admin/databack/reduction', '还原备份', '1', '1', '', '62');
INSERT INTO `bear_auth_rules` VALUES ('63', 'admin/databack/del', '删除备份', '1', '1', '', '63');
INSERT INTO `bear_auth_rules` VALUES ('64', 'admin/user/index', '用户测试', '1', '1', '', '64');
INSERT INTO `bear_auth_rules` VALUES ('65', 'admin/user/add', '添加用户', '1', '1', '', '65');
INSERT INTO `bear_auth_rules` VALUES ('66', 'admin/user/edit', '编辑用户', '1', '1', '', '66');
INSERT INTO `bear_auth_rules` VALUES ('67', 'admin/user/del', '删除用户', '1', '1', '', '67');
INSERT INTO `bear_auth_rules` VALUES ('68', 'admin/ueditor/server', '编辑器上传', '1', '1', '', '68');
INSERT INTO `bear_auth_rules` VALUES ('73', 'admin/database/index', '数据表管理', '1', '1', '', '73');
INSERT INTO `bear_auth_rules` VALUES ('74', 'admin/database', '数据维护', '1', '1', '', '74');
INSERT INTO `bear_auth_rules` VALUES ('75', 'admin/database/optimize', '优化表', '1', '1', '', '75');
INSERT INTO `bear_auth_rules` VALUES ('76', 'admin/database/repair', '修复表', '1', '1', '', '76');
INSERT INTO `bear_auth_rules` VALUES ('77', 'admin/database/view', '查看表详情', '1', '1', '', '77');
INSERT INTO `bear_auth_rules` VALUES ('79', '123', '12', '1', '1', '', '79');
INSERT INTO `bear_auth_rules` VALUES ('80', '/addasd/asdasdsad', '测试菜单', '1', '1', '', '80');
INSERT INTO `bear_auth_rules` VALUES ('81', '/asd/asdadsad', 'ce 阿萨德啊a', '1', '1', '', '81');

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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='Excel示例表';

-- ----------------------------
-- Records of bear_excel_examples
-- ----------------------------
INSERT INTO `bear_excel_examples` VALUES ('1', '于破熊', '25', '男', '济南');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='新闻表';

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='新闻类别表';

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='请求类型表';

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='系统参数表';

-- ----------------------------
-- Records of bear_sysconfigs
-- ----------------------------
INSERT INTO `bear_sysconfigs` VALUES ('1', '后台名称12', 'site_name', 'BearAdminfggfg', '1', '网站后台名称，title和登录界面显示', '1502187289', '0', null);
INSERT INTO `bear_sysconfigs` VALUES ('2', '测试', 'ceshi', '昵称', '1', '昵称说明', '1506366998', '0', null);

-- ----------------------------
-- Table structure for bear_syslogs
-- ----------------------------
DROP TABLE IF EXISTS `bear_syslogs`;
CREATE TABLE `bear_syslogs` (
  `log_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `level` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '错误等级',
  `message` varchar(255) NOT NULL COMMENT '错误信息',
  `file` varchar(255) NOT NULL COMMENT '文件',
  `line` int(10) unsigned NOT NULL COMMENT '所在行数',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='系统错误日志表';

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='系统日志trace表';

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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='前台用户表';

-- ----------------------------
-- Records of bear_users
-- ----------------------------
INSERT INTO `bear_users` VALUES ('1', 'test', 'test', '1502864773', '1502866453', '1502866453');
