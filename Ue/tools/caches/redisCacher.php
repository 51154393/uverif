<?php
/**
 * 缓存类 [redis]
 * @link      http://www.Uephp.com
 * @copyright Copyright (c) 2022-2032 
 * @author    易友
 * @version   1.0.0
**/  
 
namespace Ue\tools\caches;

class redisCacher{
	
	private static $cacher = null;
	private $rdCacher;
	
	private function __construct($conf){
		$this->rdCacher = new \redis();
		$res = $this->rdCacher->connect($conf['host'], $conf['port']);
		$this->rdCacher->auth($conf['password']);
		$this->pre = $conf['pre'];
	}
	
	public static function getInstance($conf){
		if(self::$cacher == null){self::$cacher = new redisCacher($conf);}
		return self::$cacher;
	}
	
	public function get($name){
		$cacheData = $this->rdCacher->get($name);
		if(empty($cacheData)){return null;}
		return unserialize($cacheData);
	}
	
	public function set($name, $val, $expire = 3600){
		if($expire > 2592000){$expire = 2592000;}
		$this->rdCacher->setex($name, $expire, serialize($val));
	}
	
	public function removeCache($name){
		$this->rdCacher->delete($name);
	}
	
	public function removeCacheAll(){
		$this->rdCacher->flushAll();
	}
	
	public function close(){
		$this->rdCacher->close();
	}
}