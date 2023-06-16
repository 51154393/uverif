<?php include_once 'header.php';?>
	<div class="row">
		<div class="col-md-12">
			<div class="card shadow">
				<div class="card-body">
					<div class="toolbar">
						<form class="form">
							<div class="form-row">
								<div class="form-group col-auto mr-auto">
									<select class="form-control" id="state" name="so">
										<option value="-1">全部</option>
										<option value="0">待处理</option>
										<option value="1">已驳回</option>
										<option value="2">已完成</option>
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
										代理ID
									</th>
									<th class="text-left">
										收款人/收款账号
									</th>
									<th class="wd-20">
										提现金额
									</th>
									<th class="wd-20">
										收款类型
									</th>
									<th class="wd-20">
										申请时间/打款时间
									</th>
									<th class="wd-20">
										状态
									</th>
									<th class="wd-20">
										操作
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
	
	<div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modal-title">打款</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body p-4">
					<div class="text-center">
						<table class="table table-bordered table-centered mb-0">
							<tbody>
								<tr>
									<td>收款姓名</td>
									<td id="modal_name">张三</td>
								</tr>
								<tr>
									<td>收款账号</td>
									<td id="modal_account">51154393@qq.com</td>
								</tr>
								<tr>
									<td>提现金额</td>
									<td id="modal_money">0.00</td>
								</tr>
								<tr>
									<td>提现方式</td>
									<td id="modal_way">支付宝</td>
								</tr>
							</tbody>
						</table>
					</div>
					<input type="number" class="form-control" id="modal_id" placeholder="1" hidden>
					
					<div class="form-group mt-2" id="div_rebut" hidden>
						<label>驳回理由</label>
						<textarea class="form-control" id="rebut_msg" rows="3" placeholder="驳回理由"></textarea>
					</div>
					
					<div class="text-center mt-2">
						<button type="button" class="btn btn-success btn-block mt-4" id="pay_submit">已打款</button>
						<button type="button" class="btn btn-danger btn-block" id="rebut_submit" hidden>驳回</button>
					</div>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
	<script>
		function init(i,n={}){return initSubmit=i,$.ajax({cache:!1,type:"POST",data:i,dataType:"json",success:function(i){initSuccess(i,n)},error:function(i,t,c){initError(t,n)}}),!1}
		function add_list(t,a=!1){a&&$("#tbody_list").empty();for(var s=0;s<t.length;s++){var e="<td>"+t[s].agid+"</td>";e+='<td class="text-left"><p class="mb-0">'+t[s].name+'</p><small class="mb-0 text-muted">'+t[s].account+"</small></td>",e+="<td>￥ "+t[s].money+"</td>",e+='<td><span class="badge badge-pill badge-'+("ali"==t[s].way?"primary":"success")+'">'+("ali"==t[s].way?"支付宝":"微信")+"</span></td>",e+='<td><p class="mb-0">'+timeToDate(t[s].add_time)+'</p><small class="mb-0 text-muted">'+(isEmpty(t[s].end_time)?"NULL":timeToDate(t[s].end_time))+"</small></td>",e+='<td><span class="mr-1 dot dot-lg bg-'+(0==t[s].state?"warning":2==t[s].state?"success":"danger")+'"></span>'+(0==t[s].state?"待打款":2==t[s].state?"已打款":"已驳回")+"</td>",e+="<td><button "+(0!=t[s].state?"disabled":"")+' type="button" class="btn btn-success btn-sm mr-1" onclick="modals(\''+base64_encode(JSON.stringify(t[s]))+'\',2);"><span class="small">打款</span></button><button '+(0!=t[s].state?"disabled":"")+' type="button" class="btn btn-danger btn-sm" onclick="modals(\''+base64_encode(JSON.stringify(t[s]))+'\',1);"><span class="small">驳回</span></button></td>',$("#list_"+t[s].id).length>0?$("#list_"+t[s].id).html(e):a?$("#tbody_list").append('<tr id="list_'+t[s].id+'">'+e+"</tr>"):$("#tbody_list").prepend('<tr id="list_'+t[s].id+'">'+e+"</tr>")}}function modals(t,a){var s=base64_decode(t),e=JSON.parse(s);$("#modal_id").val(e.id),$("#rebut_msg").val(""),$("#modal_name").html(e.name),$("#modal_account").html(e.account),$("#modal_money").html(e.money),$("#modal_way").html("ali"==e.way?"支付宝":"微信"),1==a?($("#modal-title").html("驳回"),$("#rebut_submit").attr("hidden",!1),$("#div_rebut").attr("hidden",!1),$("#pay_submit").attr("hidden",!0)):($("#modal-title").html("打款"),$("#rebut_submit").attr("hidden",!0),$("#div_rebut").attr("hidden",!0),$("#pay_submit").attr("hidden",!1)),$("#modal").modal("show")}$("#pay_submit").click(function(){var t={act:"pay"};return t.id=$("#modal_id").val(),$("#pay_submit").html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>正在提交'),$("#pay_submit").attr("disabled",!0),$.ajax({cache:!1,type:"POST",data:t,dataType:"json",success:function(t){$("#pay_submit").html("已打款"),$("#pay_submit").attr("disabled",!1),200==t.code?(add_list(t.data.list),cocoMessage.success("打款状态更新成功",3e3),$("#modal").modal("hide")):cocoMessage.error(t.msg,3e3)},error:function(t,a,s){cocoMessage.error("系统发生错误，请打开浏览器调试模式查看具体原因",3e3),$("#pay_submit").html("已打款"),$("#pay_submit").attr("disabled",!1)}}),!1}),$("#rebut_submit").click(function(){var t={act:"rebut"};return t.id=$("#modal_id").val(),t.rebut_msg=$("#rebut_msg").val(),$("#rebut_submit").html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>正在提交'),$("#rebut_submit").attr("disabled",!0),$.ajax({cache:!1,type:"POST",data:t,dataType:"json",success:function(t){$("#rebut_submit").html("驳回"),$("#rebut_submit").attr("disabled",!1),200==t.code?(add_list(t.data.list),cocoMessage.success("驳回状态更新成功",3e3),$("#modal").modal("hide")):cocoMessage.error(t.msg,3e3)},error:function(t,a,s){cocoMessage.error("系统发生错误，请打开浏览器调试模式查看具体原因",3e3),$("#rebut_submit").html("驳回"),$("#rebut_submit").attr("disabled",!1)}}),!1});
	</script>	
<?php include_once 'footer.php';?>