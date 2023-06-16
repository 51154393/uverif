<style type="text/css">
.ue-trace{width:100%; position:fixed; z-index:99999; left:0; bottom:0; background-color:#FFFFFF; border-top:1px solid #e6e6e6; display:none;}
.ue-trace-item{width:31%; float:left; overflow:hidden; padding:10px 1%;}
.ue-trace-item > .title{line-height:50px; font-size:15px; font-weight:bold; color:#666666; border-bottom:1px solid #E9E9E9;}
.ue-trace-item > .text{line-height:2.2em; font-size:13px; padding:20px 0; height:158px; overflow-y:auto; margin:8px 0;}
.ue-trace-item > .text .sql{border-bottom:1px dashed #E9E9E9; padding-bottom:10px; margin-bottom:10px;}
.ue-trace-item > .text .sql span{color:#888888; font-size:16px;}
.ue-trace-item > .text .sql i{color:#FF0036;}
.ue-trace-item > .text .sql a{font-size:13px; color:#3688FF;}
.ue-trace-item > .text .sql b{font-weight:400; font-size:13px;}
.green{color:green;}
.red{color:red;}
.ue-trace-small{padding:8px 5px; background-color:#FFF; position:fixed; right:0;box-shadow:1px 1px 5px #d8d8d8; cursor:pointer; font-size:0; line-height:0;}
.ue-trace-small img{float:left; width:30px; margin-left:5px;padding-top:6px}
.ue-trace-small-msg{margin-right:10px; float:left; overflow:hidden; font-size:13px; line-height:20px; color:#333333;}
</style>
<?php $cost = runCost();?>
<div class="ue-trace" id="ue-trace">
	<div class="ue-trace-item">
		<div class="title">运行信息</div>
		<div class="text">
			远程时间 : <?php echo date('Y-m-d H:i:s');?><br />
			运行耗时 : <?php echo $cost[0];?> 毫秒<br />
			内存消耗 : <?php echo $cost[1];?> k<br />
		</div>
	</div>
	<?php $includedFiles = get_included_files();?>
	<div class="ue-trace-item">
		<div class="title">引入文件 [ <?php echo count($includedFiles);?> ]</div>
		<div class="text">
			<?php 
			foreach($includedFiles as $k => $file){ 
				echo ($k+1).'. '.$file.'<br />';
			}?>
		</div>
	</div>
	<div class="ue-trace-item">
		<div class="title">sql 运行日志</div>
		<div class="text">
			<?php foreach($GLOBALS['runSql'] as $k => $sql){?>
				<div class="sql">
					<span>记录 <?php echo $k + 1;?></span><br />
					结果 : <?php if($sql[0] == '执行成功'){echo '<b class="green">'.$sql[0].'</b>';}else{echo '<b class="red">'.$sql[0].'</b>';}?> 耗时 : <?php echo $sql[2];?> 毫秒 <br />
					语句 : <?php echo $sql[1];?><br  />
					<?php if(!empty($sql[3])){echo '错误 : <i>'.$sql[3].'</i>&nbsp;&nbsp;&nbsp;[ <a href="https://fanyi.baidu.com/#en/zh/'.urlencode($sql[3]).'" target="_blank">翻译一下</a> ]';}?>
				</div>
			<?php }?>
		</div>
	</div>
</div>
<div class="ue-trace-small" onclick="showTrace()" id="showTrace" style="bottom: 10px;">
	<div class="ue-trace-small-msg">
		<img src="data:image/png;base64,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">
	</div>
	<div class="ue-trace-small-msg">
		运行耗时 : <?php echo $cost[0];?> ms<br />
		内存消耗 : <?php echo $cost[1];?> k
	</div>
</div>
<script type="text/javascript">
var ueTraceStatus = false;
function showTrace(){
	var showTraceId = document.getElementById('showTrace');
	var ueTrace = document.getElementById('ue-trace');
	ueTraceStatus = !ueTraceStatus;
	if(ueTraceStatus){
		showTraceId.style.bottom = '300px';
		ueTrace.style.display = 'block';
	}else{
		showTraceId.style.bottom = '10px';
		ueTrace.style.display = 'none';
	}
}
</script>