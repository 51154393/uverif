<?php
/**
 * 支付类
 * @link      http://www.Uephp.com
 * @copyright Copyright (c) 2022-2032 
 * @author    阳光男孩
 * @version   1.0.0
**/
namespace Ue\tools;
class pay {
	public $PlugPath = U_E.'tools/pay';
	
	public function init() {
		$PlugName = [];
		$menu_arr = eScanDir($this->PlugPath);
		foreach($menu_arr as $fileDir){
			if(file_exists($this->PlugPath.'/'.$fileDir.'/config.php')){
				$config = require $this->PlugPath.'/'.$fileDir.'/config.php';
				$PlugName[] = ['name'=>$config['name'],'id'=>$fileDir,'type'=>$config['type'],'form'=>$config['form']];
			}
		}
		return $PlugName;
	}
	
	public function create($order_no,$name,$money,$notify_url,$return_url,$type,$config){//创建订单
		$className = "Ue\\tools\\pay\\{$type}\\{$type}Pay";
		$pay = new $className($config);
		$res = $pay->create($order_no,$name,$money,$notify_url,$return_url);
		return $res;
	}
	
	
	public function notify($type,$config){
		$className = "Ue\\tools\\pay\\{$type}\\{$type}Pay";
		$pay = new $className($config);
		$res = $pay->notify();
		return $res;
	}
}