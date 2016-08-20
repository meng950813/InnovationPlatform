<?php
return array(
	'PAGE_NUM' => 5,
	'MODULE_ALLOW_LIST' => array('Home','Master'),
	'DEFAULT_MODULE' => 'Home',	//默认模块
	'AUTOLOAD_NAMESPACE' => array(
		'Lib' => APP_PATH.'Lib'
	),
	'URL_MODEL' => '1', //URL模式
	'SESSION_AUTO_START' => true, //是否开启session
	'APP_GROUP_LIST' => 'Home,Admin', //项目分组设定
	'DEFAULT_GROUP' => 'Home', //默认分组
	'DB_TYPE'   => 'mysql', // 数据库类型
	'DB_HOST'   => 'localhost', // 服务器地址
	'DB_NAME'   => 'innovation_platform', // 数据库名
	'DB_USER'   => 'root', // 用户名
	'DB_PWD'	=> '', // 密码
	'DB_PORT'   => 3306, // 端口
	'DB_PREFIX' => 'ip_', // 数据库表前缀
	'DB_PARAMS' => array(PDO::ATTR_CASE => PDO::CASE_NATURAL),
	'DB_CHARSET'=> 'utf8', // 字符集
	'DB_DEBUG'  =>  false, // 数据库调试模式 开启后可以记录SQL日志 3.2.3新增
	// 'SHOW_PAGE_TRACE' =>true,
	'ADMIN_MARK' => array('root','admin'),
);
