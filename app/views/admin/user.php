<?php include_once 'header.php';?>
	<div class="row">
		<div class="col-md-12">
			<div class="card shadow">
				<div class="card-body">
					<div class="toolbar">
						<form class="form">
							<div class="form-row">
								<div class="form-group col-auto mr-auto">
									<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#add"><i class="fe fe-plus fe-12 mr-2"></i><span class="small">添加用户</span></button>
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
										UID
									</th>
									<th class="wd-20">
										头像
									</th>
									<th class="text-left">
										账号
									</th>
									<th class="wd-20">
										积分
									</th>
									<th class="wd-20">
										用户组
									</th>
									<th  class="wd-20">
										注册时间/IP
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
						添加用户
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form>
						<div class="form-group">
							<label>账号</label>
							<input type="text" class="form-control" id="add_unum" placeholder="设置一个账号">
						</div>
						<div class="form-group">
							<label>密码</label>
							<input type="text" class="form-control" id="add_password" placeholder="设置一个密码">
						</div>
						
						<div class="form-group">
							<label>会员到期时间</label>
							<div class="float-right">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="add_vip_long" onclick="checked_vip('add',this.checked);">
									<label class="custom-control-label" for="add_vip_long">永久会员</label>
								</div>
							</div>
							<input type="datetime-local" id="add_vip" class="form-control">
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">
						取消
					</button>
					<button type="button" class="btn mb-2 btn-primary" id="add_submit">
						确认添加
					</button>
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal fade modal-right modal-slide" id="edit" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-plus" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">
						编辑用户
					</h5>
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
											<div class="col-sm-8">
												<div class="row align-items-center">
													<div class="col-auto">
														<div class="avatar-lg">
															<img id="edit_pic" src="/assets/avatars/default.svg" class="rounded-circle img-thumbnail" style="width: 96px;">
														</div>
													</div>
													<div class="col">
														<h5 class="mt-1 mb-0 text-white" id="edit_account">123456</h5>
														<p class="font-13 text-white-50" id="edit_uname">一位神秘的网友</p>

														<ul class="mb-0 list-inline">
															<li class="list-inline-item me-3">
																<h6 class="mb-0 text-white" id="edit_reg_time">2022-10-27 18:05</h6>
																<p class="mb-0 font-13 text-white-50">注册时间</p>
															</li>
															<li class="list-inline-item">
																<h6 class="mb-0 text-white" id="edit_reg_ip">38.94.109.28</h6>
																<p class="mb-0 font-13 text-white-50">注册IP</p>
															</li>
														</ul>
													</div>
												</div>
											</div> <!-- end col-->
											<div class="col-sm-4">
												<div class="text-sm-right text-center mt-sm-2 mt-3">
													<div id="edit_qq" class="dropdown list-inline-item avatar-sm avatar-hb">
														<img src="/assets/images/logon-ico/qq.png" class="rounded-circle img-thumbnail">
													</div>
													<div id="edit_wx" class="dropdown list-inline-item avatar-sm avatar-hb">
														<img src="/assets/images/logon-ico/wx.png" class="rounded-circle img-thumbnail">
													</div>
												</div>
												<div class="text-center mt-sm-4 mt-3 text-sm-right">
													<small class="badge badge-pill text-white bg-primary-dark" id="edit_inv_id">暂无邀请人</small>
													<small class="badge badge-pill badge-danger" id="edit_vip_group">会员用户</small>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-7">
								<div class="card mb-3">
									<div class="card-body">
										<div class="form-group">
											<label>账号<span class="fe fe-help-circle ml-1" data-toggle="tooltip" title="账号设置方法：管理员后台添加、用户注册API、用户绑定API，不可编辑"></span></label>
											<input type="text" id="edit_acctno" class="form-control" placeholder="5~18位字符串" readonly>
										</div>
										<div class="row">
											<div class="form-group col-md-6">
												<label>手机号<span class="fe fe-help-circle ml-1" data-toggle="tooltip" title="手机号设置方法：用户注册API、用户绑定API，不可编辑"></span></label>
												<input type="number" id="edit_phone" class="form-control" placeholder="仅支持中国（86）11位手机号" readonly>
											</div>
											<div class="form-group col-md-6">
												<label>邮箱<span class="fe fe-help-circle ml-1" data-toggle="tooltip" title="邮箱设置方法：用户注册API、用户绑定API，不可编辑"></span></label>
												<input type="email" class="form-control" id="edit_email" placeholder="电子邮箱，推荐使用QQ邮箱" readonly>
											</div>
										</div>
										<div class="row">
    										<div class="form-group col-md-6">
    											<label>会员到期时间</label>
    											<div class="float-right">
    												<div class="custom-control custom-checkbox">
    													<input type="checkbox" class="custom-control-input" id="edit_vip_long" onclick="checked_vip('edit',this.checked);">
    													<label class="custom-control-label" for="edit_vip_long">永久会员</label>
    												</div>
    											</div>
    											<input type="datetime-local" id="edit_vip" class="form-control">
    										</div>
    										<div class="form-group col-md-6">
    											<label>积分</label>
    											<div class="input-group">
                    								<input type="number" class="form-control" placeholder="积分余额" id="edit_fen">
                    								<div class="input-group-append">
                    									<span class="input-group-text">积分</span>
                    								</div>
                    							</div>
    										</div>
        								</div>	
        								<div class="form-group">
											<label>额外绑定设备数<span class="fe fe-help-circle ml-1" data-toggle="tooltip" title="基于系统设置额外增加可绑定设备数"></span></label>
											<div class="input-group">
                								<input type="number" class="form-control" placeholder="如：1" id="edit_client_max">
                								<div class="input-group-append">
                									<span class="input-group-text">台</span>
                								</div>
                							</div>
										</div>
									</div> <!-- end card-body -->
								</div> <!-- end card -->
								
								<div class="card mb-3">
									<div class="card-body">
										<form>
											<div class="form-group" hidden>
												<label>用户ID</label>
												<input type="number" class="form-control" id="edit_id" placeholder="1">
											</div>
											
											<div class="form-group">
												<label>密码</label>
												<input type="text" class="form-control" id="edit_password" placeholder="空则不修改密码">
											</div>
											
											<div class="form-group">
												<div class="float-left">
													<div class="custom-switch">
														<input type="checkbox" class="custom-control-input" id="edit_state" onchange="checked_ban(this.checked);">
														<label class="custom-control-label" for="edit_state"></label>
													</div>
												</div>
												<label>禁用用户期限</label>
												<div class="float-right" id="edit_ban_long_div" hidden>
													<div class="custom-control custom-checkbox">
														<input type="checkbox" class="custom-control-input" id="edit_ban_long" onclick="checked_ban(this.checked,'long');">
														<label class="custom-control-label" for="edit_ban_long">永久禁用</label>
													</div>
												</div>
												<input type="datetime-local" id="edit_ban" class="form-control" data="aaaaaaaa" readonly>
											</div>
											
											<div class="form-group" id="ban_notice_div" hidden>
												<label>禁用原因</label>
												<textarea class="form-control" id="edit_ban_msg" rows="4" placeholder="如：违反用户使用协议，禁用中"></textarea>
											</div>
											
											<div class="text-center mt-3">
												<button class="btn btn-primary btn-block" type="submit" id="edit_submit">确认编辑</button>
											</div>
										</form>
									</div> <!-- end card-body -->
								</div> <!-- end card -->
							</div> <!-- end col -->
							
							<div class="col-md-5">
								<div class="card">
									<div class="card-body">
									    <ul class="nav nav-pills nav-fill mb-3" id="myTab" role="tablist">
											<li class="nav-item">
												<a class="nav-link active" data-toggle="tab" href="#log" role="tab" aria-selected="true">
													用户日志
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" data-toggle="tab" href="#client" role="tab" aria-selected="false">
													绑定设备
												</a>
											</li>
										</ul>
										<div class="tab-content">
											<div  class="tab-pane fade show active" id="log" role="tabpanel">
												<div class="p-4" id="logs_state">
													<div class="text-center mt-5 mb-5">
														<div class="avatar avatar-lg">
															<img src="/assets/images/default/nolog.svg" class="avatar-img" style="width: 200px;">
														</div>
														<div class="card-text">
															<strong class="card-title my-0">
																暂无日志
															</strong>
															<p class="small text-muted mb-5">
																There's a little problem
															</p>
														</div>
													</div>
												</div>
												<div class="table-responsive" id="logs_table" hidden>
													<table class="table table-hover table-borderless table-striped">
														<thead>
															<tr>
																<th>
																	操作类型
																</th>
																<th>
																	时间/IP
																</th>
																<th style="width: 50px;">
																	状态
																</th>
															</tr>
														</thead>
														<tbody id="tbody_logs">
															
														</tbody>
													</table>
												</div>
											</div>
											<div class="tab-pane fade" id="client" role="tabpanel">
												<div class="p-4" id="client_state">
													<div class="text-center mt-5 mb-5">
														<div class="avatar avatar-lg">
															<img src="/assets/images/default/noClient.svg" class="avatar-img" style="width:200px;">
														</div>
														<div class="card-text">
															<strong class="card-title my-0">
																暂未绑定任何设备
															</strong>
															<p class="small text-muted mb-5">
																There's a little problem
															</p>
														</div>
													</div>
												</div>
												<div class="table-responsive" id="client_table" hidden>
													<table class="table table-hover table-borderless table-striped mt-n3 mb-n1">
														<thead>
															<tr>
																<th class="text-left">
																	设备标识/时间
																</th>
																<th class="wd-20">
																	操作
																</th>
															</tr>
														</thead>
														<tbody id="tbody_client">
															
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div> <!-- end card-->
							</div><!-- end col-->
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
						<p class="mt-3">确认删除用户：<strong class="text-danger me-1" id="del_name">null</strong> ？<br>删除后不可恢复！请慎重操作</p>
						<input type="number" class="form-control" id="del_id" placeholder="1" hidden>
						<button type="button" class="btn btn-danger mr-2 my-2" id="del_submit">确认删除</button>
						<button type="button" class="btn btn-light" data-dismiss="modal">取消删除</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		function init(i,n={}){return initSubmit=i,$.ajax({cache:!1,type:"POST",data:i,dataType:"json",success:function(i){initSuccess(i,n)},error:function(i,t,c){initError(t,n)}}),!1}
		function add_list(t,e=!1){e&&$("#tbody_list").empty();for(var a=0;a<t.length;a++){var i="<td>"+t[a].id+"</td>";i+='<td><div class="avatar"><img src="/'+t[a].avatars+'" class="avatar-img rounded-circle pic" onerror="javascript:this.src=\'/assets/avatars/default.svg\';"></div></td>',i+='<td class="text-left"><p class="mb-0"><strong>'+(isEmpty(t[a].phone)?isEmpty(t[a].email)?t[a].acctno:t[a].email:t[a].phone)+'</strong></p><small class="mb-0 text-muted">'+t[a].nickname+"</small></td>",i+="<td>"+t[a].fen+"</td>",i+="<td>"+(t[a].vip<time()?'<span class="badge badge-pill badge-light">普通用户':'<span class="badge badge-pill badge-danger cursor-default" data-toggle="tooltip" title="到期时间：'+(t[a].vip>=9999999999?"永久":timeToDate(t[a].vip))+'">会员用户')+"</span></td>",i+='<td class="text-muted"><p class="mb-0">'+timeToDate(t[a].reg_time)+'</p><small class="mb-0 text-muted">'+t[a].reg_ip+"</small></td>",i+='<td><span class="dot dot-lg bg-'+(t[a].ban<time()?"success":"danger")+'"></span></td>',i+='<td><button type="button" class="btn btn-primary btn-sm mr-1" data-toggle="modal" data-target="#edit" onclick="edit(\''+base64_encode(JSON.stringify(t[a]))+'\');"><i class="fe fe-edit fe-12 mr-2"></i><span class="small">编辑</span></button><button type="button" class="btn btn-danger btn-sm" onclick="del('+t[a].id+",'"+(isEmpty(t[a].phone)?isEmpty(t[a].email)?t[a].acctno:t[a].email:t[a].phone)+'\');"><i class="fe fe-trash-2 fe-12 mr-2"></i><span class="small">删除</span></button></td>',$("#list_"+t[a].id).length>0?$("#list_"+t[a].id).html(i):e?$("#tbody_list").append('<tr id="list_'+t[a].id+'">'+i+"</tr>"):$("#tbody_list").prepend('<tr id="list_'+t[a].id+'">'+i+"</tr>")}$('[data-toggle="tooltip"]').tooltip()}function edit(t){var e=base64_decode(t),a=JSON.parse(e);if(isEmpty(a.avatars)?$("#edit_pic").attr("src","/assets/avatars/default.svg"):$("#edit_pic").attr("src","/"+a.avatars),$("#edit_account").html(isEmpty(a.phone)?isEmpty(a.email)?a.acctno:a.email:a.phone),$("#edit_uname").html(a.nickname),$("#edit_reg_time").html(timeToDate(a.reg_time)),$("#edit_reg_ip").html(a.reg_ip),$("#edit_id").val(a.id),isEmpty(a.openid_wx)?$("#edit_wx").addClass("avatar-hb"):$("#edit_wx").removeClass("avatar-hb"),isEmpty(a.openid_qq)?$("#edit_qq").addClass("avatar-hb"):$("#edit_qq").removeClass("avatar-hb"),$("#edit_inv_id").html(isEmpty(a.inv_id)?"暂无邀请人":"邀请人ID："+a.inv_id),$("#edit_vip_group").html(a.vip<time()?"普通用户":"会员用户"),a.vip<time()?$("#edit_vip_group").attr("class","badge badge-pill badge-light"):$("#edit_vip_group").attr("class","badge badge-pill badge-danger"),a.vip>=9999999999)$("#edit_vip_long").prop("checked",!0),$("#edit_vip").attr("type","text"),$("#edit_vip").val("9999-99-99 99:99:99"),$("#edit_vip").attr("readonly",!0);else if(a.vip>time()){$("#edit_vip_long").prop("checked",!1);var i=timeToDate(a.vip,!0);$("#edit_vip").attr("type","datetime-local"),$("#edit_vip").val(i),$("#edit_vip").attr("readonly",!1)}else $("#edit_vip_long").prop("checked",!1),$("#edit_vip").attr("type","datetime-local"),$("#edit_vip").val(""),$("#edit_vip").attr("readonly",!1);$("#edit_fen").val(a.fen),$("#edit_acctno").val(a.acctno),$("#edit_phone").val(a.phone),$("#edit_email").val(a.email),$("#edit_ban").attr("data",a.ban),a.ban>=9999999999?($("#edit_state").prop("checked",!0),checked_ban(!0),checked_ban(!0,"long"),$("#edit_ban_long").prop("checked",!0)):a.ban>time()?($("#edit_state").prop("checked",!0),checked_ban(!0),checked_ban(!1,"long")):(checked_ban(!1),$("#edit_state").prop("checked",!1)),$("#edit_ban_msg").val(a.ban_msg),$("#edit_client_max").val(a.client_max),isEmpty(a.client_list)?add_client([]):add_client(JSON.parse(a.client_list)),get_log(a.id)}function del(t,e){$("#del_name").html(e),$("#del_id").val(t),$("#del").modal("show")}function get_log(t){return $.ajax({cache:!1,type:"POST",data:{act:"log",id:t},dataType:"json",success:function(t){200==t.code&&add_logs(t.data.list)},error:function(t,e,a){cocoMessage.error("用户日志获取失败",2e3)}}),!1}function add_logs(t){t.length>0?($("#logs_table").attr("hidden",!1),$("#logs_state").attr("hidden",!0)):($("#logs_state").attr("hidden",!1),$("#logs_table").attr("hidden",!0)),$("#tbody_logs").empty();for(var e=0;e<t.length;e++){var a="<tr><td>"+t[e].act+'<br /><span class="small text-muted">'+t[e].aren+'</span></td><th scope="col">'+t[e].time+'<br /><span class="small text-muted">'+t[e].ip+'</span></th><td><span class="dot dot-lg bg-'+("y"==t[e].state?"success":"danger")+'"></span></td></tr>';$("#tbody_logs").append(a)}}function add_client(t){if(t.length>0){$("#client_state").attr("hidden",!0),$("#client_table").attr("hidden",!1),$("#tbody_client").empty();for(var e=0;e<t.length;e++){var a='<tr id="client_'+t[e].udid+'"><td class="text-left">'+t[e].udid+'<br /><span class="small text-muted">'+timeToDate(t[e].time)+'</span></td><td><a id="client_'+t[e].udid+'" href="javascript:del_client(\''+t[e].udid+"');\">解绑</a></td></tr>";$("#tbody_client").append(a)}}else $("#client_state").attr("hidden",!1),$("#client_table").attr("hidden",!0)}function del_client(t){var e=$("#edit_id").val();$("#client_del_"+t).html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>'),$("#logon_del_"+t).attr("style","pointer-events:none;"),$.ajax({cache:!1,type:"POST",data:{act:"del_client",id:e,cid:t},dataType:"json",success:function(e){200==e.code?($("#client_"+t).remove(),cocoMessage.success(e.msg,2e3)):($("#client_del_"+t).html("解绑"),$("#client_del_"+t).attr("style",""),cocoMessage.error(e.msg,2e3))},error:function(e,a,i){$("#client_del_"+t).html("解绑"),$("#client_del_"+t).attr("style",""),cocoMessage.error(a,2e3)}})}function checked_vip(t,e){if(e){var a="9999-99-99 99:99:99";$("#"+t+"_vip").attr("type","text"),$("#"+t+"_vip").val(a)}else{a="edit"==t?"":timeToDate(null,!0);$("#"+t+"_vip").attr("type","datetime-local"),$("#"+t+"_vip").val(a)}$("#"+t+"_vip").attr("readonly",e)}function checked_ban(t,e=null){if("long"==e){if(t){var a="9999-99-99 99:99:99";$("#edit_ban").attr("type","text"),$("#edit_ban").val(a),$("#edit_ban").attr("readonly",!0)}else{if($("#edit_ban").attr("type","datetime-local"),(d=$("#edit_ban").attr("data"))>0){var i=timeToDate(d,!0);$("#edit_ban").val(i)}else $("#edit_ban").val("")}$("#edit_ban").attr("readonly",t)}else{var d;if($("#edit_ban").attr("type","datetime-local"),$("#edit_ban").val(""),$("#edit_ban").attr("readonly",!t),$("#ban_notice_div").attr("hidden",!t),$("#edit_ban_long").prop("checked",!1),$("#edit_ban_long_div").attr("hidden",!t),t)if((d=$("#edit_ban").attr("data"))>time())if(d>=9999999999){a="9999-99-99 99:99:99";$("#edit_ban").attr("type","text"),$("#edit_ban").val(a),$("#edit_ban").attr("readonly",!0),$("#edit_ban_long").prop("checked",!0)}else{i=timeToDate(d,!0);$("#edit_ban").val(i)}else $("#edit_ban").val("")}}$("#add_submit").click(function(){var t={act:"add"};t.acctNo=$("#add_unum").val(),t.password=$("#add_password").val();var e=$("#add_vip").val();if(!!$("#add_vip_long").prop("checked"))t.vip=9999999999;else{var a=Date.parse(e)/1e3;(!isEmpty(a)||a>time())&&(t.vip=a)}return $("#add_submit").html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>正在提交'),$("#add_submit").attr("disabled",!0),$.ajax({cache:!1,type:"POST",data:t,dataType:"json",success:function(t){$("#add_submit").html("确认添加"),$("#add_submit").attr("disabled",!1),200==t.code?(cocoMessage.success("添加成功",2e3),add_list(t.data.list),$("#add").modal("hide")):cocoMessage.error(t.msg,2e3)},error:function(t,e,a){cocoMessage.error("系统发生错误，请打开浏览器调试模式查看具体原因",2e3),$("#add_submit").html("确认添加"),$("#add_submit").attr("disabled",!1)}}),!1}),$("#edit_submit").click(function(){var t={act:"edit"};t.id=$("#edit_id").val();var e=$("#edit_vip").val();if(!!$("#edit_vip_long").prop("checked"))t.vip=9999999999;else{var a=Date.parse(e)/1e3;isEmpty(a)?t.vip=time():t.vip=a}var i=!!$("#edit_state").prop("checked"),d=!!$("#edit_ban_long").prop("checked");if(i)if(d)t.ban=9999999999,t.ban_msg=$("#edit_ban_msg").val();else{var s=$("#edit_ban").val(),l=Date.parse(s)/1e3;isEmpty(l)||l<time()?t.ban=0:(t.ban=l,t.ban_msg=$("#edit_ban_msg").val())}else t.ban=0;t.client_max=$("#edit_client_max").val(),t.fen=$("#edit_fen").val();var n=$("#edit_password").val();return isEmpty(n)||(t.password=$("#edit_password").val()),$("#edit_submit").html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>正在提交'),$("#edit_submit").attr("disabled",!0),$.ajax({cache:!1,type:"POST",data:t,dataType:"json",success:function(t){$("#edit_submit").html("确认编辑"),$("#edit_submit").attr("disabled",!1),200==t.code?(cocoMessage.success("编辑成功",2e3),add_list(t.data.list)):cocoMessage.error(t.msg,2e3)},error:function(t,e,a){cocoMessage.error("系统发生错误，请打开浏览器调试模式查看具体原因",2e3),$("#edit_submit").html("确认编辑"),$("#edit_submit").attr("disabled",!1)}}),!1}),$("#del_submit").click(function(){var t=$("#del_id").val();return $("#del_submit").html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>正在删除'),$("#del_submit").attr("disabled",!0),$.ajax({cache:!1,type:"POST",data:{act:"del",id:t},dataType:"json",success:function(e){($("#del_submit").html("确认删除"),$("#del_submit").attr("disabled",!1),200==e.code)?($("#list_"+t).remove(),cocoMessage.success(e.msg,2e3),$("#del").modal("hide"),$("#tbody_list").children().length<1&&init(initSubmit)):cocoMessage.error(e.msg,2e3)},error:function(t,e,a){cocoMessage.error("系统发生错误，请打开浏览器调试模式查看具体原因",2e3),$("#del_submit").html("确认删除"),$("#del_submit").attr("disabled",!1)}}),!1});
	</script>	
<?php include_once 'footer.php';?>
