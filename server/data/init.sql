CREATE TABLE `op_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `op_userid` int(10) unsigned NOT NULL COMMENT '操作用户id',
  `op_username` varchar(32) NOT NULL DEFAULT '' COMMENT '操作用户名',
  `business_type` int(10) unsigned DEFAULT '0' COMMENT '业务类型',
  `business_id` char(255) DEFAULT NULL COMMENT '业务id',
  `content` text COMMENT '业务内容',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='后台用户操作日志记录';

CREATE TABLE `sys_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `key` varchar(255) DEFAULT '' COMMENT '配置key',
  `value` varchar(1024) DEFAULT NULL COMMENT '配置值',
  `desc` varchar(255) DEFAULT '' COMMENT '描述',
  `status` tinyint(4) DEFAULT '0' COMMENT '状态,0_未启用,1_启用',
  `type` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '配置类型(text和json)',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='后台用配置表';


CREATE TABLE `sys_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned DEFAULT '0' COMMENT '父级ID',
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '名称',
  `path` char(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '路由path',
  `icon` char(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '图标',
  `component` char(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '视图组件',
  `redirect` char(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '重定向',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态,1-启用,0-禁用',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='菜单表';

INSERT INTO `hyperf_admin`.`sys_menu`(`id`, `pid`, `name`, `path`, `icon`, `component`, `redirect`, `status`, `created_at`, `updated_at`) VALUES (1, 0, 'Dashboard ', '/', 'dashboard', 'Layout', '/dashboard', 1, '2020-10-22 14:41:11', '2020-11-15 17:21:48');
INSERT INTO `hyperf_admin`.`sys_menu`(`id`, `pid`, `name`, `path`, `icon`, `component`, `redirect`, `status`, `created_at`, `updated_at`) VALUES (2, 0, '系统设置', '/system', 'el-icon-setting', 'Layout', '', 1, '2020-10-22 14:41:11', '2020-10-28 18:18:34');
INSERT INTO `hyperf_admin`.`sys_menu`(`id`, `pid`, `name`, `path`, `icon`, `component`, `redirect`, `status`, `created_at`, `updated_at`) VALUES (3, 2, '菜单管理', 'menu', 'el-icon-menu', 'system/menu/list', '', 1, '2020-10-22 14:41:11', '2020-10-22 14:41:16');
INSERT INTO `hyperf_admin`.`sys_menu`(`id`, `pid`, `name`, `path`, `icon`, `component`, `redirect`, `status`, `created_at`, `updated_at`) VALUES (4, 2, '用户管理', 'user', 'el-icon-user', 'system/user/list', '', 1, '2020-10-22 22:42:49', '2020-10-22 22:42:49');
INSERT INTO `hyperf_admin`.`sys_menu`(`id`, `pid`, `name`, `path`, `icon`, `component`, `redirect`, `status`, `created_at`, `updated_at`) VALUES (5, 1, '系统信息', 'dashboard', 'dashboard', 'dashboard/index', '', 1, '2020-11-04 17:27:14', '2020-11-04 17:27:14');
INSERT INTO `hyperf_admin`.`sys_menu`(`id`, `pid`, `name`, `path`, `icon`, `component`, `redirect`, `status`, `created_at`, `updated_at`) VALUES (6, 2, '权限分组', 'group', 'el-icon-s-check', 'system/group/list', '', 1, '2020-11-06 21:05:53', '2020-11-06 21:06:48');
INSERT INTO `hyperf_admin`.`sys_menu`(`id`, `pid`, `name`, `path`, `icon`, `component`, `redirect`, `status`, `created_at`, `updated_at`) VALUES (7, 2, '操作日志', 'opLog', 'el-icon-tickets', 'system/opLog/list', NULL, 1, '2020-11-10 17:17:07', '2020-11-10 17:17:19');
INSERT INTO `hyperf_admin`.`sys_menu`(`id`, `pid`, `name`, `path`, `icon`, `component`, `redirect`, `status`, `created_at`, `updated_at`) VALUES (23, 2, '配置管理', 'config', 'el-icon-setting', 'system/config/list', NULL, 1, '2020-11-12 12:05:40', '2020-11-15 17:24:51');
INSERT INTO `hyperf_admin`.`sys_menu`(`id`, `pid`, `name`, `path`, `icon`, `component`, `redirect`, `status`, `created_at`, `updated_at`) VALUES (24, 0, 'Demo', '/demo', ' el-icon-magic-stick', 'Layout', NULL, 1, '2020-11-13 11:22:30', '2020-11-13 11:23:59');
INSERT INTO `hyperf_admin`.`sys_menu`(`id`, `pid`, `name`, `path`, `icon`, `component`, `redirect`, `status`, `created_at`, `updated_at`) VALUES (25, 24, '输入建议', 'suggestions', 'el-icon-s-order', 'demo/suggestions', NULL, 1, '2020-11-13 11:30:24', '2020-11-13 11:30:24');
INSERT INTO `hyperf_admin`.`sys_menu`(`id`, `pid`, `name`, `path`, `icon`, `component`, `redirect`, `status`, `created_at`, `updated_at`) VALUES (26, 24, '转盘抽奖', 'lottery', 'el-icon-s-order', 'demo/lottery', NULL, 1, '2020-11-21 14:54:44', '2020-11-21 14:55:12');
INSERT INTO `hyperf_admin`.`sys_menu`(`id`, `pid`, `name`, `path`, `icon`, `component`, `redirect`, `status`, `created_at`, `updated_at`) VALUES (27, 24, '签到', 'sign', 'el-icon-s-order', 'demo/sign', NULL, 1, '2020-11-23 15:14:43', '2020-11-23 15:14:51');
INSERT INTO `hyperf_admin`.`sys_menu`(`id`, `pid`, `name`, `path`, `icon`, `component`, `redirect`, `status`, `created_at`, `updated_at`) VALUES (28, 24, 'AES解密', 'aes', 'el-icon-s-order', 'demo/aes', NULL, 1, '2020-11-26 22:06:38', '2020-11-26 22:06:51');
INSERT INTO `hyperf_admin`.`sys_menu`(`id`, `pid`, `name`, `path`, `icon`, `component`, `redirect`, `status`, `created_at`, `updated_at`) VALUES (29, 24, '基金持仓', 'fund', 'el-icon-s-order', 'demo/fund', NULL, 1, '2020-12-07 16:04:31', '2020-12-07 16:04:39');
INSERT INTO `hyperf_admin`.`sys_menu`(`id`, `pid`, `name`, `path`, `icon`, `component`, `redirect`, `status`, `created_at`, `updated_at`) VALUES (30, 0, '今日热榜', '/hotArticle', 'el-icon-magic-stick', 'Layout', NULL, 1, '2020-12-29 12:23:17', '2020-12-29 12:23:42');
INSERT INTO `hyperf_admin`.`sys_menu`(`id`, `pid`, `name`, `path`, `icon`, `component`, `redirect`, `status`, `created_at`, `updated_at`) VALUES (31, 30, '网站分类', 'siteType', 'el-icon-s-order', 'hotArticle/siteType', NULL, 1, '2020-12-29 12:24:15', '2020-12-29 12:24:42');
INSERT INTO `hyperf_admin`.`sys_menu`(`id`, `pid`, `name`, `path`, `icon`, `component`, `redirect`, `status`, `created_at`, `updated_at`) VALUES (32, 30, '网站管理', 'siteConf', 'el-icon-s-order', 'hotArticle/siteConfig', NULL, 1, '2020-12-29 12:35:30', '2020-12-29 12:35:30');


CREATE TABLE `sys_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` char(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '用户名',
  `password` char(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '密码',
  `nickname` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '昵称',
  `group_id` int(10) DEFAULT '0' COMMENT '分组ID',
  `status` tinyint(4) DEFAULT '0' COMMENT '状态,1-启用,0-禁用',
  `password_error_count` tinyint(4) DEFAULT '0' COMMENT '密码输入错误次数',
  `login_ip` char(32) COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '最后登录IP',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `username_idx` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='用户表';


INSERT INTO `hyperf_admin`.`sys_user`(`id`, `username`, `password`, `nickname`, `group_id`, `status`, `password_error_count`, `login_ip`, `created_at`, `updated_at`) VALUES (1, 'admin', '1a3c5857a7adcb3f0e672f0320e287aa', 'admin', 1, 1, 0, '127.0.0.1', '2020-10-28 12:17:24', '2020-11-11 15:30:47');
INSERT INTO `hyperf_admin`.`sys_user`(`id`, `username`, `password`, `nickname`, `group_id`, `status`, `password_error_count`, `login_ip`, `created_at`, `updated_at`) VALUES (2, 'test', '3d739f565c3878f469a2a414af1bf5ef', 'test11', 2, 1, 0, '127.0.0.1', '2020-10-28 12:53:52', '2020-11-11 15:31:15');
INSERT INTO `hyperf_admin`.`sys_user`(`id`, `username`, `password`, `nickname`, `group_id`, `status`, `password_error_count`, `login_ip`, `created_at`, `updated_at`) VALUES (3, 'boss', '3d739f565c3878f469a2a414af1bf5ef', 'boss', 3, 1, 1, '127.0.0.1', '2020-11-06 20:29:43', '2020-11-11 15:30:58');


CREATE TABLE `sys_user_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '' COMMENT '名称',
  `menu_ids` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT '菜单节点',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态,1-启用,0-禁用',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='菜单表';

INSERT INTO `hyperf_admin`.`sys_user_group`(`id`, `name`, `menu_ids`, `status`, `created_at`, `updated_at`) VALUES (1, '管理员', '1,5,2,3,4,6', 1, '2020-10-30 10:18:36', '2020-11-10 11:44:33');
INSERT INTO `hyperf_admin`.`sys_user_group`(`id`, `name`, `menu_ids`, `status`, `created_at`, `updated_at`) VALUES (2, '测试', '1,5,2,3,6', 1, '2020-10-30 10:18:50', '2020-11-09 22:21:27');
INSERT INTO `hyperf_admin`.`sys_user_group`(`id`, `name`, `menu_ids`, `status`, `created_at`, `updated_at`) VALUES (3, '产品', '1,5,3', 1, '2020-10-30 10:19:14', '2020-11-09 22:21:34');

CREATE TABLE `site_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '网站名称',
  `english_name` char(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '网站英文名称',
  `url` char(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '网站地址',
  `type_id` int(11) DEFAULT NULL COMMENT '网站类型',
  `is_login` tinyint(4) DEFAULT '0' COMMENT '是否需要登录凭证,0_不需要,1_需要',
  `logo` char(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '网站logo',
  `login_cookie` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '登录凭证,存放cookie',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态,0_禁用,1_启用',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `english_name_unique` (`english_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='网站配置表';


INSERT INTO `hyperf_admin`.`site_config`(`id`, `name`, `english_name`, `url`, `type_id`, `is_login`, `logo`, `login_cookie`, `status`, `created_at`, `updated_at`) VALUES (1, '知乎', 'zhihu', 'https://www.zhihu.com/hot', 2, 1, '', '_zap=f0e5c325-63a6-4f64-9964-a6e08ba35ea6; d_c0=\"AODnu7ofdA-PThC4t5A6dVg4Ws70Qb92pqo=|1558270983\"; __gads=ID=22541f247e27c6b6:T=1558444838:S=ALNI_MbwjnMqNiRULqpil1KfcGh32_Dvmg; __utmv=51854390.000--|3=entry_date=20190519=1; _ga=GA1.2.1816932802.1563285349; __utma=51854390.1816932802.1563285349.1563285349.1567520404.2; q_c1=89b7754ceafa4314a8aaed7173d1726d|1582193662000|1558270985000; _xsrf=4653cc58-8fd9-4769-ab26-79c5253257d6; _gid=GA1.2.91324763.1600057796; Hm_lvt_98beee57fd2ef70ccdd5ca52b9740c49=1600068561,1600074295,1600091302,1600141413; SESSIONID=KEHGGui7ClbZtPdklxFDAedPs3Mns6OJ7wQ80eJRSog; capsion_ticket=\"2|1:0|10:1600141412|14:capsion_ticket|44:ODE0Y2NmOTdiNmMwNGNiYThkY2NiOTI2Y2IwNWE1MzU=|6541fb4fdacd1d5eae77c3098bda1ff0b4d97f5ae3c43c6806761428edfb101d\"; JOID=VFoQAE1M05Ho4z7WcUkHBPlwe65jA5HfqqRPuEAPu_S0km62HSvq8rLnPddyiCX2X370FELMaTSWlJ5wGsDnUqw=; osd=U1oVBElL05Ts5znWdE0DA_l1f6pkA5TbrqNPvUQLvPSxlmqxHS7u9rXnONN2jyXzW3rzFEfIbTOWkZp0HcDiVqg=; z_c0=\"2|1:0|10:1600141487|4:z_c0|92:Mi4xd2V3V0VnQUFBQUFBNE9lN3VoOTBEeVlBQUFCZ0FsVk5yNFpOWUFBX2Q1bVJCbXFTS0xZVUYtNWlRTG1fVzEzT3RR|40be796862d4fd8fae60a24455efcdf597f83b9de3fda77d459d9648ae846fee\"; unlock_ticket=\"APDjJau7_g8mAAAAYAJVTbc_YF-skR88QE1gy4JDbZE4yXMzm9i3jg==\"; Hm_lpvt_98beee57fd2ef70ccdd5ca52b9740c49=1600142072; tshl=; tst=h; KLBRSID=57358d62405ef24305120316801fd92a|1600142214|1600141407', 1, '2020-09-11 16:44:56', '2020-12-29 17:30:13');
INSERT INTO `hyperf_admin`.`site_config`(`id`, `name`, `english_name`, `url`, `type_id`, `is_login`, `logo`, `login_cookie`, `status`, `created_at`, `updated_at`) VALUES (2, '微博', 'weibo', 'https://s.weibo.com/top/summary', 1, 0, '', NULL, 1, '2020-09-11 18:12:35', '2021-01-03 11:48:27');
INSERT INTO `hyperf_admin`.`site_config`(`id`, `name`, `english_name`, `url`, `type_id`, `is_login`, `logo`, `login_cookie`, `status`, `created_at`, `updated_at`) VALUES (3, 'it之家', 'ithome', 'https://www.ithome.com/', 1, 0, '', NULL, 1, '2020-09-11 18:16:07', '2021-01-03 11:48:41');
INSERT INTO `hyperf_admin`.`site_config`(`id`, `name`, `english_name`, `url`, `type_id`, `is_login`, `logo`, `login_cookie`, `status`, `created_at`, `updated_at`) VALUES (4, 'v2ex', 'v2ex', 'https://www.v2ex.com/?tab=hot', 5, 0, '', NULL, 1, '2020-09-11 18:16:21', '2020-09-11 18:16:21');
INSERT INTO `hyperf_admin`.`site_config`(`id`, `name`, `english_name`, `url`, `type_id`, `is_login`, `logo`, `login_cookie`, `status`, `created_at`, `updated_at`) VALUES (5, '贴吧', 'tieba', 'http://tieba.baidu.com/hottopic/browse/topicList', 5, 0, '', NULL, 1, '2020-09-11 18:16:37', '2021-01-03 21:04:48');
INSERT INTO `hyperf_admin`.`site_config`(`id`, `name`, `english_name`, `url`, `type_id`, `is_login`, `logo`, `login_cookie`, `status`, `created_at`, `updated_at`) VALUES (6, '豆瓣', 'douban', 'https://www.douban.com/group/explore', 1, 0, '', NULL, 1, '2020-09-11 18:16:50', '2021-01-03 11:54:20');
INSERT INTO `hyperf_admin`.`site_config`(`id`, `name`, `english_name`, `url`, `type_id`, `is_login`, `logo`, `login_cookie`, `status`, `created_at`, `updated_at`) VALUES (7, '天涯', 'tianya', 'http://bbs.tianya.cn/list.jsp?item=funinfo&grade=3&order=1', 5, 0, '', NULL, 1, '2020-09-11 18:17:04', '2021-01-03 11:49:19');
INSERT INTO `hyperf_admin`.`site_config`(`id`, `name`, `english_name`, `url`, `type_id`, `is_login`, `logo`, `login_cookie`, `status`, `created_at`, `updated_at`) VALUES (8, '虎扑', 'hupu', 'https://bbs.hupu.com/all-gambia', 10, 0, '', NULL, 1, '2020-09-11 18:17:35', '2021-01-03 20:55:54');
INSERT INTO `hyperf_admin`.`site_config`(`id`, `name`, `english_name`, `url`, `type_id`, `is_login`, `logo`, `login_cookie`, `status`, `created_at`, `updated_at`) VALUES (9, 'Github', 'Github', 'https://github.com/trending', 6, 0, '', NULL, 1, '2020-09-11 18:18:00', '2021-01-03 20:36:58');
INSERT INTO `hyperf_admin`.`site_config`(`id`, `name`, `english_name`, `url`, `type_id`, `is_login`, `logo`, `login_cookie`, `status`, `created_at`, `updated_at`) VALUES (10, '百度', 'baidu', 'http://top.baidu.com/buzz?b=341&c=513&fr=topbuzz_b1', 1, 0, '', NULL, 1, '2020-09-11 18:18:19', '2021-01-03 11:49:43');
INSERT INTO `hyperf_admin`.`site_config`(`id`, `name`, `english_name`, `url`, `type_id`, `is_login`, `logo`, `login_cookie`, `status`, `created_at`, `updated_at`) VALUES (11, '36氪', 'sanliukr', 'https://36kr.com/', 6, 0, '', NULL, 1, '2020-09-11 18:18:38', '2020-12-29 20:11:59');
INSERT INTO `hyperf_admin`.`site_config`(`id`, `name`, `english_name`, `url`, `type_id`, `is_login`, `logo`, `login_cookie`, `status`, `created_at`, `updated_at`) VALUES (12, '好奇心', 'qdaily', 'https://www.qdaily.com/tags/29.html', 1, 0, '', NULL, 1, '2020-09-11 18:18:57', '2021-01-03 21:00:49');
INSERT INTO `hyperf_admin`.`site_config`(`id`, `name`, `english_name`, `url`, `type_id`, `is_login`, `logo`, `login_cookie`, `status`, `created_at`, `updated_at`) VALUES (13, '果壳', 'guokr', 'https://www.guokr.com', 2, 0, '', NULL, 1, '2020-09-11 18:19:13', '2021-01-03 17:36:02');
INSERT INTO `hyperf_admin`.`site_config`(`id`, `name`, `english_name`, `url`, `type_id`, `is_login`, `logo`, `login_cookie`, `status`, `created_at`, `updated_at`) VALUES (14, '虎嗅', 'huxiu', 'https://www.huxiu.com/article', 2, 0, '', NULL, 1, '2020-09-11 18:19:27', '2021-01-03 14:46:34');
INSERT INTO `hyperf_admin`.`site_config`(`id`, `name`, `english_name`, `url`, `type_id`, `is_login`, `logo`, `login_cookie`, `status`, `created_at`, `updated_at`) VALUES (15, 'segmentfault', 'segmentfault', 'https://segmentfault.com/hottest', 6, 0, '', NULL, 1, '2020-09-11 18:19:50', '2021-01-03 12:48:07');
INSERT INTO `hyperf_admin`.`site_config`(`id`, `name`, `english_name`, `url`, `type_id`, `is_login`, `logo`, `login_cookie`, `status`, `created_at`, `updated_at`) VALUES (16, 'csdn', 'csdn', 'https://blog.csdn.net/rank/list', 6, 0, '', NULL, 1, '2020-09-11 18:20:24', '2021-01-03 12:24:09');
INSERT INTO `hyperf_admin`.`site_config`(`id`, `name`, `english_name`, `url`, `type_id`, `is_login`, `logo`, `login_cookie`, `status`, `created_at`, `updated_at`) VALUES (17, '微信公众号', 'weixin', 'https://weixin.sogou.com/?pid=sogou-wsse-721e049e9903c3a7&kw=', 3, 0, '', NULL, 1, '2020-09-11 20:11:45', '2020-12-31 23:57:15');

CREATE TABLE `site_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '类型名称',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态,0_禁用,1_启用',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='网站类型表';

INSERT INTO `hyperf_admin`.`site_type`(`id`, `name`, `status`, `created_at`, `updated_at`) VALUES (1, '综合', 1, '2020-09-11 07:42:10', '2020-09-16 17:30:31');
INSERT INTO `hyperf_admin`.`site_type`(`id`, `name`, `status`, `created_at`, `updated_at`) VALUES (2, '科技', 1, '2020-09-11 07:42:10', '2020-09-11 07:42:10');
INSERT INTO `hyperf_admin`.`site_type`(`id`, `name`, `status`, `created_at`, `updated_at`) VALUES (3, '公众号', 1, '2020-09-11 07:42:11', '2020-09-11 07:42:11');
INSERT INTO `hyperf_admin`.`site_type`(`id`, `name`, `status`, `created_at`, `updated_at`) VALUES (4, '娱乐', 1, '2020-09-11 14:43:01', '2020-09-11 14:43:03');
INSERT INTO `hyperf_admin`.`site_type`(`id`, `name`, `status`, `created_at`, `updated_at`) VALUES (5, '社区', 1, '2020-09-11 07:43:27', '2020-09-16 17:32:54');
INSERT INTO `hyperf_admin`.`site_type`(`id`, `name`, `status`, `created_at`, `updated_at`) VALUES (6, 'IT', 1, '2020-09-11 07:44:12', '2020-09-16 17:29:59');
INSERT INTO `hyperf_admin`.`site_type`(`id`, `name`, `status`, `created_at`, `updated_at`) VALUES (7, '游戏', 1, '2020-09-11 07:44:22', '2020-09-11 07:44:22');
INSERT INTO `hyperf_admin`.`site_type`(`id`, `name`, `status`, `created_at`, `updated_at`) VALUES (8, '军事', 1, '2020-09-11 07:44:35', '2020-09-11 17:09:42');
INSERT INTO `hyperf_admin`.`site_type`(`id`, `name`, `status`, `created_at`, `updated_at`) VALUES (9, '财经', 1, '2020-09-11 07:44:41', '2020-09-16 17:29:12');
INSERT INTO `hyperf_admin`.`site_type`(`id`, `name`, `status`, `created_at`, `updated_at`) VALUES (10, '体育', 1, '2020-09-11 17:31:10', '2020-12-29 16:53:28');

CREATE TABLE `top_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文章标题',
  `url` char(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文章链接',
  `site_id` int(11) DEFAULT NULL COMMENT '网站ID,对应site_config表的id',
  `desc` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '描述,如多少评论,多少回复,多少赞',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `site_id_and_url_idx` (`site_id`,`url`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1385 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='文章表';
