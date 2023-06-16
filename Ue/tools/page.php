<?php
/**
 * 分页类
 * @link      http://www.Uephp.com
 * @copyright Copyright (c) 2022-2032 
 * @author    易友
 * @version   1.0.0
**/
namespace Ue\tools;
class page{
	public $dataTotal;//总计页面
	public $currentPage;//当前页码
	public $pageRows;//页面条数
	public $pageTotal;//页面最大值
	public $mold = 1;//分页类型，1=返回带连接的，2=返回参数
	
	public function __construct($mold,$currentPage, $pageRows){
		$this->mold = $mold;
		$this->currentPage = $currentPage;
		$this->pageRows = $pageRows;
	}
	
	public function get($dataTotal){
		$this->dataTotal = $dataTotal;
		$this->pageTotal = ceil($this->dataTotal / $this->pageRows);//最大页面值
		if($this->mold == 1){
			//获取URL
			if(U_URL != ''){
				$currentUrl = '/'.U_C.'/'.U_M.'/'.U_URL;
			}else{
				$currentUrl = '/'.U_C.'/'.U_M;
			}
			$suffix = 'U_SUFFIX' ? U_SUFFIX : '/';
			$getsRec         = $this->addGet();
			$minPage = $currentUrl.$suffix.$getsRec;//最小页面
			
			if(($this->currentPage - 1) > 1){
				$prePage   = $currentUrl.'/page-'.($this->currentPage - 1).$suffix .$getsRec;
			}else{
				$prePage   = $currentUrl.$suffix.$getsRec;//上一页
			}
			
			$nextPage  = $currentUrl.'/page-'.(($this->currentPage + 1 > $this->pageTotal) ? $this->currentPage:$this->currentPage + 1).$suffix .$getsRec;
			$maxPage  = $currentUrl.'/page-'.$this->pageTotal.$suffix.$getsRec;	
			
			//分页列表
			if($this->currentPage <= 3){
				$start = 1; $end = 5;
			}else{
				$start = $this->currentPage - 2; $end = $this->currentPage + 3;
			}
			if($end > $this->pageTotal){$end = $this->pageTotal;}
			if($end - $start < 4){$start = $end - 4;}
			if($start < 1){$start = 1;}
			$pageList = [];
			for($i = $start; $i <= $end; $i++){
				if($i > 1){
					$pageList[$i] = $currentUrl.'/page-'.$i.$suffix.$getsRec;
				}else{
					$pageList[$i] = $currentUrl.$suffix.$getsRec;
				}
			}
		}else{
			if($this->currentPage <= 3){
				$start = 1; $end = 5;
			}else{
				$start = $this->currentPage - 2; $end = $this->currentPage + 3;
			}
			if($end > $this->pageTotal){$end = $this->pageTotal;}
			if($end - $start < 4){$start = $end - 4;}
			if($start < 1){$start = 1;}
			$pageList = [];
			for($i = $start; $i <= $end; $i++){
				if($i > 1){
					$pageList[] = $i;
				}else{
					$pageList[] = $i;
				}
			}
			$maxPage = $this->pageTotal;
			$minPage = 1;
			$prePage = $this->currentPage <=1 ? $this->currentPage : $this->currentPage-1;//上一页
			$nextPage = $this->currentPage >= $this->pageTotal ? $this->currentPage : $this->currentPage+1;//下一页
		}
		return ['dataTotal'=>$this->dataTotal,'pageTotal'=>$this->pageTotal,'prePage'=>$prePage,'nextPage'=>$nextPage,'minPage'=>$minPage,'maxPage'=>$maxPage,'currentPage'=>$this->currentPage,'pageList'=>$pageList];
	}
	
	
	public function addGet(){
		if(empty($_GET)){return '';}
		$str = '?';
		foreach($_GET as $k => $v){
			$str = $str . $k . '=' . $v . '&';
		}
		return rtrim($str, '&');
	}
}