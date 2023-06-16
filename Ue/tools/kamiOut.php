<?php
/**
 * 卡密输出类
 * Version:1.0
 * Author:阳光男孩
 * Author QQ:51154393
 * Author Url:www.uephp.com
**/

namespace Ue\tools;
class kamiOut {
	function createCsv($filename, $data,$path = U_APP) {
		$dir = $path.'/data/kami';
		if (!file_exists($dir)) {
			mkdir($dir, 0755, true); // 创建目录并设置权限为 0755
		}
		$file = $dir.'/'.$filename.'.csv';
		if (!file_exists($file)) {
			touch($file); // 创建文件
		}
		
		if(is_file($file)){
			$fp = fopen($file, 'w');      // 打开文件
			foreach ($data as $row) {     // 遍历数据
				fputcsv($fp, $row);       // 将每行数据写入文件，以逗号分隔
			}
			fclose($fp);                  // 关闭文件
			return $file;
		}return false;
	}
	
	public function createTxt($filename, $data,$path = U_APP) {
		$dir = $path.'/data/kami';
		if (!file_exists($dir)) {
			mkdir($dir, 0755, true); // 创建目录并设置权限为 0755
		}
		$file = $dir.'/'.$filename.'.txt';
		if (!file_exists($file)) {
			touch($file); // 创建文件
		}
		
		if(is_file($file)){
			$fp = fopen($file, 'w+');      // 打开文件
			foreach ($data as $row) {     // 遍历数据
				$line = implode('----', $row) . "\n";      // 将每行数据写入文件，以逗号分隔
				fwrite($fp, $line);
			}
			fclose($fp);                  // 关闭文件
			return $file;
		}return false;
	}
}