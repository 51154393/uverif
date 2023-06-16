<?php
/*初始化创建文件*/
function is_CreateConf(){//检查创建配置文件
	if(!is_dir(U_CONF)){mkdir(U_CONF, 0777, true);}
	if(!file_exists(U_CONF.'/config.php')){UeCreateConfig();}//创建核心配置文件
}

function is_CreateApp(){//检查应用目录是否创建
	if(is_dir(U_APP)){return true;}
	//创建外层目录
	mkdir(U_APP, 0777, true);
	//创建控制器
	mkdir(U_APP.'/'.U_CONTROLLER, 0777, true);
	UeCreateAppIndexController();
	//创建视图
	mkdir(U_APP.'/'.U_VIEW, 0777, true);
	UeCreateAppIndexView();
	//创建模型
	mkdir(U_APP.'/'.U_MODEL, 0777, true);
	UeCreateAppIndexModel();
	//伪静态文件
	file_put_contents('./.htaccess', '<IfModule mod_rewrite.c>
RewriteEngine on
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)$ ./index.php?s=$1 [QSA,PT,L]
</IfModule>');
}

function UeCreateAppIndexController(){
	$str = '<?php
/* 控制器 Uephp 超轻量级框架 */
class indexController extends Ue{
	
	//__init 函数会在控制器被创建时自动运行用于初始化工作，如果您要使用它，请按照以下格式编写代码即可：
	/*
	public function __init(){
		//your code ......
	}
	*/
	public function index(){
		//系统会自动调用视图 index/index.php
	}
	
	public function test(){
		//系统会自动调用视图 index/test.php
		//开启系统路由可测试
		echo \'路由测试\';
	}
	
}';
	file_put_contents(U_APP.'/'.U_CONTROLLER.'/index.php', $str);
}

function UeCreateAppIndexView(){
	$str = '<?php if(!defined(\'U_VER\')){exit;}?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>welcome to Ue</title>
</head>
<body>
	<div style="font-size:22px; line-height:1.8em; font-family:微软雅黑; padding:100px;">
		<span style="font-size:60px; font-family:微软雅黑;">(: </span><br />
		Welcome to Uephp! <a href="http://www.Uephp.com" target="_blank">访问官网</a>
	</div>
</body>
</html>';
	mkdir(U_APP.'/'.U_VIEW.'/index', 0777, true);
	file_put_contents(U_APP.'/'.U_VIEW.'/index/index.php', $str);
	
}

function UeCreateAppIndexModel(){
	$str = '<?php
/* 模型 Uephp 超轻量级框架 */
namespace app\models;
class model extends \UeModel{
	
	//全局模型文件创建及命名规则
	
	
}';
	file_put_contents(U_APP.'/'.U_MODEL.'/model.php', $str);
}

function UeCreateConfig(){
	$str = "<?php
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
		'databaseType'   =>    'mysql',     // 数据库类型
    	'host'           =>    '127.0.0.1', // 数据库主机地址
    	'port'           =>    '3306',      // 数据库端口
		'user'           =>    'root',      // 数据库账户
		'pwd'            =>    '123456',      // 数据库密码 
		'dbname'         =>    'ue',      // 数据库名称
		'charset'        =>    'utf8',      // 字符集
		'pre'            =>    'ue_'           // 数据表统一前缀
	],
	// 缓存设置
	'cache'             => [
		'type'          => 'file',
		'host'          => '127.0.0.1', // 主机地址 [ 'memcache', 'redis' 需要设置 ]
		'password'      => '', // 对应各类服务的密码, 为空代表不需要密码
		'port'          => '6379', // 对应服务的端口
		'pre'           => 'ue_'
	]
];";
	file_put_contents(U_CONF.'/config.php', $str);
}