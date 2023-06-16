<?php
/*
Name:皆网短信类
Version:1.1
Author:易如意
Author QQ:51154393
Author Url:www.youecn.com
*/ 
namespace Ue\tools\sms\jie;
class jieSms {
	public $host = "http://www.jienet.com/sms/api";//皆网短信API
	public $method = "POST";
	public $AccessKey;
	public $mid;
	
	public function __construct($config){//初始化
		$this->AccessKey = $config['accesskey'];
		$this->mid = $config['mid'];
    }
	
	/*
	 * 发送短信
	 * @（文本型）mobile=收信手机号
	 * @（文本型or整数型）code=验证码
	 * @（文本型or整数型）time=有效期
	*/
	public function send($mobile,$code,$time){
		if(empty($this->AccessKey))return false;
		$data = ['mid'=>$this->mid,'mobile'=>$mobile,'param'=>json_encode(['code'=>$code,'time'=>$time])];
		$post = http_build_query($data);
		$param = $post."&sign=".$this->sign($data);
		$this->host .= '/send';
		$res = $this->submit($param);
		$result = json_decode($res,true);
		if(is_array($result)){
			return $result;
		}return false;
		
	}
	
	public function sign($data) {//数据签名
		ksort($data);//数组重新排序
		$arr = urldecode(http_build_query($data));
		return md5($arr.$this->AccessKey);
	}
	
	public function submit($param){//请求
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $this->method);
		curl_setopt($curl, CURLOPT_URL,$this->host);
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