<?php
/*
Name:友易支付类
Version:1.1
Author:易如意
Author QQ:51154393
Author Url:www.youecn.com
*/ 
namespace Ue\tools\pay\jie;
class jiePay {
	public $host = "http://www.jienet.com/pay/api";//皆网支付API
	public $method = "POST";
	public $AccessKey;
	public $Pid;
	public $head = [];
	
	public function __construct($config){//初始化
		$this->AccessKey = $config['accesskey'];
		$this->Pid = $config['pid'];
    }
	
	public function create($order_no,$name,$money,$notify_url,$return_url){//创建支付
		if(empty($this->AccessKey) || empty($this->Pid))return false;
		$data = ['pid'=>$this->Pid,'trade_no'=>$order_no,'name'=>$name,'money'=>$money,'notify_url'=>$notify_url,'return_url'=>$return_url];
		$post = http_build_query($data);
		$param = $post."&sign=".$this->sign($data);
		$this->host .= '/create';
		$res = $this->submit($param);
		$result = json_decode($res,true);
		if(is_array($result)){
			return $result;
		}return false;
	}
	
	public function notify(){//异步通知
		if($this->verify($_GET)){
			return $_GET['order_no'];
		}
		return false;
	}
	
	public function query($data){//查询订单
		if(empty($this->AccessKey) || !is_array($data))return false;
		$post = http_build_query($data);
		$param = $post."&sign=".$this->sign($data);
		$this->host .= '/query';
		$res = $this->submit($param);
		return $res;
	}
	
	public function sign($data) {//数据签名
		ksort($data);//数组重新排序
		$arr = urldecode(http_build_query($data));
		return md5($arr.$this->AccessKey);
	}
	
	public function verify($data) {//签名验证
		if(!isset($data['sign']) || empty($data['sign']))return false;
		$sign = strtolower($data['sign']);
		unset($data['sign']);
		ksort($data);//数组重新排序
		$arr = urldecode(http_build_query($data));
		if(md5($arr.$this->AccessKey) != $sign)return false;
		return true;
	}
	
	public function submit($param){//请求
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $this->method);
		curl_setopt($curl, CURLOPT_URL,$this->host);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $this->head);
		curl_setopt($curl, CURLOPT_FAILONERROR, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		if (1 == strpos("$".$this->host, "https://")){
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		}
		curl_setopt($curl, CURLOPT_POSTFIELDS,$param);
		return curl_exec($curl);
	}
}

?>