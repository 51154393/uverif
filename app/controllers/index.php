<?php
/* 控制器 Uephp 超轻量级框架 */
class indexController extends Ue{
	public $errorInfo = ['code'=>404,'msg'=>'访问页面不存在'];
	
	public function index(){
		if(!file_exists(U_E.'install.lock')){//需要安装
			if(file_exists('install.php')){
				header("Location: ./install.php"); 
			}else{
				$this->errorInfo = ['code'=>404,'msg'=>'安装程序不存在'];
				$this->display('404.php');die();
			}
			die;
		}
	}
	
	public function install(){
		if(!defined('U_INSTALL')){$this->display('index.html');die();}
		if(file_exists(U_E.'install.lock')){$this->errorInfo = ['code'=>500,'msg'=>'当前程序已安装，请勿重复操作'];$this->display('404.php');die();}
		if(U_POST){
			$checkRules  = [
				'mysql_site' => ['string','9,128','数据库地址有误'],
				'mysql_port' => ['int','1,5','数据库端口有误'],
				'mysql_name' => ['string','1,64','数据库名称有误'],
				'mysql_user' => ['string','4,64','数据库账号有误'],
				'mysql_psw' => ['string','4,64','数据库密码有误'],
				'mysql_pre' => ['wordnum','1,8','数据表前缀不规范'],
				'admin_user' => ['wordnum','6,18','系统账号设置有误：6~18位'],
				'admin_psw' => ['reg','[a-zA-Z\d.*_-]{6,18}','系统密码不规范：6~18位']
			];
			$dataChecker = t('dataChecker',$_POST, $checkRules);
			$res = $dataChecker->check();
			if(!$res)$this->json($dataChecker->error,201);
			
			if(!file_exists(U_CONF.U_D.'config.php')){
				$this->json('系统配置文件不存在，请检查后安装',201);
			}
			if(!file_exists(U_CONF.U_D.'admin.php')){
				$this->json('管理员配置文件不存在，请检查后安装',201);
			}
			
			if(!file_exists('install.sql')){
				$this->json('安装sql数据库文件不存在',201);
			}
			
			$install = t('install','install.sql','u');
			$res = $install->input($_POST['mysql_site'].':'.$_POST['mysql_port'],$_POST['mysql_user'],$_POST['mysql_psw'],$_POST['mysql_name'],$_POST['mysql_pre']);
			
			if($res){
				$config = file_get_contents(U_CONF.U_D.'config.php');
                $config = preg_replace("/'host'           => '.*?'/", "'host'           => '{$_POST['mysql_site']}'", $config);
                $config = preg_replace("/'port'           => '.*?'/", "'port'           => '{$_POST['mysql_port']}'", $config);
                $config = preg_replace("/'user'           => '.*?'/", "'user'           => '{$_POST['mysql_user']}'", $config);
                $config = preg_replace("/'pwd'            => '.*?'/", "'pwd'            => '{$_POST['mysql_psw']}'", $config);
				$config = preg_replace("/'dbname'         => '.*?'/", "'dbname'         => '{$_POST['mysql_name']}'", $config);
				$config = preg_replace("/'pre'            => '.*?'/", "'pre'            => '{$_POST['mysql_pre']}_'", $config);
                file_put_contents(U_CONF.U_D.'config.php', $config);
				$SAFEKEY = getcode(64);
				$data = [
					'ADM_USER'     => $_POST['admin_user'],
					'ADM_PASSWORD' => md5($_POST['admin_psw'].$SAFEKEY),
					'ADM_KEY'      => $SAFEKEY
				];
				cAlter('admin',$data);
				cAlter('app',['USER_TOKENKEY'=>md5(time())]);
				fopen(U_E."install.lock", "w");
				if($install->code == 1){
					$this->json($install->msg,202);
				}else{
				    unlink('install.sql');//删除数据库文件
				    unlink('install.php');//删除安装入口
				    $this->json($install->msg,200);
				}
			}else{
				$this->json($install->msg,201);
			}
		}
	}
	
}