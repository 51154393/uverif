<?php
/**
 * 短信类
 * @link      http://www.Uephp.com
 * @copyright Copyright (c) 2022-2032 
 * @author    阳光男孩
 * @version   1.0.0
**/
namespace Ue\tools;
class sms {
	public $PlugPath = U_E.'tools/sms';
	public function init() {
		$PlugName = [];
		$menu_arr = eScanDir($this->PlugPath);
		foreach($menu_arr as $fileDir){
			if(file_exists($this->PlugPath.'/'.$fileDir.'/config.php')){
				$config = require $this->PlugPath.'/'.$fileDir.'/config.php';
				$PlugName[] = ['name'=>$config['name'],'id'=>$fileDir,'form'=>$config['form']];
			}
		}
		return $PlugName;
	}
	
	public function send($mobile,$code,$time,$type,$config) {
		$className = "Ue\\tools\\sms\\{$type}\\{$type}Sms";
		$sms = new $className($config);
		$res = $sms->send($mobile,$code,$time);
		return $res;
	}
}