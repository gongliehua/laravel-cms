# Host: localhost  (Version: 5.7.17)
# Date: 2019-05-29 12:43:28
# Generator: MySQL-Front 5.3  (Build 4.234)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "admin_roles"
#

DROP TABLE IF EXISTS `admin_roles`;
CREATE TABLE `admin_roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `role_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '角色ID',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='管理员角色表';

#
# Data for table "admin_roles"
#

INSERT INTO `admin_roles` VALUES (1,1,1,NULL,NULL);

#
# Structure for table "admins"
#

DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '密码',
  `name` varchar(255) DEFAULT '' COMMENT '名称',
  `avatar` varchar(255) DEFAULT NULL COMMENT '头像',
  `remember_token` varchar(255) DEFAULT NULL COMMENT 'Token',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='管理员表';

#
# Data for table "admins"
#

INSERT INTO `admins` VALUES (1,'admin','7c4a8d09ca3762af61e59520943dc26494f8941b','admin',NULL,NULL,NULL,NULL);

#
# Structure for table "articles"
#

DROP TABLE IF EXISTS `articles`;
CREATE TABLE `articles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `keywords` varchar(255) DEFAULT NULL COMMENT '关键字',
  `description` varchar(255) DEFAULT NULL COMMENT '描述',
  `content` text NOT NULL COMMENT '内容',
  `category_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '栏目ID',
  `admin_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章表';

#
# Data for table "articles"
#


#
# Structure for table "categorys"
#

DROP TABLE IF EXISTS `categorys`;
CREATE TABLE `categorys` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '栏目名称',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '类型:1列表,2单页,3链接',
  `keywords` varchar(255) DEFAULT NULL COMMENT '关键字',
  `description` varchar(255) DEFAULT NULL COMMENT '描述',
  `content` text COMMENT '内容',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='栏目表';

#
# Data for table "categorys"
#


#
# Structure for table "configs"
#

DROP TABLE IF EXISTS `configs`;
CREATE TABLE `configs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '类型:1单行文本,2多行文本,3单选按钮,4复选框,5下拉框',
  `value` varchar(255) DEFAULT NULL COMMENT '配置值',
  `values` varchar(255) DEFAULT NULL COMMENT '可选值',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='配置表';

#
# Data for table "configs"
#


#
# Structure for table "permissions"
#

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `icon` varchar(255) DEFAULT NULL COMMENT '图标',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `uri` varchar(255) DEFAULT NULL COMMENT 'URI',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `is_menu` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否菜单:0不是,1是',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COMMENT='权限表';

#
# Data for table "permissions"
#

INSERT INTO `permissions` VALUES (1,'fa-users','管理员管理',NULL,2,1,0,NULL,NULL),(2,'fa-circle-o','权限列表','admin/permissionList',2,1,1,NULL,NULL),(3,NULL,'权限添加','admin/permissionAdd',0,0,1,NULL,NULL),(4,NULL,'权限信息','admin/permissionInfo',0,0,1,NULL,NULL),(5,NULL,'权限编辑','admin/permissionEdit',0,0,1,NULL,NULL),(6,NULL,'权限删除','admin/permissionDel',0,0,1,NULL,NULL),(7,'fa-circle-o','管理员列表','admin/adminList',0,1,1,'2019-05-29 11:41:50','2019-05-29 11:44:25'),(8,NULL,'管理员添加','admin/adminAdd',0,0,1,'2019-05-29 11:42:07','2019-05-29 11:42:07'),(9,NULL,'管理员信息','admin/adminInfo',0,0,1,'2019-05-29 11:42:21','2019-05-29 11:42:21'),(10,NULL,'管理员编辑','admin/adminEdit',0,0,1,'2019-05-29 11:42:36','2019-05-29 11:42:36'),(11,NULL,'管理员删除','admin/adminDel',0,0,1,'2019-05-29 11:42:49','2019-05-29 11:42:49'),(12,'fa-circle-o','角色列表','admin/roleList',1,1,1,NULL,NULL),(13,NULL,'角色添加','admin/roleAdd',0,0,1,NULL,NULL),(14,NULL,'角色信息','admin/roleInfo',0,0,1,NULL,NULL),(15,NULL,'角色编辑','admin/roleEdit',0,0,1,NULL,NULL),(16,NULL,'角色删除','admin/roleDel',0,0,1,NULL,NULL),(17,'fa-gears','系统管理',NULL,3,1,0,NULL,NULL),(18,'fa-circle-o','配置列表','admin/configList',0,1,17,NULL,NULL),(19,NULL,'配置添加','admin/configAdd',0,0,17,NULL,NULL),(20,NULL,'配置信息','admin/configInfo',0,0,17,NULL,NULL),(21,NULL,'配置编辑','admin/configEdit',0,0,17,NULL,NULL),(22,NULL,'配置删除','admin/configDel',0,0,17,NULL,NULL),(23,'fa-circle-o','配置管理','admin/configSave',0,1,17,NULL,NULL),(24,'fa-file-text','文章管理',NULL,0,1,0,NULL,NULL),(25,'fa-circle-o','文章列表','admin/articleList',0,1,24,NULL,NULL),(26,NULL,'文章添加','admin/articleAdd',0,0,24,NULL,NULL),(27,NULL,'文章信息','admin/articleInfo',0,0,24,NULL,NULL),(28,NULL,'文章编辑','admin/articleEdit',0,0,24,NULL,NULL),(29,NULL,'文章删除','admin/articleDel',0,0,24,NULL,NULL),(30,'fa-align-justify','栏目管理',NULL,1,1,0,NULL,NULL),(31,'fa-circle-o','栏目列表','admin/categoryList',0,1,30,NULL,NULL),(32,NULL,'栏目添加','admin/categoryAdd',0,0,30,NULL,NULL),(33,NULL,'栏目信息','admin/categoryInfo',0,0,30,NULL,NULL),(34,NULL,'栏目编辑','admin/categoryEdit',0,0,30,NULL,NULL),(35,NULL,'栏目删除','admin/categoryDel',0,0,30,NULL,NULL);

#
# Structure for table "role_permissions"
#

DROP TABLE IF EXISTS `role_permissions`;
CREATE TABLE `role_permissions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '角色ID',
  `permission_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '权限ID',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='角色权限表';

#
# Data for table "role_permissions"
#


#
# Structure for table "roles"
#

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态:0禁用,1正常',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='角色表';

#
# Data for table "roles"
#

INSERT INTO `roles` VALUES (1,'管理员',1,NULL,NULL);
