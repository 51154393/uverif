<?php
/**
 * 数据输出类
 * @link      http://www.uverif.com
 * @copyright Copyright (c) 2010-2023
 * @author    51154393@qq.com
 * @version   1.0
 */
namespace Ue\tools;
class out {
	public $Config;//配置
	public $ver = null;//APP应用
	public $encryption = false;
	public $data;
	
	public function __construct($Config){
		$this->Config = $Config;
	}
	
	public function setVer($v,$apiname){
		$this->ver = $v;
		$ApiWhiteList = explode(",",$this->Config['API_WHITE']);//白名单返回数组
		$this->encryption = !in_array($apiname,$ApiWhiteList)?true:false;//是否加密
		return $this;
	}
	
	public function setData($data){
		$this->data = $data;
		return $this;
	}
	
	public function e($code,$msg = null) {//输出
		if(empty($msg)){
			$lang_msg = c('msg');//返回数组
			$msg = $lang_msg[$code];
		}
		if($this->Config['API_OUT_TYPE'] == 'xml'){
			$data = ['code'=>$code,'msg'=>$msg,'time'=>time()];
			$xml = t('Xml');//实例化类
			if(is_array($this->data)){$data['data'] = $xml->toXml($this->data);}elseif(!empty($this->data)){$data['data'] = $this->data;}
			if($this->ver && $this->ver['mi_state'] == 'on' && !empty($this->data)){
				if(!isset($miKey[$this->ver['mi_type']])){
				    $data['code'] = 201;
				    $data['msg'] = '加密密钥配置有误';
				}else{
                    $keyConfig = $miKey[$this->ver['mi_type']];
                    if($this->ver['mi_type'] == 'rc4' && !empty($keyConfig) && $this->encryption){
                    	$Rc4 = t('Rc4');
                    	$data['data'] = $Rc4->mi($data['data'],$keyConfig);
                    }elseif($this->ver['mi_type'] == 'aes' && strlen($keyConfig['key']) == 32 && strlen($keyConfig['iv']) == 16 && $this->encryption){//AES加密密钥长度必须是16个字符
                    	$Aes = t('Aes',$keyConfig['key'],$keyConfig['iv']);
                    	$data['data'] = $Aes->encode($data['data']);//加密
                    }elseif($this->ver['mi_type'] == 'rsa' && !isset($keyConfig['private']) && $this->encryption){
                    	$Rsa = t('Rsa');
                    	$data['data'] = $Rsa->privateEncrypt($data['data'],$keyConfig['private']);
                    }
				}
			}
			if($this->Config['API_RUN_COST'] == 'on'){//计算运行成本
				$cost = runCost();
				$data['run'] = ['ms'=>$cost[0].'ms','ram'=>$cost[1].'k'];
			}
			$echo_data = $xml->toXml($data);//转为xml
		}else{
			$data = ['code'=>$code,'msg'=>$msg,'time'=>time()];
			if(is_array($this->data)){$data['data'] = json_encode($this->data);}elseif(!empty($this->data)){$data['data'] = $this->data;}
			if($this->ver && $this->ver['mi_state'] == 'on' && !empty($this->data)){
				$miKey = json_decode($this->ver['mi_key'],true);
				if(!isset($miKey[$this->ver['mi_type']])){
				    $data['code'] = 201;
				    $data['msg'] = '加密密钥配置有误';
				}else{
                    $keyConfig = $miKey[$this->ver['mi_type']];
                    if($this->ver['mi_type'] == 'rc4' && !empty($keyConfig) && $this->encryption){
                    	$Rc4 = t('Rc4');
                    	$data['data'] = $Rc4->mi($data['data'],$keyConfig);
                    }elseif($this->ver['mi_type'] == 'aes' && strlen($keyConfig['key']) == 32 && strlen($keyConfig['iv']) == 16 && $this->encryption){//AES加密密钥长度必须是16个字符
                    	$Aes = t('Aes',$keyConfig['key'],$keyConfig['iv']);
                    	$data['data'] = $Aes->encode($data['data']);//加密
                    }elseif($this->ver['mi_type'] == 'rsa' && !isset($keyConfig['private']) && $this->encryption){
                    	$Rsa = t('Rsa');
                    	$data['data'] = $Rsa->privateEncrypt($data['data'],$keyConfig['private']);
                    }
				}	
			}
			if($this->Config['API_RUN_COST'] == 'on'){//计算运行成本
				$cost = runCost();
				$data['run'] = ['ms'=>$cost[0],'ram'=>$cost[1].'k'];
			}
			$echo_data = json_encode($data);
		}
		echo $echo_data;
		exit;
	}
	
}