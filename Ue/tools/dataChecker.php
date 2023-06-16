<?php
/**
 * 数据验证类
 * @link      http://www.Uephp.com
 * @copyright Copyright (c) 2022-2032 
 * @author    易友
 * @version   1.1
**/

namespace Ue\tools;
class dataChecker{
	
	public $data;
	public $checkRules;
	public $checkAct;
	public $error;
	
	public function __construct($data = null, $checkRules = null, $checkAct = 'act'){
		$this->data       = $data;
		$this->checkRules = $checkRules;
		$this->checkAct = $checkAct;
	}
	
	public function check(){
		foreach($this->checkRules as $k => $rule){
			$actKey = isset($this->data[$this->checkAct])?$this->data[$this->checkAct]:null;//操作索引
			
			if(isset($rule[3])){//赋值信息
				if(!is_array($rule[3])){
					if($rule[3] === true){
						continue;
					}else{
						if(!isset($this->data[$k]) || empty($this->data[$k])){
							$this->data[$k] = $rule[3];
							$_POST[$k] = $rule[3];
							continue;
						}
					}
				}else{
					foreach($rule[3] as $rule_k => $rule_v){
						if(stripos($rule_k,',')){
							$actRule = explode(',',$rule_k);
							if($actKey && !in_array($actKey,$actRule)){//不存在
								continue 2;
							}
							if(!isset($this->data[$k]) || empty($this->data[$k])){
								if($rule_v === false){
									$this->error = $rule[2];return false;
								}elseif($rule_v === true){
									continue 2;
								}else{
									$this->data[$k] = $rule_v;
									$_POST[$k] = $rule_v;
									continue;
								}
							}
						}else{
							if($rule_k != $actKey){
								continue 2;
							}
							if(!isset($this->data[$k]) || empty($this->data[$k])){
								if($rule_v === false){
									$this->error = $rule[2];return false;
								}elseif($rule_v === true){
									continue 2;
								}else{
									$this->data[$k] = $rule_v;
									$_POST[$k] = $rule_v;
									continue;
								}
							}
						}
					}
				}
			}else{
				if(!isset($this->data[$k]) || empty($this->data[$k])){
					$this->error = $rule[2];return false;
				}
			}
			//数据校验开始
			if(is_array($rule[0])){
				foreach($rule as $ruleNew){
					$methodName = 'check'.ucfirst($ruleNew[0]);
					if(!method_exists($this, $methodName)){throw new \Exception("数据检查规则配置错误1");}
					$res = $this->$methodName($this->data[$k], $ruleNew[1]);
					if(!$res){$this->error = $ruleNew[2]; return false;}
				}
			}else{
				if(strpos($rule[0],',')){
					$Rules = explode(',',$rule[0]);
					foreach($Rules as $ruleNew){
						$methodName = 'check'.ucfirst($ruleNew);
						if(!method_exists($this, $methodName)){throw new \Exception('数据检查规则配置错误'.$methodName);}
						
						$res = $this->$methodName($this->data[$k], $rule[1]);
						if($res){
							$this->error = "";
							break;
						}else{
							$this->error = $rule[2];
						}
					}
					if(!empty($this->error)){return false;}
				}else{
					$methodName = 'check'.ucfirst($rule[0]);
					if(!method_exists($this, $methodName)){throw new \Exception('数据检查规则配置错误'.$methodName);}
					$res = $this->$methodName($this->data[$k], $rule[1]);
					if(!$res){$this->error = $rule[2]; return false;}
				}
			}
		}	
		return $this->data;
	}
	
	//字符串及长度检查
	public function checkString($checkData, $checkRule){
		$checkRules = explode(',', $checkRule);
		$checkDatal = mb_strlen(trim($checkData));
		return $checkDatal >= $checkRules[0] && $checkDatal <= $checkRules[1]?true:false;
		
		//return preg_match('/^.{'.$checkRule.'}$/Uis', trim($checkData));
	}
	
	//整数检查
	public function checkIsInt($checkData, $param = null){
		return preg_match('/^\-?[0-9]+$/', $checkData);
	}
	
	//整数及长度检查
	public function checkInt($checkData, $checkRule){
		return preg_match('/^\-?[0-9]{'.$checkRule.'}$/', $checkData);
	}
	
	//整数及区间
	public function checkBetweend($checkData, $checkRule){
		if(!$this->checkIsInt($checkData)){return false;}
		$checkRules = explode(',', $checkRule);
		if($checkData > $checkRules[1] || $checkData < $checkRules[0]){return false;}
		return true;
	}
	
	//数值区间
	public function checkBetween($checkData, $checkRule){
		$checkRules = explode(',', $checkRule);
		if($checkData > $checkRules[1] || $checkData < $checkRules[0]){return false;}
		return true;
	}
	
	//小数检查
	public function checkIsFloat($checkData, $param = null){
		return preg_match('/^(\d+)\.(\d+)$/', $checkData);
	}
	
	//小数及区间检查
	public function checkBetweenf($checkData, $checkRule){
		if(!$this->checkIsFloat($checkData)){return false;}
		$checkRules = explode(',', $checkRule);
		if($checkData > $checkRules[1] || $checkData < $checkRules[0]){return false;}
		return true;
	}
	
	//小数及小数位数检查
	public function checkFloatLenght($checkData, $checkRule){
		if(!$this->checkIsFloat($checkData)){return false;}
		return preg_match('/^(\d+)\.(\d{'.$checkRule.'})$/', $checkData);
	}
	
	//大于
	public function checkGt($checkData, $checkRule){
		return ($checkData > $checkRule);
	}
	
	//大于等于
	public function checkGtAndSame($checkData, $checkRule){
		return ($checkData >= $checkRule);
	}
	
	//小于
	public function checkLt($checkData, $checkRule){
		return ($checkData < $checkRule);
	}
	
	//小于等于
	public function checkLtAndSame($checkData, $checkRule){
		return ($checkData <= $checkRule);
	}
	
	//等于
	public function checkSame($checkData, $checkRule){
		return ($checkData == $checkRule);
	}
	
	//等于某一个值
	public function checkSameone($checkData, $checkRule){
		$checkRule = explode(',', $checkRule);
		
		return in_array($checkData,$checkRule);
	}
	
	//不等于
	public function checkNotSame($checkData, $checkRule){
		return ($checkData != $checkRule);
	}
	
	//邮箱
	public function checkEmail($checkData, $checkRule){
		return preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', $checkData);
	}
	
	//手机号
	public function checkPhone($checkData, $checkRule){
		return preg_match('/^1((34[0-8]\d{7})|((3[0-3|5-9])|(4[5-7|9])|(5[0-3|5-9])|(66)|(7[2-3|5-8])|(8[0-9])|(9[1|8|9]))\d{8})$/', $checkData);
	}
	
	//电话
	public function checkMobile($checkData, $checkRule){
		return preg_match('/^(0[0-9]{2,3}(\-)?)?([2-9][0-9]{6,7})+((\-)?[0-9]{1,4})?$/', $checkData);
	}
	
	//姓名
	public function checkName($checkData, $checkRule){
		return preg_match("/^([\xe4-\xe9][\x80-\xbf]{2}){".$checkRule."}$/", $checkData);
	}
	
	//身份证
	public function checkIdcard($checkData, $checkRule){
		$checkData = strtoupper($checkData); 
		$regx = "/(^\d{15}$)|(^\d{17}([0-9]|X)$)/"; 
		$arr_split = []; 
		if(!preg_match($regx, $checkData)) { 
			return FALSE; 
		} 
		if(15==strlen($checkData)){ //检查15位 
			$regx = "/^(\d{6})+(\d{2})+(\d{2})+(\d{2})+(\d{3})$/"; 
			@preg_match($regx, $checkData, $arr_split); 
			//检查生日日期是否正确 
			$dtm_birth = "19".$arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4]; 
			if(!strtotime($dtm_birth)){ 
				return FALSE; 
			}else{ 
				return TRUE; 
			} 
		}else{//检查18位 
			$regx = "/^(\d{6})+(\d{4})+(\d{2})+(\d{2})+(\d{3})([0-9]|X)$/"; 
			@preg_match($regx, $checkData, $arr_split); 
			$dtm_birth = $arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4]; 
			if(!strtotime($dtm_birth)){ //检查生日日期是否正确 
				return FALSE; 
			}else{ 
				//检验18位身份证的校验码是否正确。 
				//校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。 
				$arr_int = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2]; 
				$arr_ch = ['1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2']; 
				$sign = 0; 
				for($i = 0; $i < 17; $i++){ 
					$b = (int) $checkData[$i]; 
					$w = $arr_int[$i]; 
					$sign += $b * $w; 
				} 
				$n = $sign % 11; 
				$val_num = $arr_ch[$n]; 
				if($val_num != substr($checkData,17, 1)){ 
					return FALSE; 
				}else{ 
					return TRUE; 
				} 
			} 
		}
		
	}
	
	//银行卡
	public function checkBank($checkData, $checkRule){
		return preg_match('/^(\d{15}|\d{16}|\d{19})$/isu', $checkData);
	}
	
	//url
	public function checkUrl($checkData, $checkRule){
		return preg_match('/^(http|https):\/\/.*$/i', $checkData);
	}
	//域名
	public function checkwww($checkData, $checkRule){
	    return preg_match('/^(\w+:\/\/)?\w+(\.\w+)+.*$/', $checkData);
	}
	
	//邮编
	public function checkZipcode($checkData, $checkRule){
		return preg_match('/^[0-9]{6}$/', $checkData);
	}
	
	//字母
	public function checkword($checkData, $checkRule){
		return preg_match('/^[a-zA-Z]$/', $checkData);
	}
	
	//字母+数字
	public function checkwordnum($checkData, $checkRule){
		if(!empty($checkRule)){
            if(!$this->checkString($checkData, $checkRule))return false;
	    }
		return preg_match('/^[a-zA-Z0-9]*$/', $checkData);
	}
	
	//字母开头+数字
	public function checkwordnumS($checkData, $checkRule){
		if(!empty($checkRule)){
            if(!$this->checkString($checkData, $checkRule))return false;
	    }
		return preg_match("/^[a-zA-Z][A-Za-z0-9]+$/", $checkData);
	}
	
	//字母、数字、-_
	public function checkudid($checkData, $checkRule){
		if(!empty($checkRule)){
            if(!$this->checkString($checkData, $checkRule))return false;
	    }
		return preg_match("/^[a-zA-Z0-9_-]+$/", $checkData);
	}
	
	//不允许特殊字符
	public function checkSpecial($checkData, $checkRule){
	    if(!empty($checkRule)){
            if(!$this->checkString($checkData, $checkRule))return false;
	    }
		return !preg_match("/[',:;*?~`!#$%^&+=)(<>{}]|\]|\[|\/|\\\|\"|\|/", $checkData);
	}
	
	//不允许空格
	public function checkspace($checkData, $checkRule){
	    if(!empty($checkRule)){
            if(!$this->checkString($checkData, $checkRule))return false;
	    }
		return !preg_match('/\s+/', $checkData);
	}
	
	//密码
	public function checkPassword($checkData, $checkRule){
		return preg_match('/^[a-zA-Z\d.*_-]{'.$checkRule.'}$/', $checkData);
	}
	
	
	//正则
	public function checkReg($checkData, $checkRule){
		return preg_match('/^'.$checkRule.'$/', $checkData);
	}
	
	//金额检测2位小数
    public function checkMoney($checkData, $checkRule){
        $checkRules = explode(',', $checkRule);
        if($checkData > $checkRules[1] || $checkData < $checkRules[0]){return false;}
        if(!preg_match("/(\d\.\d{0,2})(?!\d)/", $checkData) && strstr($checkData,'.')){
        	return false;
        }
        return true;
    }
	
	//前缀
	public function checkKami($checkData, $checkRule){
		return preg_match('/^[a-zA-Z\d_-]{'.$checkRule.'}$/', $checkData);
	}
	
	//JWT
	public function checkJwt($checkData, $checkRule){
		return preg_match('/([a-zA-Z0-9]{36})\.(.*?)\.([a-zA-Z\d.*_-]{43})/i', $checkData);
	}
}