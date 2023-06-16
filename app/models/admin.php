<?php
/* 模型 Uephp 超轻量级框架 */
namespace app\models;
class admin extends \UeModel{
	public $appid;
	//全局模型文件创建及命名规则
	
	public function getHome($appid){//缓存类
		$this->appid = $appid;
		return $this->cache('homedata','__getHome',$appid);
	}
	
	public function __getHome(){//数据类
		$data = [];
		$times = timeRange();
		$timee = timeRange(0,1);
		
		$data['user']['count'] = db('user')->where('appid = ?',[$this->appid])->count();//统计用户数
		$data['user']['diary_count'] = db('logs')->where('appid = ? and type = ? and time between ? and ?',[$this->appid,'diary',$times,$timee])->count();//签到数
		$kami_res = db('kami')->where('appid = ?',[$this->appid])->fetch("count(*) as count,count(case when use_time>0 then 1 end) as use_count");//统计卡密数
		$data['kami'] = $kami_res ;
		
		$message_res = db('message')->where('appid = ?',[$this->appid])->fetch("count(*) as count,count(case when state=0 then 1 end) as wait_count");//统计留言数
		$data['message'] = $message_res;
		
		
		$data['order'] = db('order')->where('appid = ?',[$this->appid])->fetch("count(*) as all_os,count(case when ptype='ali' then 1 else 0 end) as ali_os,IFNULL(sum(case when state = 2 and add_time between {$times} and {$timee} then money else 0 end),0) as day_money,IFNULL(sum(case when state = 2 then money else 0 end),0) as total_money");
		
		$census = [];
		$o_db = db('order');
		for ($i=0; $i<15; $i++){
		    $tmp_db = $o_db->where('add_time between ? and ?', [timeRange(-$i),timeRange(-$i,1)])->fetch("count(*) as all_os,SUM(CASE state WHEN 2 THEN 1 ELSE 0 END) as success_os");
			$census['all'][$i] = intval($tmp_db['all_os']);
			$census['success'][$i] = intval($tmp_db['success_os']);
		}
		
		$data['order']['census']['date'] = array_reverse(dateArr(15));
		$data['order']['census']['all'] = array_reverse($census['all']);
		$data['order']['census']['success'] = array_reverse($census['success']);
		
		$log_db = db('logs');
		$logRes = $log_db->join("as LOG left join {$log_db->pre}user as U on (LOG.uid = U.id)")->where('LOG.appid = ? or LOG.appid IS NULL',[$this->appid])->order('LOG.id desc')->limit(0,5)->fetchAll('LOG.*,U.email,U.phone,U.acctno');
		
		$logList = [];
		$this->logType = c('logs');
		foreach ($logRes as $rows){
			$actUser = empty($rows['uid'])?'admin':(!empty($rows['email'])?$rows['email']:(!empty($rows['phone'])?$rows['phone']:$rows['acctno']));
			$logList[] = [
				'id'=>$rows['id'],
				'type'=>!empty($this->logType[$rows['type']])?$this->logType[$rows['type']]:$rows['type'],
				'person'=>empty($rows['uid'])?'管理员':'用户',
				'user'=>$actUser,
				'time'=>date("Y-m-d H:i",$rows['time']),
				'ip'=>$rows['ip'],
				'state'=>$rows['state']
			];
		}
		$data['logs'] = $logList;
		$data['server_info'] = [
			'sys_ver'=>c('app.APP_VERSION'),
			'domain'=>$_SERVER['SERVER_NAME'],
			'serverapp'=>$_SERVER["SERVER_SOFTWARE"],
			'php_ver'=>PHP_VERSION,
			'mysql_ver'=>$log_db->mysqlV(),
			'uploadfile_maxsize'=>ini_get('upload_max_filesize')
		];
		return $data;
	}
}