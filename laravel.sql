# Host: localhost  (Version: 5.7.17)
# Date: 2019-02-02 12:58:11
# Generator: MySQL-Front 5.3  (Build 4.234)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "laravel_admins"
#

DROP TABLE IF EXISTS `laravel_admins`;
CREATE TABLE `laravel_admins` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` varchar(255) NOT NULL DEFAULT '' COMMENT '昵称',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '密码',
  `avatar` varchar(255) DEFAULT NULL COMMENT '头像',
  `remember_token` varchar(255) DEFAULT NULL COMMENT 'Token',
  `group_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户组ID',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='用户表';

#
# Data for table "laravel_admins"
#

INSERT INTO `laravel_admins` VALUES (1,'admin','Admin','7c4a8d09ca3762af61e59520943dc26494f8941b',NULL,NULL,1,NULL,NULL),(2,'guest','Guest','7c4a8d09ca3762af61e59520943dc26494f8941b','',NULL,2,'2019-02-02 12:44:41','2019-02-02 12:44:41');

#
# Structure for table "laravel_articles"
#

DROP TABLE IF EXISTS `laravel_articles`;
CREATE TABLE `laravel_articles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `keywords` varchar(255) DEFAULT NULL COMMENT '关键字',
  `description` varchar(255) DEFAULT NULL COMMENT '描述',
  `content` text NOT NULL COMMENT '内容',
  `click` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '点击量',
  `category_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '栏目ID',
  `admin_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章表';

#
# Data for table "laravel_articles"
#


#
# Structure for table "laravel_categorys"
#

DROP TABLE IF EXISTS `laravel_categorys`;
CREATE TABLE `laravel_categorys` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '栏目名称',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '类型:1列表,2单页,3链接',
  `keywords` varchar(255) DEFAULT NULL COMMENT '关键字',
  `description` varchar(255) DEFAULT NULL COMMENT '描述',
  `content` text COMMENT '内容',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `sort` int(11) unsigned NOT NULL DEFAULT '50' COMMENT '排序',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='栏目表';

#
# Data for table "laravel_categorys"
#


#
# Structure for table "laravel_configs"
#

DROP TABLE IF EXISTS `laravel_configs`;
CREATE TABLE `laravel_configs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '类型:1单行文本,2多行文本,3单选按钮,4复选框,5下拉框',
  `value` varchar(255) DEFAULT '' COMMENT '值',
  `values` varchar(255) DEFAULT '' COMMENT '可选值',
  `sort` int(11) unsigned NOT NULL DEFAULT '50' COMMENT '排序',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='配置表';

#
# Data for table "laravel_configs"
#


#
# Structure for table "laravel_emails"
#

DROP TABLE IF EXISTS `laravel_emails`;
CREATE TABLE `laravel_emails` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `to` varchar(255) NOT NULL DEFAULT '' COMMENT '收件人',
  `subject` varchar(255) NOT NULL DEFAULT '' COMMENT '主题',
  `body` text NOT NULL COMMENT '内容',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='电子邮件表';

#
# Data for table "laravel_emails"
#


#
# Structure for table "laravel_group_rules"
#

DROP TABLE IF EXISTS `laravel_group_rules`;
CREATE TABLE `laravel_group_rules` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL DEFAULT '0' COMMENT '角色ID',
  `rule_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '权限ID',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8 COMMENT='角色权限表';

#
# Data for table "laravel_group_rules"
#

INSERT INTO `laravel_group_rules` VALUES (1,1,1,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(2,1,2,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(3,1,3,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(4,1,4,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(5,1,5,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(6,1,6,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(7,1,7,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(8,1,8,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(9,1,9,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(10,1,10,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(11,1,11,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(12,1,12,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(13,1,13,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(14,1,14,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(15,1,15,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(16,1,16,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(17,1,17,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(18,1,18,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(19,1,19,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(20,1,20,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(21,1,21,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(22,1,22,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(23,1,23,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(24,1,24,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(25,1,25,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(26,1,26,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(27,1,27,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(28,1,28,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(29,1,29,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(30,1,30,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(31,1,31,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(32,1,32,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(33,1,33,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(34,1,34,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(35,1,35,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(36,1,36,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(37,1,37,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(38,1,38,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(39,1,39,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(40,1,40,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(41,1,41,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(42,1,42,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(43,1,43,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(44,1,44,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(45,1,45,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(46,1,46,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(47,1,47,'2019-02-02 12:43:36','2019-02-02 12:43:36'),(48,2,1,'2019-02-02 12:44:28','2019-02-02 12:44:28'),(49,2,2,'2019-02-02 12:44:28','2019-02-02 12:44:28'),(50,2,3,'2019-02-02 12:44:28','2019-02-02 12:44:28'),(51,2,7,'2019-02-02 12:44:28','2019-02-02 12:44:28'),(52,2,8,'2019-02-02 12:44:28','2019-02-02 12:44:28'),(53,2,9,'2019-02-02 12:44:28','2019-02-02 12:44:28'),(54,2,13,'2019-02-02 12:44:28','2019-02-02 12:44:28'),(55,2,14,'2019-02-02 12:44:28','2019-02-02 12:44:28'),(56,2,15,'2019-02-02 12:44:28','2019-02-02 12:44:28'),(57,2,19,'2019-02-02 12:44:28','2019-02-02 12:44:28'),(58,2,20,'2019-02-02 12:44:28','2019-02-02 12:44:28'),(59,2,21,'2019-02-02 12:44:28','2019-02-02 12:44:28'),(60,2,25,'2019-02-02 12:44:28','2019-02-02 12:44:28'),(61,2,26,'2019-02-02 12:44:28','2019-02-02 12:44:28'),(62,2,27,'2019-02-02 12:44:28','2019-02-02 12:44:28'),(63,2,31,'2019-02-02 12:44:28','2019-02-02 12:44:28'),(64,2,32,'2019-02-02 12:44:28','2019-02-02 12:44:28'),(65,2,36,'2019-02-02 12:44:28','2019-02-02 12:44:28'),(66,2,37,'2019-02-02 12:44:28','2019-02-02 12:44:28'),(67,2,41,'2019-02-02 12:44:28','2019-02-02 12:44:28'),(68,2,42,'2019-02-02 12:44:28','2019-02-02 12:44:28'),(69,2,47,'2019-02-02 12:44:28','2019-02-02 12:44:28');

#
# Structure for table "laravel_groups"
#

DROP TABLE IF EXISTS `laravel_groups`;
CREATE TABLE `laravel_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态:0禁用,1正常',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='角色表';

#
# Data for table "laravel_groups"
#

INSERT INTO `laravel_groups` VALUES (1,'管理员组',1,NULL,NULL),(2,'观察者',1,'2019-02-02 12:44:28','2019-02-02 12:44:28');

#
# Structure for table "laravel_links"
#

DROP TABLE IF EXISTS `laravel_links`;
CREATE TABLE `laravel_links` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `sort` int(11) unsigned NOT NULL DEFAULT '50' COMMENT '排序',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='链接表';

#
# Data for table "laravel_links"
#


#
# Structure for table "laravel_rules"
#

DROP TABLE IF EXISTS `laravel_rules`;
CREATE TABLE `laravel_rules` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '规则名称',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '规则标题',
  `icon` varchar(255) DEFAULT NULL COMMENT '图标',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态:0禁用,1正常',
  `ismenu` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否为菜单:0不是,1是',
  `sort` int(11) unsigned NOT NULL DEFAULT '50' COMMENT '排序',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8 COMMENT='规则表';

#
# Data for table "laravel_rules"
#

INSERT INTO `laravel_rules` VALUES (1,'#','文章管理','fa-file-text',1,1,10,0,NULL,'2019-02-02 12:49:17'),(2,'admin/article','文章列表','fa-circle-o',1,1,50,1,NULL,'2019-02-02 12:49:17'),(3,'admin/infoArticle','查看文章','fa-circle-o',1,0,50,1,NULL,'2019-02-02 12:49:17'),(4,'admin/addArticle','添加文章','fa-circle-o',1,0,50,1,NULL,'2019-02-02 12:49:17'),(5,'admin/editArticle','编辑文章','fa-circle-o',1,0,50,1,NULL,'2019-02-02 12:49:17'),(6,'admin/delArticle','删除文章','fa-circle-o',1,0,50,1,NULL,'2019-02-02 12:49:17'),(7,'#','栏目管理','fa-align-justify',1,1,20,0,NULL,'2019-02-02 12:49:17'),(8,'admin/category','栏目列表','fa-circle-o',1,1,50,7,NULL,'2019-02-02 12:49:17'),(9,'admin/infoCategory','查看栏目','fa-circle-o',1,0,50,7,NULL,'2019-02-02 12:49:17'),(10,'admin/addCategory','添加栏目','fa-circle-o',1,0,50,7,NULL,'2019-02-02 12:49:17'),(11,'admin/editCategory','编辑栏目','fa-circle-o',1,0,50,7,NULL,'2019-02-02 12:49:17'),(12,'admin/delCategory','删除栏目','fa-circle-o',1,0,50,7,NULL,'2019-02-02 12:49:17'),(13,'#','链接管理','fa-link',1,1,30,0,NULL,'2019-02-02 12:49:17'),(14,'admin/link','链接列表','fa-circle-o',1,1,50,13,NULL,'2019-02-02 12:49:17'),(15,'admin/infoLink','查看链接','fa-circle-o',1,0,50,13,NULL,'2019-02-02 12:49:17'),(16,'admin/addLink','添加链接','fa-circle-o',1,0,50,13,NULL,'2019-02-02 12:57:33'),(17,'admin/editLink','编辑链接','fa-circle-o',1,0,50,13,NULL,'2019-02-02 12:57:33'),(18,'admin/delLink','删除链接','fa-circle-o',1,0,50,13,NULL,'2019-02-02 12:57:33'),(19,'#','邮件管理','fa-envelope',1,1,40,0,NULL,'2019-02-02 12:57:33'),(20,'admin/email','邮件列表','fa-circle-o',1,1,50,19,NULL,'2019-02-02 12:57:33'),(21,'admin/infoEmail','查看邮件','fa-circle-o',1,0,50,19,NULL,'2019-02-02 12:57:33'),(22,'admin/addEmail','添加邮件','fa-circle-o',1,0,50,19,NULL,'2019-02-02 12:57:33'),(23,'admin/editEmail','编辑邮件','fa-circle-o',1,0,50,19,NULL,'2019-02-02 12:57:33'),(24,'admin/delEmail','删除邮件','fa-circle-o',1,0,50,19,NULL,'2019-02-02 12:57:33'),(25,'#','用户管理','fa-users',1,1,50,0,NULL,'2019-02-02 12:57:33'),(26,'admin/rule','权限列表','fa-circle-o',1,1,30,25,NULL,'2019-02-02 12:57:33'),(27,'admin/infoRule','查看权限','fa-circle-o',1,0,50,25,NULL,'2019-02-02 12:57:33'),(28,'admin/addRule','添加权限','fa-circle-o',1,0,50,25,NULL,'2019-02-02 12:57:33'),(29,'admin/editRule','编辑权限','fa-circle-o',1,0,50,25,NULL,'2019-02-02 12:57:33'),(30,'admin/delRule','删除权限','fa-circle-o',1,0,50,25,NULL,'2019-02-02 12:57:33'),(31,'admin/group','角色列表','fa-circle-o',1,1,20,25,NULL,'2019-02-02 12:57:43'),(32,'admin/infoGroup','查看角色','fa-circle-o',1,0,50,25,NULL,'2019-02-02 12:57:43'),(33,'admin/addGroup','添加角色','fa-circle-o',1,0,50,25,NULL,'2019-02-02 12:57:43'),(34,'admin/editGroup','编辑角色','fa-circle-o',1,0,50,25,NULL,'2019-02-02 12:57:43'),(35,'admin/delGroup','删除角色','fa-circle-o',1,0,50,25,NULL,'2019-02-02 12:57:43'),(36,'admin/admin','用户列表','fa-circle-o',1,1,10,25,NULL,'2019-02-02 12:57:43'),(37,'admin/infoAdmin','查看用户','fa-circle-o',1,0,50,25,NULL,'2019-02-02 12:57:43'),(38,'admin/addAdmin','添加用户','fa-circle-o',1,0,50,25,NULL,'2019-02-02 12:57:43'),(39,'admin/editAdmin','编辑用户','fa-circle-o',1,0,50,25,NULL,'2019-02-02 12:57:43'),(40,'admin/delAdmin','删除用户','fa-circle-o',1,0,50,25,NULL,'2019-02-02 12:57:43'),(41,'#','系统管理','fa-gears',1,1,60,0,NULL,'2019-02-02 12:57:43'),(42,'admin/config','配置列表','fa-circle-o',1,1,50,41,NULL,'2019-02-02 12:57:43'),(43,'admin/infoConfig','查看配置','fa-circle-o',1,0,50,41,NULL,'2019-02-02 12:57:43'),(44,'admin/addConfig','添加配置','fa-circle-o',1,0,50,41,NULL,'2019-02-02 12:57:43'),(45,'admin/editConfig','编辑配置','fa-circle-o',1,0,50,41,NULL,'2019-02-02 12:57:43'),(46,'admin/delConfig','删除配置','fa-circle-o',1,0,50,41,NULL,NULL),(47,'admin/setting','配置管理','fa-circle-o',1,1,50,41,NULL,NULL);
