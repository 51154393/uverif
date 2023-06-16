<?php include_once 'header.php';?>
	<div class="row">
		<div class="col-md-12">
			
			<div class="card shadow">
				<div class="card-body">
					<div class="toolbar">
						<form class="form">
							<div class="form-row">
								<div class="form-group col-auto mr-auto">
									<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#add"><i class="fe fe-plus fe-12 mr-2"></i><span class="small">添加代理</span></button>
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
										头像
									</th>
									<th class="text-left">
										账号/备注
									</th>
									<th class="wd-20">
										余额
									</th>
									<th class="wd-20">
										用户数
									</th>
									<th class="wd-20">
										代理组
									</th>
									<th class="wd-20">
										姓名/提现账号
									</th>
									<th class="wd-20">
										充值分成/开卡折扣
									</th>
									<th class="wd-20">
										状态
									</th>
									<th class="wd-20">
										管理
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
	
	<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">
						添加代理
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form>
						<div class="form-group">
							<label>代理组</label>
							<div class="float-right">
								<a href="javascript:get_ag(true);" id="add_ag_refresh">刷新</a>
							</div>
							<select class="form-control" id="add_ag">
								
							</select>
						</div>
						
						<div class="form-group">
							<label>代理备注</label>
							<input type="text" class="form-control" placeholder="如：活动卡密（可空）" id="add_note">
						</div>
						<div class="form-group">
							<label>代理账号<span class="fe fe-help-circle ml-1" data-toggle="tooltip" title="代理必须先成为用户，使用用户账号"></span></label>
							<input type="text" class="form-control" placeholder="可以是：用户ID、用户账号、用户手机号、用户邮箱" id="add_uid">
						</div>
						
						<div class="form-group mb-2">
							<button type="submit" class="btn btn-primary btn-block" id="add_submit">提交</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade modal-right modal-slide" id="edit" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-plus-min" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">编辑代理</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body bg-light">
					<form>
						<div class="row mb-3">
							<div class="col-sm-12">
								<!-- Profile -->
								<div class="card bg-primary">
									<div class="card-body profile-user-box">
										<div class="row">
											<div class="card-body text-center">
												<div  class="avatar avatar-lg">
													<img id="edit_pic" src="/assets/avatars/default.svg" class="rounded-circle img-thumbnail">
												</div >
												<div class="card-text my-2">
													<strong class="my-0 text-white" id="edit_user">51154393</strong>
												</div>
											</div>
										</div> <!-- end row -->
										<ul class="list-group list-group-flush">
											<li class="list-group-item bg-primary border-bottom-primary">
												<p class="d-flex justify-content-between align-items-center mb-0 text-white">
													余额 <span class="col-auto" id="edit_money">￥ 0.00</span>
												</p>
											</li>
											<li class="list-group-item bg-primary border-bottom-primary">
												<p class="d-flex justify-content-between align-items-center mb-0 text-white">
													用户数 <span class="col-auto" id="edit_xu_num">0</span>
												</p>
											</li>
											<li class="list-group-item bg-primary border-bottom-primary">
												<p class="d-flex justify-content-between align-items-center mb-0 text-white">
													代理组 <span class="col-auto" id="edit_group_name">白银代理</span>
												</p>
											</li>
											<li class="list-group-item bg-primary border-bottom-primary">
												<p class="d-flex justify-content-between align-items-center mb-0 text-white">
													创建时间 <span class="col-auto" id="edit_time">2023-06-08 23:12</span>
												</p>
											</li>
											<li class="list-group-item bg-primary border-bottom-primary">
												<p class="d-flex justify-content-between align-items-center mb-0 text-white">
													提现姓名 <span class="col-auto" id="edit_cash_name">张三</span>
												</p>
											</li>
											<li class="list-group-item bg-primary border-bottom-primary">
												<p class="d-flex justify-content-between align-items-center mb-0 text-white">
													提现账号 <span class="col-auto" id="edit_cash_account">51154393@qq.com</span>
												</p>
											</li>
											<li class="list-group-item bg-primary border-bottom-primary">
												<p class="d-flex justify-content-between align-items-center mb-0 text-white">
													提现方式 <span class="col-auto" id="edit_cash_way">支付宝</span>
												</p>
											</li>
											
											<li class="list-group-item bg-primary border-bottom-primary">
												<label class="mb-0 text-white">代理状态</label>
												<div class="float-right ml-auto">
													<div class="custom-switch">
														<input type="checkbox" class="custom-control-input success" id="edit_state">
														<label class="custom-control-label success" for="edit_state"></label>
													</div>
												</div>
											</li>
										</ul>
									</div> <!-- end card-body/ profile-user-box-->
								</div><!--end profile/ card -->
							</div> <!-- end col-->
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="card mb-3">
									<div class="card-body">
										<form>
											<input type="number" class="form-control" id="edit_id" hidden>
											<div class="form-group">
                    							<label>备注</label>
                    							<input type="text" class="form-control" placeholder="设置卡密的备注" id="edit_note">
                    						</div>
											
											<div class="form-group">
                    							<label>代理组</label>
												<div class="float-right">
													<a href="javascript:get_ag(true);" id="edit_ag_refresh">刷新</a>
												</div>
												<select class="form-control" id="edit_ag" onchange="select_ag()">
													
												</select>
                    						</div>
											<div class="form-group">
												<label>充值分成<span class="fe fe-help-circle ml-1" data-toggle="tooltip" title="可单独设置代理充值分成，不受代理组限制"></span></label>
												<div class="input-group">
													<input type="number" class="form-control" placeholder="如：30，空则同步代理组" id="edit_pay_divide">
													<div class="input-group-append">
														<span class="input-group-text">%</span>
													</div>
												</div>
											</div>
											
											<div class="form-group">
												<label>开卡折扣<span class="fe fe-help-circle ml-1" data-toggle="tooltip" title="可单独设置代理开卡折扣，不受代理组限制"></span></label>
												<div class="input-group">
													<input type="number" class="form-control" placeholder="如：7，空则同步代理组" id="edit_km_discount">
													<div class="input-group-append">
														<span class="input-group-text">折</span>
													</div>
												</div>
											</div>
											<button class="btn btn-primary btn-block" type="submit" id="edit_submit">确认编辑</button>
										</form>
									</div> <!-- end card-body -->
								</div> <!-- end card-->
								
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	
	<div id="del" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-body p-4">
					<div class="text-center">
						<h4 class="mt-2">删除警告</h4>
						<p class="mt-3">确认删除代理：<strong class="text-danger me-1" id="del_name">null</strong> ？<br>删除后不可恢复！请慎重操作</p>
						<input type="number" class="form-control" id="del_id" placeholder="1" hidden>
						<button type="button" class="btn btn-danger mr-2 my-2" id="del_submit">确认删除</button>
						<button type="button" class="btn btn-light" data-dismiss="modal">取消删除</button>
					</div>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<script>
		function init(i,n={}){return initSubmit=i,$.ajax({cache:!1,type:"POST",data:i,dataType:"json",success:function(i){initSuccess(i,n)},error:function(i,t,c){initError(t,n)}}),get_ag();!1}
		
		function add_list(t,a=!1){a&&$("#tbody_list").empty();for(var e=0;e<t.length;e++){var d="<td>"+t[e].id+"</td>";d+='<td><div class="avatar"><img src="/'+t[e].avatars+'" class="avatar-img rounded-circle pic" onerror="javascript:this.src=\'/assets/avatars/default.svg\';"></div></td>',d+='<td class="text-left"><p class="mb-0">'+t[e].user+'</p><small class="mb-0 text-muted">'+t[e].note+"</small></td>",d+="<td>￥ "+t[e].money+"</td>",d+="<td>"+t[e].xu_num+"</td>",d+="<td>"+t[e].Gname+"</td>",d+='<td><p class="mb-0">'+(isEmpty(t[e].cash_name)?"未设置":t[e].cash_name)+'</p><small class="mb-0 text-muted">'+(isEmpty(t[e].cash_account)?"未设置":t[e].cash_account)+"</small></td>",d+='<td><p class="mb-0">'+t[e].pay_divide+' %</p><small class="mb-0 text-muted">'+t[e].km_discount+"折</small></td>",d+='<td><span class="mr-1 dot dot-lg bg-'+("on"==t[e].state?"success":"danger")+'"></span>'+("on"==t[e].state?"正常":"禁用")+"</td>",d+='<td><button type="button" class="btn btn-primary btn-sm mr-1" data-toggle="modal" data-target="#edit" onclick="edit(\''+base64_encode(JSON.stringify(t[e]))+'\');"><i class="fe fe-edit fe-12 mr-2"></i><span class="small">编辑</span></button><button type="button" class="btn btn-danger btn-sm" onclick="del('+t[e].id+",'"+t[e].user+'\');"><i class="fe fe-trash-2 fe-12 mr-2"></i><span class="small">删除</span></button></td>',$("#list_"+t[e].id).length>0?$("#list_"+t[e].id).html(d):a?$("#tbody_list").append('<tr id="list_'+t[e].id+'">'+d+"</tr>"):$("#tbody_list").prepend('<tr id="list_'+t[e].id+'">'+d+"</tr>")}$('[data-toggle="tooltip"]').tooltip()}function edit(t){var a=base64_decode(t),e=JSON.parse(a);$("#edit_id").val(e.id),$("#edit_money").html("￥ "+e.money),$("#edit_xu_num").html(e.xu_num),$("#edit_group_name").html(e.Gname),$("#edit_time").html(timeToDate(e.time)),$("#edit_cash_name").html(isEmpty(e.cash_name)?"未设置":e.cash_name),$("#edit_cash_account").html(isEmpty(e.cash_account)?"未设置":e.cash_account),$("#edit_cash_way").html(isEmpty(e.cash_way)?"未设置":"ali"==e.cash_way?"支付宝":"微信"),$("#edit_state").prop("checked","on"==e.state),$("#edit_note").val(e.note),$("#edit_ag").val(e.aggid),$("#edit_pay_divide").val(e.pay_divide),$("#edit_km_discount").val(e.km_discount),isEmpty(e.avatars)?$("#edit_pic").attr("src","/assets/avatars/default.svg"):$("#edit_pic").attr("src","/"+e.avatars)}function select_ag(){var t=$("#edit_ag option:selected").attr("divide"),a=$("#edit_ag option:selected").attr("discount");$("#edit_pay_divide").val(t),$("#edit_km_discount").val(a)}function out_select(t){$("#add_out_type_div").attr("hidden",!t)}function checked_state(t){$("#edit_state_label").html(t?"正常":"禁用")}function del(t,a){$("#del_name").html(a),$("#del_id").val(t),$("#del").modal("show")}$("#add_submit").click(function(){var t={act:"add"};return t.aggid=$("#add_ag").val(),t.note=$("#add_note").val(),t.uid=$("#add_uid").val(),$("#add_submit").html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>正在提交'),$("#add_submit").attr("disabled",!0),$.ajax({cache:!1,type:"POST",data:t,dataType:"json",success:function(t){$("#add_submit").html("确认添加"),$("#add_submit").attr("disabled",!1),200==t.code?(cocoMessage.success(t.msg,3e3),$("#add_note").val(""),$("#add_uid").val(""),add_list(t.data.list),$("#add").modal("hide")):cocoMessage.error(t.msg,3e3)},error:function(t,a,e){cocoMessage.error("系统发生错误，请打开浏览器调试模式查看具体原因",3e3),$("#add_submit").html("确认添加"),$("#add_submit").attr("disabled",!1)}}),!1}),$("#edit_submit").click(function(){var t={act:"edit"};return t.id=$("#edit_id").val(),t.aggid=$("#edit_ag").val(),t.note=$("#edit_note").val(),t.pay_divide=$("#edit_pay_divide").val(),t.km_discount=$("#edit_km_discount").val(),t.state=$("#edit_state").prop("checked")?"on":"off",$("#edit_submit").html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>正在提交'),$("#edit_submit").attr("disabled",!0),$.ajax({cache:!1,type:"POST",data:t,dataType:"json",success:function(t){$("#edit_submit").html("确认编辑"),$("#edit_submit").attr("disabled",!1),200==t.code?(add_list(t.data.list),cocoMessage.success("编辑成功",3e3)):cocoMessage.error(t.msg,3e3)},error:function(t,a,e){cocoMessage.error("系统发生错误，请打开浏览器调试模式查看具体原因",3e3),$("#edit_submit").html("确认编辑"),$("#edit_submit").attr("disabled",!1)}}),!1}),$("#del_submit").click(function(){var t=$("#del_id").val();return $("#del_submit").html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>正在删除'),$("#del_submit").attr("disabled",!0),$.ajax({cache:!1,type:"POST",data:{act:"del",id:t},dataType:"json",success:function(a){($("#del_submit").html("确认删除"),$("#del_submit").attr("disabled",!1),200==a.code)?($("#list_"+t).remove(),cocoMessage.success(a.msg,3e3),$("#del").modal("hide"),$("#tbody_list").children().length<1&&init(initSubmit)):cocoMessage.error(a.msg,3e3)},error:function(t,a,e){cocoMessage.error("系统发生错误，请打开浏览器调试模式查看具体原因",3e3),$("#add_submit").html("确认添加"),$("#add_submit").attr("disabled",!1)}}),!1});var iniag=!1;function get_ag(t=!1){return!(!t&&iniag)&&($("#add_ag_refresh").html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>刷新中'),$("#edit_ag_refresh").html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>刷新中'),$.ajax({cache:!1,type:"POST",data:{act:"get_ag"},dataType:"json",success:function(t){if(200==t.code){var a=t.data.list;if(a.length>=1){$("#add_ag").empty(),$("#edit_ag").empty();for(var e=0;e<a.length;e++)$("#add_ag").append('<option value="'+a[e].id+'">'+a[e].name+"</option>"),$("#edit_ag").append('<option value="'+a[e].id+'" discount="'+a[e].km_discount+'" divide="'+a[e].pay_divide+'">'+a[e].name+"</option>");iniag=!0}else $("#add_ag").append('<option value="0">请先添加代理组</option>'),$("#edit_ag").append('<option value="0">请先添加代理组</option>')}$("#add_ag_refresh").html("刷新"),$("#edit_ag_refresh").html("刷新")},error:function(t,a,e){$("#add_ag_refresh").html("刷新"),$("#edit_ag_refresh").html("刷新")}}),!1)}
		
    </script>	
<?php include_once 'footer.php';?>