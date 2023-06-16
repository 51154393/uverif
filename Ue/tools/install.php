<?php
/**
 * 系统安装类
 * Version:1.0
 * Author:阳光男孩
 * Author QQ:51154393
 * Author Url:www.uephp.com
**/
namespace Ue\tools;
class install {
	
	public $msg;
	public $code = 2;
	public $sqlPath;//sql文件路径
	public $defaultPre;//sql默认前缀
	
	public function __construct($sqlPath,$defaultPre = 'ue'){
		$this->sqlPath = $sqlPath;
		$this->defaultPre = $defaultPre;
	}
	/*
     * 数据库文件导入
     * $host 数据库链接地址(默认端口3306，其他端口连接地址为 host:端口号)   
     * $user 数据库用户名
     * $pass 数据库密码   
     * $database 选择的数据库
    */
    public function input($host,$user,$pass,$database,$pre = 'ue'){
        //读取文件内容
		if(!file_exists($this->sqlPath)){$this->code = -1;$this->msg = '安装sql文件不存在';return false;}
        $_sql = file_get_contents($this->sqlPath);
        $_arr = $this->parse_sql($_sql,0);
		$conn = @mysqli_connect($host,$user,$pass);
		$sql_i = 0;
		mysqli_query($conn,"set names utf8");
		if ($conn) {
			if (@mysqli_select_db($conn, $database)) {
				foreach($_arr as $value){
					if(stripos($value,$this->defaultPre.'_')){
						$value = str_replace($this->defaultPre.'_',$pre.'_',$value);
					}
					$r = mysqli_query($conn,$value.';');
					if($r){$sql_i++;}
				}
				$res = mysqli_close($conn);
				if($sql_i==count($_arr)){
					$this->msg = '安装成功';
					$this->code = 2;
					return true;
				}elseif($sql_i>0 && $sql_i < count($_arr)){
					$this->msg = '部分数据安装成功，建议检查数据表';
					$this->code = 1;
					return true;
				}else{
					$this->msg = '安装失败，建议手动安装';
					$this->code = -1;
					return false;
				}
				die;
			} else {
				$this->msg = '未找到数据库:'.$database;
				$this->code = -1;
				return false;
				die;
			}
		} else {
			$this->msg = '数据库连接失败:'.mysql_error();
			$this->code = -1;
			return false;
			die;
		}
    }
 
	
	/**
     * 解析sql语句
     * @param  string $content sql内容
     * @param  int $limit  如果为1，则只返回一条sql语句，默认返回所有
     * @param  array $prefix 替换表前缀
     * @return array|string 除去注释之后的sql语句数组或一条语句
     */
    public function parse_sql($sql = '', $limit = 0, $prefix = []) {
        // 被替换的前缀
        $from = '';
        // 要替换的前缀
        $to = '';
        // 替换表前缀
        if (!empty($prefix)) {
            $to   = current($prefix);
            $from = current(array_flip($prefix));
        }
        if ($sql != '') {
            // 纯sql内容
            $pure_sql = [];
            // 多行注释标记
            $comment = false;
            // 按行分割，兼容多个平台
            $sql = str_replace(["\r\n", "\r"], "\n", $sql);
            $sql = explode("\n", trim($sql));
            // 循环处理每一行
            foreach ($sql as $key => $line) {
                // 跳过空行
                if ($line == '') {
                    continue;
                }
                // 跳过以#或者--开头的单行注释
                if (preg_match("/^(#|--)/", $line)) {
                    continue;
                }
                // 跳过以/**/包裹起来的单行注释
                if (preg_match("/^\/\*(.*?)\*\//", $line)) {
                    continue;
                }
                // 多行注释开始
                if (substr($line, 0, 2) == '/*') {
                    $comment = true;
                    continue;
                }
                // 多行注释结束
                if (substr($line, -2) == '*/') {
                    $comment = false;
                    continue;
                }
                // 多行注释没有结束，继续跳过
                if ($comment) {
                    continue;
                }
                // 替换表前缀
                if ($from != '') {
                    $line = str_replace('`'.$from, '`'.$to, $line);
                }
                if ($line == 'BEGIN;' || $line =='COMMIT;') {
                    continue;
                }
                // sql语句
                array_push($pure_sql, $line);
            }
            // 只返回一条语句
            if ($limit == 1) {
                return implode("",$pure_sql);
            }
            // 以数组形式返回sql语句
            $pure_sql = implode("\n",$pure_sql);
            $pure_sql = explode(";\n", $pure_sql);
            return $pure_sql;
        } else {
            return $limit == 1 ? '' : [];
        }
    }
}