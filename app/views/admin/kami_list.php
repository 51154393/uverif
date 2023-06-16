<?php include_once 'header.php';?>
	<div class="row">
		<div class="col-md-12">
			
			<div class="card shadow">
				<div class="card-body">
					<div class="toolbar">
						<form class="form">
							<div class="form-row">
								<div class="form-group col-auto mr-auto">
									<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#add"><i class="fe fe-plus fe-12 mr-2"></i><span class="small">添加卡密</span></button>
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
									<th class="text-left">
										卡密/备注
									</th>
									<th class="wd-20">
										面值/创建人
									</th>
									<th class="wd-20">
										使用者/时间
									</th>
									<th class="wd-20">
										创建/导出时间
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
						添加卡密
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form>
						<div class="form-group">
							<label>卡密分组</label>
							<div class="float-right">
								<a href="javascript:get_kmg(true);" id="add_kmg_refresh">刷新</a>
							</div>
							<select class="form-control" id="add_kmg">
								
							</select>
						</div>
						
						<div class="form-group">
							<label>卡密备注</label>
							<input type="text" class="form-control" placeholder="如：活动卡密（可空）" id="add_km_note">
						</div>
						<div class="form-group">
							<label>卡密长度<span class="fe fe-help-circle ml-1" data-toggle="tooltip" title="可自定义卡密长度，为保证卡密唯一性，卡密长度仅可在16~32位字符区间"></span></label>
							<input type="number" class="form-control" placeholder="卡密长度16~32区间" id="add_km_length" value="18">
						</div>
						<div class="form-group">
							<label>卡密前缀<span class="fe fe-help-circle ml-1" data-toggle="tooltip" title="卡密前缀有助于区分卡密，支持字母、数字、下划线(_)、横杠(-)"></span></label>
							<input type="text" class="form-control" placeholder="如：TK-（可空）" id="add_km_pre">
						</div>
						
						<div class="form-group">
							<label>生成数量<span class="fe fe-help-circle ml-1" data-toggle="tooltip" title="一次性最多生成1万张"></span></label>
							<div class="float-right">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="add_out" onchange="out_select(this.checked)">
									<label class="custom-control-label" for="add_out">生成后立即导出</label>
								</div>
							</div>
							<div class="input-group">
								<input type="number" class="form-control" placeholder="1" id="add_num" value="1">
								<div class="input-group-append">
									<span class="input-group-text">张</span>
								</div>
							</div>
						</div>
						
						<div class="form-group" id="add_out_type_div" hidden>
							<label>导出格式</label>
							<select class="form-control" id="add_out_type">
								<option value="txt">文本（txt）</option>
								<option value="csv">表格（csv）</option>
							</select>
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
					<h5 class="modal-title">编辑卡密</h5>
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
											<div class="col-12">
												<div class="row align-items-center">
													<div class="col-12">
														<div class="row align-items-center">
															<div class="col-auto mr-auto">
																<h5 class="mt-1 mb-0 text-white" id="edit_cardNo">YK-2D3657C4F36ACR4SZT</h5>
															</div>
															<div class="col-auto col-md-3">
																<div class="mt-1 text-white float-right">
																	<div class="float-left">
																		<div class="custom-switch">
																			<input type="checkbox" class="custom-control-input success" id="edit_state" onchange="checked_state(this.checked);" checked>
																			<label class="custom-control-label success" for="edit_state"></label>
																		</div>
																	</div>
																	<label class="mb-0" id="edit_state_label">正常</label>
																</div>
															</div>
														</div>
														<p class="mb-0 font-13 text-white-50">卡号</p>
													</div>
												</div>
											</div>
										</div> <!-- end row -->
										<ul class="list-group list-group-flush">
											<li class="list-group-item bg-primary border-bottom-primary">
												<p class="d-flex justify-content-between align-items-center mb-0 text-white">
													卡密组 <span class="col-auto" id="edit_kmGroup">天卡</span>
												</p>
											</li>
											
											<li class="list-group-item bg-primary border-bottom-primary">
												<p class="d-flex justify-content-between align-items-center mb-0 text-white">
													创建者 <span class="col-auto" id="edit_set_user">管理员</span>
												</p>
											</li>
											<li class="list-group-item bg-primary border-bottom-primary">
												<p class="d-flex justify-content-between align-items-center mb-0 text-white">
													创建时间 <span class="col-auto" id="edit_add_time">2023-06-08 23:12</span>
												</p>
											</li>
											<li class="list-group-item bg-primary border-bottom-primary">
												<p class="d-flex justify-content-between align-items-center mb-0 text-white">
													创建IP <span class="col-auto" id="edit_add_ip">127.0.0.1</span>
												</p>
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
                    							<input type="text" class="form-control" placeholder="设置卡密的备注" id="edit_km_note">
                    						</div>
										</form>
									</div> <!-- end card-body -->
								</div> <!-- end card-->
								
								<div class="card mb-3" id="edit_use_info" hidden>
									<div class="card-body">
										<ul class="list-group list-group-flush">
											<li class="list-group-item">
												<p class="d-flex justify-content-between align-items-center mb-0 text-uppercase">
													使用者 <span class="col-auto" id="edit_use_user">管理员</span>
												</p>
											</li>
											<li class="list-group-item">
												<p class="d-flex justify-content-between align-items-center mb-0 text-uppercase">
													使用时间 <span class="col-auto" id="edit_use_time">2023-06-08 23:12</span>
												</p>
											</li>
											<li class="list-group-item">
												<p class="d-flex justify-content-between align-items-center mb-0 text-uppercase">
													使用IP <span class="col-auto" id="edit_use_ip">127.0.0.1</span>
												</p>
											</li>
										</ul>
									</div>
								</div>
								
								<div class="card">
									<div class="card-body">
										<form>
											<div class="text-center">
												<button class="btn btn-primary btn-block" type="submit" id="edit_submit">确认编辑</button>
											</div>
										</form>
									</div>
								</div>
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
						<p class="mt-3">确认删除卡密：<strong class="text-danger me-1" id="del_name">null</strong> ？<br>删除后不可恢复！请慎重操作</p>
						<input type="number" class="form-control" id="del_id" placeholder="1" hidden>
						<button type="button" class="btn btn-danger mr-2 my-2" id="del_submit">确认删除</button>
						<button type="button" class="btn btn-light" data-dismiss="modal">取消删除</button>
					</div>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<script>
		function init(i,n={}){return initSubmit=i,$.ajax({cache:!1,type:"POST",data:i,dataType:"json",success:function(i){initSuccess(i,n)},error:function(i,t,c){initError(t,n)}}),get_kmg();!1}
		
		function add_list(t,e=!1){e&&$("#tbody_list").empty();for(var d=0;d<t.length;d++){var a="<td>"+t[d].id+"</td>";a+='<td class="text-left"><p class="mb-0">'+t[d].cardNo+'<small class="ml-1 badge badge-'+("vip"==t[d].type?"danger":"fen"==t[d].type?"warning":"info")+'-lighten">'+("vip"==t[d].type?"会员卡":"fen"==t[d].type?"积分卡":"设备卡")+'</small></p><small class="mb-0 text-muted">'+t[d].note+"</small></td>",a+='<td data-toggle="tooltip" title="分组: '+t[d].Gname+'"><p class="mb-0">'+("vip"==t[d].type?valTotype(t[d].val):t[d].val+("fen"==t[d].type?"积分":"台"))+'</p><small class="mb-0 text-muted">'+t[d].set_user+"</small></td>",a+='<td data-toggle="tooltip" title="使用IP: '+t[d].use_ip+'"><p class="mb-0">'+(isEmpty(t[d].use_uid)?"未使用":isEmpty(t[d].use_user)?t[d].use_uid:t[d].use_user)+'</p><small class="mb-0 text-muted">'+(isEmpty(t[d].use_time)?"未使用":timeToDate(t[d].use_time))+"</small></td>",a+='<td><p class="mb-0">'+timeToDate(t[d].add_time)+'</p><small class="mb-0 text-muted">'+(isEmpty(t[d].out_time)?"未导出":timeToDate(t[d].out_time))+"</small></td>",a+='<td><span class="mr-1 dot dot-lg bg-'+("y"==t[d].state?"success":"danger")+'"></span>'+("y"==t[d].state?"正常":"禁用")+"</td>",a+='<td><button type="button" class="btn btn-primary btn-sm mr-1" data-toggle="modal" data-target="#edit" onclick="edit(\''+base64_encode(JSON.stringify(t[d]))+'\');"><i class="fe fe-edit fe-12 mr-2"></i><span class="small">编辑</span></button><button type="button" class="btn btn-danger btn-sm" onclick="del('+t[d].id+",'"+t[d].cardNo+'\');"><i class="fe fe-trash-2 fe-12 mr-2"></i><span class="small">删除</span></button></td>',$("#list_"+t[d].id).length>0?$("#list_"+t[d].id).html(a):e?$("#tbody_list").append('<tr id="list_'+t[d].id+'">'+a+"</tr>"):$("#tbody_list").prepend('<tr id="list_'+t[d].id+'">'+a+"</tr>")}$('[data-toggle="tooltip"]').tooltip()}function edit(t){var e=base64_decode(t),d=JSON.parse(e);$("#edit_cardNo").html(d.cardNo),$("#edit_state").prop("checked","y"==d.state),checked_state("y"==d.state),$("#edit_kmGroup").html(d.Gname),$("#edit_set_user").html(d.set_user),$("#edit_add_time").html(timeToDate(d.add_time)),$("#edit_add_ip").html(d.add_ip),$("#edit_km_note").val(d.note),isEmpty(d.use_uid)?$("#edit_use_info").attr("hidden",!0):($("#edit_use_info").attr("hidden",!1),$("#edit_use_user").html(d.use_user),$("#edit_use_time").html(timeToDate(d.use_time)),$("#edit_use_ip").html(d.use_ip)),$("#edit_id").val(d.id)}function out_select(t){$("#add_out_type_div").attr("hidden",!t)}function checked_state(t){$("#edit_state_label").html(t?"正常":"禁用")}function del(t,e){$("#del_name").html(e),$("#del_id").val(t),$("#del").modal("show")}$("#add_submit").click(function(){var t={act:"add"};return t.kgid=$("#add_kmg").val(),t.note=$("#add_km_note").val(),t.length=$("#add_km_length").val(),t.pre=$("#add_km_pre").val(),t.num=$("#add_num").val(),t.out=$("#add_out").prop("checked")?"y":"n",t.out_type=$("#add_out_type").val(),$("#add_submit").html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>正在提交'),$("#add_submit").attr("disabled",!0),$.ajax({cache:!1,type:"POST",data:t,dataType:"json",success:function(e){$("#add_submit").html("确认添加"),$("#add_submit").attr("disabled",!1),200==e.code?(cocoMessage.success(e.msg,3e3),init(initSubmit),"y"==t.out&&(window.location.href=e.data.downUrl),$("#add_km_note").val(""),$("#add_out").prop("checked",!1),out_select(!1),$("#add_num").val("1"),$("#add").modal("hide")):cocoMessage.error(e.msg,3e3)},error:function(t,e,d){cocoMessage.error("系统发生错误，请打开浏览器调试模式查看具体原因",3e3),$("#add_submit").html("确认添加"),$("#add_submit").attr("disabled",!1)}}),!1}),$("#edit_submit").click(function(){var t={act:"edit"};return t.id=$("#edit_id").val(),t.note=$("#edit_km_note").val(),t.state=$("#edit_state").prop("checked")?"y":"n",$("#edit_submit").html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>正在提交'),$("#edit_submit").attr("disabled",!0),$.ajax({cache:!1,type:"POST",data:t,dataType:"json",success:function(t){$("#edit_submit").html("确认编辑"),$("#edit_submit").attr("disabled",!1),200==t.code?(add_list(t.data.list),cocoMessage.success("编辑成功",3e3)):cocoMessage.error(t.msg,3e3)},error:function(t,e,d){cocoMessage.error("系统发生错误，请打开浏览器调试模式查看具体原因",3e3),$("#edit_submit").html("确认编辑"),$("#edit_submit").attr("disabled",!1)}}),!1}),$("#del_submit").click(function(){var t=$("#del_id").val();return $("#del_submit").html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>正在删除'),$("#del_submit").attr("disabled",!0),$.ajax({cache:!1,type:"POST",data:{act:"del",id:t},dataType:"json",success:function(e){($("#del_submit").html("确认删除"),$("#del_submit").attr("disabled",!1),200==e.code)?($("#list_"+t).remove(),cocoMessage.success(e.msg,3e3),$("#del").modal("hide"),$("#tbody_list").children().length<1&&init(initSubmit)):cocoMessage.error(e.msg,3e3)},error:function(t,e,d){cocoMessage.error("系统发生错误，请打开浏览器调试模式查看具体原因",3e3),$("#add_submit").html("确认添加"),$("#add_submit").attr("disabled",!1)}}),!1});var inikmg=!1;function get_kmg(t=!1){return!(!t&&inikmg)&&($("#add_kmg_refresh").html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>刷新中'),$.ajax({cache:!1,type:"POST",data:{act:"get_kmg"},dataType:"json",success:function(t){if(200==t.code){var e=t.data.list;if(e.length>=1){$("#add_kmg").empty();for(var d=0;d<e.length;d++)$("#add_kmg").append('<option value="'+e[d].id+'">'+e[d].name+"</option>");inikmg=!0}else $("#add_kmg").append('<option value="0">请先添加卡密组</option>')}$("#add_kmg_refresh").html("刷新")},error:function(t,e,d){$("#add_kmg_refresh").html("刷新")}}),!1)}function valTotype(t){var e;return 9999999999==t?"永久":t>=86400?(e=t/60/60/24,(isInteger(e)?e:e.toFixed(2))+"天"):t>=3600?(e=t/60/60,(isInteger(e)?e:e.toFixed(2))+"时"):t>=60?(e=t/60,(isInteger(e)?e:e.toFixed(2))+"分"):t+"秒"}
    </script>	
<?php include_once 'footer.php';?>