<?php include_once 'header.php';?>
	<div class="row">
		<div class="col-md-12">
			
			<div class="card shadow">
				<div class="card-body">
					<div class="toolbar">
						<form class="form">
							<div class="form-row">
								<div class="form-group col-auto mr-auto">
									<select class="form-control" id="type" name="so">
										<option value="all">全部</option>
										<?php foreach ($this->logType as $key => $value){?>
										<option value="<?=$key?>"><?=$value?></option>
										<?php } ?>
									</select>
								</div>
								
								<div class="form-group col-auto col-md-3">
									<div class="input-group so">
										<input type="text" class="form-control" placeholder="输入搜索内容" id="so" name="so">
										<div class="input-group-append">
											<button class="btn btn-primary" type="button" id="so_submit">搜索</button>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="table-responsive">
						<table class="table table-borderless table-hover mb-0">
							<thead>
								<tr>
									<th class="wd-20">
										ID
									</th>
									<th class="wd-20">
										操作类型
									</th>
									<th class="text-left">
										操作人
									</th>
									<th class="wd-20">
										操作时间/IP
									</th>
									<th class="wd-20">
										状态
									</th>
								</tr>
							</thead>
							<tbody id="tbody_list">

								
							</tbody>
						</table>
					</div>	
					<nav class="mb-0">
						<ul class="pagination justify-content-center mb-0" id="poge_list_ul">
							
						</ul>
					</nav>
					
				</div>
			</div>
		</div>
	</div>
	
	<script>
		function init(i,n={}){return initSubmit=i,$.ajax({cache:!1,type:"POST",data:i,dataType:"json",success:function(i){initSuccess(i,n)},error:function(i,t,c){initError(t,n)}}),!1}
		
		function add_list(t,s=!1){s&&$("#tbody_list").empty();for(var l=0;l<t.length;l++){var d="<td>"+t[l].id+"</td>";d+='<td><p class="mb-0">'+t[l].type+'</p><small class="mb-0 text-muted">操作类型</small></td>',d+='<td class="text-left"><p class="mb-0">'+t[l].user+'</p><small class="mb-0 text-muted">'+t[l].person+"</small></td>",d+='<td><p class="mb-0">'+t[l].time+'</p><small class="mb-0 text-muted">'+t[l].ip+"</small></td>",d+='<td><span class="dot dot-lg bg-'+("y"==t[l].state?"success":"danger")+'"></span></td>',$("#tbody_list").append('<tr id="list_'+t[l].id+'">'+d+"</tr>")}}
	</script>	
<?php include_once 'footer.php';?>