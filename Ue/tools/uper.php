<?php
/**
 * Name:文件上传类
 * Version:1.1
 * Author:阳光男孩
 * Author QQ:51154393
 * Author Url:www.uephp.com
**/

namespace Ue\tools;
class uper{
	//文件域名称
	public $fileField;
	//文件名称
	public $fileName;
	//目标文件夹
	private $targetDir;
	//允许上传文件的类型， 格式如下：
	public $allowType     = 'image/png,image/jpeg,image/pjpeg,image/x-png,image/gif';
	//允许上传文件的扩展名
	public $allowExeName  = 'jpg,gif,png';
	//允许上传文件的大小 [单位 K]
	public $allowSize     = 1024;
	//上传错误信息
	public $error;
	//上传文件扩展名
	private $exeName;
	//子文件夹创建规则  no - 不自动创建  |  y - 年 | m - 月 | d - 日
	public $dirCreateRule = 'no';
	//文件重命名规则  1: 不重命名 | 2: 随机重命名 | 3: _1 后缀形式重命名
	public $renameRule    = 2;
	//已上传文件路径
	public $uploadedFileUrl;
	//上传文件名称自定义(设置后自动重命名将失效)
	public $uploadedFileUseName;
	
	//构造函数
	public function __construct($fileField, $targetDir, $uploadedFileUseName = null){
		$this->fileField           = $fileField;
		$this->targetDir           = $targetDir;
		$this->uploadedFileUseName = $uploadedFileUseName;
	}
	
	//设置允许
	public function setAllow($allowType = 'image/png,image/jpeg,image/pjpeg,image/x-png,image/gif',$allowExeName = 'jpg,gif,png',$allowSize = 1024){
		$this->allowType = $allowType;
		$this->allowExeName = $allowExeName;
		$this->allowSize = $allowSize;
	}
	
	//获取文件扩展名方法
	private function getExeName(){
		$pathinfo      = pathinfo($_FILES[$this->fileField]['name']);
		$this->exeName = $pathinfo['extension'];
	}
	
	//上传方法
	public function upload($fileCheck = false){
		if(empty($_FILES[$this->fileField]['tmp_name'])){
			$this->error = '请选择需要上传的文件';
			return false;
		}
		
		//检查文件扩展名
		$this->getExeName();
		$this->allowExeName  = strtolower($this->allowExeName);
		$this->exeName       = strtolower($this->exeName);
		if(!is_integer(strpos($this->allowExeName, $this->exeName))){
			$this->error = '上传文件扩展名错误';
			return false;
		}
		
		//检查文件大小
		if($_FILES[$this->fileField]['size'] > $this->allowSize*1024){
			$this->error = '上传文件的大小超过了'. $this->allowSize .'k';
			return false;
		}
		
		//检查文件类型
		if(!empty($this->allowType)){
			if(!is_integer(strpos($this->allowType, $_FILES[$this->fileField]['type']))){
				$this->error = '上传文件类型错误';
				return false;
			}
		}
		
		//自动创建子文件夹
		switch ($this->dirCreateRule){
			case 'y':
				$this->targetDir = $this->targetDir.'/'.date('Y');
			break;
			case 'm':
				$this->targetDir = $this->targetDir.'/'.date('Ym');
			break;
			case 'd':
				$this->targetDir = $this->targetDir.'/'.date('Ymd');
			break;
		}
		
		//检查子文件夹
		if(!is_dir($this->targetDir)){mkdir($this->targetDir, 0777, true);}
		
		if(is_null($this->uploadedFileUseName)) {
			//根据重命名规则组合路径信息
			switch ($this->renameRule){
				case 1:
					$this->fileName = $_FILES[$this->fileField]['name'];
					$this->targetDir .= '/'.$this->fileName;
				break;
				case 2:
					$this->fileName = uniqid().'.'.$this->exeName;
					$this->targetDir .= '/'.$this->fileName;
				break;
				case 3:
					$frontName = substr($_FILES[$this->fileField]['name'],0,-(strlen($this->exeName)+1));
					while(is_file($this->targetDir.'/'.$frontName.'.'.$this->exeName)){
						$frontName .= '_1';
					}
					$this->fileName = $frontName.'.'.$this->exeName;
					$this->targetDir .= '/'.$this->fileName;
				break;
				default:
					$this->targetDir .= '/'.$_FILES[$this->fileField]['name'];
			}
		}else{
			$this->targetDir .= '/'.$this->uploadedFileUseName;
		}
		
		if($fileCheck && !$this->fileCheck($_FILES[$this->fileField]['tmp_name'])){
			$this->error = '上传非法文件已拦截';
			return false;
		}
		
		if(move_uploaded_file($_FILES[$this->fileField]['tmp_name'], $this->targetDir)){
			$this->uploadedFileUrl = $this->targetDir;
			return $this->uploadedFileUrl;
		}
		
		$this->error = '文件上传失败';
		return false;
	}
	
	
	//上传图片非法代码检测
	public function fileCheck($image){
		if (file_exists($image)) {
			$resource = fopen($image, 'rb');
			$fileSize = filesize($image);
			fseek($resource, 0);
			if ($fileSize > 512) { // 取头和尾
				$hexCode = bin2hex(fread($resource, 512));
				fseek($resource, $fileSize - 512);
				$hexCode .= bin2hex(fread($resource, 512));
			} else { // 取全部
				$hexCode = bin2hex(fread($resource, $fileSize));
			}
			fclose($resource);
			/* 匹配16进制中的 <% ( ) %> */
			/* 匹配16进制中的 <? ( ) ?> */
			/* 匹配16进制中的 <script | /script> 大小写亦可*/
			if (preg_match("/(3c25)|(3c3f.*?706870)|(3C534352495054)|(2F5343524950543E)|(3C736372697074)|(2F7363726970743E)/is", $hexCode)) {
				return false;
			}
		}
		return true;
	}
}