<?php
/*自定义函数文件/


/**
 * 遍历目录方法
 * $dir:遍历目录
 * $type：0=目录和文件，1=只要目录，2=只要文件
 * return arr
**/
function eScanDir($dir,$type = 0){//PHP 实现遍历出目录及其子文件
	$file_arr = scandir($dir);
	$new_arr = [];
	foreach($file_arr as $item){
		if($type == 0 && $item != ".." && $item != "."){//目录和文件
			$new_arr[] = $item;
		}elseif($type == 1 &&  is_dir($dir.'/'.$item) && $item != ".." && $item != "."){//只要目录
			$new_arr[] = $item;
		}elseif($type == 2 &&  is_file($dir.'/'.$item) && $item != ".." && $item != "."){//只要文件
			$new_arr[] = $item;
		}
	}
	return $new_arr;
}

/**
 * 取近期时间
 * $day:天
 * $type：d=日期，w=周
 * $load：false=正数，true=负数
 * return string
**/
function dateArr($day = 0,$type = 'd',$load = true){
	$date = [];
	$week = ["周日","周一","周二","周三","周四","周五","周六"];
	for ($i=0; $i<$day; $i++){
		if($load){
			$res = ($type == 'd') ? date('m-d', strtotime(-$i.' day')):$week[date('w', strtotime(-$i.' day'))];
		}else{
			$res = ($type == 'd') ? date('m-d', strtotime($i.' day')):$week[date('w', strtotime($i.' day'))];
		}
		$date[$i] = $res;
	}
	return $date;
}

/**
 * 取随机字符方法
 * $length:长度
 * $unique：false=随机，true=唯一
 * return string
**/
function getcode($length,$unique=false){
	if(!$unique){
		$str = null;  
		$strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";  
		$max = strlen($strPol)-1;  
		for($i=0;$i<$length;$i++){
			$str.=$strPol[rand(0,$max)];
		}  
		return $str; 
	}else{
		$origin = (40 - $length)<0?0:((40 - $length)>8?8:(40 - $length));
		return substr(sha1(getcode(rand(5,15)).mt_rand(1,1000000)),$origin,$length);
	}
}

/**
 * 取随整数方法
 * $length:长度
 * return string
**/
function getNumbercode($length) {
    $min = pow(10, $length-1);
    $max = pow(10, $length) - 1;
    return random_int($min, $max);
}

/**
 * 转换成 年 天 时 分 秒
 * $time：秒
 * return void
 */
function Sec2Time($time){
    if (is_numeric($time)) {
        $value = array(
            "years" => 0, "days" => 0, "hours" => 0,
            "minutes" => 0, "seconds" => 0,
        );
        $t = '';
        if ($time >= 31556926) {
            $value["years"] = floor($time / 31556926);
            $time = ($time % 31556926);
            $t .= $value["years"] . "年";
        }
        if ($time >= 86400) {
            $value["days"] = floor($time / 86400);
            $time = ($time % 86400);
            $t .= $value["days"] . "天";
        }
        if ($time >= 3600) {
            $value["hours"] = floor($time / 3600);
            $time = ($time % 3600);
            $t .= $value["hours"] . "时";
        }
        if ($time >= 60) {
            $value["minutes"] = floor($time / 60);
            $time = ($time % 60);
            $t .= $value["minutes"] . "分";
        }
        $value["seconds"] = floor($time);
        $t .= $value["seconds"] . "秒";
        return $t;

    } else {
        return (bool)false;
    }
}

/**
 * 取时间范围方法
 * $day:天
 * $type：0=0点开始，1=23点结束
 * $date：false=时间戳，true=日期
 * return string
**/

function timeRange($day = 0,$type = 0,$date = false){
	$startFix = ' 00:00:00';
	$endFix = ' 23:59:59';
	$res = date('Y-m-d', strtotime($day.' day')).(($type==0) ? $startFix : $endFix);
	if($date == true){
		return $res;
	}else{
		return strtotime($res);
	}
}

/**
 * 修改配置内容
 * @param name 配置文件
 * @param arr 配置参数
 */
function cAlter($name,$arr){
	if(!file_exists(U_CONF.U_D.$name.'.php')){return false;}
	$userdata = file_get_contents(U_CONF.U_D.$name.'.php');
	foreach ($arr as $k => $v){
		if(is_int($v)){
			$userdata = preg_replace("/'{$k}'=>.*?,/", "'{$k}'=>{$v},", $userdata);
		}else{
			$userdata = preg_replace("/'{$k}'=>'.*?'/", "'{$k}'=>'{$v}'", $userdata);
		}
	}
	$res = file_put_contents(U_CONF.U_D.$name.'.php', $userdata);
	return $res;
}

function txtArr($txt){//文本转数组
	$arr = explode('&', $txt);
	$array = [];
	foreach($arr as $value){
		$tmp_arr = explode('=',$value);
		if(is_array($tmp_arr) && count($tmp_arr) == 2){
			$array = array_merge($array,[$tmp_arr[0]=>$tmp_arr[1]]);
		}
	}
	return $array;
}
	
function arrSign($arr,$key){//签名
	unset($arr['sign']);
	$data ='';
	foreach ($arr as $v => $k){
		$data .= $v."=".$k.'&';
	}
	return md5(rtrim($data,"&").$key);
}