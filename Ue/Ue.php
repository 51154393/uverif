<?php
/**
 * 框架核心文件
 * @link      http://www.Uephp.com
 * @copyright Copyright (c) 2022-2032 
 * @author    易友
 * @version   1.0.0
**/

define('U_START_MEMORY',memory_get_usage());// 运行内存记录
define('U_START_TIME',microtime(true));// 运行时间记录
define('U_VER','1.0');// 框架版本
define('U_D',DIRECTORY_SEPARATOR);// 系统分隔符
define('U_E',dirname(__FILE__).U_D);//框架核心目录

defined('U_DEBUG')  or define('U_DEBUG',false);// 是否打开调试模式
defined('U_ERROR')  or define('U_ERROR',false);// 是否展示错误信息 [ 默认隐藏所有错误,运行报错服务器状态 500 ]
defined('U_ROUTE')  or define('U_ROUTE' ,false);// 是否开启自定义路由
defined('U_SUFFIX') or define('U_SUFFIX' , false);// 页面后缀
defined('U_CACHE')  or define('U_CACHE' , false);// 全局关闭缓存 [ 调试时可以开启此项来观察数据变化 ]
defined('U_AUTE_DISPLAY') or define('U_AUTE_DISPLAY',true);// 是否自动展示视图, 如果项目为api接口不需要视图可以设置为 false
defined('U_ERROR_UPLOADING')  or define('U_ERROR_UPLOADING',true);//错误收集

defined('U_SESSION_START') or define('U_SESSION_START', false); //全局开启 session
defined('U_SESSION_DIR')   or define('U_SESSION_DIR' , './sessions');// 文件型 sessions 文件存放路径
defined('U_SESSION_TYPE')  or define('U_SESSION_TYPE' , 'file');// session 存储类型  [file, memcache, redis]
defined('U_SESSION_HOST')  or define('U_SESSION_HOST' , 'tcp://127.0.0.1:11211');//session 类似为 memcache 或 redis 时，对应的主机地址 [memcache 11211 redis 6379]

defined('U_CONF')       or define('U_CONF','./config');// 配置文件夹
defined('U_APP')        or define('U_APP','./app');// 应用所在目录
defined('U_MODEL')      or define('U_MODEL','models');// 模型文件所在目录
defined('U_VIEW')       or define('U_VIEW','views');// 视图文件所在目录
defined('U_CONTROLLER') or define('U_CONTROLLER','controllers');///控制器文件所在目录
require(U_E.'UeFunctions.php');// 加载框架函数库


class Ue{// 基础类
	public $db;// 数据表操作对象
	public $ip;// 获取客户端IP
	public $gets;// url 解析后获得的数据
	public $trace        = true;// 程序运行追踪
	public $postFilter   = true;// 是否过滤 $_POST 数据内的 < > , 可防止跨站攻击
	public $Sessionid    = null;// Sessionid
	public $lastrunMname = null;// 最后运行的方法名
	protected $cacher    = null;// 缓存对象
	protected $cacheName;// 缓存名称
	protected $table;// 数据表操作对象
	protected $viewDir;// 视图文件所在目录
	
	public function __construct(){// 初始化基础构造
		$this->viewDir = U_APP.'/'.U_VIEW.'/';
		if($this->table != null){$this->db = db($this->table);}
		
		if(!empty($_POST)){// 过滤 $_POST
			define('U_POST', true);
			if($this->postFilter){$_POST = str_replace(array('<','>', '"', "'"),array('&lt;','&gt;', '&quot;', '\''), $_POST);}
		}else{
			define('U_POST', false);
		}
		// 过滤 $_GET
		if(!empty($_GET)){$_GET = str_replace(array('<','>', '"', "'"),array('&lt;','&gt;', '&quot;',''), $_GET);}
		if(!empty($this->gets)){$this->gets = str_replace(array('<','>', '"', "'"),array('&lt;','&gt;', '&quot;',''), $this->gets);}
	}
	
	public function __init(){}// 初始化
	
	public function index(){}// 默认 index
	
	public function display($tplName = null,$die = false){// 视图展示
		$viewUrl = is_null($tplName) ? $this->viewDir.U_C.'/'.U_M.'.php' : $this->viewDir.$tplName;
		if(is_file($viewUrl)){include($viewUrl);}
		if($die){die;}
	}
	
	protected function json($msg,$code = 200,$data = null){// 输出 json 形式的信息并终止程序运行
		$json = ['code' => $code, 'msg' => $msg];
		if($data){$json = array_merge($json,['data'=>$data]);}
		exit(json_encode($json));
	}
	
	protected function setLang($langType){// 语言包设置
		pgSetCookie('ueLang', $langType);
	}
	
	protected function getCacher(){// 获取缓存对象
		if(!empty($this->cacher)){return null;}
		$config         = c('cache');
		if(empty($config)){throw new Exception('缓存设置错误');}
		$type           = strtolower($config['type']);
		$className      = 'Ue\\tools\\caches\\'.$type.'Cacher';
		$this->cacher   = $className::getInstance($config);
	}
	
	protected function cache($name, $queryMethod, $id = null, $timer = 3600, $isSuper = true){// 进行缓存工作
		if(U_CACHE){
			$queryRes    = $this->$queryMethod();
			$this->$name = $queryRes;
			return false;
		}
		$this->getCacher();
		$this->cacheName = getCacheName($name, $id, $isSuper);
		$cachedRes = $this->cacher->get($this->cacheName);
		if($cachedRes){$this->$name = $cachedRes; return true;}
		$queryRes = $this->$queryMethod();
		$this->cacher->set($this->cacheName, $queryRes, $timer);
		$this->$name = $queryRes;
	}
	
	public function delCache($name, $id = null, $isSuper = true){// 删除缓存
		$this->getCacher();
		if($name == '*'){
			$this->cacher->removeCacheAll();
		}else{
			$name = getCacheName($name, $id, $isSuper);
			$this->cacher->removeCache($name);
		}
	}
}

/* 模型基础类 */
class UeModel{
	public $db           = null;// 数据操作对象
	protected $pk        = null;// 数据表主键
	protected $table     = null;// 数据表名称
	protected $cacher    = null;// 缓存对象
	
	public function __construct(){// 构造函数用于初始化获取数据表操作对象
		if($this->table != null){$this->db = db($this->table);}
	}
	
	public function findById($id, $fields = '*'){// 利用 id 查询一条数据
		return $this->db->where($this->pk.' = ?', [$id])->fetch($fields);
	}
	
	protected function getCacher(){// 获取缓存对象
		if(!empty($this->cacher)){return null;}
		$config         = c('cache');
		if(empty($config)){throw new Exception('缓存设置错误');}
		$type           = strtolower($config['type']);
		$className      = 'Ue\\tools\\caches\\'.$type.'Cacher';
		$this->cacher   = $className::getInstance($config);
	}
	
	public function cache($name, $queryMethod, $parameter = null, $timer = 3600, $isSuper = true){// 在模型内设置缓存并获取缓存数据
		if(U_CACHE){return $this->$queryMethod();}
		$this->getCacher();
		$name             = getCacheName($name, $parameter, $isSuper);
		$cachedRes        = $this->cacher->get($name);
		if($cachedRes){return $cachedRes;}
		$queryRes         = $this->$queryMethod();
		if(empty($queryRes)){return $queryRes;}
		$this->cacher->set($name, $queryRes, $timer);
		return $queryRes;
	}
	
	public function delCache($name, $parameter = null, $isSuper = true){// 模型内清除指定缓存
		$this->getCacher();
		if($name == '*'){
			$this->cacher->removeCacheAll();
		}else{	
			$name = getCacheName($name, $parameter, $isSuper);
			$this->cacher->removeCache($name);
		}
	}
	
	public function getSql(){return $this->m->getSql();}// 获取刚刚运行的 sql 语句
	
	public function error(){return $this->m->error();}// 获取 数据操作过程中产生的错误信息
}

try{// 框架启动
	header('content-type:text/html; charset=utf-8');
	autFunction();//自动加载方法
	$router = Router();
	$controllerName = preg_match('/^([a-z]|[A-Z]|[0-9])+$/Uis',$router[0])?$router[0]:'index';
	$controllerFile = U_APP.'/'.U_CONTROLLER.'/'.$controllerName.'.php';
	if(!is_file($controllerFile)){
		$controllerName = 'index';
		$controllerFile = U_APP.'/'.U_CONTROLLER.'/index.php';
	}
	require $controllerFile;
	define('U_C', $controllerName);
	$controllerName .= 'Controller';
	$controller = new $controllerName;
	if(!$controller instanceof Ue){throw new Exception('[ '.$controllerName.' ] 必须继承自 Ue');}
	
	$methodName  = preg_match('/^(?!_)[a-zA-Z0-9](?!.*__)(?!.*_$)/Uis',$router[1])?$router[1]:'index';
	$Methods = ['display', 'json','getCacher', 'cache','delCache'];
	if(in_array($methodName, $Methods)){$methodName  = 'index';}
	if(!method_exists($controller, $methodName)){$methodName  = 'index';}else{array_shift($router);}
	
	define('U_M', $methodName);
	array_shift($router);
	$controller->gets = $router;
	define('U_URL', implode('/', $router));
	if(U_SESSION_START){startSession($controller->Sessionid);}//全局Session
	call_user_func(array($controller, '__init'));
	$GLOBALS['runSql'] = [];
	call_user_func(array($controller, $methodName));
	if(U_AUTE_DISPLAY){call_user_func(array($controller, 'display'));}
	if($controller->trace){runTrace();}
}catch(Exception $e){if(U_DEBUG || U_ERROR){include U_E.'template'.U_D.'debug.php';}}
