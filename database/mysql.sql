-- Create syntax for TABLE 'log_admin_op'
CREATE TABLE `log_admin_op` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自动编号',
  `uid` int(11) DEFAULT '0' COMMENT '用户编号',
  `url` varchar(255) DEFAULT '' COMMENT '请求ＵＲＬ',
  `request` text COMMENT '操作内容',
  `ip` varchar(20) DEFAULT NULL COMMENT 'ip',
  `ltime` int(11) DEFAULT '0' COMMENT '操作时间',
  PRIMARY KEY (`id`),
  KEY `ltime` (`ltime`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=57676 DEFAULT CHARSET=utf8 COMMENT='管理员操作日志';

-- Create syntax for TABLE 'log_user_login'
CREATE TABLE `log_user_login` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户编号',
  `online` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态 0:上线;1:下线',
  `utype` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '登陆类型 1:mobile;3admin',
  `ldate` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '时间',
  `ip` varchar(15) NOT NULL DEFAULT '''''' COMMENT 'ip',
  `ver` varchar(10) NOT NULL DEFAULT '' COMMENT '版本号',
  PRIMARY KEY (`id`),
  KEY `ldate` (`ldate`)
) ENGINE=InnoDB AUTO_INCREMENT=9649 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='用户登陆日志';

-- Create syntax for TABLE 'sys_admin_user'
CREATE TABLE `sys_admin_user` (
  `user_id` int(11) NOT NULL COMMENT '用户',
  `role_id` int(11) NOT NULL COMMENT '角色',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`user_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统管理员';

-- Create syntax for TABLE 'sys_app'
CREATE TABLE `sys_app` (
  `app_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '应用编号',
  `app_ename` varchar(50) DEFAULT NULL COMMENT '应用Code',
  `app_name` varchar(100) DEFAULT NULL COMMENT '应用名称',
  `app_img` varchar(200) DEFAULT NULL COMMENT '图片',
  `app_order` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `app_tree_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否在导航中显示',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`app_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='后台应用表';

-- Create syntax for TABLE 'sys_app_function'
CREATE TABLE `sys_app_function` (
  `func_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '功能编号',
  `app_id` int(11) NOT NULL COMMENT '应用编号',
  `func_name` varchar(50) DEFAULT NULL COMMENT '功能名称',
  `func_ename` varchar(100) DEFAULT NULL COMMENT '功能代码',
  `func_url` varchar(200) DEFAULT NULL COMMENT '地址',
  `func_img` varchar(200) DEFAULT NULL COMMENT '图标',
  `func_order` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` varchar(45) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`func_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='后台功能表';

-- Create syntax for TABLE 'sys_role'
CREATE TABLE `sys_role` (
  `role_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色',
  `role_name` varchar(100) DEFAULT NULL COMMENT '角色名称',
  `role_ename` varchar(50) DEFAULT NULL COMMENT '角色代码',
  `role_funcnames` varchar(2000) DEFAULT NULL COMMENT '角色功能',
  `role_funcids` varchar(2000) DEFAULT NULL COMMENT '角色功能代码',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1008 DEFAULT CHARSET=utf8 COMMENT='后台角色表';

-- Create syntax for TABLE 'sys_role_function'
CREATE TABLE `sys_role_function` (
  `role_id` int(11) NOT NULL COMMENT '角色编号',
  `func_id` int(11) NOT NULL COMMENT '功能编号',
  `func_op` varchar(100) DEFAULT NULL COMMENT '功能操作',
  PRIMARY KEY (`role_id`,`func_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='后台角色功能表';

-- Create syntax for TABLE 'sys_user'
CREATE TABLE `sys_user` (
  `uid` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户编号',
  `username` varchar(64) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` char(32) NOT NULL DEFAULT '' COMMENT '密码',
  `tel` varchar(20) NOT NULL DEFAULT '' COMMENT '手机',
  `email` char(32) NOT NULL DEFAULT '' COMMENT '邮箱',
  `birthday` varchar(50) NOT NULL DEFAULT '' COMMENT '生日',
  `reg_ip` char(15) NOT NULL DEFAULT '' COMMENT '注册ip',
  `reg_date` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `gender` tinyint(3) NOT NULL DEFAULT '0' COMMENT '性别 1男2女 9保密',
  `utype` char(10) NOT NULL DEFAULT '' COMMENT '用户类型 (appstore/android)',
  `nickname` char(20) NOT NULL DEFAULT '' COMMENT '昵称',
  `locale` varchar(20) NOT NULL COMMENT '语言',
  `avatar` varchar(255) NOT NULL COMMENT '头像',
  `login_ip` char(15) NOT NULL DEFAULT '' COMMENT '登录ip',
  `login_times` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `login_date` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后一次登陆时间',
  `sign` varchar(255) NOT NULL DEFAULT '' COMMENT '签名',
  `regprovince` varchar(50) NOT NULL COMMENT '省份',
  `regcity` varchar(45) NOT NULL DEFAULT '' COMMENT '城市',
  `regarea` varchar(100) NOT NULL DEFAULT '' COMMENT '区域',
  `address` varchar(200) NOT NULL COMMENT '地址',
  `newuser` tinyint(1) NOT NULL DEFAULT '1' COMMENT '新用户',
  `user_salt` char(6) NOT NULL DEFAULT '' COMMENT 'salt',
  `online` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否在线',
  `online_times` int(11) NOT NULL DEFAULT '0' COMMENT '在线时长',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`) USING BTREE,
  KEY `logindate` (`login_date`),
  KEY `regdate` (`reg_date`),
  KEY `regcity` (`regcity`)
) ENGINE=InnoDB AUTO_INCREMENT=100048 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='用户表';

-- Create syntax for TABLE 'user_online'
CREATE TABLE `user_online` (
  `user_id` int(11) NOT NULL COMMENT '在线用户编号',
  `user_url` varchar(200) DEFAULT NULL COMMENT '当前ＵＲＬ',
  `url_ip` varchar(45) DEFAULT NULL COMMENT 'ip',
  `last_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后一次操作时间',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户在线';

INSERT INTO `sys_user` (`uid`, `username`, `password`, `tel`, `email`, `birthday`, `reg_ip`, `reg_date`, `gender`, `utype`, `nickname`, `locale`, `avatar`, `login_ip`, `login_times`, `login_date`, `sign`, `regprovince`, `regcity`, `regarea`, `address`, `newuser`, `user_salt`, `online`, `online_times`)
VALUES
	(100011, 'admin', '08cfa1b542c1dfae4c602cd99753074f', '', 'cexo255@163.com', '', '', 1427109932, 2, '', '管理员', 'zh_CN', '2015051718394187655.png', '127.0.0.1', 80, 1432974883, '', '上海市', '上海市', '上海市', '', 1, '1ec59f', 1, 299983);

INSERT INTO `sys_role_function` (`role_id`, `func_id`, `func_op`)
VALUES
	(1000, 4, 'view-add-edit--4'),
	(1000, 5, 'view-add-edit-delete-5'),
	(1000, 22, 'view----22'),
	(1006, 1, 'view-add-edit-delete-1'),
	(1006, 2, 'view-add-edit-delete-2'),
	(1006, 3, 'view-add-edit-delete-3'),
	(1006, 4, 'view-add-edit-delete-4'),
	(1006, 5, 'view-add-edit-delete-5'),
	(1006, 22, 'view-add-edit-delete-22');

INSERT INTO `sys_role` (`role_id`, `role_name`, `role_ename`, `role_funcnames`, `role_funcids`, `status`)
VALUES
	(1000, '系统管理员', 'Administrator', '缓存管理;用户管理;操作日志;', 'view-add-edit-delete-5;view-add-edit--4;view----22;', 1),
	(1006, '超级管理员', 'SuperAdmin', '缓存管理;用户管理;操作日志;管理员管理;功能管理;角色权限;', 'view-add-edit-delete-5;view-add-edit-delete-4;view-add-edit-delete-22;view-add-edit-delete-1;view-add-edit-delete-3;view-add-edit-delete-2;', 1);

INSERT INTO `sys_app_function` (`func_id`, `app_id`, `func_name`, `func_ename`, `func_url`, `func_img`, `func_order`, `status`)
VALUES
	(1, 1, '管理员管理', 'AdminManage', 'admin/sys/admin_list', 'admin.gif', 9010, '1'),
	(2, 1, '角色权限', 'RoleManage', 'admin/sys/role_list', 'access.gif', 9030, '1'),
	(3, 1, '功能管理', 'FunctionManage', 'admin/sys/func_list', 'function.gif', 9020, '1'),
	(4, 2, '用户管理', 'UserManage', 'admin/sys/user_list', 'role.gif', 1045, '1'),
	(5, 2, '缓存管理', 'CacheManage', 'admin/setting/cache_list', 'cache.gif', 1000, '1'),
	(22, 3, '操作日志', 'LogManage', 'admin/log/tree', 'ico_comment.gif', 8800, '1');

INSERT INTO `sys_app` (`app_id`, `app_ename`, `app_name`, `app_img`, `app_order`, `app_tree_show`, `status`)
VALUES
	(1, 'Admin', '管理员权限', 'role.gif', 9000, 1, 1),
	(2, 'Setting', '系统设置', 'game16x16.gif', 1000, 1, 1),
	(3, 'Report', '报表系统', 'report16x16.gif', 8000, 1, 1);

INSERT INTO `sys_admin_user` (`user_id`, `role_id`, `status`)
VALUES
	(100011, 1006, 1);


