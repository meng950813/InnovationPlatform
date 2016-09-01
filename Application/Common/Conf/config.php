<?php

// sae 数据库配置
// define('SAE_MYSQL_HOST_M', 'w.rdc.sae.sina.com.cn');
// define('SAE_MYSQL_HOST_S', 'r.rdc.sae.sina.com.cn');
// define('SAE_MYSQL_PORT', '3307');
// define('SAE_MYSQL_DB', 'app_oncm');
// define('SAE_MYSQL_USER', 'noy3mylyoz');
// define('SAE_MYSQL_PASS', 'xyk3khxw0430mi302mkmzwh3mj1j0ylwjx153ilk');

// 本地数据库设置
define('SAE_MYSQL_HOST_M', 'localhost');
define('SAE_MYSQL_HOST_S', 'r.rdc.sae.sina.com.cn');
define('SAE_MYSQL_PORT', '3306');
define('SAE_MYSQL_DB', 'innovation_platform');
define('SAE_MYSQL_USER', 'root');
define('SAE_MYSQL_PASS', '');

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
	'DB_HOST'   => SAE_MYSQL_HOST_M, // 服务器地址
	'DB_NAME'   => SAE_MYSQL_DB, // 数据库名
	'DB_USER'   => SAE_MYSQL_USER, // 用户名
	'DB_PWD'	=> SAE_MYSQL_PASS, // 密码
	'DB_PORT'   => SAE_MYSQL_PORT, // 端口
	'DB_PREFIX' => 'cx_', // 数据库表前缀
	'DB_PARAMS' => array(PDO::ATTR_CASE => PDO::CASE_NATURAL),
	'DB_CHARSET'=> 'utf8', // 字符集
	// 'DB_DEBUG'  =>  false, // 数据库调试模式 开启后可以记录SQL日志 3.2.3新增
	// 'SHOW_PAGE_TRACE' =>true,
	'ADMIN_MARK' => array('root','admin'),
);
