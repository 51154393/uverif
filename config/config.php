<?php
/**
 * 核心配置文件
 * @link      http://www.Uephp.com
 * @copyright Copyright (c) 2022-2032 
 * @author    易友
 * @version   1.0.0
**/
return [
	//数据库配置
	'db'                 => [
		'databaseType'   => 'mysql',     // 数据库类型
    	'host'           => '127.0.0.1', // 数据库主机地址
    	'port'           => '3306',      // 数据库端口
		'user'           => 'user_test_uverif',      // 数据库账户
		'pwd'            => '3ew3REeCrKarwdtw',      // 数据库密码 
		'dbname'         => 'user_test_uverif',      // 数据库名称
		'charset'        => 'utf8',      // 字符集
		'pre'            => 'uss_'           // 数据表统一前缀
	],
	// 缓存设置
	'cache'             => [
		'type'          => 'file',
		'host'          => '127.0.0.1', // 主机地址 [ 'memcache', 'redis' 需要设置 ]
		'password'      => '', // 对应各类服务的密码, 为空代表不需要密码
		'port'          => '3306', // 对应服务的端口
		'pre'           => 'u_'
	]
];