<?php
//支付异步通知处理
class notifyController extends Ue{
	
	protected $table = 'order';
	protected $order_no;
	
	public function __init(){
		$this->appConfig = c('app');
		if(count($this->gets)<1){
			die('fail');
		}
		$this->order_no = $this->gets[0];
	}
	
	public function ali(){//支付宝处理
		$Ores = $this->db->where('order_no = ? and ptype = ?',[$this->order_no,'ali'])->fetch();
		if(!$Ores)die('fail');
		if($Ores['state'] != 0)die('success');//已处理的订单不再处理
		
		$app = db('app')->where('id = ?',[$Ores['appid']])->fetch('pay_ali_type,pay_ali_config');
		if(!$app)die('fail');
		
		$ali_config = json_decode($app['pay_ali_config'],true);
		if(!is_array($ali_config))$this->out->e(150);
		$result = t('pay')->notify($app['pay_ali_type'],$ali_config);
		if(!$result)die('fail3');
		
		$this->__up($Ores,$result);
	}
	
	public function wx(){//微信处理
		$Ores = $this->db->where('order_no = ? and ptype = ?',[$this->order_no,'wx'])->fetch();
		if(!$Ores)die('fail');
		if($Ores['state'] != 0)die('success');//已处理的订单不再处理
		
		$app = db('app')->where('id = ?',[$Ores['appid']])->fetch('pay_wx_type,pay_wx_config');
		if(!$app)die('fail');
		
		$ali_config = json_decode($app['pay_wx_config'],true);
		if(!is_array($ali_config))$this->out->e(150);
		$result = t('pay')->notify($app['pay_wx_type'],$ali_config);
		if(!$result)die('fail');
		$this->__up($Ores,$result);
	}
	
	
	protected function __up($Ores,$trade_no){
		$Ures = db('user')->where('id = ? and appid = ?',[$Ores['uid'],$Ores['appid']])->fetch();
		if(!$Ures)die('fail');
		
		$this->db->beginTransaction();//开启事务
		$oupRes = $this->db->where('id = ?',[$Ores['id']])->update(['end_time'=>time(),'trade_no'=>$trade_no,'state'=>2]);
		if(!$oupRes)die('fail');
		
		if($Ores['type'] == 'vip'){
			if($Ures['vip'] >= 9999999999){
				$data = ['vip'=>$Ures['vip']];
			}elseif($Ures['vip'] > time()){
				$data = ['vip'=>$Ures['vip']+$Ores['val']];
			}else{
				$data = ['vip'=>time()+$Ores['val']];
			}
			$uupRes = db('user')->where('id = ?',[$Ores['uid']])->update($data);
			if(!$uupRes){
				$this->db->rollback();//事务回滚
				die('fail');
			}
			$this->db->commit();//提交
			die('success');
		}elseif($Ores['type'] == 'fen'){
			$data = ['fen'=>$Ures['fen']+$Ores['val']];
			$uupRes = db('user')->where('id = ?',[$Ores['uid']])->update($data);
			if(!$uupRes){
				$this->db->rollback();//事务回滚
				die('fail');
			}
			$this->db->commit();//提交
			die('success');
		}elseif($Ores['type'] == 'agent'){
			$AGres = db('agent_group')->where('id = ? and appid = ?',[$Ores['val'],$Ores['appid']])->fetch();
			if(!$AGres){
				$this->db->rollback();//事务回滚
				die('fail');
			}
			$Adb = db('agent');
			$Ares = $Adb->where('uid = ?',[$Ures['id']])->fetch();
			if($Ares){
				$data = [];
				if($Ares['aggid'] != $Ores['val']){
					$data['aggid'] = $Ores['val'];
				}
				if($Ares['pay_divide'] < $AGres['pay_divide']){
					$data['pay_divide'] = $AGres['pay_divide'];
				}
				if($Ares['km_discount'] > $AGres['km_discount']){
					$data['km_discount'] = $AGres['km_discount'];
				}
				
				if(empty($data)){
					$this->db->rollback();//事务回滚
					die('fail');
				}
				$res = $Adb->where('id = ?',[$Ares['id']])->update($data);
				if(!$res){
					$this->db->rollback();//事务回滚
					die('fail');
				}
			}else{
				$data = ['aggid'=>$AGres['id'],'uid'=>$Ures['id'],'pay_divide'=>$AGres['pay_divide'],'km_discount'=>$AGres['km_discount'],'time'=>time(),'appid'=>$Ores['appid']];
				$res = $Adb->add($data);
				if(!$res){
					$this->db->rollback();//事务回滚
					die('fail');
				}
			}
			$this->db->commit();//提交
			die('success');
		}
		$this->db->commit();//提交
		die('success');
	}
}