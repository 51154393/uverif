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
										<option value="0">待支付</option>
										<option value="2">已支付</option>
										<option value="3">支付失败</option>
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
										订单号/官方订单
									</th>
									<th class="text-left">
										订单名称/用户
									</th>
									<th class="wd-20">
										金额
									</th>
									<th class="wd-20">
										商品类型
									</th>
									<th class="wd-20">
										支付类型
									</th>
									<th class="wd-20">
										创建时间/付款时间
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
		
		function add_list(t,a=!1){a&&$("#tbody_list").empty();for(var e=0;e<t.length;e++){var s="<td>"+t[e].id+"</td>";s+='<td><p class="mb-0">'+t[e].order_no+'</p><small class="mb-0 text-muted">'+t[e].trade_no+"</small></td>",s+='<td class="text-left"><p class="mb-0">'+t[e].name+'</p><small class="mb-0 text-muted">'+t[e].user+"</small></td>",s+="<td>￥ "+t[e].money+"</td>",s+='<td><span class="badge badge-pill badge-'+("vip"==t[e].type?"danger":"fen"==t[e].type?"warning":"primary")+'-lighten">'+("vip"==t[e].type?"会员":"fen"==t[e].type?"积分":"代理组")+"</span></td>",s+='<td><span class="badge badge-pill badge-'+("ali"==t[e].ptype?"primary":"success")+'">'+("ali"==t[e].ptype?"支付宝":"微信")+"</span></td>",s+='<td><p class="mb-0">'+timeToDate(t[e].add_time)+'</p><small class="mb-0 text-muted">'+(isEmpty(t[e].end_time)?"NULL":timeToDate(t[e].end_time))+"</small></td>",s+='<td><span class="mr-1 dot dot-lg bg-'+(0==t[e].state?"warning":2==t[e].state?"success":"danger")+'"></span>'+(0==t[e].state?"待付款":2==t[e].state?"已支付":"支付失败")+"</td>",$("#tbody_list").append('<tr id="list_'+t[e].id+'">'+s+"</tr>")}}
	</script>	
<?php include_once 'footer.php';?>