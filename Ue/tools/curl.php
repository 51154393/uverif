<?php
/**
 * Name:curl
 * Version:1.0
 * Author:阳光男孩
 * Author QQ:51154393
 * Author Url:www.uephp.com
**/

namespace Ue\tools;
class curl {
	public $httpStatus;
	public $curlHndle;
	public $speed;
	public $HttpStatusCode;
	public $timeOut = 60;
	
	public function __construct(){
		$this->curlHandle = curl_init();
		curl_setopt($this->curlHandle, CURLOPT_TIMEOUT, $this->timeOut);
	}
	
	public function setopt($key, $val){
		curl_setopt($this->curlHandle, $key , $val);
	}
	
	public function get($url){
        curl_setopt($this->curlHandle, CURLOPT_URL            , $url);
        curl_setopt($this->curlHandle, CURLOPT_RETURNTRANSFER , true);
        curl_setopt($this->curlHandle, CURLOPT_SSL_VERIFYPEER , false);
        curl_setopt($this->curlHandle, CURLOPT_SSL_VERIFYHOST , false);
		curl_setopt($this->curlHandle, CURLOPT_ENCODING       , 'gzip,deflate');
		curl_setopt($this->curlHandle, CURLOPT_TIMEOUT        , $this->timeOut);
		$result =  curl_exec($this->curlHandle);
        $this->http_status = curl_getinfo($this->curlHandle);
        $this->HttpStatusCode = curl_getinfo($this->curlHandle,CURLINFO_HTTP_CODE);
		$this->speed       = round($this->httpStatus['pretransfer_time']*1000, 2);
        return $result;
	}
	
	public function post($url,$data){
		if(is_array($data)){
			$data_txt = '';
			foreach ($data as $v => $k){
				$data_txt .= $v."=".urlencode($k).'&';
			}
			$data_txt = rtrim($data_txt,"&");
		}else{
			$data_txt = $data;
		}
		curl_setopt($this->curlHandle, CURLOPT_POST, 1);
		curl_setopt($this->curlHandle, CURLOPT_POSTFIELDS, $data_txt);
        return $this->get($url);
	}
	
	public function files($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 0); 
		curl_setopt($ch,CURLOPT_URL,$url); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		$file_content = curl_exec($ch);
		curl_close($ch);
		
		return $file_content;
	}
	
	public function HttpStatusCode(){
		return $this->HttpStatusCode;
	}
}