<?php
//后台文件
class adminController extends Ue{
	
	public $errorInfo = ['code'=>404,'msg'=>'访问页面不存在'];
	public $pageName = ['index'=>'首页','home'=>'总览','user'=>'用户管理','info'=>'应用信息','ver'=>'版本控制','reglog'=>'注册登录','award'=>'奖励控制','send'=>'发信控制','logs'=>'运行日志','extend'=>'扩展配置','notice'=>'通知公告','pay'=>'支付控制','goods'=>'商品管理','fen'=>'积分事件','order'=>'订单管理','agent_list'=>'代理列表','agent_group'=>'代理分组','agent_cash'=>'提现管理','kami_list'=>'卡密列表','kami_group'=>'卡密分组'];//页面名称
	public $pageEnums = 10;
	public $appid;
	public $app;
	
	public function __init(){
		$this->ip = t('ip')->getIp();//获取客户端IP
		$this->admConf = c('admin');
		$this->appConf = c('app');
		startSession();
		if(!defined('U_ADMIN')){
			if(md5($this->ip.$this->admConf['ADM_KEY']) != getSession('admSession')){
				$this->errorInfo = ['code'=>500,'msg'=>'管理后台已关闭，请使用后台入口访问进入:'.getSession('admSession')];
				$this->display('404.php');
			}
			define('U_ADMIN',TRUE);
		}else{
			setSession('admSession',md5($this->ip.$this->admConf['ADM_KEY']));
			header('location:/admin');
		}
		$this->Jwt = t('Jwt',$this->admConf['ADM_KEY'])->setIss($this->admConf['ADM_USER']);
		
		if(!in_array(U_M, ['login'])){//登录检测
			$this->__checkLogin();//检查登录
		}
		
		if(!in_array(U_M, ['login','index','logout'])){//应用检测
			$this->appid = getCookies('appid');
			if(intval($this->appid) < 0){
				$this->display(U_C.'/null.php');die();
			}
			$this->app = db('app')->where('id = ?',[$this->appid])->fetch();
			if(!$this->app){
				$this->display(U_C.'/null.php');die();
			}
		}
		
		$this->pageEnums = $this->appConf['APP_PAGE_ENUMS'];//每一页显示数据的条数
	}
	
	public function index(){
		if(U_POST){
			$checkRules  = [
				'act' => ['sameone','get,add,del','操作类型有误'],
				'pg' => ['int','1,11','页面有误',1,'get'],
				'name' => ['string','2,64','应用名称不规范',['add'=>false]],
				'bb' => ['between','1,999','应用版本号应在1~999区间',['add'=>'1.0']],
				'appid' => ['int','1,11','应用配置继承ID有误',['add'=>0]],
				'del_id' => ['int','1,11','删除应用ID有误',['del'=>false]],
			];
			$dataChecker = t('dataChecker',$_POST, $checkRules);
			$res = $dataChecker->check();
			if(!$res)$this->json($dataChecker->error,201);
			
			$db = db('app');
			if($_POST['act'] == 'get'){//获取APP列表
				$this->pageEnums = 12;
				$page = isset($_POST['pg']) ? (intval($_POST['pg']) >= 1 ? intval($_POST['pg']):1) : 1;
				
				$list = $db->join("as A LEFT JOIN (SELECT appid,COUNT(*) AS us FROM {$db->pre}user GROUP BY appid) AS U ON (A.id=U.appid) LEFT JOIN (SELECT appid,COUNT(*) AS ks FROM {$db->pre}kami GROUP BY appid) AS K ON (A.id=K.appid)")->order('id desc')->page($page,$this->pageEnums)->fetchAll('A.*,IFNULL(U.us,0) as u_total,IFNULL(K.ks,0) as k_total');
				
				$this->json('获取成功',200,$list);
			}elseif($_POST['act'] == 'add'){//添加APP
				$res = $db->where('app_name = ?',[$_POST['name']])->fetch();
				if($res)$this->json("应用名称重复",201);
				
				$add_data = ['app_name'=>$_POST['name'],'app_key'=>md5(getcode(10).uniqid())];
				
				if(count($_FILES) > 0 && isset($_FILES['file_applogo'])){
					$uper = t('uper','file_applogo','assets/logo');
					$uploadedFile = $uper->upload();
					if(!$uploadedFile){
						$this->json('LOGO上传错误 : '.$uper->error,201);
					}
					$add_data['app_logo'] = $uploadedFile;
				}
				
				if($_POST['appid'] > 0){
					$app_res = $this->db->where('id = ?',[$_POST['appid']])->fetch();
					if(!$app_res)$this->json('继承应用不存在',201);
					unset($app_res['id']);
					unset($app_res['app_name']);
					unset($app_res['app_key']);
					unset($app_res['app_logo']);
					$add_data = array_merge($add_data,$app_res);
				}
				
				$app_ver_db = db('app_ver');
				$db->beginTransaction();
				$add_id = $db->add($add_data);
				if($add_id){
					$app_ver_res = $app_ver_db->add(['appid'=>$add_id,'ver_val'=>$_POST['bb']]);
					if($app_ver_res){
						$db->commit();
					}else{
						$db->rollback();
						$this->json('版本创建失败，请重试',201);
					}
					
					$list = $db->join("as A LEFT JOIN (SELECT appid,COUNT(*) AS us FROM {$db->pre}user GROUP BY appid) AS U ON (A.id=U.appid) LEFT JOIN (SELECT appid,COUNT(*) AS ks FROM {$db->pre}kami GROUP BY appid) AS K ON (A.id=K.appid)")->where('A.id = ?',[$add_id])->fetch('A.*,IFNULL(U.us,0) as u_total,IFNULL(K.ks,0) as k_total');
					$this->app['id'] = $add_id;
					$this->__log('app_add');//记录日志
					
					$this->json('创建成功',200,['list'=>[$list]]);
				}$this->json('应用创建失败，请重试',201,$this->db->error());
			}elseif($_POST['act'] == 'del'){//删除APP
				$res = $db->where('id = ?', [$_POST['del_id']])->delete();
				$this->app['id'] = $_POST['del_id'];
				if($res){
					db('user')->where('appid = ?', [$_POST['del_id']])->delete();//清空用户
					db('kami')->where('appid = ?', [$_POST['del_id']])->delete();//清空卡密
					db('agent')->where('appid = ?', [$_POST['del_id']])->delete();//清空代理
					db('agent_group')->where('appid = ?', [$_POST['del_id']])->delete();//清空代理
					db('agent_cash')->where('appid = ?', [$_POST['del_id']])->delete();//清空代理提现记录
					db('app_ver')->where('appid = ?', [$_POST['del_id']])->delete();//清空版本
					db('app_extend')->where('appid = ?', [$_POST['del_id']])->delete();//清空扩展
					db('app_notice')->where('appid = ?', [$_POST['del_id']])->delete();//清空通知
					db('goods')->where('appid = ?', [$_POST['del_id']])->delete();//清空商品
					db('message')->where('appid = ?', [$_POST['del_id']])->delete();//清空留言
					db('order')->where('appid = ?', [$_POST['del_id']])->delete();//清空订单
					$this->__log('app_del');//记录日志
					$this->json('删除成功');
				}else{
					$this->__log('app_del',201);//记录日志
					$this->json('删除失败',201);
				}
			}else{
				$this->json('操作有误',201);
			}
		}
	}
	
	public function home(){
		if(U_POST){
			$checkRules  = ['act' => ['sameone','get','操作类型有误']];
			$dataChecker = t('dataChecker',$_POST, $checkRules);
			$res = $dataChecker->check();
			if(!$res)$this->json($dataChecker->error,201);
			
			if($_POST['act'] == 'get'){
				if(!isset($this->app) || !$this->app)$this->json('应用访问失败：该应用不存在',201);
				$data_res = m('admin')->getHome($this->app['id']);
				$this->json('成功',200,array_merge($this->app,['census'=>$data_res]));
			}
		}
	}
	
	public function user(){//用户管理
		if(U_POST){
			$checkRules  = [
				'act' => ['sameone','get,add,edit,del,del_client,log','操作类型有误'],
				'pg' => ['int','1,11','页面有误',['get'=>1]],
				'so' => ['string','1,64','搜索内容不规范',['get'=>true]],
				'id' => ['int','1,11','操作用户ID有误',['edit,del,del_client,log'=>false]],
				'acctNo' => ['wordnum','2,18','账号不规范',['add'=>false]],
				'password' => ['Password','5,18','密码不规范',['add'=>false,'edit'=>true]],
				'vip' => ['betweend','0,9999999999','VIP到期时间有误',['add,edit'=>true]],
				'fen' => ['int','1,10','积分数值不规范',0],
				'client_max' => ['int','1,10','额外绑定设备数不规范',0],
				'ban' => ['betweend','0,9999999999','禁用到期时间不规范',['edit'=>true]],
				'ban_msg' => ['String','2,255','禁用通知不规范',['edit'=>true]],
			];
			$dataChecker = t('dataChecker',$_POST, $checkRules);
			$res = $dataChecker->check();
			if(!$res)$this->json($dataChecker->error,201);
			
			$db = db('user');
			if($_POST['act'] == 'get'){
				$page = isset($_POST['pg']) ? (intval($_POST['pg']) >= 1 ? intval($_POST['pg']):1) : 1;
				if(isset($_POST['so']) && !empty($_POST['so'])){
					$db = $db->where('appid = ? and (nickname LIKE ? or phone LIKE ? or email LIKE ? or acctno LIKE ?)',[$this->app['id'],'%'.$_POST['so'].'%','%'.$_POST['so'].'%','%'.$_POST['so'].'%','%'.$_POST['so'].'%']);
				}else{
					$db = $db->where('appid = ?',[$this->app['id']]);
				}
				$list = $db->order('id desc')->page($page,$this->pageEnums)->fetchAll();
				if(!$list)$this->json('数据获取失败',201);
				$this->json('成功',200,$list);
				
			}
			
			if($_POST['act'] == 'add'){//添加
				$res = $db->where('acctno = ? and appid = ?',[$_POST['acctNo'],$this->app['id']])->fetch();
				if($res)$this->json("账号已存在",201);
				$data = ['acctno'=>$_POST['acctNo'],'password'=>md5($_POST['password']),'reg_time'=>time(),'reg_ip'=>$this->ip,'appid'=>$this->app['id']];
				if(isset($_POST['vip'])){$data['vip'] = $_POST['vip'];}
				$addid = $db->add($data);
				if($addid){
					$this->__log('user_add');//记录日志
					$list = $db->where('id = ?',[$addid])->fetch();
					
					$this->json('添加成功',200,['list'=>[$list]]);
				}else{
					$this->__log('user_add','n');//记录日志
					$this->json('添加失败:'.$db->error(),201);
				}
			}
			
			if($_POST['act'] == 'edit'){//编辑
				$data = ['vip'=>isset($_POST['vip'])?$_POST['vip']:0,'fen'=>$_POST['fen'],'client_max'=>$_POST['client_max'],'ban'=>$_POST['ban'],'ban_msg'=>isset($_POST['ban_msg'])?$_POST['ban_msg']:''];
				if(isset($_POST['password']) && !empty($_POST['password'])){
					$data['password'] = md5($_POST['password']);
				}
				
				$edit_user = $db->where('id = ?',[$_POST['id']])->fetch();
				if(!$edit_user)json('编辑用户不存在',201);
				
				$upRes = $db->where('id = ?',[$_POST['id']])->update($data);
				if($upRes){
					$this->__log('user_edit',200,['uid'=>$_POST['id']]);//记录日志
					$res = $db->where('id = ?',[$_POST['id']])->fetch();
					$this->json('编辑成功',200,['list'=>[$res]]);
				}else{
					$this->__log('user_edit',201,['uid'=>$_POST['id']]);//记录日志
					$this->json('编辑失败:'.$db->error(),201);
				}
			}
			
			if($_POST['act'] == 'del'){//删除
				$res = $db->where('id = ?', [$_POST['id']])->delete();
				if($res){
					$this->__log('user_del');//记录日志
					$this->json('删除成功');
				}else{
					$this->__log('user_del','n');//记录日志
					$this->json('删除失败',201);
				}
			}
			
			if($_POST['act'] == 'del_client'){//删除已登录设备
				$client_res = $db->where('id = ?',[$_POST['id']])->fetch('client_list');
				if(!$client_res)$this->json('编辑ID不存在',201);
				$client_arr = json_decode($client_res['client_list'],true);
				
				$client = [];
				foreach ($client_arr as $rows){
				    if($rows['udid'] != $_POST['cid']){
				        $client[] = ['udid'=>$rows['udid'],'time'=>$rows['time']];
				    }
				}
				
				$data = ['client_list'=>json_encode($client)];
				$res = $db->where('id = ?',[$_POST['id']])->update($data);
				if($res){
					$this->__log('client_del');//记录日志
					$this->json('解绑成功');
				}else{
					$this->__log('client_del','n');//记录日志
					$this->json('解绑失败',201);
				}
			}
			
			if($_POST['act'] == 'log'){//获取日志设备信息
				$logs = db('logs')->where('uid = ?', [$_POST['id']])->order('id desc')->limit(0,5)->fetchAll('ug,type,state,time,ip');
				
				$logs_list = [];
				$this->logType = c('logs');
				foreach ($logs as $rows){
					if($rows['ug']=='user'){
						$aren = '用户';
					}elseif($rows['ug']=='agent'){
						$aren = '代理';
					}else{
						$aren = '管理员';
					}
					
					$logs_list[] = [
						'act'=>!empty($this->logType[$rows['ug']][$rows['type']])?$this->logType[$rows['ug']][$rows['type']]:$rows['type'],
						'aren'=>$aren,
						'time'=>date("Y-m-d H:i",$rows['time']),
						'ip'=>$rows['ip'],
						'state'=>$rows['state']
					];
				}
				$this->json('成功',200,['list'=>$logs_list]);
			}
		}
	}
	
	public function kami_list(){//卡密列表
		if(U_POST){
			$checkRules  = [
				'act' => ['sameone','get,add,edit,del,get_kmg','操作类型有误'],
				'pg' => ['int','1,11','页面有误',['get'=>1]],
				'so' => ['string','1,64','搜索内容不规范',['get'=>true]],
				'id' => ['int','1,11','操作ID有误',['edit,del'=>false]],
				'kgid' => ['int','1,11','卡密分组有误',['add'=>false]],
				'note' => ['String','1,64','卡密备注不规范',['edit,add'=>true]],
				'length' => ['betweend','16,32','卡密长度有误(16~32位)',['add'=>false]],
				'state' => ['sameone','y,n','卡密状态有误',['edit'=>false]],
				'pre' => ['Kami','1,10','卡密前缀不规范：1~10位字符',['add'=>true]],
				'num' => ['betweend','1,10000','卡密生成数量有误，一次最多生成1W张',['add'=>false]],
				'out' => ['sameone','y,n','导出状态有误',['add'=>false]],
				'out_type' => ['sameone','txt,csv','导出类型有误',['add'=>false]],
			];
			$dataChecker = t('dataChecker',$_POST, $checkRules);
			$res = $dataChecker->check();
			if(!$res)$this->json($dataChecker->error,201);
			
			$db = db('kami');
			if($_POST['act'] == 'get'){
				$page = isset($_POST['pg']) ? (intval($_POST['pg']) >= 1 ? intval($_POST['pg']):1) : 1;
				
				$db = $db->join("as K LEFT JOIN {$db->pre}kami_group as KG on (K.kgid = KG.id) LEFT JOIN {$db->pre}user as U on (K.use_uid=U.id) LEFT JOIN {$db->pre}agent as AG on (K.add_uid=AG.id)");
				
				if(isset($_POST['so']) && !empty($_POST['so'])){
					$db = $db->where('K.appid = ? and (K.note LIKE ? or K.cardNo LIKE ? or KG.name LIKE ? or U.phone LIKE ? or U.email LIKE ? or U.acctno LIKE ?)',[$this->app['id'],'%'.$_POST['so'].'%','%'.$_POST['so'].'%','%'.$_POST['so'].'%','%'.$_POST['so'].'%','%'.$_POST['so'].'%','%'.$_POST['so'].'%']);
				}else{
					$db = $db->where('K.appid = ?',[$this->app['id']]);
				}
				
				$list = $db->order('K.id desc')->page($page,$this->pageEnums)->fetchAll("K.*,KG.name as Gname,IFNULL(U.phone,IFNULL(U.email,IFNULL(U.acctno,null))) as use_user,IFNULL(AG.cash_name,IFNULL(AG.note,'管理员')) as set_user");
				if(!$list)$this->json('数据获取失败'.$db->error(),201);
				
				$this->json('成功',200,$list);
			}
			
			if($_POST['act'] == 'add'){//添加
				$kg_res = db('kami_group')->where('id = ? and appid = ?',[$_POST['kgid'],$this->app['id']])->fetch();
				if(!$kg_res)$this->json("卡密组不存在",201);
				$dbkey = 'kgid,cardNo,type,val,note,add_time,add_ip,appid';
				if($_POST['out'] == 'y'){
					$dbkey .= ',out_state,out_time';
				}
				
				$addData = [];
				$note = empty($_POST['note'])?NULL:$_POST['note'];
				for($i=1;$i<=$_POST['num'];$i++){
					$kami = $_POST['pre'].strtoupper(str_shuffle(uniqid()).getcode($_POST['length']-13));
					$data = [$_POST['kgid'],$kami,$kg_res['type'],$kg_res['val'],$note,time(),$this->ip,$this->app['id']];
					if($_POST['out'] == 'y'){
						array_push($data,'y',time());
					}
					
					$addData[] = $data;
				}
		
				$addRes = $db->addbatch($dbkey,$addData);
				$snum = $db->rowCount();
				if($res && $snum >=1){
					
					$this->__log('kami_add');//记录日志
					if($_POST['out'] == 'y'){
						$kamiOut = t('kamiOut');
						$date = date('Y-m-d',time());
						$filename = date('Ymdhis',time()).$_POST['num'];
						$data = [['分组','卡号', '备注','日期']];
						foreach ($addData as $rosw){
							$data[] = [$kg_res['name'],$rosw[1],$note,$date];
						}
						
						if($_POST['out_type'] == 'csv'){
							$route = $kamiOut->createCsv($filename,$data);
						}else{
							$route = $kamiOut->createTxt($filename,$data);
						}
						$durl = '/'.U_C.'/download?path='.$route;
						$this->json('添加成功，本次添加：'.$snum.'条卡密，失败：'.($_POST['num']-$snum).'条',200,['downUrl'=>$durl]);
					}
					$this->json('添加成功，本次添加：'.$snum.'条卡密，失败：'.($_POST['num']-$snum).'条',200);
				}else{
					$this->__log('kami_add','n');//记录日志
					$this->json('添加失败:'.$db->error(),201,$db->getSql());
				}
				
			}
			
			if($_POST['act'] == 'edit'){//编辑
				$data = ['note'=>$_POST['note'],'state'=>$_POST['state']];
				
				
				$res = $db->where('id = ?',[$_POST['id']])->update($data);
				if($res){
					$list = $db->join("as K LEFT JOIN {$db->pre}kami_group as KG on (K.kgid = KG.id) LEFT JOIN {$db->pre}user as U on (K.use_uid=U.id) LEFT JOIN {$db->pre}agent as AG on (K.add_uid=AG.id)")->where('K.id = ?',[$_POST['id']])->fetch("K.*,KG.name as Gname,IFNULL(U.phone,IFNULL(U.email,IFNULL(U.acctno,null))) as use_user,IFNULL(AG.cash_name,IFNULL(AG.note,'管理员')) as set_user");
					$this->__log('kami_edit');//记录日志
					$this->json('编辑成功',200,['list'=>[$list]]);
					
				}else{
					$this->__log('kami_edit','n');//记录日志
					$this->json('编辑失败',201);
				}
			}
			
			if($_POST['act'] == 'del'){//删除
				$res = $db->where('id = ?', [$_POST['id']])->delete();
				if($res){
					$this->__log('kami_del');//记录日志
					$this->json('删除成功');
				}else{
					$this->__log('kami_del','n');//记录日志
					$this->json('删除失败',201);
				}
			}
			
			if($_POST['act'] == 'get_kmg'){//获取卡密组
				$kglist = db('kami_group')->where('appid = ?',[$this->app['id']])->fetchAll("id,name");
				if(!$kglist)$this->json('数据获取失败',201);
				$this->json('成功',200,['list'=>$kglist]);
			}
			
		}
	}
	
	public function kami_group(){//卡密分组
		if(U_POST){
			$checkRules  = [
				'act' => ['sameone','get,add,edit,del','操作类型有误'],
				'pg' => ['int','1,11','页面有误',['get'=>1]],
				'so' => ['string','1,64','搜索内容不规范',['get'=>true]],
				'id' => ['int','1,11','操作ID有误',['edit,del'=>false]],
				'type' => ['sameone','vip,fen,addmc','卡密组类型有误',['edit,add'=>false]],
				'name' => ['String','2,64','卡密组名称不规范',['edit,add'=>false]],
				'val' => ['int','1,10','卡密面值有误',['edit,add'=>false]],
				'price' => ['between','0,1000000','卡密定价有误，最高100W',['edit,add'=>true]],
			];
			$dataChecker = t('dataChecker',$_POST, $checkRules);
			$res = $dataChecker->check();
			if(!$res)$this->json($dataChecker->error,201);
			
			$db = db('kami_group');
			if($_POST['act'] == 'get'){
				$page = isset($_POST['pg']) ? (intval($_POST['pg']) >= 1 ? intval($_POST['pg']):1) : 1;
				
				$db = $db->join("as KG LEFT JOIN (SELECT kgid,COUNT(*) AS kms,count(case when use_time>0  then 1 end) as useNum FROM {$db->pre}kami GROUP BY kgid) AS K ON (KG.id=K.kgid)");
				
				if(isset($_POST['so']) && !empty($_POST['so'])){
					$db = $db->where('KG.appid = ? and (KG.name LIKE ? )',[$this->app['id'],'%'.$_POST['so'].'%']);
				}else{
					$db = $db->where('KG.appid = ?',[$this->app['id']]);
				}
				$list = $db->order('KG.id desc')->page($page,$this->pageEnums)->fetchAll("KG.*,IFNULL(K.kms,0) as km_num,IFNULL(K.useNum,0) as km_uses");
				if(!$list)$this->json('数据获取失败',201);
				
				$this->json('成功',200,$list);
			}
			
			if($_POST['act'] == 'add'){//添加
				$res = $db->where('name = ? and appid = ?',[$_POST['name'],$this->app['id']])->fetch();
				if($res)$this->json("卡密组已存在",201);
				$data = ['name'=>$_POST['name'],'type'=>$_POST['type'],'val'=>$_POST['val'],'price'=>$_POST['price'],'appid'=>$this->app['id']];
				$addid = $db->add($data);
				if($addid){
					$list = $db->where('id = ?',[$addid])->fetch();
					$list['km_num'] = 0;
					$list['km_uses'] = 0;
					$this->__log('kami_group_add');//记录日志
					$this->json('添加成功',200,['list'=>[$list]]);
				}else{
					$this->__log('kami_group_add','n');//记录日志
					$this->json('添加失败：'.$db->error(),201);
				}
			}
			
			if($_POST['act'] == 'edit'){//编辑
				$data = ['name'=>$_POST['name'],'type'=>$_POST['type'],'val'=>$_POST['val'],'price'=>$_POST['price']];
				
				$query_res = $db->where('appid = ? and name = ?',[$this->app['id'],$_POST['name']])->fetch();
				if($query_res && $query_res['id'] != $_POST['id'])$this->json('编辑失败，重复卡密组名称',201);
				
				
				$res = $db->where('id = ?',[$_POST['id']])->update($data);
				if($res){
					$list = $db->join("as KG LEFT JOIN (SELECT kgid,COUNT(*) AS kms,count(case when use_time>0  then 1 end) as useNum FROM {$db->pre}kami GROUP BY kgid) AS K ON (KG.id=K.kgid)")->where('KG.id = ?',[$_POST['id']])->fetch('KG.*,IFNULL(K.kms,0) as km_num,IFNULL(K.useNum,0) as km_uses');
					$this->__log('kami_group_edit');//记录日志
					$this->json('编辑成功',200,['list'=>[$list]]);
				}else{
					$this->__log('kami_group_edit','n');//记录日志
					$this->json('编辑失败：'.$db->error(),201);
				}
			}
			
			if($_POST['act'] == 'del'){//删除
				$res = $db->where('id = ?', [$_POST['id']])->delete();
				if($res){
					$this->__log('kami_group_del');//记录日志
					$this->json('删除成功');
				}else{
					$this->__log('kami_group_del','n');//记录日志
					$this->json('删除失败：'.$db->error(),201);
				}
			}
			
		}
	}
	
	public function agent_list(){//代理列表
		if(U_POST){
			$checkRules  = [
				'act' => ['sameone','get,add,edit,del,get_ag','操作类型有误'],
				'pg' => ['int','1,11','页面有误',['get'=>1]],
				'so' => ['string','1,64','搜索内容不规范',['get'=>true]],
				'id' => ['int','1,11','操作ID有误',['edit,del'=>false]],
				'aggid' => ['int','1,11','代理组有误',['edit,add'=>false]],
				'note' => ['String','1,64','备注不规范',['edit,add'=>true]],
				'uid' => ['int,phone,email,wordnum','1,64','用户账号不规范',['add'=>false]],
				'pay_divide' => ['betweend','0,100','充值分成比例不规范0~100',['edit'=>true]],
				'km_discount' => ['betweend','0,10','开卡折扣不规范0~10',['edit'=>true]],
				'state' => ['sameone','on,off','编辑状态有误',['edit'=>false]],
			];
			$dataChecker = t('dataChecker',$_POST, $checkRules);
			$res = $dataChecker->check();
			if(!$res)$this->json($dataChecker->error,201);
			
			$db = db('agent');
			if($_POST['act'] == 'get'){
				$page = isset($_POST['pg']) ? (intval($_POST['pg']) >= 1 ? intval($_POST['pg']):1) : 1;
				
				$db = $db->join("as A LEFT JOIN {$db->pre}agent_group as AG on (A.aggid = AG.id) LEFT JOIN {$db->pre}user as U on (A.uid=U.id) LEFT JOIN (SELECT inviter_id,COUNT(*) AS xu_num FROM {$db->pre}user GROUP BY inviter_id) AS XU ON (A.uid=XU.inviter_id)");
				
				if(isset($_POST['so']) && !empty($_POST['so'])){
					$db = $db->where('A.appid = ? and (A.note LIKE ? or A.cash_name LIKE ? or A.cash_account LIKE ? or U.phone LIKE ? or U.email LIKE ? or U.acctno LIKE ?)',[$this->app['id'],'%'.$_POST['so'].'%','%'.$_POST['so'].'%','%'.$_POST['so'].'%','%'.$_POST['so'].'%','%'.$_POST['so'].'%','%'.$_POST['so'].'%']);
				}else{
					$db = $db->where('A.appid = ?',[$this->app['id']]);
				}
				
				$list = $db->order('A.id desc')->page($page,$this->pageEnums)->fetchAll("A.*,AG.name as Gname,U.avatars,IFNULL(U.phone,IFNULL(U.email,U.acctno)) as user,IFNULL(XU.xu_num,0) as xu_num");
				if(!$list)$this->json('数据获取失败'.$db->error(),201);
				
				$this->json('成功',200,$list);
			}
			
			if($_POST['act'] == 'add'){//添加
				$AGres = db('agent_group')->where('id = ? and appid = ?',[$_POST['aggid'],$this->app['id']])->fetch();
				if(!$AGres)$this->json("代理组不存在",201);
				
				$Ures = db('user')->where('(id = ? or email = ? or phone = ? or acctno = ?) and appid = ?',[$_POST['uid'],$_POST['uid'],$_POST['uid'],$_POST['uid'],$this->app['id']])->fetch();
				if(!$Ures)$this->json("代理用户不存在",201);
				
				$res = $db->where('uid = ?',[$Ures['id']])->fetch();
				if($res)$this->json("当前代理已存在",201);
				
				$data = ['aggid'=>$AGres['id'],'uid'=>$Ures['id'],'note'=>$_POST['note'],'pay_divide'=>$AGres['pay_divide'],'km_discount'=>$AGres['km_discount'],'time'=>time(),'appid'=>$this->app['id']];
				$addID = $db->add($data);
				if($addID){
					$this->__log('agent_add');//记录日志
					$list = $db->where('id = ?',[$addID])->fetch();
					$list['Gname'] = $AGres['name'];
					$list['avatars'] = $Ures['avatars'];
					$list['user'] = !empty($Ures['phone'])?$Ures['phone']:(!empty($Ures['email'])?$Ures['email']:$Ures['acctno']);
					$this->json('添加成功',200,['list'=>$list]);
				}else{
					$this->__log('agent_add','n');//记录日志
					$this->json('添加失败',201);
				}
				
			}
			
			if($_POST['act'] == 'edit'){//编辑
				$AGres = db('agent_group')->where('id = ? and appid = ?',[$_POST['aggid'],$this->app['id']])->fetch();
				if(!$AGres)$this->json("代理组不存在",201);
				
				$data = ['aggid'=>$AGres['id'],'pay_divide'=>$AGres['pay_divide'],'km_discount'=>$AGres['km_discount'],'note'=>$_POST['note'],'state'=>$_POST['state']];
				if(!empty($_POST['pay_divide'])){
					$data['pay_divide'] = $_POST['pay_divide'];
				}
				if(!empty($_POST['km_discount'])){
					$data['km_discount'] = $_POST['km_discount'];
				}
				
				$res = $db->where('id = ?',[$_POST['id']])->update($data);
				if($res){
					$list = $db->join("as A LEFT JOIN {$db->pre}agent_group as AG on (A.aggid = AG.id) LEFT JOIN {$db->pre}user as U on (A.uid=U.id) LEFT JOIN (SELECT inviter_id,COUNT(*) AS xu_num FROM {$db->pre}user GROUP BY inviter_id) AS XU ON (A.uid=XU.inviter_id)")->where('A.id = ?',[$_POST['id']])->fetch("A.*,AG.name as Gname,U.avatars,IFNULL(U.phone,IFNULL(U.email,U.acctno)) as user,IFNULL(XU.xu_num,0) as xu_num");
					$this->__log('agent_edit');//记录日志
					$this->json('编辑成功',200,['list'=>[$list]]);
					
				}else{
					$this->__log('agent_edit','n');//记录日志
					$this->json('编辑失败：'.$db->error(),201);
				}
			}
			
			if($_POST['act'] == 'del'){//删除
				$res = $db->where('id = ?', [$_POST['id']])->delete();
				if($res){
					$this->__log('agent_del');//记录日志
					$this->json('删除成功');
				}else{
					$this->__log('agent_del','n');//记录日志
					$this->json('删除失败',201);
				}
			}
			
			if($_POST['act'] == 'get_ag'){//获取卡密组
				$list = db('agent_group')->where('appid = ?',[$this->app['id']])->fetchAll("id,name,pay_divide,km_discount");
				if(!$list)$this->json('数据获取失败',201);
				$this->json('成功',200,['list'=>$list]);
			}
			
		}
	}
	
	public function agent_group(){//代理分组
		if(U_POST){
			$checkRules  = [
				'act' => ['sameone','get,add,edit,del','操作类型有误'],
				'pg' => ['int','1,11','页面有误',['get'=>1]],
				'so' => ['string','1,64','搜索内容不规范',['get'=>true]],
				'id' => ['int','1,11','操作ID有误',['edit,del'=>false]],
				'name' => ['String','2,64','卡密组名称不规范',['edit,add'=>false]],
				'pay_divide' => ['betweend','0,100','充值分成比例不规范0~100',['edit,add'=>true]],
				'km_discount' => ['betweend','0,10','开卡折扣不规范0~10',['edit,add'=>true]],
			];
			$dataChecker = t('dataChecker',$_POST, $checkRules);
			$res = $dataChecker->check();
			if(!$res)$this->json($dataChecker->error,201);
			
			
			$db = db('agent_group');
			if($_POST['act'] == 'get'){
				$page = isset($_POST['pg']) ? (intval($_POST['pg']) >= 1 ? intval($_POST['pg']):1) : 1;
				
				if(isset($_POST['so']) && !empty($_POST['so'])){
					$db = $db->where('appid = ? and (name LIKE ? )',[$this->app['id'],'%'.$_POST['so'].'%']);
				}else{
					$db = $db->where('appid = ?',[$this->app['id']]);
				}
				$list = $db->order('id desc')->page($page,$this->pageEnums)->fetchAll();
				if(!$list)$this->json('数据获取失败',201);
				
				$this->json('成功',200,$list);
			}
			
			if($_POST['act'] == 'add'){//添加
				$res = $db->where('name = ? and appid = ?',[$_POST['name'],$this->app['id']])->fetch();
				if($res)$this->json("代理组已存在",201);
				$data = ['name'=>$_POST['name'],'pay_divide'=>$_POST['pay_divide'],'km_discount'=>$_POST['km_discount'],'appid'=>$this->app['id']];
				$addid = $db->add($data);
				if($addid){
					$list = $db->where('id = ?',[$addid])->fetch();
					$this->__log('agent_group_add');//记录日志
					$this->json('添加成功',200,['list'=>[$list]]);
				}else{
					$this->__log('agent_group_add','n');//记录日志
					$this->json('添加失败：'.$db->error(),201);
				}
			}
			
			if($_POST['act'] == 'edit'){//编辑
				$data = ['name'=>$_POST['name'],'pay_divide'=>$_POST['pay_divide'],'km_discount'=>$_POST['km_discount']];
				
				$query_res = $db->where('appid = ? and name = ?',[$this->app['id'],$_POST['name']])->fetch();
				if($query_res && $query_res['id'] != $_POST['id'])$this->json('编辑失败，重复代理组名称',201);
				
				
				$res = $db->where('id = ?',[$_POST['id']])->update($data);
				if($res){
					$list = $db->where('id = ?',[$_POST['id']])->fetch();
					$this->__log('agent_group_edit');//记录日志
					$this->json('编辑成功',200,['list'=>[$list]]);
				}else{
					$this->__log('agent_group_edit','n');//记录日志
					$this->json('编辑失败：'.$db->error(),201);
				}
			}
			
			if($_POST['act'] == 'del'){//删除
				$res = $db->where('id = ?', [$_POST['id']])->delete();
				if($res){
					$this->__log('agent_group_del');//记录日志
					$this->json('删除成功');
				}else{
					$this->__log('agent_group_del','n');//记录日志
					$this->json('删除失败',201);
				}
			}
			
		}
	}
	
	public function agent_cash(){//提现管理
		if(U_POST){
			$checkRules  = [
				'act' => ['sameone','get,pay,rebut','操作类型有误'],
				'pg' => ['int','1,11','页面有误',['get'=>1]],
				'so' => ['string','1,64','搜索内容不规范',['get'=>true]],
				'state' => ['Between','-1,3','搜索状态不规范',['get'=>true]],
				'id' => ['int','1,11','操作ID有误',['pay,rebut'=>false]],
				'rebut_msg' => ['String','2,255','驳回理由不规范',['rebut'=>false]],
			];
			$dataChecker = t('dataChecker',$_POST, $checkRules);
			$res = $dataChecker->check();
			if(!$res)$this->json($dataChecker->error,201);
			
			
			$db = db('agent_cash');
			if($_POST['act'] == 'get'){
				$page = isset($_POST['pg']) ? (intval($_POST['pg']) >= 1 ? intval($_POST['pg']):1) : 1;
				$status = isset($_POST['state']) ? $_POST['state']:-1;
				
				if(isset($_POST['so']) && !empty($_POST['so'])){
					if($status > -1){
					    $db = $db->where('appid = ? and state = ? (name LIKE ? or account LIKE ?)',[$this->app['id'],$status,'%'.$_POST['so'].'%','%'.$_POST['so'].'%']);
					}else{
					    $db = $db->where('appid = ? and (name LIKE ? or account LIKE ?)',[$this->app['id'],'%'.$_POST['so'].'%','%'.$_POST['so'].'%']);
					}
				}else{
					if($status > -1){
					    $db = $db->where('appid = ? and state = ?',[$this->app['id'],$status]);
					}else{
					    $db = $db->where('appid = ?',[$this->app['id']]);
					}
				}
				$list = $db->order('id desc')->page($page,$this->pageEnums)->fetchAll();
				if(!$list)$this->json('数据获取失败',201);
				
				$this->json('成功',200,$list);
			}
			
			if($_POST['act'] == 'pay'){//打款
				$res = $db->where('id = ?',[$_POST['id']])->update(['state'=>2,'end_time'=>time()]);
				if($res){
					$list = $db->where('id = ?',[$_POST['id']])->fetch();
					$this->json('打款状态更新成功',200,['list'=>[$list]]);
				}else{
					$this->json('打款状态更新失败：'.$db->error(),201);
				}
			}
			
			if($_POST['act'] == 'rebut'){//驳回
				$ACres = $db->where('id = ?',[$_POST['id']])->fetch();
				if(!$ACres)$this->json("驳回账单不存在",201);
				
				$db->beginTransaction();
				$res = $db->where('id = ?',[$_POST['id']])->update(['state'=>1,'rebut_msg'=>$_POST['rebut_msg'],'end_time'=>time()]);
				if(!$res)$this->json('驳回失败：'.$db->error(),201);
				
				$sres = db('agent')->where('id = ?',[$ACres['agid']])->field(['money'=>$ACres['money']]);
				if(!$sres){
					$db->rollback();//事务回滚
					$this->json('驳回回款失败',201);
				}
				$db->commit();//提交
				$list = $db->where('id = ?',[$_POST['id']])->fetch();
				$this->json('驳回成功',200,['list'=>[$list]]);
			}
			
		}
	}
	
	public function goods(){//商品管理
		if(U_POST){
			$checkRules  = [
				'act' => ['sameone','get,add,edit,del,get_ag','操作类型有误'],
				'pg' => ['int','1,11','页面有误',['get'=>1]],
				'so' => ['string','1,64','搜索内容不规范',['get'=>true]],
				'id' => ['int','1,11','操作ID有误',['edit,del'=>false]],
				'name' => ['string','1,64','搜索内容不规范',['add,edit'=>false]],
				'type' => ['sameone','vip,fen,agent','商品类型有误',['add,edit'=>false]],
				'val' => ['int','1,11','会员值或代理组有误',['add,edit'=>false]],
				'money' => ['money','1,999999','会员值或代理组有误',['add,edit'=>false]],
				'blurb' => ['string','1,255','商品介绍不规范',['add,edit'=>true]],
				'state' => ['sameone','y,n','商品状态不规范',['edit'=>false]],
			];
			$dataChecker = t('dataChecker',$_POST, $checkRules);
			$res = $dataChecker->check();
			if(!$res)$this->json($dataChecker->error,201);
			
			
			$db = db('goods');
			if($_POST['act'] == 'get'){
				$page = isset($_POST['pg']) ? (intval($_POST['pg']) >= 1 ? intval($_POST['pg']):1) : 1;
				
				$db = $db->join("as G LEFT JOIN {$db->pre}agent_group AS AG ON G.val = AG.id AND G.type='agent'");
				if(isset($_POST['so']) && !empty($_POST['so'])){
					$db = $db->where('G.appid = ? and (G.name LIKE ? or G.blurb LIKE ?)',[$this->app['id'],'%'.$_POST['so'].'%','%'.$_POST['so'].'%']);
				}else{
					$db = $db->where('G.appid = ?',[$this->app['id']]);
				}
				$list = $db->order('G.id desc')->page($page,$this->pageEnums)->fetchAll("G.*,CASE WHEN G.type='agent' THEN AG.name ELSE NULL END AS AGname");
				if(!$list)$this->json('数据获取失败',201);
				
				$this->json('成功',200,$list);
			}
			
			if($_POST['act'] == 'add'){//添加
				if($_POST['type'] == 'agent'){
					$AGres = db('agent_group')->where('id = ? and appid = ?',[$_POST['val'],$this->app['id']])->fetch();
					if(!$AGres)$this->json("代理组不存在",201);
				}
				
				$data = ['name'=>$_POST['name'],'type'=>$_POST['type'],'val'=>$_POST['val'],'money'=>$_POST['money'],'blurb'=>$_POST['blurb'],'appid'=>$this->app['id']];
				
				$addID = $db->add($data);
				if($addID){
					$this->__log('goods_add');//记录日志
					$list = $db->where('id = ?',[$addID])->fetch();
					if($_POST['type'] == 'agent'){
						$list['AGname'] = $AGres['name'];
					}
					$this->json('添加成功',200,['list'=>[$list]]);
				}else{
					$this->__log('goods_add','n');//记录日志
					$this->json('添加失败：'.$db->error(),201);
				}
			}
			
			if($_POST['act'] == 'edit'){//添加
				if($_POST['type'] == 'agent'){
					$AGres = db('agent_group')->where('id = ? and appid = ?',[$_POST['val'],$this->app['id']])->fetch();
					if(!$AGres)$this->json("代理组不存在",201);
				}
				
				$data = ['name'=>$_POST['name'],'type'=>$_POST['type'],'val'=>$_POST['val'],'money'=>$_POST['money'],'blurb'=>$_POST['blurb'],'state'=>$_POST['state']];
				
				$res = $db->where('id = ?',[$_POST['id']])->update($data);
				if($res){
					$this->__log('goods_edit');//记录日志
					$list = $db->where('id = ?',[$_POST['id']])->fetch();
					if($_POST['type'] == 'agent'){
						$list['AGname'] = $AGres['name'];
					}
					$this->json('更新成功',200,['list'=>[$list]]);
				}else{
					$this->__log('goods_edit','n');//记录日志
					$this->json('更新失败：'.$db->error(),201);
				}
			}
			
			if($_POST['act'] == 'del'){//删除
				$res = $db->where('id = ?', [$_POST['id']])->delete();
				if($res){
					$this->__log('goods_del');//记录日志
					$this->json('删除成功');
				}else{
					$this->__log('goods_del','n');//记录日志
					$this->json('删除失败',201);
				}
			}
			
			if($_POST['act'] == 'get_ag'){//获取卡密组
				$list = db('agent_group')->where('appid = ?',[$this->app['id']])->fetchAll("id,name");
				if(!$list)$this->json('数据获取失败',201);
				$this->json('成功',200,['list'=>$list]);
			}
		}
	}
	
	public function order(){//商品订单
		if(U_POST){
			$checkRules  = [
				'act' => ['sameone','get,pay,rebut','操作类型有误'],
				'pg' => ['int','1,11','页面有误',['get'=>1]],
				'so' => ['string','1,64','搜索内容不规范',['get'=>true]],
				'state' => ['Between','-1,3','搜索状态不规范',['get'=>true]],
				'id' => ['int','1,11','操作ID有误',['pay,rebut'=>false]],
			];
			$dataChecker = t('dataChecker',$_POST, $checkRules);
			$res = $dataChecker->check();
			if(!$res)$this->json($dataChecker->error,201);
			$db = db('order');
			
			if($_POST['act'] == 'get'){
				$page = isset($_POST['pg']) ? (intval($_POST['pg']) >= 1 ? intval($_POST['pg']):1) : 1;
				$status = isset($_POST['state']) ? $_POST['state']:-1;
				
				$db = $db->join("as O LEFT JOIN {$db->pre}user as U on (O.Uid = U.id)");
				if(isset($_POST['so']) && !empty($_POST['so'])){
					if($status > -1){
					    $db = $db->where('O.appid = ? and O.state = ? (O.order_no LIKE ? or O.trade_no LIKE ? or O.name LIKE ? or U.phone LIKE ? or U.email LIKE ? or U.acctno LIKE ?)',[$this->app['id'],$status,'%'.$_POST['so'].'%','%'.$_POST['so'].'%','%'.$_POST['so'].'%','%'.$_POST['so'].'%','%'.$_POST['so'].'%','%'.$_POST['so'].'%']);
					}else{
					    $db = $db->where('O.appid = ? and (O.order_no LIKE ? or O.trade_no LIKE ? or O.name LIKE ? or U.phone LIKE ? or U.email LIKE ? or U.acctno LIKE ?)',[$this->app['id'],'%'.$_POST['so'].'%','%'.$_POST['so'].'%','%'.$_POST['so'].'%','%'.$_POST['so'].'%','%'.$_POST['so'].'%','%'.$_POST['so'].'%']);
					}
				}else{
					if($status > -1){
					    $db = $db->where('O.appid = ? and O.state = ?',[$this->app['id'],$status]);
					}else{
					    $db = $db->where('O.appid = ?',[$this->app['id']]);
					}
				}
				$list = $db->order('O.id desc')->page($page,$this->pageEnums)->fetchAll("O.*,IFNULL(U.phone,IFNULL(U.email,U.acctno)) as user");
				if(!$list)$this->json('数据获取失败',201);
				
				$this->json('成功',200,$list);
			}
		}
	}
	
	public function fen(){//积分事件
		if(U_POST){
			$checkRules  = [
				'act' => ['sameone','get,add,edit,del','操作类型有误','get'],
				'pg' => ['int','1,11','页面有误',['get'=>1]],
				'so' => ['string','1,64','搜索内容不规范',['get'=>true]],
				'id' => ['int','1,11','操作商品ID有误',['edit,del'=>false]],
				'name' => ['String','2,125','事件名称不规范',['add,edit'=>false]],
				'fen' => ['betweend','1,1000000','事件扣除积分数值有误',['add,edit'=>false]],
				'vip' => ['betweend','0,9999999999','事件兑换会员数值有误',['add,edit'=>true]],
				'vip_free' => ['sameone','y,n','VIP免费选择有误',['add,edit'=>true]],
				'state' => ['sameone','on,off','商品状态有误',['edit'=>false]],
			];
			$dataChecker = t('dataChecker',$_POST, $checkRules);
			$res = $dataChecker->check();
			if(!$res)$this->json($dataChecker->error,201);
			$db = db('fen_event');
			
			$page = isset($_POST['pg']) ? (intval($_POST['pg']) >= 1 ? intval($_POST['pg']):1) : 1;
			if($_POST['act'] == 'get'){
				$db = $db->join("as F LEFT JOIN (SELECT fid,COUNT(*) AS fss FROM {$db->pre}fen_order GROUP BY fid) AS FO ON (F.id=FO.fid)");
				
				if(isset($_POST['so']) && !empty($_POST['so'])){
					$db = $db->where('F.appid = ? and F.name LIKE ?',[$this->app['id'],'%'.$_POST['so'].'%']);
				}else{
					$db = $db->where('F.appid = ?',[$this->app['id']]);
				}
				$list = $db->order('F.id desc')->page($page,$this->pageEnums)->fetchAll("F.*,IFNULL(FO.fss,0) as Fo_num");
				
				if(!$list)$this->json('数据获取失败',201);
				$this->json('成功',200,$list);
			}
			
			if($_POST['act'] == 'add'){//添加
				$res = $db->where('name = ? and appid = ?',[$_POST['name'],$this->app['id']])->fetch();
				if($res)$this->json("事件名称已存在",201);
				$data = ['name'=>$_POST['name'],'fen'=>$_POST['fen'],'vip'=>$_POST['vip'],'vip_free'=>$_POST['vip_free'],'appid'=>$this->app['id']];
				$addid = $db->add($data,true);
				if($addid){
					$this->__log('fen_event_add');//记录日志
					$res = $db->where('id = ?',[$addid])->fetch();
					$this->json('添加成功',200,['list'=>[$res]]);
					
				}else{
					$this->__log('fen_event_add',201);//记录日志
					$this->json('添加失败',201);
				}
			}
			
			if($_POST['act'] == 'edit'){//编辑
				$data = ['name'=>$_POST['name'],'fen'=>$_POST['fen'],'vip'=>$_POST['vip'],'vip_free'=>$_POST['vip_free'],'state'=>$_POST['state']];
				
				$query_res = $db->where('appid = ? and name = ?',[$this->app['id'],$_POST['name']])->fetch();
				if($query_res && $query_res['id'] != $_POST['id'])$this->json('编辑失败，重复事件名称',201);
				
				
				$upRes = $db->where('id = ?',[$_POST['id']])->update($data,true);
				if($upRes){
					$this->__log('fen_event_edit');//记录日志
					$res = $db->where('id = ?',[$_POST['id']])->fetch();
					$this->json('编辑成功',200,['list'=>[$res]]);
				}else{
					$this->__log('fen_event_edit',201);//记录日志
					$this->json('编辑失败',201);
				}
			}
			
			if($_POST['act'] == 'del'){//删除
				$res = $db->where('id = ?', [$_POST['id']])->delete();
				if($res){
					$this->__log('fen_event_del');//记录日志
					$this->json('删除成功');
				}else{
					$this->__log('fen_event_del',201);//记录日志
					$this->json('删除失败',201);
				}
			}
		}
	}
	
	public function message(){
		if(U_POST){
			$checkRules  = [
				'act' => ['sameone','get,get_msg,reply,del','操作类型有误'],
				'pg' => ['int','1,11','页面有误',['get'=>1]],
				'so' => ['string','1,64','搜索内容不规范',['get'=>true]],
				'state' => ['Between','-1,2','搜索状态不规范',['get'=>true]],
				'id' => ['int','1,11','操作ID有误',['get_msg,reply,del'=>false]],
				'content' => ['string','1,255','回复消息不规范',['reply'=>false]],
			];
			$dataChecker = t('dataChecker',$_POST, $checkRules);
			$res = $dataChecker->check();
			if(!$res)$this->json($dataChecker->error,201);
			$db = db('message');
			
			if($_POST['act'] == 'get'){
				$page = isset($_POST['pg']) ? (intval($_POST['pg']) >= 1 ? intval($_POST['pg']):1) : 1;
				$status = isset($_POST['state']) ? $_POST['state']:-1;
				
				$db = $db->join("as M LEFT JOIN {$db->pre}user as U on (M.Uid = U.id)");
				if(isset($_POST['so']) && !empty($_POST['so'])){
					if($status > -1){
					    $db = $db->where('M.appid = ? and M.state = ? and M.reply_id is null and (M.title LIKE ? or M.content LIKE ?)',[$this->app['id'],$status,'%'.$_POST['so'].'%','%'.$_POST['so'].'%']);
					}else{
					    $db = $db->where('M.appid = ? and M.reply_id is null and (M.title LIKE ? or M.content LIKE ?)',[$this->app['id'],'%'.$_POST['so'].'%','%'.$_POST['so'].'%']);
					}
				}else{
					if($status > -1){
					    $db = $db->where('M.appid = ? and M.state = ? and M.reply_id is null',[$this->app['id'],$status]);
					}else{
					    $db = $db->where('M.appid = ? and M.reply_id is null',[$this->app['id']]);
					}
				}
				$list = $db->order('M.id desc')->page($page,$this->pageEnums)->fetchAll("M.*,IFNULL(U.phone,IFNULL(U.email,IFNULL(U.acctno,'管理员'))) as user");
				if(!$list)$this->json('数据获取失败',201);
				
				$this->json('成功',200,$list);
			}
			
			if($_POST['act'] == 'reply'){//回复
				$Mres = $db->where('id = ?',[$_POST['id']])->fetch();
				if(!$Mres)$this->json('回复留言不存在',201);
				if($Mres['state']==2)$this->json('该留言已解决，无需继续回复',201);
				
				$data = ['content'=>$_POST['content'],'reply_id'=>$_POST['id'],'time'=>time(),'appid'=>$this->app['id']];
				
				if(count($_FILES) > 0){
					$file = [];
					foreach ($_FILES as $key => $rows){
						$uper = t('uper',$key, 'assets/message/'.$_POST['id'].'/'.date('Ymd'));
						$uploadedFile = $uper->upload();
						if(!$uploadedFile)$this->json('图片上传错误 : '.$uper->error,201);
						array_push($file,$uploadedFile);
					}
					$data['file'] = json_encode($file);
				}
				$addID = $db->add($data);
				if($addID){
					$db->where('id = ?',[$_POST['id']])->update(['last_time'=>time(),'state'=>1]);
					$list = ['user'=>'管理员','time'=>$data['time'],'content'=>$data['content'],'file'=>null,'state'=>0];
					if(isset($data['file'])){
						$list['file'] = $data['file'];
					}
					$this->json('回复成功',200,['list'=>[$list]]);
				}else{
					$this->json('回复失败：'.$db->error(),201);
				}
				
			}
			
			if($_POST['act'] == 'get_msg'){
				$db = $db->join("as M LEFT JOIN {$db->pre}user as U on (M.Uid = U.id)");
				$list = $db->where('(M.id = ? or M.reply_id = ?) and M.appid = ?',[$_POST['id'],$_POST['id'],$this->app['id']])->fetchAll("M.*,U.avatars,IFNULL(U.phone,IFNULL(U.email,IFNULL(U.acctno,'管理员'))) as user");
				if(!$list)$this->json('数据获取失败'.$db->error(),201);
				
				$db->where('uid IS NOT NULL and reply_id = ?',[$_POST['id']])->update(['state'=>2]);
				$this->json('成功',200,['list'=>$list]);
			}
			
			if($_POST['act'] == 'del'){//删除
				$res = $db->where('id = ? or reply_id = ?', [$_POST['id'],$_POST['id']])->delete();
				if($res){
					$this->__log('message_del');//记录日志
					$this->json('删除成功');
				}else{
					$this->__log('message_del','n');//记录日志
					$this->json('删除失败',201);
				}
			}
		}
	}
	
	public function info(){//应用信息
		if(U_POST){
			$checkRules  = [
				'app_name' => ['string','2,64','应用名称不规范'],
				'app_key' => ['wordnum','16,32','应用KEY必须是字母+数字16~32位'],
				'app_mode' => ['sameone','y,n','应用模式有误'],
				'app_state' => ['sameone','on,off','应用状态有误'],
				'app_off_msg' => ['string','2,255','应用关闭通知内容不规范',true]
			];
			$dataChecker = t('dataChecker',$_POST, $checkRules);
			$res = $dataChecker->check();
			if(!$res)$this->json($dataChecker->error,201);
			
			$db = db('app');
			$res = $db->where('app_name = ?',[$_POST['app_name']])->fetch();
			if($res && $res['id'] != $this->app['id'])$this->json("应用名称与其他应用名称重复",201);
			
			$update = ['app_name'=>$_POST['app_name'],'app_key'=>$_POST['app_key'],'app_mode'=>$_POST['app_mode'],'app_state'=>$_POST['app_state'],'app_off_msg'=>$_POST['app_off_msg']];
			
			if(count($_FILES) > 0 && isset($_FILES['app_logo'])){
				$uper = t('uper','app_logo', 'data/logo');
				$uploadedFile = $uper->upload();
				if(!$uploadedFile){
					$this->json('LOGO上传错误 : '.$uper->error,201);
				}
				$update['app_logo'] = $uploadedFile;
			}
			
			$update_res = $db->where('id = ?',[$this->app['id']])->update($update);
			if($update_res){
				$this->__log('app_info_edit');//记录日志
				if($db->rowCount() >=1){
					$this->json('更新成功',200);
				}else{
					$this->json('没有更改任何内容',201);
				}
			}else{
				$this->__log('app_info_edit','n');//记录日志
				$this->json('更新失败',201);
			}
		}
	}
	
	public function reglog(){//注册登录
		if(U_POST){
			$checkRules  = [
				'reg_state' => ['sameone','on,off','注册控制状态有误'],
				'logon_state' => ['sameone','on,off','登录控制状态有误'],
				'reg_way' => ['sameone','phone,email,wordnum','注册方式有误'],
				'reg_time_mc' => ['Int','1,10','应用版本号应在1~999区间'],
				'reg_time_ip' => ['Int','1,10','应用版本号应在1~999区间'],
				'reg_off_msg' => ['string','2,255','关闭注册提示内容不规范',true],
				
				'logon_mc_num' => ['Int','1,2','登录设备数设置有误'],
				'logon_mc_unbdeType' => ['sameone','vip,fen','解绑扣费类型有误'],
				'logon_mc_unbdeVal' => ['Int','1,10','解绑扣费值有误'],
				'logon_off_msg' => ['string','2,255','关闭登录提示内容不规范',true],
			];
			
			$dataChecker = t('dataChecker',$_POST, $checkRules);
			$res = $dataChecker->check();
			if(!$res)$this->json($dataChecker->error,201);
			
			$db = db('app');
			
			$update_res = $db->where('id = ?',[$this->app['id']])->update($_POST);
			if($update_res){
				$this->__log('reglog_edit');//记录日志
				if($db->rowCount() >=1){
					$this->json('更新成功',200);
				}else{
					$this->json('没有更改任何内容',201);
				}
			}else{
				$this->__log('reglog_edit','n');//记录日志
				$this->json('更新失败'.$db->error(),201);
			}
		}
	}
	
	public function award(){//奖励控制
		if(U_POST){
			$checkRules  = [
				'reg_award' => ['sameone','vip,fen','注册奖励类型设置有误'],
				'reg_award_val' => ['Int','1,10','注册奖励数设置有误'],
				'invitee_award' => ['sameone','vip,fen','受邀者奖励类型设置有误'],
				'invitee_award_val' => ['Int','1,10','受邀者奖励数量设置有误'],
				'inviter_award' => ['sameone','vip,fen','邀请者奖励类型设置有误'],
				'inviter_award_val' => ['Int','1,10','邀请者奖励数量设置有误'],
				'diary_award' => ['sameone','vip,fen','签到奖励类型设置有误'],
				'diary_award_val' => ['Int','1,10','签到奖励数量设置有误'],
			];
			
			$dataChecker = t('dataChecker',$_POST, $checkRules);
			$res = $dataChecker->check();
			if(!$res)$this->json($dataChecker->error,201);
			
			$db = db('app');
			
			$update_res = $db->where('id = ?',[$this->app['id']])->update($_POST);
			if($update_res){
				$this->__log('award_edit');//记录日志
				if($db->rowCount() >=1){
					$this->json('更新成功',200);
				}else{
					$this->json('没有更改任何内容',201);
				}
			}else{
				$this->__log('award_edit','n');//记录日志
				$this->json('更新失败'.$this->db->error(),201);
			}
		}
	}
	
	public function send(){//发信控制
		$this->smsPlug = t('sms')->init();
		if(U_POST){
			$checkRules  = [
			    'vc_length' => ['between','4,6','验证码长度有误'],
			    'vc_time' => ['between','1,30','验证码有效期有误'],
			    
				'smtp_state' => ['sameone','on,off','邮箱控制状态设置有误','off'],
				'sms_state' => ['sameone','on,off','短信控制状态设置有误','off'],
				
				'smtp_host' => ['string','8,64','邮箱发信服务器设置有误',true],
				'smtp_port' => ['Int','2,4','邮箱端口设置有误',true],
				'smtp_user' => ['string','4,64','邮箱发信账号设置有误',true],
				'smtp_pass' => ['string','4,64','邮箱发信密码设置有误',true],
				
				'sms_type' => ['wordnumS','2,12','短信发信类型不规范','jie'],
				'sms_config' => ['string','1,2048','短信发信参数不规范',true],
			];
			
			$dataChecker = t('dataChecker',$_POST, $checkRules);
			$res = $dataChecker->check();
			if(!$res)$this->json($dataChecker->error,201);
			
			$db = db('app');
			$_POST['sms_config'] = htmlspecialchars_decode($_POST['sms_config']);
			$update_res = $db->where('id = ?',[$this->app['id']])->update($_POST);
			if($update_res){
				$this->__log('send_edit');//记录日志
				if($db->rowCount() >=1){
					$this->json('更新成功',200);
				}else{
					$this->json('没有更改任何内容',201);
				}
			}else{
				$this->__log('send_edit','n');//记录日志
				$this->json('更新失败',201);
			}
		}
	}
	
	public function pay(){//支付
		$this->plug = t('pay')->init();
		if(U_POST){
			$checkRules  = [
				'pay_ali_state' => ['sameone','on,off','支付宝控制状态设置有误','off'],
				'pay_wx_state' => ['sameone','on,off','微信控制状态设置有误','off'],
				
				'pay_ali_type' => ['wordnumS','2,12','支付宝支付引擎不规范','jie'],
				'pay_wx_type' => ['wordnumS','2,12','微信支付引擎不规范','jie'],
				
				'pay_ali_config' => ['string','1,2048','支付宝支付参数不规范',true],
				'pay_wx_config' => ['string','1,2048','微信支付参数不规范',true],
			];
			
			$dataChecker = t('dataChecker',$_POST, $checkRules);
			$res = $dataChecker->check();
			if(!$res)$this->json($dataChecker->error,201);
			$db = db('app');
			$_POST['pay_ali_config'] = htmlspecialchars_decode($_POST['pay_ali_config']);
			$_POST['pay_wx_config'] = htmlspecialchars_decode($_POST['pay_wx_config']);
			$update_res = $db->where('id = ?',[$this->app['id']])->update($_POST);
			if($update_res){
				$this->__log('pay_edit');//记录日志
				if($db->rowCount() >=1){
					$this->json('更新成功',200);
				}else{
					$this->json('没有更改任何内容',201);
				}
			}else{
				$this->__log('pay_edit','n');//记录日志
				$this->json('更新失败'.$db->error(),201);
			}
		}
	}
	
	public function extend(){//扩展配置
		if(U_POST){
			$checkRules  = [
				'act' => ['sameone','get,add,edit,del','操作类型有误','get'],
				'pg' => ['int','1,11','页面有误',['get'=>1]],
				'so' => ['string','1,64','搜索内容不规范',true],
				'id' => ['int','1,11','操作ID有误',['del,edit'=>false]],
				'name' => ['string','2,64','扩展名不规范',['add,edit'=>false]],
				'var_key' => ['wordnumS','2,16','扩展键不规范,仅允许字母加数字,且必须字母开头',['add,edit'=>false]],
				'var_val' => ['string','2,128','扩展值不规范',['add,edit'=>false]],
				'all' => ['sameone','y,n','全局配置选择有误','n'],
			];
			$dataChecker = t('dataChecker',$_POST, $checkRules);
			$res = $dataChecker->check();
			if(!$res)$this->json($dataChecker->error,201);
			
			$db = db('app_extend');
			$page = isset($_POST['pg']) ? (intval($_POST['pg']) >= 1 ? intval($_POST['pg']):1) : 1;
			
			if($_POST['act'] == 'get'){//获取
				if(isset($_POST['so']) && !empty($_POST['so'])){
					$db = $db->where('(appid = ? or appid is null) and (name LIKE ? or var_key LIKE ? or var_val LIKE ?)',[$this->app['id'],'%'.$_POST['so'].'%','%'.$_POST['so'].'%','%'.$_POST['so'].'%']);
				}else{
					$db = $db->where('appid = ? or appid is null',[$this->app['id']]);
				}
				$list = $db->order('id desc')->page($page,$this->pageEnums)->fetchAll();
				
				if(!$list)$this->json('列表获取失败',201);
				
				$this->json('列表获取成功',200,$list);
			}
			
			if($_POST['act'] == 'add'){//添加
				$query_res = $db->where('(appid = ? or appid is null) and var_key = ?',[$this->app['id'],$_POST['var_key']])->fetch();
				if($query_res)$this->json('添加失败，存在重复扩展键',201);
				if($_POST['all'] == 'n'){
				    $addid = $db->add(['name'=>$_POST['name'],'var_key'=>$_POST['var_key'],'var_val'=>$_POST['var_val'],'appid'=>$this->app['id']],true);
				}else{
				   $addid = $db->add(['name'=>$_POST['name'],'var_key'=>$_POST['var_key'],'var_val'=>$_POST['var_val']],true); 
				}
				
				if($addid){
					$this->__log('extend_add');//记录日志
					$list = $db->where('id = ?',[$addid])->fetch();
					$this->json('添加成功',200,['list'=>[$list]]);
				}$this->json('添加失败'.$db->error(),201);
			}
			
			if($_POST['act'] == 'edit'){//编辑
				$edit_id = $_POST['id'];
				if(isset($_POST['var_key'])){
					$query_res = $db->where('(appid = ? or appid is null) and ver_key = ?',[$this->app['id'],$_POST['var_key']])->fetch();
					if($query_res && $query_res['id'] != $edit_id)$this->json('编辑失败，存在重复扩展键',201);
				}
				if($_POST['all'] == 'n'){
				    $update_res = $db->where('id = ?',[$edit_id])->update(['name'=>$_POST['name'],'var_key'=>$_POST['var_key'],'var_val'=>$_POST['var_val'],'appid'=>$this->app['id']]);
				}else{
				    $update_res = $db->where('id = ?',[$edit_id])->update(['name'=>$_POST['name'],'var_key'=>$_POST['var_key'],'var_val'=>$_POST['var_val'],'appid'=>null]);
				}
				
				if($update_res){
					$this->__log('extend_edit');//记录日志
					if($db->rowCount() >=1){
						$this->json('更新成功',200);
					}else{
						$this->json('没有更改任何内容',201);
					}
				}else{
					$this->__log('extend_edit','n');//记录日志
					$this->json('更新失败'.$db->error(),201);
				}
			}
			
			if($_POST['act'] == 'del'){//删除
				$res = $db->where('id = ?', [$_POST['id']])->delete();
				if($res){
					$this->__log('extend_del');//记录日志
					$this->json('删除成功');
				}else{
					$this->__log('extend_del','n');//记录日志
					$this->json('删除失败',201);
				}
			}
		}
	}
	
	public function notice(){//通知公告
		if(U_POST){
			$checkRules  = [
				'act' => ['sameone','get,add,del','操作类型有误','get'],
				'pg' => ['int','1,11','页面有误',['get'=>1]],
				'id' => ['int','1,11','操作ID有误',['del'=>false]],
				'content' => ['string','2,2048','发布内容不规范',['add'=>false]],
				'all' => ['sameone','y,n','全局配置选择有误',['add'=>'n']],
			];
			$dataChecker = t('dataChecker',$_POST, $checkRules);
			$res = $dataChecker->check();
			if(!$res)$this->json($dataChecker->error,201);
			
			$db = db('app_notice');
			$page = isset($_POST['pg']) ? (intval($_POST['pg']) >= 1 ? intval($_POST['pg']):1) : 1;
			if($_POST['act'] == 'get'){//获取
				$db = $db->where('appid = ? or appid is null',[$this->app['id']]);
				$list = $db->order('id desc')->page($page,$this->pageEnums)->fetchAll();
				
				if(!$list)$this->json('列表获取失败',201);
				$this->json('列表获取成功',200,$list);
			}
			
			if($_POST['act'] == 'add'){//添加
				if($_POST['all'] == 'n'){
				    $addid = $db->add(['content'=>$_POST['content'],'time'=>time(),'appid'=>$this->app['id']],true);
				}else{
				    $addid = $db->add(['content'=>$_POST['content'],'time'=>time()],true);
				}
				if($addid){
					$this->__log('notice_add');//记录日志
					$list = $db->where('id = ?',[$addid])->fetch();
					$this->json('添加成功',200,['list'=>[$list]]);
				}$this->json('添加失败'.$db->error(),201);
			}
			
			if($_POST['act'] == 'del'){//删除
				$res = $db->where('id = ?', [$_POST['id']])->delete();
				if($res){
					$this->__log('notice_del');//记录日志
					$this->json('删除成功');
				}else{
					$this->__log('notice_del','n');//记录日志
					$this->json('删除失败',201);
				}
			}
		}
	}
	
	public function ver(){//版本控制
		if(U_POST){
			$checkRules  = [
				'act' => ['sameone','get,add,edit,edit_state,del','操作类型有误'],
				'pg' => ['int','1,11','页面有误',['get'=>1]],
				'id' => ['int','1,11','操作版本ID有误',['edit,del'=>false]],
				'ver_val' => ['between','1,999','应用版本号应在1~999区间',['add'=>'1.0','edit'=>false]],
				'ver_name' => ['string','2,64','版本名称不规范',['add,edit'=>false]],
				'ver_key' => ['wordnum','2,16','版本索引不规范',['add,edit'=>false]],
				'ver_new_url' => ['Url','2,128','更新地址不规范',['add,edit'=>true]],
				'ver_new_content' => ['string','2,255','更新内容不规范',['add,edit'=>true]],
				'mi_time' => ['int','1,11','时差校验值不规范',['add,edit'=>0]],
				'mi_rc4_key' => ['Password','10,32','RC4加密密钥长度应该>=10~32',['add,edit'=>true]],
				'mi_aes_key' => ['string','32,32','AES加密密钥长度必须为32',['add,edit'=>true]],
				'mi_aes_iv' => ['string','16,16','AES加密IV长度必须为16',['add,edit'=>true]],
				'mi_public_key' => ['string','200,300','RSA公钥内容不规范',['add,edit'=>true]],
				'mi_private_key' => ['string','800,900','RSA私钥内容不规范',['add,edit'=>true]],
				'ver_off_msg' => ['string','2,255','版本关闭通知内容不规范',['add,edit'=>'当前版本维护中']],
				'mi_type' => ['sameone','rsa,rc4,ase','加密类型有误',['add'=>'rc4','edit'=>true]],
				'mi_sign' => ['sameone','off,on','数据签名状态操作有误',['add'=>'off','edit'=>false,'edit_state'=>true]],
				'mi_state' => ['sameone','off,on','数据加密状态操作有误',['add'=>'off','edit'=>false,'edit_state'=>true]],
				'ver_state' => ['sameone','off,on','版本开关状态操作有误',['add'=>'on','edit'=>false,'edit_state'=>true]],
			];
			$dataChecker = t('dataChecker',$_POST, $checkRules);
			$res = $dataChecker->check();
			if(!$res)$this->json($dataChecker->error,201);
			
			$db = db('app_ver');
			if($_POST['act'] == 'get'){//获取
				$this->pageEnums = 12;
				$page = isset($_POST['pg']) ? (intval($_POST['pg']) >= 1 ? intval($_POST['pg']):1) : 1;
				
				$ver_list = $db->where('appid = ?',[$this->app['id']])->order('id desc')->page($page,$this->pageEnums)->fetchAll();
				if(!$ver_list)$this->json('版本列表获取失败',201);
				
				$this->json('版本列表获取成功',200,$ver_list);
			}
			
			if($_POST['act'] == 'add'){//添加
				$query_res = $db->where('appid = ? and ver_key = ?',[$this->app['id'],$_POST['ver_key']])->fetch();
				if($query_res)$this->json('添加失败，存在重复版本索引',201);
				$add = [
					'appid' => $this->app['id'],
					'ver_val' => $_POST['ver_val'],
					'ver_name' => $_POST['ver_name'],
					'ver_key' => $_POST['ver_key'],
					'ver_new_url' => $_POST['ver_new_url'],
					'ver_new_content' => $_POST['ver_new_content'],
					'mi_time' => $_POST['mi_time'],
					'ver_off_msg' => $_POST['ver_off_msg'],
					'mi_type' => $_POST['mi_type'],
					'mi_sign'=>$_POST['mi_sign'],
					'mi_state'=>$_POST['mi_state'],
					'ver_state' => $_POST['ver_state']
				];
				$keyConfig = [];
				if($add['mi_type'] == 'rc4'){
					$keyConfig['rc4'] = $_POST['mi_rc4_key'];
				}
				if($add['mi_type'] == 'aes'){
					$keyConfig['aes'] = ['key'=>$_POST['mi_aes_key'],'iv'=>$_POST['mi_aes_iv']];
				}
				if($add['mi_type'] == 'rsa'){
					$keyConfig['rsa'] = ['public'=>$_POST['mi_public_key'],'private'=>$_POST['mi_private_key']];
				}
				$add['mi_key'] = json_encode($keyConfig);
				
				$add_id = $db->add($add);
				if($add_id){
					$this->__log('var_add');//记录日志
					$list = $db->where('appid = ? and id = ?',[$this->app['id'],$add_id])->fetch();
					$this->json('添加成功',200,['list'=>[$list]]);
				}$this->json('添加失败'.$db->error(),201);
			}
			
			if($_POST['act'] == 'edit'){//编辑
				$query_res = $db->where('appid = ? and ver_key = ?',[$this->app['id'],$_POST['ver_key']])->fetch();
				if($query_res && $query_res['id'] != $_POST['id'])$this->json('编辑失败，存在重复版本索引',201);
				
				$update = [
					'ver_val' => $_POST['ver_val'],
					'ver_name' => $_POST['ver_name'],
					'ver_key' => $_POST['ver_key'],
					'ver_new_url' => $_POST['ver_new_url'],
					'ver_new_content' => $_POST['ver_new_content'],
					'mi_time' => $_POST['mi_time'],
					'ver_off_msg' => $_POST['ver_off_msg'],
					'mi_type' => $_POST['mi_type'],
					'mi_sign'=>$_POST['mi_sign'],
					'mi_state'=>$_POST['mi_state'],
					'ver_state' => $_POST['ver_state']
				];
				$keyConfig = [];
				if($update['mi_type'] == 'rc4'){
					$keyConfig['rc4'] = $_POST['mi_rc4_key'];
				}
				if($update['mi_type'] == 'aes'){
					$keyConfig['aes'] = ['key'=>$_POST['mi_aes_key'],'iv'=>$_POST['mi_aes_iv']];
				}
				if($update['mi_type'] == 'rsa'){
					$keyConfig['rsa'] = ['public'=>$_POST['mi_public_key'],'private'=>$_POST['mi_private_key']];
				}
				$update['mi_key'] = json_encode($keyConfig);
				
				$update_res = $db->where('id = ?',[$_POST['id']])->update($update);
				if($update_res){
					$this->__log('var_edit');//记录日志
					if($db->rowCount() >=1){
						$list = $db->where('appid = ? and id = ?',[$this->app['id'],$_POST['id']])->fetch();
						$this->json('更新成功',200,['list'=>[$list]]);
					}else{
						$this->json('没有更改任何内容',201);
					}
				}else{
					$this->__log('var_edit','n');//记录日志
					$this->json('更新失败'.$db->error(),201);
				}
			}
			if($_POST['act'] == 'edit_state'){//编辑
				$edit_id = $_POST['id'];
				unset($_POST['act']);
				unset($_POST['id']);
				$update_res = $db->where('id = ?',[$edit_id])->update($_POST);
				if($update_res){
					$this->__log('var_edit_state');//记录日志
					$this->json('更新成功',200);
				}else{
					$this->__log('var_edit_state','n');//记录日志
					$this->json('更新失败'.$db->error(),201);
				}
			}
			
			if($_POST['act'] == 'del'){//删除
				$res = $db->where('id = ?', [$_POST['id']])->delete();
				if($res){
					$this->__log('var_del');//记录日志
					$this->json('删除成功');
				}else{
					$this->__log('var_del','n');//记录日志
					$this->json('删除失败',201);
				}
			}	
		}
	}
	
	public function logs(){//日志
		$this->logType = c('logs');
		if(U_POST){
			$checkRules  = [
				'act' => ['sameone','get','操作类型有误'],
				'pg' => ['int','1,11','页面有误',true],
				'so' => ['string','1,64','搜索内容不规范',true],
				'type' => ['string','1,64','搜索类型不规范','all'],
			];
			$dataChecker = t('dataChecker',$_POST, $checkRules);
			$res = $dataChecker->check();
			if(!$res)$this->json($dataChecker->error,201);
			
			$db = db('logs');
			if($_POST['act'] == 'get'){
				$page = isset($_POST['pg']) ? (intval($_POST['pg']) >= 1 ? intval($_POST['pg']):1) : 1;
				$db = $db->join("as LOG left join {$db->pre}user as U on (LOG.uid = U.id)");
				if($_POST['type'] != 'all'){
					if(isset($_POST['so']) && !empty($_POST['so'])){
						$db = $db->where('LOG.appid = ? and LOG.type LIKE ? and (U.email LIKE ? or U.phone LIKE ? or U.acctno LIKE ?)',[$this->app['id'],'%'.$_POST['type'].'%','%'.$_POST['so'].'%','%'.$_POST['so'].'%','%'.$_POST['so'].'%']);
					}else{
						$db = $db->where('LOG.appid = ? and LOG.type LIKE ?',[$this->app['id'],'%'.$_POST['type'].'%']);
					}
				}else{
					if(isset($_POST['so']) && !empty($_POST['so'])){
						$db = $db->where('LOG.appid = ? and (U.email LIKE ? or U.phone LIKE ? or U.acctno LIKE ?)',[$this->app['id'],'%'.$_POST['so'].'%','%'.$_POST['so'].'%','%'.$_POST['so'].'%']);
					}else{
						$db = $db->where('LOG.appid = ?',[$this->app['id']]);
					}
				}
				$logList = $db->order('LOG.id desc')->page($page,$this->pageEnums)->fetchAll('LOG.*,U.email,U.phone,U.acctno');
				if(!$logList)$this->json('数据获取失败',201);
				$list = [];
				foreach ($logList['list'] as $rows){
					$actUser = empty($rows['uid'])?'admin':(!empty($rows['email'])?$rows['email']:(!empty($rows['phone'])?$rows['phone']:$rows['acctno']));
					$list[] = [
						'id'=>$rows['id'],
						'type'=>!empty($this->logType[$rows['type']])?$this->logType[$rows['type']]:$rows['type'],
						'person'=>empty($rows['uid'])?'管理员':'用户',
						'user'=>$actUser,
						'time'=>date("Y-m-d H:i",$rows['time']),
						'ip'=>$rows['ip'],
						'state'=>$rows['state']
					];
				}
				
				$logList['list'] = $list;
				$this->json('成功',200,$logList);
			}
		}
	}
	
	public function login(){//登录
		$this->admcookies = getCookies('admcookies');
		if($this->admcookies){
			if($this->__checkCookies()){
				header('location:/admin');
			}
		}
		if(U_POST){
			$checkRules  = [
				'user' => ['wordnum','5,18','账号不规范'],
				'password'  => ['Password','6,18','密码不规范']
			];
			$dataChecker = t('dataChecker',$_POST, $checkRules);
			$res = $dataChecker->check();
			if(!$res)$this->json($dataChecker->error,201);
			
			if($this->admConf['ADM_USER'] == $_POST['user'] && $this->admConf['ADM_PASSWORD'] == md5($_POST['password'].$this->admConf['ADM_KEY'])){
        		$token = $this->Jwt->getToken();
				setCookies('admcookies',$token,24*60*60);
				$this->__log(U_M);//记录日志
				$this->json('登录成功');
			}else{
				$this->__log(U_M,201);//记录日志
				$this->json('账号密码不正确',201);
			}
		}
	}
	public function logout(){//退出登录
		delCookies("admcookies");
		header('location:/admin/login');
	}
	
	public function set(){//系统设置
		if(U_POST){
			$checkRules  = [
				'web_url' => ['url','','系统URL不规范'],
				'app_page_enums' => ['between','10,100','页面数据条数不规范，仅限（10~100）范围'],
				'app_adm_log'  => ['sameone','on,off','记录管理员日志状态不规范'],
				'app_user_log'  => ['sameone','on,off','记录用户日志状态不规范'],
				'user_upfile_size'  => ['between','1,10','上传文件字节限制不规范，仅限（1~10）范围'],
				'api_run_cost'  => ['sameone','on,off','API运算成本状态不规范'],
				'api_out_type'  => ['sameone','json,xml','API输出类型有误'],
				'api_white'  => ['String','1,128','API白名单不规范'],
				'sys_cache' => ['sameone','on,off','系统缓存开关设置有误'],
				'sys_debug' => ['sameone','on,off','系统调试模式设置有误'],
				'sys_error' => ['sameone','on,off','系统调报错开关设置有误'],
				'error_uploading' => ['sameone','on,off','错误信息上传开关设置有误'],
			];
			$dataChecker = t('dataChecker',$_POST, $checkRules);
			$res = $dataChecker->check();
			if(!$res)$this->json($dataChecker->error,201);
			
			$indexdata = file_get_contents('index.php');
			if($_POST['sys_cache'] == 'off'){
			    if(!U_CACHE){$indexdata = str_replace("'U_CACHE',FALSE", "'U_CACHE',TRUE",$indexdata);}
			}else{
			    if(U_CACHE){$indexdata = str_replace("'U_CACHE',TRUE", "'U_CACHE',FALSE", $indexdata);}
			}
			if($_POST['sys_error'] == 'on'){
			    if(!U_ERROR){$indexdata = str_replace("'U_ERROR',FALSE", "'U_ERROR',TRUE",$indexdata);}
			}else{
			    if(U_ERROR){$indexdata = str_replace("'U_ERROR',TRUE", "'U_ERROR',FALSE", $indexdata);}
			}
			if($_POST['sys_debug'] == 'on'){
			    if(!U_DEBUG){$indexdata = str_replace("'U_DEBUG',FALSE", "'U_DEBUG',TRUE",$indexdata);}
			}else{
			    if(U_DEBUG){$indexdata = str_replace("'U_DEBUG',TRUE", "'U_DEBUG',FALSE", $indexdata);}
			}
			if($_POST['error_uploading'] == 'on'){
			    if(!U_ERROR_UPLOADING){$indexdata = str_replace("'U_ERROR_UPLOADING',FALSE", "'U_ERROR_UPLOADING',TRUE",$indexdata);}
			}else{
			    if(U_ERROR_UPLOADING){$indexdata = str_replace("'U_ERROR_UPLOADING',TRUE", "'U_ERROR_UPLOADING',FALSE", $indexdata);}
			}
			file_put_contents('index.php',$indexdata);
			$data = [
				'WEB_URL'        => $_POST['web_url'],
				'APP_PAGE_ENUMS' => (int)$_POST['app_page_enums'],
				'APP_ADM_LOG'    => $_POST['app_adm_log'],
				'APP_USER_LOG'   => $_POST['app_user_log'],
				'API_RUN_COST'   => $_POST['api_run_cost'],
				'API_OUT_TYPE'   => $_POST['api_out_type'],
				'API_WHITE'      => $_POST['api_white'],
				'USER_UPFILE_SIZE'   => (int)$_POST['user_upfile_size'],
			];
			$res = cAlter('app',$data);
			if($res){
				$this->__log(U_M);//记录日志
				$this->json('编辑成功');
			}else{
				$this->__log(U_M,'n');//记录日志
				$this->json('编辑失败',201);
			}
		}
	}
	
	public function cap(){//修改账号密码
		if(U_POST){
			$checkRules  = [
				'user' => ['wordnum','5,18','账号不规范'],
				'pwd'  => ['Password','6,18','密码不规范'],
				'newpwd'  => ['Password','6,18','新密码不规范',true]
			];
			$dataChecker = t('dataChecker',$_POST, $checkRules);
			$res = $dataChecker->check();
			if(!$res)$this->json($dataChecker->error,201);
			
			if($this->admConf['ADM_PASSWORD'] == md5($_POST['pwd'].$this->admConf['ADM_KEY'])){
                $user = $_POST['user'];
                $newpwd = $_POST['newpwd'];
                if(empty($newpwd) && $user == $this->admConf['ADM_USER'])$this->json('未修改任何参数',201);
				
                $userdata = file_get_contents(U_CONF.U_D.'admin.php');
				$userdata = preg_replace("/'ADM_USER'=>'.*?'/", "'ADM_USER'=>'{$user}'", $userdata);
				if(!empty($newpwd)){
                    $newpwd = md5($newpwd.$this->admConf['ADM_KEY']);
                    $userdata = preg_replace("/'ADM_PASSWORD'=>'.*?'/", "'ADM_PASSWORD'=>'{$newpwd}'", $userdata);
				}
				$res = file_put_contents(U_CONF.U_D.'admin.php', $userdata);
				if($res){
				    $this->__log(U_M);//记录日志
					delCookies("admcookies");
					$this->json('修改成功');
				}else{
				    $this->__log(U_M,'n');//记录日志
				    $this->json('密码错误',201);
				}
			}$this->json('当前密码错误',201);
		}
	}
	
	protected function __checkLogin(){//检查登录
		$this->admcookies = getCookies('admcookies');
		if(!$this->admcookies){
			if(U_POST){
				$this->json('登录后操作',201);
			}header('location:/admin/login');
		}else{
			if(!$this->__checkCookies()){
				header('location:/admin/login');
			}
		}
	}
	
	public function __checkCookies(){//检查cookies
		$verifyResult = $this->Jwt->verifyToken($this->admcookies);
		if (!$verifyResult)return false;
		$this->pName = isset($this->pageName[U_M])?$this->pageName[U_M]:$this->pageName['index'];
		$this->title = $this->pName.' - 管理中心 - '.$this->appConf['APP_NAME'];
		return true;
	}
	
	protected function __log($type,$state='y',$expandArr=[]){//记录日志
		if($this->appConf['APP_ADM_LOG'] == 'on'){
			$logData = ['ug'=>'adm','type'=>$type,'time'=>time(),'ip'=>$this->ip,'state'=>$state];
			if(isset($this->app) && isset($this->app['id'])){
				$logData['appid'] = $this->app['id'];
			}
			$logData = array_merge($logData,$expandArr);
			db('logs')->add($logData);
		}
	}
	
	public function download(){
		$path = isset($_GET['path'])?$_GET['path']:$this->display('404.php');
		if (!file_exists($path)) {
			$this->display('404.php');
		}
		t('download')->download($path,basename($path));
	}
}