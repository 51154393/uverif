<?php
/**
 * 文件型缓存支持类
 * @link      http://www.Uephp.com
 * @copyright Copyright (c) 2022-2032 
 * @author    易友
 * @version   1.0.0
**/
namespace Ue\tools\caches;

class fileCacher{
	
	private static $cacher = null;
	private $cacheDir      = 'caches';
	
	private function __construct($config){
		$this->cacheDir = U_APP.'/'.$this->cacheDir.'/';
		if(!is_dir($this->cacheDir)){mkdir($this->cacheDir, 0777, true);}
	}
	
	public static function getInstance($config){
		if(self::$cacher == null){self::$cacher = new fileCacher($config);}
		return self::$cacher;
	}
	
	public function get($name){
		$cacheFile = $this->cacheDir.$name.'.php';
		if(!is_file($cacheFile)){return false;}
		$cacheData = require $cacheFile;
		$cacheData = unserialize($cacheData);
		if($cacheData['expire'] < time()){return false;}
		return $cacheData['data'];
	}
	
	public function set($name, $data, $expire){
		$cacheFile = $this->cacheDir.$name.'.php';
		$cacheContent = '<?php
if(!defined("U_APP")){exit();}
$data = <<<EOF
';
		$cacheData = array(
			'data'   => $data,
			'expire' => time() + $expire
		);
		$cacheData = str_replace('\\', '\\\\', serialize($cacheData));
		$cacheData = str_replace('$', '\$', $cacheData);
		$cacheContent .=  $cacheData.'
EOF;
return $data;';
		file_put_contents($cacheFile, $cacheContent);
	}
	
	public function removeCache($name){
		$cacheFile = $this->cacheDir.$name.'.php';
		if(!is_file($cacheFile)){return true;}
		unlink($cacheFile);
		return true;
	}
	
	public function removeCacheAll(){
		$files = scandir($this->cacheDir);
		foreach($files as $v){
			if($v != '.' && $v != '..'){
				$cacheUrl = $this->cacheDir.$v;
				if(is_file($cacheUrl)){
					@unlink($cacheUrl);
				}
			}
		}
		return true;
	}
	
	public function close(){
		
	}
}