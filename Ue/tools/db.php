<?php
/**
 * mysql 数据库操作类基于 pdo
 * @link      http://www.Uephp.com
 * @copyright Copyright (c) 2022-2032 
 * @author    易友
 * @version   1.0.0
**/
namespace Ue\tools;
class db {
	
	public static  $operater;
	public $tableName;
	public $pdo;
	public $sql;
	public $pretreatment;
	public $where;
	public $group;
	public $join;
	public $order;
	public $limit;
	public $Page;
	public $conf;
	public $pre;
	
	public function __construct($conf){
		if(empty($conf['databaseType'])){$conf['databaseType'] = 'mysql';}
		$this->conf = $conf;
		$this->pre = $conf['pre'];
		try{
			switch($this->conf['databaseType']){
				case "sqlsrv":
				$this->pdo  = new \PDO("{$conf['databaseType']}:Server={$conf['host']},{$conf['port']};Database={$conf['dbname']}", $conf['user'], $conf['pwd']);
				break;
				case "mysql":
				$this->pdo  = new \PDO("mysql:host={$conf['host']};port={$conf['port']};dbname={$conf['dbname']}", $conf['user'], $conf['pwd']);
				$this->pdo->query("set names {$conf['charset']}");
				break;				
			}			
		}catch(Exception $e){throw new \Exception('数据库连接失败:请检查数据库相关配置');}
	}
	
	
	public function __clone(){
		$this->sql = null;
		$this->pretreatment = null;
		$this->where = null;
		$this->group = null;
		$this->join = null;
		$this->order = null;
		$this->limit = null;
	}
	
	public static function getInstance($conf, $tableName, $configName){
		$tableName = $conf['pre'].$tableName;
		if(empty(self::$operater[$configName])){
			self::$operater[$configName] = new db($conf);
			self::$operater[$configName]->tableName = $tableName;
			return self::$operater[$configName];
		}
		if(self::$operater[$configName]->tableName == $tableName){return self::$operater[$configName];}
		$cloner = clone self::$operater[$configName];
		$cloner->tableName = $tableName;
		return $cloner;
	}
	
	// 记录 sql 运行过程
	protected function trace($res, $startTime, $sql = null){
		if($sql == null){$sql = $this->sql;}
		$sqlRec = array();
		$sqlRec[0] = $res != FALSE ? '执行成功' : '执行失败';
		$sqlRec[1] = $sql;
		$sqlRec[2] = round((microtime(true) - $startTime) * 1000, 2);
		$sqlRec[3] = $res ? '' : $this->error();
		$GLOBALS['runSql'][] = $sqlRec;
	}
	// 执行 sql 语句
	public function query($sql, $execute = null){
		$startTime = microtime(true);
		$this->pretreatment = $this->pdo->prepare($sql);
		$res = $this->pretreatment->execute($execute);
		$this->trace($res, $startTime, $sql);
		return $res;
	}
	
	// 获取来自 query 查询的单条数据
	public function queryFetch(){
		return $this->pretreatment->fetch(\PDO::FETCH_ASSOC);
	}
	
	// 获取来自 query 查询的全部数据
	public function queryFetchAll(){
		return $this->pretreatment->fetchAll(\PDO::FETCH_ASSOC);
	}
	
	// 添加数据
	public function add($data = null){
		$startTime = microtime(true);
		if(empty($data)){$data = $_POST;}
		if(!is_array($data)){throw new \Exception('插入数据错误:插入数据应为一个一维数组');}
		$this->sql   = "insert into $this->tableName (";
		$fields      = array(); $placeHolder = array(); $insertData  = array();
		foreach ($data as $k => $v){$fields[] = "$k"; $placeHolder[] = "?"; $insertData[] = $v !== '0' && $v !== 0 && empty($v)?NULL:$v;}
		$this->sql .= implode(', ', $fields).') values ('.implode(', ', $placeHolder).');';
		$this->pretreatment = $this->pdo->prepare($this->sql);
		
		$this->pretreatment->execute($insertData);
		$res = $this->pdo->lastInsertId();
		$this->trace($res, $startTime);
		return $res;
	}
	
	// 批量添加数据
	public function addbatch($key,$data){
		$startTime = microtime(true);
		if(!is_array($data)){throw new \Exception('插入数据错误:插入数据应为一个一维数组');}
		$this->sql   = "insert ignore into $this->tableName ({$key}) values ";
		$sql = '';
		foreach ($data as $v) {
			$sql .= "('" . implode("','", $v) . "'),";
		}
		$this->sql .= rtrim($sql, ',');
		return $this->query($this->sql);
	}
	
	// 更新数据
	public function update($data=null){
		$startTime = microtime(true);
		if(is_null($data)){$data = $_POST;}
		if(empty($data) || !is_array($data)){throw new \Exception('update($data) 函数的参数应该为一个一维数组');}
		if(empty($this->where)){throw new \Exception('请使用模型对象的 where() 方法设置更新条件');}
		$where = $this->getWhere();
		$join = $this->getJoin();
		$this->sql   = empty($join)?"update {$this->tableName} set ":"update {$this->tableName} {$join} set ";
		$updateData  = array();
    	foreach ($data as $k => $v){
            if(is_array($v)){
                if(count($v)==2 && $v[0]=='+'){
                    $this->sql .= "$k = $k + ?, ";
                    $updateData[] = $v[1] !== '0' && $v !== 0 && empty($v[1])?NULL:$v[1];
                }else{return false;}
            }else{
                $this->sql .= "$k = ?, ";
                $updateData[] = $v !== '0' && $v !== 0 && empty($v)?NULL:$v;
            }
    	}
		$this->sql   = substr($this->sql, 0,-2).$where[0].';';
		$updateData  = array_merge($updateData, $where[1]);
		
		$this->pretreatment = $this->pdo->prepare($this->sql);//预处理
		$res = $this->pretreatment->execute($updateData);//执行
		$this->trace($res, $startTime);
		return $res;
	}
	
	// 删除数据
	public function delete(){
		$startTime = microtime(true);
		if(empty($this->where)){throw new \Exception('请使用模型对象的 where() 函数设置删除条件');}
		$where              = $this->getWhere();
		$this->sql          = "delete from $this->tableName {$where[0]};";
		$this->pretreatment = $this->pdo->prepare($this->sql);
		$res = $this->pretreatment->execute($where[1]);
		$this->trace($res, $startTime);
		return $res;
	}
    
    public function field($data){
        $startTime = microtime(true);
        if(!is_array($data)){throw new \Exception('field($data) 函数的参数应该为一个一维数组');}
        $set = "";
        foreach ($data as $key => $value) {
        	$val = round($value,2);
        	$set .= "{$key} = {$key} + {$val},";
        }
        $set = rtrim($set,",");
        $this->sql = "update {$this->tableName} set {$set}";
        $where     = $this->getWhere(); 
		$this->sql .= $where[0].';'; 
        return $this->query($this->sql, $where[1]);
    }
	
	// 查询单条数据
	public function fetch($fields = null){
		$startTime           = microtime(true);
		$preArray            = $this->prepare($fields);
		$this->sql           = $preArray[0];
		$this->pretreatment  = $this->pdo->prepare($this->sql);
		$this->pretreatment->execute($preArray[1]);
		$res = $this->pretreatment->fetch(\PDO::FETCH_ASSOC);
		$this->trace($res, $startTime);
		return $res;
	}
	
	// 查询多条数据
	public function fetchAll($fields = null){
		$startTime   = microtime(true);
		$preArray    = $this->prepare($fields, false);		
		$this->sql   = $preArray[0];
    	if($this->Page){
			$mode         = '/^select .* from (.*)$/Uis';
			preg_match($mode, $this->sql, $arr_preg);
			$sql          = 'select count(*) as total from '.$arr_preg['1'];
			if(strpos($sql, 'group by ')){$sql = 'select count(*) as total from ('.$sql.') as witCountTable;';}
			$pretreatment = $this->pdo->prepare($sql);
			$pretreatment->execute($preArray[1]);
			$arrTotal     = $pretreatment->fetch(\PDO::FETCH_ASSOC);
			$pageList = $this->Page->get($arrTotal['total']);
		}
		$this->sql .= $this->getLimit().';';
		$this->pretreatment  = $this->pdo->prepare($this->sql);
		$this->pretreatment->execute($preArray[1]);
		$res = $this->pretreatment->fetchAll(\PDO::FETCH_ASSOC);
		$this->trace($res, $startTime);
		if($this->Page){$res = array_merge(['list'=>$res],$pageList);}
		return $res;
	}
	
	// 预处理
	public function prepare($fields, $limit = true){
		$exeArray = array();
    	$join = $this->getJoin();
    	if(!empty($join)){is_null($fields) ? $sql = 'select * from '.$this->tableName.' '.$join.' ' : $sql = 'select '.$fields.' from '.$this->tableName.' '.$join.' ';}else{is_null($fields) ? $sql = 'select * from '.$this->tableName.' ' : $sql = 'select '.$fields.' from '.$this->tableName.' ';}
    	$where = $this->getWhere();
    	
    	if(!is_null($where)){$sql .= $where[0]; $exeArray = $where[1];}
		$limit ? $sql .= $this->getGroup().$this->getOrder().$this->getLimit().';' : $sql .= $this->getGroup().$this->getOrder();
    	return array($sql,$exeArray);
	}
	
	// 设置条件
	public function where($where, $array = []){
		$this->where[0] = $where;
		is_array($array) ? $this->where[1] = $array : $this->where[1] = array($array);
		return $this;
	}
	
	// 获取条件
	public function getWhere($nullWhere = false){
		if(empty($this->where)){return null;}
		$return = array(' where '.$this->where[0].' ', $this->where[1]);
		if(!$nullWhere){
			$this->where = null;
		}
		return $return;
	}
	
	// 设置 group by
	public function group($group){
		$this->group = $group; return $this;
	}
	
	// 获取 group by
	public function getGroup(){
		if(empty($this->group)){return null;}
		$group = $this->group;
		$this->group = null;
		return ' group by '.$group.' ';
	}
	
	// 设置排序
	public function order($order){
		$this->order = $order; return $this;
	}
	
	// 获取排序
	public function getOrder(){
		if(empty($this->order)){return null;}
		$return  = 'order by '.$this->order.' ';
		$this->order = null;
		return $return;
	}
	
	// 设置多表联合
	public function join($join_sql){
		$this->join = $join_sql;
		return $this;
	}
	
	// 获取多表联合信息
	public function getJoin(){
		if(empty($this->join)){return null;}
		$return = $this->join;
		$this->join = null;
		return $return;
	}
	
	// 设置 limit
	public function limit($start, $length=0){
		$this->limit = [$start, $length];
		return $this;
	}
	
	// 设置 获取
	public function getLimit(){
		if(empty($this->limit)){return null;}
		$return = empty($this->limit[1])?' limit '.$this->limit[0].' ':' limit '.$this->limit[0].','.$this->limit[1].' ';
		$this->limit = null;
		return $return;
	}
	
	// 设置 分页
	public function page($args1 = 10, $args2 = null){
		if(empty($args2)){
		    $currentPage = U_PAGE;
		    $eachPage = $args1;
		    $this->Page = new page(1,$currentPage,$eachPage);
		}else{
		    $currentPage = $args1;
		    $eachPage = $args2;
		    $this->Page = new page(2,$currentPage,$eachPage);
		}
		$start = ($currentPage-1)*$eachPage;
		$this->limit = [$start, $eachPage];
		
		return $this;
	}
	
	// 获取SQL
	public function getSql(){
		return $this->sql;
	}
	
	// 获取错误信息
	public function error(){
		$error = is_null($this->pretreatment) ? $this->pdo_obj->errorInfo() : $this->pretreatment->errorInfo();
		if(isset($error[2])){return $error[2];}
		return null;
	}
	
	// 获取影响的数据条目数
	public function rowCount(){
		if(empty($this->pretreatment)){return null;}
		return $this->pretreatment->rowCount();
	}
	
	// 获取刚刚插入数据的主键值
	public function lastInsertId(){
		return $this->pdo->lastInsertId();
	}
	
	// 获取 pdo 对象
    public function getDb(){
    	return $this->pdo;
    }
    
	// 获取数据总数
	public function count(){
		$this->sql = "select count(*) as total from $this->tableName ";
		$where = $this->getWhere(); $this->sql.= $where[0].';';
		$this->query($this->sql, $where[1]);
		$return = $this->pretreatment->fetch();
		if(empty($return['total'])){return 0;}
		return $return['total'];
	}
	
	// 获取某个字段数据最大值
	public function max($field){
		$this->sql = "select max($field) as max from $this->tableName";
		$where = $this->getWhere(); $this->sql .= $where[0].';';
		$this->query($this->sql, $where[1]);
		$return = $this->pretreatment->fetch();
		if(empty($return['max'])){return 0;}
		return $return['max'];
	}
	
	// 获取某个字段数据最小值
	public function min($field){
		$this->sql = "select min($field) as min from $this->tableName";
		$where = $this->getWhere(); $this->sql .= $where[0].';';
		$this->query($this->sql, $where[1]);
		$retutn = $this->pretreatment->fetch();
		if(empty($retutn['min'])){return 0;}
		return $retutn['min'];
	}
	
	// 获取某个字段数据平均值
	public function avg($field){
		$this->sql = "select avg($field) as avg from $this->tableName";
		$where = $this->getWhere();
		$this->sql .= $where[0].';';
		$this->query($this->sql, $where[1]);
		$return = $this->pretreatment->fetch();
		if(empty($return['avg'])){return 0;}
		return $return['avg'];
	}
	
	// 获取某个字段数据总和
	public function sum($field){
		$this->sql = "select sum($field) as sum from $this->tableName";
		$where = $this->getWhere(); $this->sql .= $where[0].';';
		$this->query($this->sql, $where[1]);
		$return = $this->pretreatment->fetch();
		if(empty($return['sum'])){return 0;}
		return $return['sum'];
	}
	
	// 获取 mysql 版本
	public function mysqlV(){
		$this->query('select version();');
		$return = $this->pretreatment->fetch();
		return $return[0];
	}
	
	// 获取当前数据表表结构
	public function desc(){
		$this->query('select ORDINAL_POSITION,COLUMN_NAME ,DATA_TYPE, COLUMN_COMMENT  
		from information_schema.columns where table_schema = ? and table_name = ? 
		order by ORDINAL_POSITION asc;', array($this->conf['dbname'], $this->tableName));
		return $this->queryFetchAll();
	}
	
	// 开启事务
	public function beginTransaction(){
		$this->pdo->beginTransaction();
	}
	
	// 提交事务
	public function commit(){
		$this->pdo->commit();
	}
	
	// 回滚
	public function rollback(){
		$this->pdo->rollback();
	}
}