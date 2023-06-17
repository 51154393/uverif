<?php
//api接口
class apiController extends Ue{
	
	public $app;
	public $out;
	public $Token;
	protected $m;
	protected $table = 'user';
	
	public function __init(){
		$this->ip = t('ip')->getIp();//获取客户端IP
		$this->times = time();
		$this->appConfig = c('app');
		$this->out   = t('out',$this->appConfig);
		$this->Token = t('Token',$this->appConfig['USER_TOKENKEY']);
	}
	
	public function __info(){//获取信息
		$checkRules  = [
			'token' => ['Jwt','','Token令牌有误']
		];
		$dataChecker = t('dataChecker',$_POST, $checkRules);
		$res = $dataChecker->check();
		if(!$res)$this->out->e(201,$dataChecker->error);
		
		$Ures = $this->__TokenCheck();
		
		$info = [
			'uid'=>$Ures['id'],
			'phone'=>$Ures['phone'],
			'email'=>$Ures['email'],
			'acctno'=>$Ures['acctno'],
			'name'=>$Ures['nickname'],
			'pic'=>$Ures['avatars'],
			'invID'=>$Ures['inviter_id'],
			'fen'=>$Ures['fen'],
			'vipExpTime'=>$Ures['vip'],
			'vipExpDate'=>date("Y-m-d H:i:s",$Ures['vip'])
		];
		$this->out->setData($info)->e(200,'登录成功');
	}
	
	public function __vip(){//验证会员
		$checkRules  = [
			'token' => ['Jwt','','Token令牌有误']
		];
		$dataChecker = t('dataChecker',$_POST, $checkRules);
		$res = $dataChecker->check();
		if(!$res)$this->out->e(201,$dataChecker->error);
		
		$Ures = $this->__TokenCheck();
		if($Ures['vip'] < time())$this->out->e(201,'验证失败');
		$this->out->e(200,'验证成功');
	}
	
	public function __fen(){//积分验证
		$checkRules  = [
			'token' => ['Jwt','','Token有误'],
			'fenid' => ['int','1,11','积分事件ID有误'],
			'fenmark'=> ['string','1,128','积分事件标记有误',true],
		];	
		$dataChecker = t('dataChecker',$_POST, $checkRules);
		$res = $dataChecker->check();
		if(!$res)$this->out->e(201,$dataChecker->error);
		
		$Ures = $this->__TokenCheck();
		
		$fenRes = db('fen_event')->where('id = ? and appid = ?',[$_POST['fenid'],$this->app['id']])->fetch();
		if(!$fenRes)$this->out->e(146);
		
		if($fenRes['vip_free'] == 'y'){
			if($Ures['vip'] > time()){
				$this->out->e(200,'验证成功');
			}
		}
		
		$foData = ['fid'=>$_POST['fenid'],'uid'=>$Ures['id'],'name'=>$fenRes['name'],'fen'=>$fenRes['fen'],'vip'=>$fenRes['vip'],'time'=>time(),'appid'=>$this->app['id']];
		
		if($fenRes['vip'] > 0){
		    if($Ures['vip'] >= 9999999999)$this->out->e(199);
		    if(isset($_POST['fenmark']) && !empty($_POST['fenmark'])){
    		    $fenO = db('fen_order')->where('fid = ? and uid = ? and mark = ? and appid = ?',[$_POST['fenid'],$Ures['id'],$_POST['fenmark'],$this->app['id']])->fetch();
    		    if($fenO)$this->out->e(147);//已经兑换过一次了
    		    $foData['mark'] = $_POST['fenmark'];
    		}
		}else{
		    if(isset($_POST['fenmark']) && !empty($_POST['fenmark'])){
    		    $fenO = db('fen_order')->where('fid = ? and uid = ? and mark = ? and appid = ?',[$_POST['fenid'],$Ures['id'],$_POST['fenmark'],$this->app['id']])->fetch();
    		    if($fenO)$this->out->e(200,'验证成功');
    		    $foData['mark'] = $_POST['fenmark'];
    		}
		}
		if($Ures['fen'] < $fenRes['fen'])$this->out->e(201,'积分余额不足');
		$addRes = db('fen_order')->add($foData);
		if(!$addRes)$this->out->e(201,'验证失败，请重试');
	    if($fenRes['vip'] > 0){
	        if($Ures['vip'] > time()){
	            $res = $this->db->where('id = ? and appid = ?',[$Ures['id'],$this->app['id']])->update(['fen'=>($Ures['fen']-$fenRes['fen']),'vip'=>($Ures['vip']+$fenRes['vip'])]);
	        }else{
	            $res = $this->db->where('id = ? and appid = ?',[$Ures['id'],$this->app['id']])->update(['fen'=>($Ures['fen']-$fenRes['fen']),'vip'=>(time()+$fenRes['vip'])]);
	        }
	    }else{
	        $res = $this->db->where('id = ? and appid = ?',[$Ures['id'],$this->app['id']])->field(['fen'=>-$fenRes['fen']]);
	    }
		if(!$res)$this->out->e(201,'验证失败');
		$this->out->e(200,'验证成功');
	}
	
	public function __kamiTopup(){//卡密充值
		$checkRules  = [
			'token' => ['Jwt','','Token令牌有误'],
			'kami' => ['Kami','16,32','Token令牌有误'],
		];
		$dataChecker = t('dataChecker',$_POST, $checkRules);
		$res = $dataChecker->check();
		if(!$res)$this->out->e(201,$dataChecker->error);
		
		$Ures = $this->__TokenCheck();
		$kmDB = db('kami');
		$Kres = $kmDB->where('cardNo = ? and appid = ?', [$_POST['kami'],$this->app['id']])->fetch();
		if(!$Kres)$this->out->e(140);
		if(!empty($Kres['use_uid']))$this->out->e(141);
		
		$data = [];
		if($Kres['type'] == 'vip'){
			if($Ures['vip'] == 9999999999)$this->out->e(199);
			
			if($Kres['val'] == 9999999999){
				$data['vip'] = $Kres['val'];
			}else{
				if($Ures['vip'] > time()){
					$data['vip'] = $Ures['vip']+$Kres['val'];
				}else{
					$data['vip'] = time()+$Kres['val'];
				}
			}
		}else if($Kres['type'] == 'fen'){
			$data['fen'] = $Ures['fen']+$Kres['val'];
		}elseif($Kres['type'] == 'addmc'){
			$data['client_max'] = $Ures['client_max']+$Kres['val'];
		}else{
			$this->out->e(142);
		}
		
		$this->db->beginTransaction();//开启事务
		$upRes = $this->db->where('id = ? and appid = ?',[$Ures['id'],$this->app['id']])->update($data);
		if(!$upRes){
			$this->db->rollback();//事务回滚
			$this->__log($Ures['id'],$this->m,201);
			$this->out->e(201,'充值失败');
		}
		$upKres = $kmDB->where('id = ? and appid = ?',[$Kres['id'],$this->app['id']])->update(['use_uid'=>$Ures['id'],'use_time'=>time(),'use_ip'=>$this->ip]);
		if(!$upKres){
			$this->db->rollback();//事务回滚
			$this->__log($Ures['id'],$this->m,201);
			$this->out->e(201,'充值失败');
		}
		$this->db->commit();//提交
		$this->__log($Ures['id'],$this->m);
		$this->out->e(200,'充值成功');
	}
	
	public function __pay(){//充值
		$checkRules  = [
			'uid' => ['int','1,11','用户ID有误'],
			'gid' => ['int','1,11','商品ID有误'],
			'type' => ['sameone','ali,wx','支付类型有误'],
		];
		$dataChecker = t('dataChecker',$_POST, $checkRules);
		$res = $dataChecker->check();
		if(!$res)$this->out->e(201,$dataChecker->error);
		
		$Ures = $this->db->where('id = ? and appid = ?',[$_POST['uid'],$this->app['id']])->fetch();
		if(!$Ures)$this->out->e(129);
		
		$Gres = db('goods')->where('id = ? and appid = ?',[$_POST['gid'],$this->app['id']])->fetch();
		if(!$Gres)$this->out->e(151);
		if($Gres['state'] != 'y')$this->out->e(152);
		
		$order_no = date("YmdHis",time()).rand(10000,99999);
		$data = ['uid'=>$Ures['id'],'gid'=>$Gres['id'],'order_no'=>$order_no,'name'=>$Gres['name'],'money'=>$Gres['money'],'type'=>$Gres['type'],'val'=>$Gres['val'],'ptype'=>$_POST['type'],'add_time'=>time(),'appid'=>$this->app['id']];
		if($Gres['type'] == 'agent'){
			$AGdb = db('agent_group');
			$AGres = $AGdb->where('id = ? and appid = ?',[$Gres['val'],$this->app['id']])->fetch();
			if(!$AGres)$this->out->e(153);
		}
		$Odb = db('order');
		$notify_url = $this->appConfig['WEB_URL'].'notify/'.$_POST['type'].'/'.$order_no;
		$return_url = $this->appConfig['WEB_URL'].'return/'.$_POST['type'].'/'.$order_no;
		if($_POST['type'] == 'ali'){
			if($this->app['pay_ali_state'] != 'on' || empty($this->app['pay_ali_config']))$this->out->e(150);
			$ali_config = json_decode($this->app['pay_ali_config'],true);
			if(!is_array($ali_config))$this->out->e(150);
			
			$result = t('pay')->create($order_no,$Gres['name'],$Gres['money'],$notify_url,$return_url,$this->app['pay_ali_type'],$ali_config);
		}
		if($_POST['type'] == 'wx'){
			if($this->app['pay_wx_state'] != 'on' || empty($this->app['pay_wx_config']))$this->out->e(150);
			$wx_config = json_decode($this->app['pay_wx_config'],true);
			if(!is_array($wx_config))$this->out->e(150);
			
			$result = t('pay')->create($order_no,$Gres['name'],$Gres['money'],$notify_url,$return_url,$this->app['pay_wx_type'],$wx_config);
		}
		
		if(!$result || !isset($result['code']) || !isset($result['msg']))$this->out->e(156);
		if($result['code'] != 200)$this->out->e(156,$result['msg']);
		if(!isset($result['data']))$this->out->e(157);
		$res = $Odb->add($data);
		if(!$res)$this->out->e(201,'订单创建失败');
		$this->out->setData($result['data'])->e(200,'订单创建成功');
	}
	
	public function __order(){//获取订单
        $checkRules  = [
			'token' => ['Jwt','','Token令牌有误'],
		];
		$dataChecker = t('dataChecker',$_POST, $checkRules);
		$res = $dataChecker->check();
		if(!$res)$this->out->e(201,$dataChecker->error);
		
		$Ures = $this->__TokenCheck();
		$gDB = db('order');
		$list = $gDB->where('uid = ? and appid = ?',[$Ures['id'],$this->app['id']])->order('id desc')->fetchAll('order_no,trade_no,name,money,ptype,add_time,end_time,state');
		$this->out->setData(['list'=>$list])->e(200,'获取成功');
	}
	
	public function __goods(){//获取商品
		$checkRules  = [
			'token' => ['Jwt','','Token令牌有误'],
		];
		$dataChecker = t('dataChecker',$_POST, $checkRules);
		$res = $dataChecker->check();
		if(!$res)$this->out->e(201,$dataChecker->error);
		
		$Ures = $this->__TokenCheck();
		$gDB = db('goods');
		$list = $gDB->where('state = ? and appid = ?',['y',$this->app['id']])->order('id desc')->fetchAll('id,name,type,money,blurb');
		
		$this->out->setData(['list'=>$list])->e(200,'获取成功');
	}
	
	public function __message(){//获取留言列表
		$checkRules  = [
			'token' => ['Jwt','','Token令牌有误'],
		];
		$dataChecker = t('dataChecker',$_POST, $checkRules);
		$res = $dataChecker->check();
		if(!$res)$this->out->e(201,$dataChecker->error);
		
		$Ures = $this->__TokenCheck();
		$mDB = db('message');
		$list = $mDB->where('uid = ? and reply_id is null and appid = ?',[$Ures['id'],$this->app['id']])->order('id desc')->fetchAll('id,title,time,last_time,state');
		$this->out->setData(['list'=>$list])->e(200,'获取成功');
	}
	
	public function __messageLog(){//获取留言对话记录
		$checkRules  = [
			'token' => ['Jwt','','Token令牌有误'],
			'mid' => ['int','1,11','留言ID有误']
		];
		$dataChecker = t('dataChecker',$_POST, $checkRules);
		$res = $dataChecker->check();
		if(!$res)$this->out->e(201,$dataChecker->error);
		
		$Ures = $this->__TokenCheck();
		$mDB = db('message');
		$list = $mDB->where('(id = ? or reply_id = ?) and (uid = ? or uid is null) and appid = ?',[$_POST['mid'],$_POST['mid'],$Ures['id'],$this->app['id']])->fetchAll();
		
		$lists = [];
		foreach ($list as $rows){
			$lists[] = [
				'content'=>$rows['content'],
				'files'=>json_decode($rows['file'],true),
				'date'=>date('Y-m-d h:i:s',$rows['time']),
				'state'=>$rows['state'],
			];
		}
		
		$mDB->where('uid IS NULL and reply_id = ?',[$_POST['mid']])->update(['state'=>2]);
		$this->out->setData(['list'=>$lists])->e(200,'获取成功');
	}
	
	public function __messageSubmit(){//提交留言
		$checkRules  = [
			'token' => ['Jwt','','Token令牌有误'],
			'title' => ['string','4,128','留言标题有误'],
			'content' => ['string','4,255','留言内容有误'],
		];
		$dataChecker = t('dataChecker',$_POST, $checkRules);
		$res = $dataChecker->check();
		if(!$res)$this->out->e(201,$dataChecker->error);
		
		$Ures = $this->__TokenCheck();
		$mDB = db('message');
		$Mres = $mDB->where('uid = ? and title = ? and appid = ?',[$Ures['id'],$_POST['title'],$this->app['id']])->fetch();
		if($Mres)$this->out->e(201,'您已经提交过一个相同的留言了');
		
		$data = ['uid'=>$Ures['id'],'title'=>$_POST['title'],'content'=>$_POST['content'],'time'=>time(),'appid'=>$this->app['id']];
		
		if(count($_FILES) > 0){
			$file = [];
			foreach ($_FILES as $key => $rows){
				$uper = t('uper',$key, 'assets/message/'.$Ures['id'].'/'.date('Ymd'));
				$uploadedFile = $uper->upload();
				if(!$uploadedFile)$this->json('图片上传错误 : '.$uper->error,201);
				array_push($file,$uploadedFile);
			}
			$data['file'] = json_encode($file);
		}
		
		$addID = $mDB->add($data);
		if(!$addID){
			$this->__log($Ures['id'],$this->m,201);
			$this->out->e(201,'提交失败');
		}
		$this->__log($Ures['id'],$this->m);
		$this->out->e(200,'提交成功');
		
	}
	
	public function __messageReply(){//回复留言
		$checkRules  = [
			'token' => ['Jwt','','Token令牌有误'],
			'mid' => ['int','1,11','留言ID有误'],
			'content' => ['string','4,255','留言内容有误'],
		];
		$dataChecker = t('dataChecker',$_POST, $checkRules);
		$res = $dataChecker->check();
		if(!$res)$this->out->e(201,$dataChecker->error);
		
		$Ures = $this->__TokenCheck();
		$mDB = db('message');
		$Mres = $mDB->where('id = ? and uid = ? and appid = ?',[$_POST['mid'],$Ures['id'],$this->app['id']])->fetch();
		if(!$Mres)$this->out->e(201,'回复留言不存在');
		if($Mres['state']==2)$this->out->e(201,'您已关闭该留言，若问题为解决，请创建新的留言');
		
		$data = ['uid'=>$Ures['id'],'content'=>$_POST['content'],'reply_id'=>$Mres['id'],'time'=>time(),'appid'=>$this->app['id']];
		
		if(count($_FILES) > 0){
			$file = [];
			foreach ($_FILES as $key => $rows){
				$uper = t('uper',$key, 'assets/message/'.$Ures['id'].'/'.date('Ymd'));
				$uploadedFile = $uper->upload();
				if(!$uploadedFile)$this->json('图片上传错误 : '.$uper->error,201);
				array_push($file,$uploadedFile);
			}
			$data['file'] = json_encode($file);
		}
		$addID = $mDB->add($data);
		if(!$addID){
			$this->__log($Ures['id'],$this->m,201);
			$this->out->e(201,'回复失败');
		}
		$mDB->where('id = ?',[$Mres['id']])->update(['last_time'=>time(),'state'=>0]);
		$this->out->e(200,'回复成功');
	}
	
	public function __messageEnd(){//留言结束
		$checkRules  = [
			'token' => ['Jwt','','Token令牌有误'],
			'mid' => ['int','1,11','留言ID有误'],
		];
		$dataChecker = t('dataChecker',$_POST, $checkRules);
		$res = $dataChecker->check();
		if(!$res)$this->out->e(201,$dataChecker->error);
		
		$Ures = $this->__TokenCheck();
		$mDB = db('message');
		$Mres = $mDB->where('id = ? and uid = ? and appid = ?',[$_POST['mid'],$Ures['id'],$this->app['id']])->update(['state'=>2]);
		if($Mres){
			$this->out->e(200,'操作成功');
		}
		$this->out->e(201,'操作失败');
	}
	
	public function __modifyName(){//修改名称
		$checkRules  = [
			'token' => ['Jwt','','Token令牌有误'],
			'name' => ['string','1,64','Token令牌有误'],
		];
		$dataChecker = t('dataChecker',$_POST, $checkRules);
		$res = $dataChecker->check();
		if(!$res)$this->out->e(201,$dataChecker->error);
		
		$Ures = $this->__TokenCheck();
		
		$upRes = $this->db->where('id = ? and appid = ?',[$Ures['id'],$this->app['id']])->update(['nickname'=>$_POST['name']]);
		if(!$upRes){
			$this->__log($Ures['id'],$this->m,201);
			$this->out->e(201,'修改失败');
		}
		$this->__log($Ures['id'],$this->m);
		$this->out->e(200,'修改成功');
	}
	
	public function __modifyPwd(){//修改密码
		$checkRules  = [
			'token' => ['Jwt','','Token令牌有误'],
			'password'  => ['Password','6,18','当前密码有误'],
			'newPassword'  => ['Password','6,18','新密码长度需要满足6-18位数,不支持中文以及.-*_以外特殊字符'],
		];
		$dataChecker = t('dataChecker',$_POST, $checkRules);
		$res = $dataChecker->check();
		if(!$res)$this->out->e(201,$dataChecker->error);
		
		$Ures = $this->__TokenCheck();
		
		if($Ures['password'] != md5($_POST['password']))$this->out->e(132);
		if(md5($_POST['newPassword']) == md5($_POST['password']))$this->out->e(133);
		
		$upRes = $this->db->where('id = ? and appid = ?',[$Ures['id'],$this->app['id']])->update(['password'=>md5($_POST['newPassword'])]);
		if(!$upRes){
			$this->__log($Ures['id'],$this->m,201);
			$this->out->e(201,'修改失败');
		}
		$this->__log($Ures['id'],$this->m);
		$this->out->e(200,'修改成功');
	}
	
	public function __modifyPic(){//修改头像
		$checkRules  = [
			'token' => ['Jwt','','Token令牌有误'],
		];	
		$dataChecker = t('dataChecker',$_POST, $checkRules);
		$res = $dataChecker->check();
		if(!$res)$this->out->e(201,$dataChecker->error);
		$Ures = $this->__TokenCheck();
		
		if(count($_FILES) >= 1){
			foreach($_FILES as $k =>$v){
			    $uper = t('uper',$k, 'assets/avatars');
    			$uploadedFile = $uper->upload();
    			if(!$uploadedFile){
    				$this->json('头像上传错误 : '.$uper->error,201);
    			}
			}
		}
		if(isset($uploadedFile)){
		    $res = $this->db->where('id = ? and appid = ?',[$Ures['id'],$this->app['id']])->update(['avatars'=>$uploadedFile]);
		    if(!$res){
				unlink($uploadedFile);
				$this->__log($Ures['id'],$this->m,201);
				$this->out->e(201,'头像上传失败');
			}
			$this->__log($Ures['id'],$this->m);
		    $this->out->e(200,'头像上传成功');
		}$this->out->e(201,'请上传头像');
	}
	
	public function __resetPwd(){//重置密码
		$checkRules  = [
			'account' => ['email,phone','5,32','账号有误'],
			'newPassword'  => ['Password','6,18','密码长度需要满足6-18位数,不支持中文以及.-*_以外特殊字符'],
			'code'  => ['int','4,6','验证码填写有误'],
		];	
		$dataChecker = t('dataChecker',$_POST, $checkRules);
		$res = $dataChecker->check();
		if(!$res)$this->out->e(201,$dataChecker->error);
		
		if(!isset($_POST['code']) || empty($_POST['code']))$this->out->e(118);//验证码为空
		$dtime = time() - (60*$this->app['vc_time']);//验证码有效期
		
		$vcDB = db('vcode');
		$res_code = $vcDB->where('eorp = ? and code = ? and type = ? and usable = ? and time > ? and appid = ?', [$_POST['account'],$_POST['code'],'repwd','y',$dtime,$this->app['id']])->update(['usable'=>'n']);
		if(!$res_code || $vcDB->rowCount() < 1)$this->out->e(119);//验证码不正确
		
		
		$Ures = $this->db->where('(phone = ? or email = ?) and appid = ?', [$_POST['account'],$_POST['account'],$this->app['id']])->fetch();
		if(!$Ures)$this->out->e(129);//账号不存在
		
		$res = $this->db->where('id = ?', [$Ures['id']])->update(['password'=>md5($_POST['newPassword'])]);
		if(!$res){
			$this->__log($Ures['id'],$this->m,201);
			$this->out->e(201,"重置密码失败");
		}
		$this->__log($Ures['id'],$this->m);
		$this->out->e(200,"重置密码成功");
	}
	
	public function __signIn(){//签到
		$checkRules  = [
			'token' => ['Jwt','','Token令牌有误'],
		];	
		$dataChecker = t('dataChecker',$_POST, $checkRules);
		$res = $dataChecker->check();
		if(!$res)$this->out->e(201,$dataChecker->error);
		
		$Ures = $this->__TokenCheck();
		
		$sRes = db('logs')->where('ug = ? and uid = ? and type = ? and state = ? and time > ? and appid = ?',['user',$Ures['id'],'signIn','y',timeRange(),$this->app['id']])->fetch();
		if($sRes)$this->out->e(134);
		
		$addRes = db('logs')->add(['ug'=>'user','uid'=>$Ures['id'],'type'=>'signIn','time'=>time(),'ip'=>$this->ip,'appid'=>$this->app['id']]);
		if($addRes){
		    if($this->app['diary_award'] == 'vip'){
		        if($this->app['diary_award_val'] > 0){
        		    if($Ures['vip'] == 9999999999)$this->out->e(200,"签到成功");
    				if($Ures['vip'] > time()){
    					$upVip = $Ures['vip']+$this->app['diary_award_val'];
    				}else{
    					$upVip = time()+$this->app['diary_award_val'];
    				}
    				$this->db->where('id = ?',[$Ures['id']])->update(['vip'=>$upVip]);
        		}
		    }else{
		        if($this->app['diary_award_val'] > 0){
    				$this->db->where('id = ?',[$Ures['id']])->update(['fen'=>$Ures['fen']+$this->app['diary_award_val']]);
        		}
		    }
    		$this->out->e(200,"签到成功");
		}$this->out->e(201,"签到失败");
	}
	
	public function __setAcctno(){//设置账号
		$checkRules  = [
			'token' => ['Jwt','','Token令牌有误'],
			'acctno' => ['wordnumS','5,18','自定义账号有误，必须以字母开头5~18位'],
		];	
		$dataChecker = t('dataChecker',$_POST, $checkRules);
		$res = $dataChecker->check();
		if(!$res)$this->out->e(201,$dataChecker->error);
		
		$Ures = $this->__TokenCheck();
		if(!empty($Ures['acctno']))$this->out->e(123);
		$Anores = $this->db->where('acctno = ? and appid = ?',[$_POST['acctno'],$this->app['id']])->fetch();
		if($Anores)$this->out->e(120);
		
		$upRes = $this->db->where('id = ?',[$Ures['id']])->update(['acctno'=>$_POST['acctno']]);
		if(!$upRes){
			$this->__log($Ures['id'],$this->m,201);
			$this->out->e(201,"设置失败");
		}
		$this->__log($Ures['id'],$this->m);
		$this->out->e(201,"设置成功");
	}
	
	public function __setEmail(){//设置邮箱
		$checkRules  = [
			'token' => ['Jwt','','Token令牌有误'],
			'email' => ['email','','邮箱账号有误'],
			'code'  => ['int','4,6','验证码填写有误'],
		];	
		$dataChecker = t('dataChecker',$_POST, $checkRules);
		$res = $dataChecker->check();
		if(!$res)$this->out->e(201,$dataChecker->error);
		
		$Ures = $this->__TokenCheck();
		if(!empty($Ures['email']))$this->out->e(124);
		
		$dtime = time() - (60*$this->app['vc_time']);//验证码有效期
		$vcDB = db('vcode');
		$res_code = $vcDB->where('eorp = ? and code = ? and type = ? and usable = ? and time > ? and appid = ?', [$_POST['email'],$_POST['code'],'ubind','y',$dtime,$this->app['id']])->update(['usable'=>'n']);
		if(!$res_code || $vcDB->rowCount() < 1)$this->out->e(119);//验证码不正确
		
		$emailRes = $this->db->where('email = ? and appid = ?',[$_POST['email'],$this->app['id']])->fetch();
		if($emailRes)$this->out->e(120,'该邮箱已被绑定');
		
		$upRes = $this->db->where('id = ?',[$Ures['id']])->update(['email'=>$_POST['email']]);
		if(!$upRes){
			$this->__log($Ures['id'],$this->m,201);
			$this->out->e(201,"绑定失败");
		}
		$this->__log($Ures['id'],$this->m);
		$this->out->e(201,"绑定成功");
	}
	
	public function __setPhone(){//设置手机号
		$checkRules  = [
			'token' => ['Jwt','','Token令牌有误'],
			'phone' => ['phone','','邮箱账号有误'],
			'code'  => ['int','4,6','验证码填写有误'],
		];	
		$dataChecker = t('dataChecker',$_POST, $checkRules);
		$res = $dataChecker->check();
		if(!$res)$this->out->e(201,$dataChecker->error);
		
		$Ures = $this->__TokenCheck();
		if(!empty($Ures['phone']))$this->out->e(125);
		
		$dtime = time() - (60*$this->app['vc_time']);//验证码有效期
		$vcDB = db('vcode');
		$res_code = $vcDB->where('eorp = ? and code = ? and type = ? and usable = ? and time > ? and appid = ?', [$_POST['phone'],$_POST['code'],'ubind','y',$dtime,$this->app['id']])->update(['usable'=>'n']);
		if(!$res_code || $vcDB->rowCount() < 1)$this->out->e(119);//验证码不正确
		
		$emailRes = $this->db->where('phone = ? and appid = ?',[$_POST['phone'],$this->app['id']])->fetch();
		if($emailRes)$this->out->e(120,'该手机号已被绑定');
		
		$upRes = $this->db->where('id = ?',[$Ures['id']])->update(['phone'=>$_POST['phone']]);
		if(!$upRes){
			$this->__log($Ures['id'],$this->m,201);
			$this->out->e(201,"绑定失败");
		}
		$this->__log($Ures['id'],$this->m);
		$this->out->e(201,"绑定成功");
	}
	
	public function __getUdid(){//获取已绑定设备列表
	    $checkRules  = [
			'token' => ['Jwt','','Token令牌有误'],
		];
		$dataChecker = t('dataChecker',$_POST, $checkRules);
		$res = $dataChecker->check();
		if(!$res)$this->out->e(201,$dataChecker->error);
		
		$Ures = $this->__TokenCheck();
		$this->out->setData(['list'=>json_decode($Ures['client_list'],true)])->e(200,'获取成功');
	}
	
	public function __reUdid(){//解绑设备
	    $checkRules  = [
			'token' => ['Jwt','','Token令牌有误'],
			'udid' => ['udid','1,128','机器码有误'],
		];
		$dataChecker = t('dataChecker',$_POST, $checkRules);
		$res = $dataChecker->check();
		if(!$res)$this->out->e(201,$dataChecker->error);
		
		$Ures = $this->__TokenCheck();
		$client_Arr = json_decode($Ures['client_list'],true);
		$find = false;
		$client = [];
		foreach ($client_Arr as $rows){
		    if($rows['udid'] == $_POST['udid']){
				$find = true;
		    }else{
		        $client[] = ['udid'=>$rows['udid'],'time'=>$rows['time']];
		    }
		}
		if(!$find)$this->out->e(201,'解绑设备不存在');
		
		$data = ['client_list'=>json_encode($client)];
        if($this->app['logon_mc_unbdeVal'] > 0){
            if($this->app['logon_mc_unbdeType'] == 'vip'){
                if($Ures['vip'] < time())$this->out->e(170);//VIP到期无法解绑
                if($Ures['vip'] < 9999999999){
                    $data['vip'] = $Ures['vip']-$this->app['logon_mc_unbdeVal'];
                }
            }else{
                if($Ures['fen'] < $this->app['logon_mc_unbdeVal'])$this->out->e(171);//积分余额不足
                $data['fen'] = $Ures['fen']-$this->app['logon_mc_unbdeVal'];
            }
        }
        $upRes = $this->db->where('id = ?',[$Ures['id']])->update($data);
		if(!$upRes){
			$this->__log($Ures['id'],$this->m,201);
			$this->out->e(201,"解绑失败");
		}
		$this->__log($Ures['id'],$this->m);
		$this->out->e(201,"解绑成功");
	}
	
	public function __bindUdid(){//绑定设备
        $checkRules  = [
			'token' => ['Jwt','','Token令牌有误'],
			'udid' => ['udid','1,128','机器码有误'],
		];
		$dataChecker = t('dataChecker',$_POST, $checkRules);
		$res = $dataChecker->check();
		if(!$res)$this->out->e(201,$dataChecker->error);
		
		$Ures = $this->__TokenCheck();
		$client_Arr = json_decode($Ures['client_list'],true);
		if(count($client_Arr) >= $this->app['logon_mc_num']+$Ures['client_max'])$this->out->e(172);//绑定上限
		
		foreach ($client_Arr as $rows){
		    if($rows['udid'] == $_POST['udid']){$this->out->e(200,"绑定成功");}
		}
		
		$client_Arr[] = ['udid'=>$_POST['udid'],'time'=>$rows['time']];
		$upRes = $this->db->where('id = ?',[$Ures['id']])->update(['client_list'=>json_encode($client_Arr)]);
		if(!$upRes){
			$this->__log($Ures['id'],$this->m,201);
			$this->out->e(201,"绑定失败");
		}
		$this->__log($Ures['id'],$this->m);
		$this->out->e(201,"绑定成功");
	}
	
	public function __subordinate(){//下级列表
        $checkRules  = [
			'token' => ['Jwt','','Token令牌有误'],
			'pg' => ['int','1,11','页面有误',1],
		];
		$dataChecker = t('dataChecker',$_POST, $checkRules);
		$res = $dataChecker->check();
		if(!$res)$this->out->e(201,$dataChecker->error);
		
		$Ures = $this->__TokenCheck();
		$page = isset($_POST['pg']) ? (intval($_POST['pg']) >= 1 ? intval($_POST['pg']):1) : 1;
		
		if($Ures['agent']){
		    $list = $this->db->where('inviter_id = ? and appid = ?',[$Ures['id'],$this->app['id']])->order('id desc')->page($page,10)->fetchAll('IFNULL(acctno,IFNULL(email,phone)) as user,nickname,avatars,vip,fen,reg_time,ban,ban_msg');
		}else{
		    $list = $this->db->where('inviter_id = ? and appid = ?',[$Ures['id'],$this->app['id']])->order('id desc')->page($page,10)->fetchAll('IFNULL(acctno,IFNULL(email,phone)) as user,nickname,avatars');
		}
		
		$this->out->setData($list)->e(200,'获取成功');
	}
	
	public function __logon(){//登录
		$checkRules  = [
			'account' => ['email,phone,wordnum','5,32','登录账号有误'],
			'password'  => ['Password','6,18','密码长度需要满足6-18位数,不支持中文以及.-*_以外特殊字符'],
			'udid'  => ['udid','1,128','机器码有误']
		];
		$dataChecker = t('dataChecker',$_POST, $checkRules);
		$res = $dataChecker->check();
		if(!$res)$this->out->e(201,$dataChecker->error);
		if($this->app['logon_state'] == 'off')$this->out->e(103,$this->app['logon_off_msg']);//关闭登录
		
		$Ures = $this->db->join("as U LEFT JOIN {$this->db->pre}agent as A on (U.id = A.uid)")->where('(U.phone = ? or U.email = ? or U.acctno = ?) and U.password = ? and U.appid = ?',[$_POST['account'],$_POST['account'],$_POST['account'],md5($_POST['password']),$this->app['id']])->fetch('U.*,IF(A.id IS NOT NULL,true,false) AS agent');
		if(!$Ures)$this->out->e(126);//账号密码有误
		if($Ures['ban'] > time())$this->out->e(127,$Ures['ban_msg']);//账号被禁用
		
		$tokenState = 'y';
		if(empty($Ures['client_list'])){
			$clientInfo = json_encode([['udid'=>$_POST['udid'],'time'=>time()]]);//绑定机器码
			$res = $this->db->where('id = ?',[$Ures['id']])->update(['client_list'=>$clientInfo]);
			$this->__log($Ures['id'],$this->m,201);
			if(!$res)$this->out->e(201,'登录失败，请重试');
		}else{
			$client_Arr = json_decode($Ures['client_list'],true);
			$found_key = array_search($_POST['udid'],array_column($client_Arr,'udid'));
			
			if($found_key !== 0 && !$found_key){//新设备登录
				if(count($client_Arr) >= $this->app['logon_mc_num']+$Ures['client_max']){
					$tokenState = 'n';
				}else{
					$client_Arr[] = ['udid'=>$_POST['udid'],'time'=>time()];
					$data = ['client_list'=>json_encode($client_Arr)];
					$res = $this->db->where('id = ?',[$Ures['id']])->update($data);
					$this->__log($Ures['id'],$this->m,201);
					if(!$res)$this->out->e(201,'登录失败，请重试');
				}
			}
		}
		$token = $this->Token->get(['uid'=>$Ures['id'],'udid'=>$_POST['udid'],'p'=>md5($Ures['password']),'appid'=>$this->app['id']]);
		$info = [
			'token'=>$token,
			'tokenState'=>$tokenState,
			'info'=>[
				'uid'=>$Ures['id'],
				'phone'=>$Ures['phone'],
				'email'=>$Ures['email'],
				'acctno'=>$Ures['acctno'],
				'name'=>$Ures['nickname'],
				'pic'=>$Ures['avatars'],
				'invID'=>$Ures['inviter_id'],
				'fen'=>$Ures['fen'],
				'vipExpTime'=>$Ures['vip'],
				'vipExpDate'=>date("Y-m-d H:i:s",$Ures['vip']),
				'agent'=>$Ures['agent']
			]	
		];
		
		$this->__log($Ures['id'],$this->m);
		$this->out->setData($info)->e(200,'登录成功');
	}
	
	
	public function __reg(){//注册账号
		$checkRules  = [
			'account' => [$this->app['reg_way'],'5,32',$this->app['reg_way']=='phone'?'注册的手机号有误':($this->app['reg_way']=='email'?'注册的邮箱有误':'注册的账号有误，仅支持5~18位字母+数字')],
			'code'  => ['int','4,6','验证码填写有误',true],
			'password'  => ['Password','6,18','密码长度需要满足6-18位数,不支持中文以及.-*_以外特殊字符'],
			'invid'  => ['int','1,11','邀请人ID填写有误',true],
			'udid'  => ['udid','1,128','机器码有误']
		];	
		$dataChecker = t('dataChecker',$_POST, $checkRules);
		$res = $dataChecker->check();
		if(!$res)$this->out->e(201,$dataChecker->error);
		if($this->app['reg_state'] == 'off')$this->out->e(102,$this->app['reg_off_msg']);//关闭注册
		
		$user = $this->app['reg_way'] == 'wordnum' ?'acctNo':$this->app['reg_way'];
		$res = $this->db->where("{$user} = ? and appid = ?",[$_POST['account'],$this->app['id']])->fetch();
		if($res)$this->out->e(120);//账号已存在
		
		$regData = [$user=>$_POST['account'],'password'=>md5($_POST['password']),'vip'=>0,'fen'=>0,'reg_time'=>$this->times,'reg_ip'=>$this->ip,'reg_udid'=>$_POST['udid'],'appid'=>$this->app['id']];
		
		if($this->app['reg_time_ip'] > 0){//获取IP重复注册间隔
			$ip_time = time()-$this->app['reg_time_ip']*3600;//小时单位
			$ip_res = $this->db->where('reg_ip = ? and appid = ? and reg_time > ?',[$this->ip,$this->app['id'],$ip_time])->fetch();//寻找相同IP
			if($ip_res)$this->out->e(121);//该IP已注册
		}
		
		if($this->app['reg_time_mc'] > 0 && !empty($_POST['udid'])){//获取机器码重复注册间隔
			$in_time = time()-$this->app['reg_time_mc']*3600;//小时单位
			$in_res = $this->db->where('reg_mc = ? and appid = ? and reg_time > ?',[$_POST['udid'],$this->app['id'],$in_time])->fetch();//寻找相同机器码
			if($in_res)$this->out->e(121);//该机器码已注册
		}
		
		if($this->app['reg_way'] == 'phone' || $this->app['reg_way'] == 'email'){
			if(!isset($_POST['code']) || empty($_POST['code']))$this->out->e(118);//验证码为空
			$dtime = time() - (60*$this->app['vc_time']);//验证码有效期
			$vcDB = db('vcode');
			$res_code = $vcDB->where('eorp = ? and code = ? and type = ? and usable = ? and time > ? and appid = ?', [$_POST['account'],$_POST['code'],'reg','y',$dtime,$this->app['id']])->update(['usable'=>'n']);
			if(!$res_code || $vcDB->rowCount() < 1)$this->out->e(119);//验证码不正确
		}
		
		if($this->app['reg_award_val'] >0){//奖励事件
			if($this->app['reg_award'] =='vip'){
				$regData['vip'] = time() + $this->app['reg_award_val'];
			}else{
				$regData['fen'] += $this->app['reg_award_val'];
			}
		}
		
		if(isset($_POST['invid']) && !empty($_POST['invid'])){//邀请奖励事件
			$inv_res = $this->db->where('id = ? and appid = ?',[$_POST['invid'],$this->app['id']])->fetch();//查询邀请者ID
			if(!$inv_res)$this->out->e(122);//邀请人不存在
			$regData['inviter_id'] = $_POST['invid'];
			
			if($this->app['inviter_award_val'] > 0){//邀请者奖励数
				$data = [];
				$logData = [];
				if($this->app['inviter_award'] =='vip'){
					$logData['vip'] = '+'.$this->app['inviter_award_val'];
					if($inv_res['vip'] != 9999999999){//奖励类型是VIP
						if($inv_res['vip'] > time()){//VIP没有过期
							$data['vip'] = $inv_res['vip'] + $this->app['inviter_award_val'];
						}else{//VIP已过期
							$data['vip'] = time() + $this->app['inviter_award_val'];
						}
					}else{
						$data['vip'] = $inv_res['vip'];
					}
				}else{
					$logData['vip'] = '+'.$this->app['inviter_award_val'];
					$data['fen'] = $inv_res['fen'] + $this->app['inviter_award_val'];
				}
				
				$res = $this->db->where('id = ? ',[$_POST['invid']])->update(['vip'=>$data]);//更新邀请人VIP数据
				if($res){
					$this->__log($inv_res['id'],'inviter_award',200,$logData);
				}else{
					$this->__log($inv_res['id'],'inviter_award',201,$logData);
				}
			}
			
			if($this->app['invitee_award_val'] > 0){//受邀者奖励事件
				if($this->app['invitee_award'] =='vip'){
					if($regData['vip'] > time()){
						$regData['vip'] += $this->app['invitee_award_val'];
					}else{
					   $regData['vip'] = time()+$this->app['invitee_award_val']; 
					}
				}else{
					$regData['fen'] += $this->app['invitee_award_val'];
				}
			}
		}
		$res = $this->db->add($regData);
		if(!$res)$this->out->e(201,'注册失败，请重试');
		$this->out->e(200,'注册成功');
	}
	
	public function __getCode(){//获取验证码
		$db = db('vcode');
		$checkRules  = [
			'account' => ['email,phone','5,32','收信账号有误'],
			'type'  => ['sameone','reg,repwd,ubind,remc','验证码类型有误']//注册、重置密码、绑定账号、换绑机器码
		];	
		$dataChecker = t('dataChecker',$_POST, $checkRules);
		$res = $dataChecker->check();
		if(!$res)$this->out->e(201,$dataChecker->error);
		if($this->app['vc_length'])
		$code = getNumbercode($this->app['vc_length']);
		
		$vcnum = $db->where('ip = ? and time between ? and ?',[$this->ip,timeRange(),timeRange(0,1)])->count();
		if($vcnum >= 10)$this->out->e(117);
		
		$vcRes = $db->where('eorp = ? and time > ?',[$_POST['account'],time()-120])->fetch();
		if($vcRes)$this->out->e(116);
		
		if(strpos($_POST['account'],'@')){//邮箱
		    if($this->app['smtp_state'] != 'on' || empty($this->app['smtp_host']) || empty($this->app['smtp_user']) || empty($this->app['smtp_pass']) || empty($this->app['smtp_port']))$this->out->e(104);
			$mailer = t('mailer');
			$mail_config = ['Host'=>$this->app['smtp_host'],'Port'=>$this->app['smtp_port'],'FromName'=>$this->app['app_name'],'Username'=>$this->app['smtp_user'],'Password'=>$this->app['smtp_pass']];
			$type = ['reg'=>'注册账号','repwd'=>'重置密码','ubind'=>'绑定账号','remc'=>'设备换绑'];
			
			$title = $type[$_POST['type']].' - '.$this->app['app_name'];
			$send_res = $mailer->send($mail_config,[$_POST['account']],$title,"您本次操作的验证码是：<b>{$code}</b>,有效期为{$this->app['vc_time']}分钟，请尽快完成验证");
			if($send_res){
				$db->add(['eorp'=>$_POST['account'],'type'=>$_POST['type'],'code'=>$code,'time'=>time(),'ip'=>$this->ip,'appid'=>$this->app['id']]);
				$this->out->e(200,'验证码发送成功');
			}else{
				$this->out->e(201,'验证码发送失败');
			}
		}else{
		    if($this->app['sms_state'] != 'on')$this->out->e(105);
			$sms_config = json_decode($this->app['sms_config'],true);
			if(!is_array($sms_config))$this->out->e(105);
			$sms = t('sms')->send($_POST['account'],$code,$this->app['vc_time'],$this->app['sms_type'],$sms_config);
			if(!$sms)$this->out->e(201,'验证码发送失败');
			if($sms['code'] == 200){
				$db->add(['eorp'=>$_POST['account'],'type'=>$_POST['type'],'code'=>$code,'time'=>time(),'ip'=>$this->ip,'appid'=>$this->app['id']]);
				$this->out->e(200,'验证码发送成功');
			}else{
				$this->out->e(201,$sms['msg']);
			}
		}
		
	}
	
	public function __ini(){//获取配置
		$data = ['bb'=>$this->app['ver']['ver_val'],'new_content'=>$this->app['ver']['ver_new_content'],'new_url'=>$this->app['ver']['ver_new_url']];
		$notice_res = db('app_notice')->where('appid = ? or appid is null',[$this->app['id']])->order('id desc')->fetch('content,time');//获取最新的通知
		
		$exten = [];
		$app_exten = [];
		$app_exten_res = db('app_extend')->where('appid = ? or appid is null',[$this->app['id']])->order('id desc')->fetchAll();//获取扩展配置
		
		foreach ($app_exten_res as $k => $v){$rows = $app_exten_res[$k];
			isset($app_exten[$rows['var_key']]) || $app_exten[$rows['var_key']] = []; 
			$app_exten[$rows['var_key']][] = [$rows['var_key']=>$rows['var_val']];
		}
		foreach ($app_exten as $k => $v){
			if(count($app_exten[$k]) > 1){
				$exten = array_merge($exten,[$k=>$v]);
			}else{
				$value = $app_exten[$k][0];
				$exten = array_merge($exten,[$k=>$value[$k]]);
			}
		}
		if(count($app_exten) > 0){
			$data = array_merge($data,['exten'=>$exten,'notice'=>$notice_res]);
		}
		$this->out->setData($data)->e(200);
	}
	
	public function index(){
		if(!U_POST){$_POST = $_GET;}//如果不是POST请求，就吧GET请求传给POST
		if(count($this->gets)<3)$this->out->e(201,'api error');
		$appid = $this->gets[0];
		$ver = $this->gets[1];
		$this->m = $this->gets[2];
		$method = "__".$this->m;
		if(!method_exists($this,$method))$this->out->e(201,"api:{$this->m} error");
		$this->app = $this->__app($appid,$ver);
		$this->out = $this->out->setVer($this->app['ver'],$this->m);
		$this->__dataCheck();
		if($this->app['app_state'] == 'off')$this->out->e(100,$this->app['app_off_msg']);
		if($this->app['ver']['ver_state'] == 'off')$this->out->e(101,$this->app['ver']['ver_off_msg']);
		$this->$method();
	}
	
	protected function __app($id,$ver){
		$appRes = db('app')->where('id = ?',[$id])->fetch();
		if(!$appRes)$this->out->e(201,"appid error");
		$verRes = db('app_ver')->where('appid = ? and ver_key = ?',[$id,$ver])->fetch();
		if(!$verRes)$this->out->e(201,"ver error");
		unset($verRes['id']);
		unset($verRes['appid']);
		$appRes['ver'] = $verRes;
		return $appRes;
	}
	
	protected function __TokenCheck(){//Token检查
		$res = $this->Token->verify($_POST['token']);
		if(!$res)$this->out->e(128);
		
		if(!isset($this->Token->param['uid']) || !isset($this->Token->param['udid']) || !isset($this->Token->param['appid']) || !isset($this->Token->param['p']))$this->out->e(128);
		
		$Ures = $this->db->join("as U LEFT JOIN {$this->db->pre}agent as A on (U.id = A.uid)")->where('U.id = ?',[$this->Token->param['uid']])->fetch('U.*,IF(A.id IS NOT NULL,true,false) AS agent');
		if(!$Ures)$this->out->e(129);
		if($Ures['ban'] > time())$this->out->e(127,$Ures['ban_notice']);//账号被禁用
		if(md5($Ures['password']) != $this->Token->param['p'])$this->out->e(131);
		
		
		$client_Arr = json_decode($Ures['client_list'],true);
		$found_key = array_search($this->Token->param['udid'],array_column($client_Arr,'udid'));
		
		if($found_key !== 0 && !$found_key && !in_array($this->m,['getUdid','reUdid']))$this->out->e(130);//登录设备信息不匹配
		return $Ures;
	}
	
	protected function __dataCheck(){
		$encryption = !in_array($this->m,explode(",",$this->appConfig['API_WHITE']))?true:false;
		if($_POST && $encryption){
			$sign = isset($_POST['sign'])?$_POST['sign']:'';
			if($this->app['ver']['mi_state'] == 'on'){
				if(!isset($_POST['data']) || empty($_POST['data']))$this->out->e(111);
				if(empty($this->app['ver']['mi_key']))$this->out->e(112);
				$mi_key = json_decode($this->app['ver']['mi_key'],true);
				if(!isset($mi_key[$this->app['ver']['mi_type']]))$this->out->e(112);
				
				$keyConfig = $mi_key[$this->app['ver']['mi_type']];
				if($this->app['ver']['mi_type'] == 'rc4'){
					$dedata = t('Rc4')->mi($_POST['data'],$keyConfig,1);
					if(empty($dedata))$this->out->e(113);
					unset($_POST['data']);
					$_POST = txtArr($dedata);
				}elseif($this->app['ver']['mi_type'] == 'aes'){
					if(!isset($keyConfig['key']) || !isset($keyConfig['iv']))$this->out->e(112);
					$aes = t('Aes',$keyConfig['key'],$keyConfig['iv']);
					$dedata = $aes->decode($_POST['data']);
					if(empty($dedata))$this->out->e(113);
					unset($_POST['data']);
					$_POST = txtArr($dedata);
				}elseif($this->app['ver']['mi_type'] == 'rsa'){
					if(!isset($keyConfig['private']))$this->out->e(112);
					$Rsa = t('Rsa');//实例化对象
					$dedata = $Rsa->privateDecrypt($_POST['data'],$keyConfig['private']);
					if(empty($dedata))$this->out->e(113);
					unset($_POST['data']);
					$_POST = txtArr($dedata);
				}else{
					$this->out->e(114);
				}
			}
			
			if($this->app['ver']['mi_time'] > 0){
				if(!isset($_POST['time']) || (time()-intval($_POST['time'])) > $this->app['ver']['mi_time'])$this->out->e(110);
			}
			
			if($this->app['ver']['mi_sign'] == 'on'){
				if(empty($sign) || $sign != arrSign($_POST,$this->app['app_key']))$this->out->e(109);
			}
		}
	}
	
	protected function __log($uid,$type,$code=200,$data = null){
		if($this->appConfig['APP_USER_LOG'] == 'on'){//记录日志
			$addData = ['uid'=>$uid,'ug'=>'user','type'=>$type,'state'=>$code==200?'y':'n','time'=>time(),'ip'=>$this->ip,'appid'=>$this->app['id']];
			if(!empty($data)){
				$addData['data'] = json_encode($data);
			}
			db('logs')->add($addData);
		}
	}
}
