/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50717
Source Host           : localhost:3306
Source Database       : bearadmin

Target Server Type    : MYSQL
Target Server Version : 50717
File Encoding         : 65001

Date: 2017-12-14 20:05:04
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='后台用户文件表';

-- ----------------------------
-- Records of bear_admin_files
-- ----------------------------

-- ----------------------------
-- Table structure for bear_admin_groups
-- ----------------------------
DROP TABLE IF EXISTS `bear_admin_groups`;
CREATE TABLE `bear_admin_groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(200) DEFAULT '' COMMENT '角色描述',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '默认为1启用，2冻结',
  `rules` varchar(350) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='角色表';

-- ----------------------------
-- Records of bear_admin_groups
-- ----------------------------
INSERT INTO `bear_admin_groups` VALUES ('1', '管理员', '管理员角色', '1', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,26,27,28,29,30,31,32,33,34,21,22,23,25,24,35,36,44,37,38,39,40,41,43,42,45,46,47,48,49,50');
INSERT INTO `bear_admin_groups` VALUES ('2', '体验用户', '系统体验用户', '1', '1,2,3,4,8,13,17,18,19,20,26,27,31,34,21,22,23,24,35,36,37,38,39,40,41,43,42,45,49,50');

-- ----------------------------
-- Table structure for bear_admin_group_access
-- ----------------------------
DROP TABLE IF EXISTS `bear_admin_group_access`;
CREATE TABLE `bear_admin_group_access` (
  `uid` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='角色用户关联表';

-- ----------------------------
-- Records of bear_admin_group_access
-- ----------------------------
INSERT INTO `bear_admin_group_access` VALUES ('1', '1');
INSERT INTO `bear_admin_group_access` VALUES ('2', '2');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='后台用户操作日志表';

-- ----------------------------
-- Records of bear_admin_logs
-- ----------------------------

-- ----------------------------
-- Table structure for bear_admin_log_datas
-- ----------------------------
DROP TABLE IF EXISTS `bear_admin_log_datas`;
CREATE TABLE `bear_admin_log_datas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_id` int(10) unsigned NOT NULL,
  `data` longtext NOT NULL,
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `delete_time` int(10) unsigned DEFAULT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='后台用户操作日志数据表';

-- ----------------------------
-- Records of bear_admin_log_datas
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='邮件发送记录表';

-- ----------------------------
-- Records of bear_admin_mail_logs
-- ----------------------------

-- ----------------------------
-- Table structure for bear_admin_menus
-- ----------------------------
DROP TABLE IF EXISTS `bear_admin_menus`;
CREATE TABLE `bear_admin_menus` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '菜单id',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父级id',
  `title` varchar(50) NOT NULL COMMENT '菜单名称',
  `url` varchar(100) NOT NULL COMMENT '模块/控制器/方法',
  `icon` varchar(50) NOT NULL DEFAULT 'fa-circle-o' COMMENT '菜单图标',
  `condition` varchar(255) DEFAULT '',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  `sort_id` smallint(5) unsigned NOT NULL DEFAULT '1000' COMMENT '排序id',
  `log_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0不记录日志，1get，2post，3put，4delete，先这些啦',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '认证方式，1为实时认证，2为登录认证',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态：1默认正常，2禁用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COMMENT='后台菜单表';

-- ----------------------------
-- Records of bear_admin_menus
-- ----------------------------
INSERT INTO `bear_admin_menus` VALUES ('1', '0', '后台首页', 'admin/index/index', 'fa-home', '', '1', '1', '0', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('2', '0', '系统管理', 'admin/sys', 'fa-desktop', '', '1', '100', '0', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('3', '2', '个人资料', 'admin/admin_user/profile', 'fa-edit', '', '0', '2', '0', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('4', '2', '用户管理', 'admin/admin_user/index', 'fa-user', '', '1', '99', '0', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('5', '4', '添加用户', 'admin/admin_user/add', 'fa-plus', '', '0', '100', '2', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('6', '4', '修改用户', 'admin/admin_user/edit', 'fa-edit', '', '0', '100', '2', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('7', '4', '删除用户', 'admin/admin_user/del', 'fa-close', '', '0', '100', '1', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('8', '2', '角色管理', 'admin/admin_group/index', 'fa-group', '', '1', '100', '0', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('9', '8', '添加角色', 'admin/admin_group/add', 'fa-plus', '', '0', '100', '2', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('10', '8', '修改角色', 'admin/admin_group/edit', 'fa-edit', '', '0', '100', '2', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('11', '8', '删除角色', 'admin/admin_group/del', 'fa-close', '', '0', '100', '1', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('12', '8', '授权管理', 'admin/admin_group/access', 'fa-key', '', '0', '100', '2', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('13', '2', '菜单管理', 'admin/admin_menu/index', 'fa-th-list', '', '1', '100', '0', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('14', '13', '添加菜单', 'admin/admin_menu/add', 'fa-plus', '', '0', '100', '2', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('15', '13', '修改菜单', 'admin/admin_menu/edit', 'fa-edit', '', '0', '100', '2', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('16', '13', '删除菜单', 'admin/admin_menu/del', 'fa-close', '', '0', '100', '1', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('17', '2', '日志管理', 'admin/admin_log', 'fa-info', '', '1', '100', '0', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('18', '17', '操作日志', 'admin/admin_log/index', 'fa-keyboard-o', '', '1', '100', '0', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('19', '18', '查看日志', 'admin/admin_log/view', 'fa-search-plus', '', '0', '100', '0', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('20', '17', '系统日志', 'admin/syslog/index', 'fa-info-circle', '', '1', '100', '0', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('21', '2', '文件管理', 'admin/admin_file/index', 'fa-file-archive-o', '', '1', '101', '0', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('22', '21', '上传文件', 'admin/admin_file/upload', 'fa-upload', '', '0', '100', '2', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('23', '21', '下载文件', 'admin/admin_file/download', 'fa-download', '', '0', '100', '1', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('24', '21', '修改文件', 'admin/admin_file/edit', 'fa-edit', '', '0', '1000', '2', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('25', '21', '删除文件', 'admin/admin_file/del', 'fa-crash', '', '0', '100', '1', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('26', '2', '数据维护', 'admin/data', 'fa-database', '', '1', '100', '0', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('27', '26', '数据库备份', 'admin/databack/index', 'fa-database', '', '1', '100', '0', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('28', '27', '添加备份', 'admin/databack/add', 'fa-plus', '', '0', '100', '0', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('29', '27', '删除备份', 'admin/databack/del', 'fa-close', '', '0', '100', '1', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('30', '27', '还原备份', 'admin/databack/reduction', 'fa-circle-o', '', '0', '100', '0', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('31', '26', '数据表管理', 'admin/database/index', 'fa-list', '', '1', '100', '0', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('32', '31', '优化表', 'admin/database/optimize', 'fa-refresh', '', '0', '100', '1', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('33', '31', '修复表', 'admin/database/repair', 'fa-circle-o-notch', '', '0', '100', '1', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('34', '31', '查看表详情', 'admin/database/view', 'fa-info-circle', '', '0', '100', '1', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('35', '2', '扩展功能', 'admin/extend/index', 'fa-plus-circle', '', '1', '102', '0', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('36', '35', 'Ueditor', 'admin/extend/ueditor', 'fa-edit', '', '0', '100', '2', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('37', '35', '短信发送', 'admin/extend/sms', 'fa-comment', '', '0', '100', '2', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('38', '35', '发送邮件', 'admin/extend/email', 'fa-envelope', '', '0', '100', '2', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('39', '35', '阿里云oss', 'admin/extend/aliyunoss', 'fa-cloud-upload', '', '0', '100', '0', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('40', '35', '七牛云存储', 'admin/extend/qiniu', 'fa-cloud', '', '0', '100', '0', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('41', '35', '二维码生成', 'admin/extend/qrcode', 'fa-qrcode', '', '0', '100', '2', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('42', '35', 'Excel导入导出', 'admin/extend/excel', 'fa-file-excel-o', '', '0', '110', '2', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('43', '35', 'MarkDown编辑器', 'admin/extend/markdown', 'fa-file-text-o', '', '0', '100', '1', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('44', '36', '编辑器上传', 'admin/extend/ueserver', 'fa-server', '', '0', '100', '2', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('45', '2', '系统设置', 'admin/sysconfig/index', 'fa-cogs', '', '1', '998', '0', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('46', '45', '添加设置', 'admin/sysconfig/add', 'fa-plus', '', '0', '100', '2', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('47', '45', '修改设置', 'admin/sysconfig/edit', 'fa-edit', '', '0', '100', '2', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('48', '45', '删除设置', 'admin/sysconfig/del', 'fa-close', '', '0', '100', '1', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('49', '0', '统计管理', 'admin/statistics/default', 'fa-pie-chart', '', '1', '1234', '0', '1', '1');
INSERT INTO `bear_admin_menus` VALUES ('50', '49', '统计概览', 'admin/statistics/index', 'fa-bar-chart', '', '1', '100', '0', '1', '1');

-- ----------------------------
-- Table structure for bear_admin_users
-- ----------------------------
DROP TABLE IF EXISTS `bear_admin_users`;
CREATE TABLE `bear_admin_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `name` varchar(50) NOT NULL COMMENT '用户名（登录帐号）',
  `password` char(32) NOT NULL COMMENT '密码',
  `nick_name` varchar(30) DEFAULT NULL COMMENT '用户昵称或中文用户名',
  `email` varchar(255) DEFAULT NULL COMMENT '邮箱',
  `mobile` char(11) DEFAULT NULL COMMENT '手机号',
  `avatar` varchar(255) DEFAULT 'avatar.png' COMMENT '用户头像',
  `qq_openid` varchar(64) DEFAULT NULL,
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int(10) unsigned DEFAULT NULL COMMENT '删除时间',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '用户状态1正常，0冻结',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COMMENT='后台用户表';

-- ----------------------------
-- Records of bear_admin_users
-- ----------------------------
INSERT INTO `bear_admin_users` VALUES ('1', 'admin', '21232f297a57a5a743894a0e4a801fc3', '超级管理员', '', '18855550000', '1\\20171212\\dc6f12bb9a981882e3d559a5af1addd0.png', null, '1488189586', '1513148717', null, '1');
INSERT INTO `bear_admin_users` VALUES ('2', 'admin2', '21232f297a57a5a743894a0e4a801fc3', '管理员2', '', '', '1\\20171212\\dc6f12bb9a981882e3d559a5af1addd0.png', null, '1488189586', '1513185374', null, '1');

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='Excel示例表';

-- ----------------------------
-- Records of bear_excel_examples
-- ----------------------------
INSERT INTO `bear_excel_examples` VALUES ('1', '于破熊', '25', '男', '济南');
INSERT INTO `bear_excel_examples` VALUES ('2', '淘气熊', '24', '女', '济南');

-- ----------------------------
-- Table structure for bear_request_type
-- ----------------------------
DROP TABLE IF EXISTS `bear_request_type`;
CREATE TABLE `bear_request_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(20) NOT NULL COMMENT '请求代码',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '默认状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COMMENT='请求类型表';

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
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '默认1，系统设置',
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `content` varchar(255) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否启用，1启用，0禁用',
  `description` varchar(255) NOT NULL,
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  `delete_time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='系统参数表';

-- ----------------------------
-- Records of bear_sysconfigs
-- ----------------------------
INSERT INTO `bear_sysconfigs` VALUES ('1', '1', '后台名称', 'site_name', 'BearAdmin', '1', '网站后台名称，title和登录界面显示', '1502187289', '0', null);
INSERT INTO `bear_sysconfigs` VALUES ('2', '1', '测试', 'ceshi', '昵称', '1', '昵称说明', '1506366998', '0', null);

-- ----------------------------
-- Table structure for bear_sysconfig_groups
-- ----------------------------
DROP TABLE IF EXISTS `bear_sysconfig_groups`;
CREATE TABLE `bear_sysconfig_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '分组名称',
  `sort_id` int(11) unsigned NOT NULL DEFAULT '1000' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='系统设置分组';

-- ----------------------------
-- Records of bear_sysconfig_groups
-- ----------------------------
INSERT INTO `bear_sysconfig_groups` VALUES ('1', '系统设置', '1000');
INSERT INTO `bear_sysconfig_groups` VALUES ('2', '扩展参数设置', '1000');

-- ----------------------------
-- Table structure for bear_syslogs
-- ----------------------------
DROP TABLE IF EXISTS `bear_syslogs`;
CREATE TABLE `bear_syslogs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `level` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '错误等级',
  `message` varchar(255) NOT NULL COMMENT '错误信息',
  `file` varchar(255) NOT NULL COMMENT '文件',
  `line` int(10) unsigned NOT NULL COMMENT '所在行数',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
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
