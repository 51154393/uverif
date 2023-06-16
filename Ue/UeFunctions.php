<?php
/**
 * 框架常用函数文件
 * @link      http://www.Uephp.com
 * @copyright Copyright (c) 2022-2032 
 * @author    易友
 * @version   1.0.0
**/

/* 行错误及异常处理  */
error_reporting(E_ALL);
ini_set('display_errors','off');
function ErrorHandler($code, $message, $file, $line){	
	if(!U_ERROR){return FALSE;}
	$json = ['code' => $code, 'msg' => $message,'file'=>$file,'line'=>$line];
	if(defined('U_POST') && U_POST){
        echo json_encode($json);
        fastcgi_finish_request();
        if(U_ERROR_UPLOADING){errorCollect($json);}
	}else{
        define('systemErrors', json_encode($json));
        include(U_E.'template'.U_D.'error.php');
	}
	exit;
}
set_error_handler("ErrorHandler");
register_shutdown_function(function(){ //获取最后发生的错误
    $error = error_get_last();
    if (!empty($error)){
		if(!U_ERROR){return FALSE;}
		$json = ['code' => 0, 'msg' => $error['message'],'file'=>$error['file'],'line'=>$error['line']];
		define('systemErrors', json_encode($json));
		include(U_E.'template'.U_D.'error.php');
		exit;
    }
});

function __UeAutoLoad($className){// 框架类文件自动加载
	if(substr($className, -10) == 'Controller'){// 自定义控制器文件加载
		$fileUri = U_APP.'/'.U_CONTROLLER.'/'.substr($className, 0, -10).'.php';
		
		if(is_file($fileUri)){require $fileUri;}
	}elseif(substr($className, 4,6) == 'models'){
		$fileUri = $className.'.php';
		if(U_D == '/'){$fileUri = str_replace('\\', '/', $fileUri);}
		if(is_file($fileUri)){require $fileUri;}
	}else{// 利用命名空间加载其它类文件
		$fileUri = U_E.substr($className,3).'.php';
		if(U_D == '/'){$fileUri = str_replace('\\', '/', $fileUri);}
		if(is_file($fileUri)){require $fileUri;}
	}
}
spl_autoload_register('__UeAutoLoad');

function autFunction(){//自动加载方法
	$path = U_E.'function';
    if(!file_exists($path)){return false;}//判断目录是否为空
    $files = scandir($path);
    foreach($files as $v) {
		if($v != '.' && $v != '..' && strrchr($v,'.') == '.php') {include_once $path.U_D.$v;}
    }
	is_CreateConf();//检查并创建核心配置文件
	is_CreateApp();//检查并创建应用
    return true;
}

/**
 * UeRouter 
 * 功能 : 路由解析
 * @return array
*/
function Router(){
	if(isset($_GET['s'])){$path = $_GET['s']; unset($_GET['s']);}else{$path = 'index/index';}
	if(U_SUFFIX){$path = str_replace(U_SUFFIX, '', $path);}
	$router = explode('/', $path);
	if(empty($router[0])){array_shift($router);}
	if(U_ROUTE){
		$routerArray = require(U_E.'/router.php');
		if(array_key_exists($router[0], $routerArray)){
			$newRouter    = array(); 
			$newRouter[0] = $routerArray[$router[0]][0];
			$newRouter[1] = $routerArray[$router[0]][1];
			if(!empty($routerArray[$router[0]][2]) && is_array($routerArray[$router[0]][2])){
				$newRouter = array_merge($newRouter, $routerArray[$router[0]][2]);	
			}
			define("U_PAGE",  1);
			array_shift($router);
			array_push($newRouter,...$router);
			return $newRouter;
		};
	}
	$router[0] = isset($router[0]) ?  $router[0] : 'index';
	$router[1] = isset($router[1]) ?  $router[1] : 'index';
	for($i = 2; $i < count($router); $i++){
		if(preg_match('/^page-(.*)('.U_SUFFIX.')*$/Ui', $router[$i], $matches)){
			define("U_PAGE",  intval($matches[1]));
			array_splice($router, $i, 1);
		}
	}
	if(!defined("U_PAGE")){define("U_PAGE",  1);}
	return $router;
}


// 运行追踪
function runTrace(){
	if(!U_DEBUG){return false;}
	include U_E.'template'.U_D.'trace.php';
}
/**
 * 运行时间、内存开销计算
 * @return array(耗时[毫秒], 消耗内存[K])
 */
function runCost(){
	return array(
		round((microtime(true) - U_START_TIME) * 1000, 2),
		round((memory_get_usage() - U_START_MEMORY) / 1024, 2)
	);
}

/**
 * 获取配置内容
 * @param key 配置名称
 */
function c($key){
	$index = explode('.', $key);
	$key1 = null;
	$key2 = null;
	if(count($index) == 3){
		if(!file_exists(U_CONF.'/'.$index[0].'.php')){return null;}
		$config = require U_CONF.'/'.$index[0].'.php';
		$key1 = $index[1];
		$key2 = $index[2];
	}elseif(count($index) == 2){
		if(file_exists(U_CONF.'/'.$index[0].'.php')){
			$config = require U_CONF.'/'.$index[0].'.php';
			$key1 = $index[1];
		}else{
			$config = require U_CONF.'/config.php';
			$key1 = $index[0];
			$key2 = $index[1];
		}
	}else{
		if(file_exists(U_CONF.'/'.$index[0].'.php')){
			$config = require U_CONF.'/'.$index[0].'.php';
		}else{
			$config = require U_CONF.'/config.php';
			$key1 = $index[0];
		}
	}
	if(is_null($key1)){return $config;}
	if(is_null($key2)){if(isset($config[$key1])){return $config[$key1];} return null;}
	if(isset($config[$key1][$key2])){return $config[$key1][$key2];}
	return null;
}

/**
 * 功能 : 打印某个变量
 * @param $var  变量
 * @param $type 默认 false 使用 print_r(), 否则使用 var_dump()
 */
function p($var, $type = false){
	if($type){var_dump($var);}else{print_r($var);}
}

/**
 * 功能 : 获取一个数据表操作对象
 * @param $tableName  数据表名称
 * @param $configName 默认 db , 对应的数据库一级2配置名称
 * @return 数据库操作对象
 */
function db($tableName, $configName = 'db'){
	$conf = c($configName);
	return Ue\tools\db::getInstance($conf, $tableName, $configName);
}

/**
 * 功能 : 获取一个模型
 * @param $modelName  模型名称
 * @param $controllers 控制器对象
 * @return 模型对象
 */
function m($modelName,$controllers=null){
	$modelName = basename(U_APP).'\\models\\'.$modelName;
	$model = new $modelName($controllers);
	return $model;
}

/**
 * 功能 : 工具实例化函数( 适用于能使用命名空间的工具类 )
 * @param $args 动态参数
 * @return 对应的工具对象
 */
function t($args) {
	$arguments = func_get_args();
	$className = array_shift($arguments);
	$className = '\\Ue\\tools\\'.$className;
	$callback = function() use ($className, $arguments) {
		return new $className(...$arguments);
	};
	return call_user_func_array($callback, $arguments);
}

/**
 * 功能 : 工具实例化函数( 适用于不能使用命名空间的工具类 )
 * @param $args 动态参数
 * @return 对应的工具对象
 */
function tool($args){
	static $staticTools = array();
	$arguments = func_get_args();
	$className = array_shift($arguments);
	if(empty($staticTools[$className])){
		$fileUri = U_E.'tools'.U_D.$className.'.php';
		if(!is_file($fileUri)){throw new Exception("类文件 {$className} 不存在");}
		include $fileUri;
		$staticTools[$className] = 1;
	}
	
	$callback = function() use ($className, $arguments) {
		return new $className(...$arguments);
	};
	return call_user_func_array($callback, $arguments);
}

/** 
 * 规划缓存命名 
 * @param $name      缓存名称
 * @param $parameter 缓存影响参数
 * @param $isSuper   是否为全局缓存
 * @return 缓存名称
 */
function getCacheName($name, $parameter = '', $isSuper = true){
	$cacheConfig = c('cache');
	$parameter   = is_array($parameter) ? implode('_', $parameter) : $parameter;
	$cacheName   = $isSuper ? $cacheConfig['pre'].$name.$parameter : $cacheConfig['pre'].U_C.'_'.U_M.'_'.$name.$parameter;
	if(empty($cacheConfig['name2md5'])){ return $cacheName; }
	return md5($cacheName);
}

/**
 * 设置 cookie
 * @param $name   cookie 名称
 * @param $val    对应的值
 * @param $expire 有效时间
 */
function setCookies($name, $val, $expire = 31536000){
	$expire += time();
	@setcookie($name, $val, $expire, '/');
	$_COOKIE[$name] = $val;
}

/**
 * 获取 cookie
 * @param $name cookie 名称
 * @return 具体 cookie 值或 null
 */
function getCookies($name){if(isset($_COOKIE[$name])){return $_COOKIE[$name];} return null;}

/**
 * 删除指定 cookie
 * @param $name cookie 名称
 */
function delCookies($name){
	setcookie($name,null, time()-100, '/');
}


/**
 * 开启 session
 * @param $id 自定义sessionID
 */
function startSession($id=null){
	switch(U_SESSION_TYPE){
		case 'file' :
			if(!is_dir(U_SESSION_DIR)){mkdir(U_SESSION_DIR, 0777, true);}
			session_save_path(U_SESSION_DIR);
		break;
		case 'memcache' :
			ini_set("session.save_handler", "memcache");
			ini_set("session.save_path", U_SESSION_HOST);
		break;
		case 'redis':
			ini_set("session.save_handler", "redis");
			ini_set("session.save_path", U_SESSION_HOST);
		break;
		default:
			if(!is_dir(U_SESSION_DIR)){mkdir(U_SESSION_DIR, 0777, true);}
			session_save_path(U_SESSION_DIR);
	}
	if($id){session_id($id);}
	session_start();
	session_write_close();
}

/**
 * 设置 session
 * @param $name session 名称
 * @param $val  对应的值
 */
function setSession($name, $val){
	session_start();
	if(is_array($val)){
		foreach($val as $k => $v){$_SESSION[$k] = $v;}
	}else{
		$_SESSION[$name] = $val;
	}
	session_write_close();
}

/**
 * 获取 session
 * @param $name session 名称
 */
function getSession($name){
	if(isset($_SESSION[$name])){return $_SESSION[$name];} 
	return null;
}

/**
 * 删除指定的 session
 * @param $name session 名称
 */
function delSession($name){
	session_start();
	if(is_array($name)){
		foreach($name as $k){
			if(isset($_SESSION[$k])){unset($_SESSION[$k]);}
		}
	}else{
		if(isset($_SESSION[$name])){unset($_SESSION[$name]);}
	}
	session_write_close();
}

/**
 * 获取语言
 * @param $key 语言包键名称
 * @return 具体的值或者null 
 */
function lang($key = null){
	static $Lang = null;
	if(is_null($Lang)){
		$langName = empty($_COOKIE['ueLang']) ? 'zh' : $_COOKIE['ueLang'];
		$langFile = U_E.'lang/'.$langName.'.php';
		if(is_file($langFile)){
			$Lang = require $langFile;
		}else{
			throw new Exception('语言包文件不存在');
		}
	}
	if(!empty($key)){
		if(isset($Lang[$key])){return $Lang[$key];}
	}else{
		return $Lang;
	}
	return null;
}
/**
 * 收集错误报告
 * @param $data 错误信息
 */
function errorCollect($data){
    t('curl')->post('http://www.uverif.com/api/errorCollect',$data);
}