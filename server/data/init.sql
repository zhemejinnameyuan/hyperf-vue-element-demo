
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for casbin_rule
-- ----------------------------
DROP TABLE IF EXISTS `casbin_rule`;
CREATE TABLE `casbin_rule` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `ptype` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `v0` char(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `v1` char(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `v2` char(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `v3` char(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `v4` char(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `v5` char(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ptype_v_idx` (`ptype`,`v0`,`v1`,`v2`,`v3`,`v4`,`v5`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of casbin_rule
-- ----------------------------
BEGIN;
INSERT INTO `casbin_rule` VALUES (24, 'g', 'admin', '1', NULL, NULL, NULL, NULL);
INSERT INTO `casbin_rule` VALUES (23, 'g', 'test', '2', NULL, NULL, NULL, NULL);
INSERT INTO `casbin_rule` VALUES (18, 'p', '1', '/api/public/login', 'all', NULL, NULL, NULL);
INSERT INTO `casbin_rule` VALUES (1, 'p', '1', '/api/public/systeminfo', 'all', NULL, NULL, NULL);
INSERT INTO `casbin_rule` VALUES (15, 'p', '1', '/api/system/configdatalist', 'all', NULL, NULL, NULL);
INSERT INTO `casbin_rule` VALUES (17, 'p', '1', '/api/system/configdelete', 'all', NULL, NULL, NULL);
INSERT INTO `casbin_rule` VALUES (16, 'p', '1', '/api/system/configsave', 'all', NULL, NULL, NULL);
INSERT INTO `casbin_rule` VALUES (2, 'p', '1', '/api/system/menudatalist', 'all', NULL, NULL, NULL);
INSERT INTO `casbin_rule` VALUES (4, 'p', '1', '/api/system/menudelete', 'all', NULL, NULL, NULL);
INSERT INTO `casbin_rule` VALUES (3, 'p', '1', '/api/system/menusave', 'all', NULL, NULL, NULL);
INSERT INTO `casbin_rule` VALUES (19, 'p', '1', '/api/user/info', 'all', NULL, NULL, NULL);
INSERT INTO `casbin_rule` VALUES (12, 'p', '1', '/api/user/menutree', 'all', NULL, NULL, NULL);
INSERT INTO `casbin_rule` VALUES (14, 'p', '1', '/api/user/opbusinesstypedatalist', 'all', NULL, NULL, NULL);
INSERT INTO `casbin_rule` VALUES (13, 'p', '1', '/api/user/oplogdatalist', 'all', NULL, NULL, NULL);
INSERT INTO `casbin_rule` VALUES (5, 'p', '1', '/api/user/userdatalist', 'all', NULL, NULL, NULL);
INSERT INTO `casbin_rule` VALUES (6, 'p', '1', '/api/user/userdelete', 'all', NULL, NULL, NULL);
INSERT INTO `casbin_rule` VALUES (8, 'p', '1', '/api/user/usergroupdatalist', 'all', NULL, NULL, NULL);
INSERT INTO `casbin_rule` VALUES (9, 'p', '1', '/api/user/usergroupdelete', 'all', NULL, NULL, NULL);
INSERT INTO `casbin_rule` VALUES (11, 'p', '1', '/api/user/usergroupoptiondatalist', 'all', NULL, NULL, NULL);
INSERT INTO `casbin_rule` VALUES (10, 'p', '1', '/api/user/usergroupsave', 'all', NULL, NULL, NULL);
INSERT INTO `casbin_rule` VALUES (7, 'p', '1', '/api/user/usersave', 'all', NULL, NULL, NULL);
INSERT INTO `casbin_rule` VALUES (21, 'p', '2', '/api/public/login', 'all', NULL, NULL, NULL);
INSERT INTO `casbin_rule` VALUES (20, 'p', '2', '/api/public/systeminfo', 'all', NULL, NULL, NULL);
INSERT INTO `casbin_rule` VALUES (22, 'p', '2', '/api/user/info', 'all', NULL, NULL, NULL);
COMMIT;

-- ----------------------------
-- Table structure for op_log
-- ----------------------------
DROP TABLE IF EXISTS `op_log`;
CREATE TABLE `op_log` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT '??????ID',
  `op_userid` int unsigned NOT NULL COMMENT '????????????id',
  `op_username` varchar(32) NOT NULL DEFAULT '' COMMENT '???????????????',
  `business_type` int unsigned DEFAULT '0' COMMENT '????????????',
  `business_id` char(255) DEFAULT NULL COMMENT '??????id',
  `content` text COMMENT '????????????',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '????????????',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '????????????',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `name_idx` (`op_username`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='??????????????????????????????'
/*!50100 PARTITION BY LIST ((`id` % 10))
(PARTITION p0 VALUES IN (0) ENGINE = InnoDB,
 PARTITION p1 VALUES IN (1) ENGINE = InnoDB,
 PARTITION p2 VALUES IN (2) ENGINE = InnoDB,
 PARTITION p3 VALUES IN (3) ENGINE = InnoDB,
 PARTITION p4 VALUES IN (4) ENGINE = InnoDB,
 PARTITION p5 VALUES IN (5) ENGINE = InnoDB,
 PARTITION p6 VALUES IN (6) ENGINE = InnoDB,
 PARTITION p7 VALUES IN (7) ENGINE = InnoDB,
 PARTITION p8 VALUES IN (8) ENGINE = InnoDB,
 PARTITION p9 VALUES IN (9) ENGINE = InnoDB) */;

-- ----------------------------
-- Table structure for site_config
-- ----------------------------
DROP TABLE IF EXISTS `site_config`;
CREATE TABLE `site_config` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '????????????',
  `english_name` char(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '??????????????????',
  `url` char(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '????????????',
  `type_id` int DEFAULT NULL COMMENT '????????????',
  `is_login` tinyint DEFAULT '0' COMMENT '????????????????????????,0_?????????,1_??????',
  `logo` char(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '??????logo',
  `login_cookie` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '????????????,??????cookie',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '??????,0_??????,1_??????',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '????????????',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '????????????',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `english_name_unique` (`english_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='???????????????';

-- ----------------------------
-- Records of site_config
-- ----------------------------
BEGIN;
INSERT INTO `site_config` VALUES (1, '??????', 'zhihu', 'https://www.zhihu.com/hot', 2, 1, '', '', 1, '2020-09-11 16:44:56', '2020-12-29 17:30:13');
INSERT INTO `site_config` VALUES (2, '??????', 'weibo', 'https://s.weibo.com/top/summary', 1, 0, '', NULL, 1, '2020-09-11 18:12:35', '2021-01-03 11:48:27');
INSERT INTO `site_config` VALUES (3, 'it??????', 'ithome', 'https://www.ithome.com/', 1, 0, '', NULL, 1, '2020-09-11 18:16:07', '2021-01-03 11:48:41');
INSERT INTO `site_config` VALUES (4, 'v2ex', 'v2ex', 'https://www.v2ex.com/?tab=hot', 5, 0, '', NULL, 1, '2020-09-11 18:16:21', '2020-09-11 18:16:21');
INSERT INTO `site_config` VALUES (5, '??????', 'tieba', 'http://tieba.baidu.com/hottopic/browse/topicList', 5, 0, '', NULL, 1, '2020-09-11 18:16:37', '2021-01-03 21:04:48');
INSERT INTO `site_config` VALUES (6, '??????', 'douban', 'https://www.douban.com/group/explore', 1, 0, '', NULL, 1, '2020-09-11 18:16:50', '2021-01-03 11:54:20');
INSERT INTO `site_config` VALUES (7, '??????', 'tianya', 'http://bbs.tianya.cn/list.jsp?item=funinfo&grade=3&order=1', 5, 0, '', NULL, 1, '2020-09-11 18:17:04', '2021-01-03 11:49:19');
INSERT INTO `site_config` VALUES (8, '??????', 'hupu', 'https://bbs.hupu.com/all-gambia', 10, 0, '', NULL, 1, '2020-09-11 18:17:35', '2021-01-03 20:55:54');
INSERT INTO `site_config` VALUES (9, 'Github', 'Github', 'https://github.com/trending', 6, 0, '', NULL, 1, '2020-09-11 18:18:00', '2021-01-03 20:36:58');
INSERT INTO `site_config` VALUES (10, '??????', 'baidu', 'http://top.baidu.com/buzz?b=341&c=513&fr=topbuzz_b1', 1, 0, '', NULL, 1, '2020-09-11 18:18:19', '2021-01-03 11:49:43');
INSERT INTO `site_config` VALUES (11, '36???', 'sanliukr', 'https://36kr.com/', 6, 0, '', NULL, 1, '2020-09-11 18:18:38', '2020-12-29 20:11:59');
INSERT INTO `site_config` VALUES (12, '?????????', 'qdaily', 'https://www.qdaily.com/tags/29.html', 1, 0, '', NULL, 1, '2020-09-11 18:18:57', '2021-01-03 21:00:49');
INSERT INTO `site_config` VALUES (13, '??????', 'guokr', 'https://www.guokr.com', 2, 0, '', NULL, 1, '2020-09-11 18:19:13', '2021-01-03 17:36:02');
INSERT INTO `site_config` VALUES (14, '??????', 'huxiu', 'https://www.huxiu.com/article', 2, 0, '', NULL, 1, '2020-09-11 18:19:27', '2021-01-03 14:46:34');
INSERT INTO `site_config` VALUES (15, 'segmentfault', 'segmentfault', 'https://segmentfault.com/hottest', 6, 0, '', NULL, 1, '2020-09-11 18:19:50', '2021-01-03 12:48:07');
INSERT INTO `site_config` VALUES (16, 'csdn', 'csdn', 'https://blog.csdn.net/rank/list', 6, 0, '', NULL, 1, '2020-09-11 18:20:24', '2021-01-03 12:24:09');
INSERT INTO `site_config` VALUES (17, '???????????????', 'weixin', 'https://weixin.sogou.com/?pid=sogou-wsse-721e049e9903c3a7&kw=', 3, 0, '', NULL, 1, '2020-09-11 20:11:45', '2022-09-28 12:41:48');
COMMIT;

-- ----------------------------
-- Table structure for site_type
-- ----------------------------
DROP TABLE IF EXISTS `site_type`;
CREATE TABLE `site_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '????????????',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '??????,0_??????,1_??????',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '????????????',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '????????????',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='???????????????';

-- ----------------------------
-- Records of site_type
-- ----------------------------
BEGIN;
INSERT INTO `site_type` VALUES (1, '??????', 1, '2020-09-11 07:42:10', '2020-09-16 17:30:31');
INSERT INTO `site_type` VALUES (2, '??????', 1, '2020-09-11 07:42:10', '2020-09-11 07:42:10');
INSERT INTO `site_type` VALUES (3, '?????????', 1, '2020-09-11 07:42:11', '2020-09-11 07:42:11');
INSERT INTO `site_type` VALUES (4, '??????', 1, '2020-09-11 14:43:01', '2020-09-11 14:43:03');
INSERT INTO `site_type` VALUES (5, '??????', 1, '2020-09-11 07:43:27', '2020-09-16 17:32:54');
INSERT INTO `site_type` VALUES (6, 'IT', 1, '2020-09-11 07:44:12', '2020-09-16 17:29:59');
INSERT INTO `site_type` VALUES (7, '??????', 1, '2020-09-11 07:44:22', '2020-09-11 07:44:22');
INSERT INTO `site_type` VALUES (8, '??????', 1, '2020-09-11 07:44:35', '2022-07-29 17:03:11');
INSERT INTO `site_type` VALUES (9, '??????', 1, '2020-09-11 07:44:41', '2020-09-16 17:29:12');
INSERT INTO `site_type` VALUES (10, '??????', 1, '2020-09-11 17:31:10', '2022-09-28 12:41:44');
COMMIT;

-- ----------------------------
-- Table structure for sys_config
-- ----------------------------
DROP TABLE IF EXISTS `sys_config`;
CREATE TABLE `sys_config` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT '??????ID',
  `key` varchar(255) DEFAULT '' COMMENT '??????key',
  `value` varchar(1024) DEFAULT NULL COMMENT '?????????',
  `desc` varchar(255) DEFAULT '' COMMENT '??????',
  `status` tinyint DEFAULT '0' COMMENT '??????,0_?????????,1_??????',
  `type` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '????????????(text???json)',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '????????????',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '????????????',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COMMENT='??????????????????';

-- ----------------------------
-- Records of sys_config
-- ----------------------------
BEGIN;
INSERT INTO `sys_config` VALUES (2, 'site_name', 'admin', '????????????', 1, 'text', '2022-07-22 16:18:26', '2022-09-29 10:52:18');
COMMIT;

-- ----------------------------
-- Table structure for sys_menu
-- ----------------------------
DROP TABLE IF EXISTS `sys_menu`;
CREATE TABLE `sys_menu` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `pid` int unsigned DEFAULT '0' COMMENT '??????ID',
  `title` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '??????',
  `name` char(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT 'vue????????????',
  `type` tinyint unsigned DEFAULT NULL COMMENT '??????,1-??????,2-??????',
  `path` char(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '??????path',
  `api_path` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT '??????api??????,????????????????????????',
  `icon` char(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '??????',
  `component` char(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '????????????',
  `redirect` char(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '?????????',
  `sort` int unsigned DEFAULT NULL COMMENT '??????',
  `status` tinyint DEFAULT '1' COMMENT '??????,1-??????,0-??????',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '????????????',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '????????????',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='?????????';

-- ----------------------------
-- Records of sys_menu
-- ----------------------------
BEGIN;
INSERT INTO `sys_menu` VALUES (1, 0, '??????', '', 1, '/dashboard', '', 'dashboard', 'Layout', '', 1, 1, '2020-10-22 14:41:11', '2022-10-19 12:18:36');
INSERT INTO `sys_menu` VALUES (2, 0, '????????????', '', 1, '/system', NULL, 'el-icon-setting', 'Layout', '', 2, 1, '2020-10-22 14:41:11', '2022-09-28 10:15:18');
INSERT INTO `sys_menu` VALUES (3, 2, '????????????', 'SystemMenuList', 1, 'menu', '/api/system/menu', 'el-icon-menu', 'system/menu/list', '', 0, 1, '2020-10-22 14:41:11', '2022-10-03 11:05:25');
INSERT INTO `sys_menu` VALUES (4, 2, '????????????', 'SystemUserList', 1, 'user', '/api/system/user', 'el-icon-user', 'system/user/list', '', 9, 1, '2020-10-22 22:42:49', '2022-10-03 16:33:51');
INSERT INTO `sys_menu` VALUES (5, 1, '????????????', 'Dashboard', 1, 'dashboard', '/api/public/systemInfo\n', 'dashboard', 'dashboard/index', '', 0, 1, '2020-11-04 17:27:14', '2022-10-03 16:31:45');
INSERT INTO `sys_menu` VALUES (6, 2, '????????????', 'SystemAuthGroupList', 1, 'authGroup', '/api/system/authGroup\n/api/system/authGroup/optionList\n/api/system/authGroup/menuTree\n/api/system/authGroup/refreshNode', 'el-icon-s-check', 'system/authGroup/list', '', 2, 1, '2020-11-06 21:05:53', '2022-10-03 16:33:27');
INSERT INTO `sys_menu` VALUES (7, 2, '????????????', 'SystemOperateLogList', 1, 'operateLog', '/api/system/operateLog\n/api/system/operateLog/businessType', 'el-icon-tickets', 'system/operateLog/list', NULL, 99, 1, '2020-11-10 17:17:07', '2022-10-09 17:18:56');
INSERT INTO `sys_menu` VALUES (23, 2, '????????????', 'SystemConfigList', 1, 'config', '/api/system/config', 'el-icon-setting', 'system/config/list', NULL, 9, 1, '2020-11-12 12:05:40', '2022-10-03 16:35:05');
INSERT INTO `sys_menu` VALUES (24, 0, 'Demo', '', 1, '/demo', NULL, ' el-icon-magic-stick', 'Layout', NULL, 3, 1, '2020-11-13 11:22:30', '2022-09-28 10:15:26');
INSERT INTO `sys_menu` VALUES (25, 24, '????????????', 'DemoSuggestions', 1, 'suggestions', NULL, 'el-icon-s-order', 'demo/suggestions', NULL, 0, 1, '2020-11-13 11:30:24', '2020-11-13 11:30:24');
INSERT INTO `sys_menu` VALUES (27, 24, '??????', 'DemoSign', 1, 'sign', NULL, 'el-icon-s-order', 'demo/sign', NULL, 0, 1, '2020-11-23 15:14:43', '2020-11-23 15:14:51');
INSERT INTO `sys_menu` VALUES (28, 24, 'AES??????', 'DemoAes', 1, 'aes', NULL, 'el-icon-s-order', 'demo/aes', NULL, 0, 1, '2020-11-26 22:06:38', '2020-11-26 22:06:51');
INSERT INTO `sys_menu` VALUES (30, 0, '????????????', '', 1, '/hotArticle', NULL, 'el-icon-magic-stick', 'Layout', NULL, 4, 1, '2020-12-29 12:23:17', '2022-09-28 17:43:35');
INSERT INTO `sys_menu` VALUES (31, 30, '????????????', 'HotArticleType', 1, 'type', '/api/hotArticle/site\n/api/hotArticle/site/optionList ', 'el-icon-s-order', 'hotArticle/type', NULL, 0, 1, '2020-12-29 12:24:15', '2022-10-03 16:36:42');
INSERT INTO `sys_menu` VALUES (32, 30, '????????????', 'HotArticleSite', 1, 'site', '/api/hotArticle/site\n/api/hotArticle/site/runSite', 'el-icon-s-order', 'hotArticle/site', NULL, 0, 1, '2020-12-29 12:35:30', '2022-10-03 16:37:14');
INSERT INTO `sys_menu` VALUES (35, 3, '????????????', '', 2, '/', NULL, NULL, 'system:menu:add', NULL, 0, 1, '2022-10-19 15:53:16', '2022-10-19 15:56:38');
INSERT INTO `sys_menu` VALUES (36, 3, '????????????', '', 2, '/', NULL, NULL, 'system:menu:edit', NULL, 0, 1, '2022-10-19 15:56:34', '2022-10-19 15:56:34');
INSERT INTO `sys_menu` VALUES (37, 3, '????????????', '', 2, '/', NULL, NULL, 'system:menu:delete', NULL, 0, 1, '2022-10-19 15:57:10', '2022-10-19 15:57:10');
COMMIT;

-- ----------------------------
-- Table structure for sys_user
-- ----------------------------
DROP TABLE IF EXISTS `sys_user`;
CREATE TABLE `sys_user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` char(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '?????????',
  `password` char(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '??????',
  `nickname` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '??????',
  `group_id` int DEFAULT '0' COMMENT '??????ID',
  `status` tinyint DEFAULT '0' COMMENT '??????,1-??????,0-??????',
  `password_error_count` tinyint DEFAULT '0' COMMENT '????????????????????????',
  `login_ip` char(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '????????????IP',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '????????????',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '????????????',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `username_idx` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='?????????';

-- ----------------------------
-- Records of sys_user
-- ----------------------------
BEGIN;
INSERT INTO `sys_user` VALUES (1, 'admin', '$2y$10$YIquEXxiefeUBUKzDKJUcOsrTvjiY0DC0O1xzcdq.7KnFWOt8jt6O', 'admin', 1, 1, 4, '127.0.0.1', '2020-10-28 12:17:24', '2022-09-29 11:21:54');
INSERT INTO `sys_user` VALUES (2, 'test', '$2y$10$fh/3vWD7ubjOtiEvBYRTw.RaTJ5SnVcro6YH9VcJf0Y/XyFAsirxu', 'test11', 2, 1, 2, '127.0.0.1', '2020-10-28 12:53:52', '2022-09-29 11:21:52');
COMMIT;

-- ----------------------------
-- Table structure for sys_user_group
-- ----------------------------
DROP TABLE IF EXISTS `sys_user_group`;
CREATE TABLE `sys_user_group` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '??????',
  `menu_ids` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT '????????????',
  `status` tinyint DEFAULT '1' COMMENT '??????,1-??????,0-??????',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '????????????',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '????????????',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='?????????';

-- ----------------------------
-- Records of sys_user_group
-- ----------------------------
BEGIN;
INSERT INTO `sys_user_group` VALUES (1, '?????????', '34,1,5,2,3,6,23,7,4,24,25,29,28,27,26,30,32,31', 1, '2020-10-30 10:18:36', '2022-09-29 11:21:42');
INSERT INTO `sys_user_group` VALUES (2, '??????', '34,1,5', 1, '2020-10-30 10:18:50', '2022-09-29 11:21:48');
COMMIT;

-- ----------------------------
-- Table structure for top_article
-- ----------------------------
DROP TABLE IF EXISTS `top_article`;
CREATE TABLE `top_article` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '????????????',
  `url` char(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '????????????',
  `site_id` int DEFAULT NULL COMMENT '??????ID,??????site_config??????id',
  `desc` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '??????,???????????????,????????????,?????????',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '????????????',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '????????????',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `site_id_and_url_idx` (`site_id`,`url`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1385 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='?????????';

SET FOREIGN_KEY_CHECKS = 1;
