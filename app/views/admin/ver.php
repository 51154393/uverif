<?php include_once 'header.php';?>
	
	<div class="row" id="ver_list">
		
	</div>
	
	
	<div class="modal fade" id="modal_ver" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modal_title">
						添加版本
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form>
						<input type="number" class="form-control" id="ver_id" placeholder="1" hidden>
						<div class="form-group row">
							<label class="col-sm-6 col-form-label">版本名称</label>
							<div class="col-sm-6">
								<input type="text" class="form-control" id="ver_name" placeholder="安卓版">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-6 col-form-label">版本号</label>
							<div class="col-sm-6">
								<input type="number" class="form-control" id="ver_val" placeholder="1.0">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-6 col-form-label">版本索引</label>
							<div class="col-sm-6">
								<input type="text" class="form-control" id="ver_key" placeholder="android">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-9 col-form-label">数据加密</label>
							<div class="col-3">
								<div class="float-right">
									<div class="custom-switch">
										<input type="checkbox" class="custom-control-input" id="mi_state" onclick="checked_state('mi_state_fieldset',!this.checked);">
										<label class="custom-control-label" for="mi_state"></label>
									</div>
								</div>
							</div>
						</div>
						
						<div class="form-group row">
							<label class="col-9 col-form-label">数据签名<span class="fe fe-help-circle ml-1" data-toggle="tooltip" title="可防止客户端发送数据被篡改"></span></label>
							<div class="col-3">
								<div class="float-right">
									<div class="custom-switch">
										<input type="checkbox" class="custom-control-input" id="mi_sign">
										<label class="custom-control-label" for="mi_sign"></label>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-6 col-form-label">时差校验<span class="fe fe-help-circle ml-1" data-toggle="tooltip" title="可校验客户端时间和服务器时间，避免客户端利用时间漏洞非法使用软件"></span></label>
							<div class="col-sm-6">
								<div class="input-group">
									<input type="number" class="form-control" placeholder="校验数据是否超时" id="mi_time" value="100">
									<div class="input-group-append">
										<span class="input-group-text">秒</span>
									</div>
								</div>
							</div>
						</div>
						<fieldset id="mi_state_fieldset" hidden>
							<div class="form-group row">
								<label class="col-form-label col-4">加密方式</label>
								<div class="col-8">
									<div class="float-right">
										<div class="form-check form-check-inline">
											<div class="custom-control custom-radio">
												<input type="radio" id="mi_rc4" name="mi_type" class="custom-control-input" checked onclick="checked_mi('rc4');">
												<label class="custom-control-label" for="mi_rc4">RC4</label>
											</div>
										</div>
										<div class="form-check form-check-inline">
											<div class="custom-control custom-radio">
												<input type="radio" id="mi_aes" name="mi_type" class="custom-control-input" onclick="checked_mi('aes');">
												<label class="custom-control-label" for="mi_aes">AES</label>
											</div>
										</div>
										<div class="form-check form-check-inline">
											<div class="custom-control custom-radio">
												<input type="radio" id="mi_rsa" name="mi_type" class="custom-control-input" onclick="checked_mi('rsa');">
												<label class="custom-control-label" for="mi_rsa">RSA</label>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group row" id="mi_rc4_div">
								<label class="col-sm-4">RC4密钥</label>
								<div class="col-sm-8">
									<textarea class="form-control" id="mi_rc4_key" rows="2" placeholder="密钥长度16~32位"></textarea>
								</div>
							</div>
							<div class="form-group" id="mi_aes_div" hidden>
								<div class="row mb-2">
									<label class="col-sm-4">AES密钥</label>
									<div class="col-sm-8">
										<textarea class="form-control" id="mi_aes_key" rows="2" placeholder="密钥长度必须为32"></textarea>
									</div>
								</div>
								<div class="row">
									<label class="col-sm-4">AESIV</label>
									<div class="col-sm-8">
										<textarea class="form-control" id="mi_aes_iv" rows="2" placeholder="IV长度必须为16"></textarea>
									</div>
								</div>
							</div>
							<div class="form-group" id="mi_rsa_div" hidden>
								<div class="row mb-2">
									<label class="col-sm-4">RSA公钥</label>
									<div class="col-sm-8">
										<textarea class="form-control" id="mi_public_key" rows="2"></textarea>
									</div>
								</div>
								<div class="row">
									<label class="col-sm-4">RSA私钥</label>
									<div class="col-sm-8">
										<textarea class="form-control" id="mi_private_key" rows="2"></textarea>
									</div>
								</div>
							</div>
						</fieldset>
						
					
						<div class="form-group row">
							<label class="col-sm-4 col-form-label">更新地址</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="ver_new_url" placeholder="版本下载地址">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-4">更新内容</label>
							<div class="col-sm-8">
								<textarea class="form-control" id="ver_new_content" rows="4" placeholder="如：1、更新了界面&#10;2、优化了用户体验&#10;3、修复了已知BUG"></textarea>
							</div>
						</div>
						<div id="modal_edit_ver_state" hidden>
							<div class="form-group row">
								<label class="col-9 col-form-label">版本状态</label>
								<div class="col-3">
									<div class="float-right">
										<div class="custom-switch">
											<input type="checkbox" class="custom-control-input success" id="ver_state" onclick="checked_state('ver_off_msg_div',this.checked);">
											<label class="custom-control-label success" for="ver_state"></label>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group row" id="ver_off_msg_div" hidden>
								<label class="col-sm-4">版本关闭通知</label>
								<div class="col-sm-8">
									<textarea class="form-control" id="ver_off_msg" rows="4" placeholder="如：当前客户端维护中"></textarea>
								</div>
							</div>
						</div>
						<div class="form-group mb-2">
							<button type="submit" class="btn btn-primary btn-block" id="submit">提交</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	
	<div id="del_ver" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-body p-4">
					<div class="text-center">
						<h4 class="mt-2">删除警告</h4>
						<p class="mt-3">确认删除版本：<strong class="text-danger me-1" id="del_name">null</strong> ？<br>删除后不可恢复！请慎重操作</p>
						<input type="number" class="form-control" id="del_id" placeholder="1" hidden>
						<button type="button" class="btn btn-danger mr-2 my-2" id="del_submit">确认删除</button>
						<button type="button" class="btn btn-light" data-dismiss="modal">取消删除</button>
					</div>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<script>
		function init(i,n={}){return initSubmit=i,$.ajax({cache:!1,type:"POST",data:i,dataType:"json",success:function(i){initSuccess(i,n)},error:function(i,t,c){initError(t,n)}}),!1};
		function add_list(e,i=!1){i&&$("#ver_list").empty();for(var a=0;a<e.length;a++){var t='<div class="card shadow"><div class="card-header mt-1"><span class="card-title h6">'+e[a].ver_name+" v"+e[a].ver_val+'</span><div class="float-right"><div class="dropdown"><a class="text-muted ml-1 text-decoration-none" href="javascript:;" id="dr1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fe fe-edit-2 fe-16"></i></a><div class="dropdown-menu dropdown-menu-right" aria-labelledby="dr1"><a class="dropdown-item" href="javascript:edit_list(\''+base64_encode(JSON.stringify(e[a]))+'\');">编辑</a><a class="dropdown-item" href="javascript:del_list('+e[a].id+",'"+e[a].ver_name+'\');">删除</a></div></div></div></div><div class="card-body my-n2"><div class="row align-items-center"><div class="col-12" style="white-space: nowrap;"><div class="mb-2"><p class="text-muted d-inline">版本索引：</p><span class="float-right cursor-pointer" onclick="sy_copy(\''+e[a].ver_key+"')\">"+e[a].ver_key+'</span></div><div class="mb-2"><p class="text-muted d-inline">数据加密：</p><div class="float-right"><div class="custom-switch"><input type="checkbox" class="custom-control-input" id="mi_state_'+e[a].id+'" '+("on"==e[a].mi_state?"checked":"")+' onclick="edit_switch('+e[a].id+',\'mi_state\');"><label class="custom-control-label" for="mi_state_'+e[a].id+'"></label></div></div></div><div class="mb-2"><p class="text-muted d-inline">加密类型：</p><div class="float-right"><span class="badge badge-pill badge-'+("off"==e[a].mi_state?"secondary":"success")+'">'+e[a].mi_type+'</span></div></div><div class="mb-2"><p class="text-muted d-inline">数据签名：</p><div class="float-right"><div class="custom-switch"><input type="checkbox" class="custom-control-input" id="mi_sign_'+e[a].id+'" '+("on"==e[a].mi_sign?"checked":"")+' onclick="edit_switch('+e[a].id+',\'mi_sign\');"><label class="custom-control-label" for="mi_sign_'+e[a].id+'"></label></div></div></div><div class="mb-2"><p class="text-muted d-inline">时差校验：</p><span class="float-right">'+e[a].mi_time+' 秒</span></div><div class="mb-2"><p class="text-muted d-inline">版本开关：</p><div class="float-right"><div class="custom-switch"><input type="checkbox" class="custom-control-input success" id="ver_state_'+e[a].id+'" '+("on"==e[a].ver_state?"checked":"")+' onclick="edit_switch('+e[a].id+',\'ver_state\');"><label class="custom-control-label success" for="ver_state_'+e[a].id+'"></label></div></div></div></div></div></div></div>';$("#ver_"+e[a].id).length>0?$("#ver_"+e[a].id).html(t):i?$("#ver_list").append('<div class="col-md-6 col-lg-4 col-xl-3 mb-4" id="ver_'+e[a].id+'">'+t+"</div>"):$("#ver_list").prepend('<div class="col-md-6 col-lg-4 col-xl-3 mb-4" id="ver_'+e[a].id+'">'+t+"</div>")}$("#ver_list").children().length>=13?$("#add_ver").attr("hidden",!0):$("#add_ver").length<1&&$("#ver_list").append('<div class="col-md-6 col-lg-4 col-xl-3 mb-4" onclick="modal_show()" id="add_ver"><div class="card shadow" style="cursor: pointer;"><div class="card-body my-n2" style="height: 265px;"><div class="avatar avatar-lg mb-2" style="padding-top: 65px;margin-left: calc(50% - 32px);"><img src="/assets/images/plus.svg" class="avatar-img"></div><p class="text-muted text-center mb-0">创建版本</p></div></div></div>')}function edit_list(e){var i=base64_decode(e),a=JSON.parse(i.replace(/\n/g,"\\n").replace(/\r/g,"\\r"));$("#modal_title").html("编辑版本"),$("#modal_edit_ver_state").attr("hidden",!1),$("#ver_id").val(a.id),$("#ver_val").val(a.ver_val),$("#ver_name").val(a.ver_name),$("#ver_key").val(a.ver_key),$("#ver_new_url").val(a.ver_new_url),$("#ver_new_content").val(a.ver_new_content),$("#mi_state").prop("checked","on"==a.mi_state),$("#mi_sign").prop("checked","on"==a.mi_sign),$("#mi_time").val(a.mi_time),$("#mi_rc4").prop("checked","rc4"==a.mi_type),$("#mi_aes").prop("checked","aes"==a.mi_type),$("#mi_rsa").prop("checked","rsa"==a.mi_type);var t=JSON.parse(a.mi_key);t&&!isEmpty(t.rc4)?$("#mi_rc4_key").val(t.rc4):$("#mi_rc4_key").val(""),t&&t.aes&&!isEmpty(t.aes.key)?($("#mi_aes_key").val(t.aes.key),$("#mi_aes_iv").val(t.aes.iv)):($("#mi_aes_key").val(""),$("#mi_aes_iv").val("")),t&&t.rsa&&!isEmpty(t.rsa.public)?($("#mi_public_key").val(t.rsa.public),$("#mi_private_key").val(t.rsa.private)):($("#mi_public_key").val(""),$("#mi_private_key").val("")),$("#mi_state_fieldset").attr("hidden","off"==a.mi_state),$("#ver_state").prop("checked","on"==a.ver_state),$("#ver_off_msg").val(a.ver_off_msg),$("#ver_off_msg_div").attr("hidden","on"==a.ver_state),checked_mi(a.mi_type),$("#modal_ver").modal("show")}function edit_switch(e,i){$("#"+i+"_"+e).attr("disabled",!0);var a={act:"edit_state"};return a.id=e,a[i]=$("#"+i+"_"+e).prop("checked")?"on":"off",$.ajax({cache:!1,type:"POST",data:a,dataType:"json",success:function(a){$("#"+i+"_"+e).attr("disabled",!1),200==a.code?cocoMessage.success("编辑成功",2e3):(cocoMessage.error(a.msg,2e3),$("#"+i+"_"+e).prop("checked")?$("#"+i+"_"+e).prop("checked",!1):$("#"+i+"_"+e).prop("checked",!0))},error:function(a,t,s){$("#"+i+"_"+e).attr("disabled",!1),cocoMessage.error(t,2e3),$("#"+i+"_"+e).prop("checked")?$("#"+i+"_"+e).prop("checked",!1):$("#"+i+"_"+e).prop("checked",!0)}}),!1}function checked_state(e,i){$("#"+e).attr("hidden",i)}function modal_show(){$("#modal_edit_ver_state").attr("hidden",!0),$("#ver_id").val(""),$("#modal_title").html("添加版本"),$("#ver_val").val(""),$("#ver_name").val(""),$("#ver_key").val(""),$("#ver_new_url").val(""),$("#ver_new_content").val(""),$("#mi_state").prop("checked",!1),$("#mi_sign").prop("checked",!1),$("#mi_time").val(""),$("#mi_rc4").prop("checked",!0),$("#mi_aes").prop("checked",!1),$("#mi_rsa").prop("checked",!1),$("#mi_key").val(""),$("#mi_public_key").val(""),$("#mi_private_key").val(""),$("#out_type_json").prop("checked",!0),$("#out_type_xml").prop("checked",!1),$("#mi_state_fieldset").attr("hidden",!0),$("#modal_ver").modal("show")}function del_list(e,i){$("#del_name").html(i),$("#del_id").val(e),$("#del_ver").modal("show")}function checked_mi(e){"rsa"==e?($("#mi_rsa_div").attr("hidden",!1),$("#mi_aes_div").attr("hidden",!0),$("#mi_rc4_div").attr("hidden",!0)):"aes"==e?($("#mi_aes_div").attr("hidden",!1),$("#mi_rc4_div").attr("hidden",!0),$("#mi_rsa_div").attr("hidden",!0)):($("#mi_aes_div").attr("hidden",!0),$("#mi_rsa_div").attr("hidden",!0),$("#mi_rc4_div").attr("hidden",!1))}function sy_copy(e){const i=document.createElement("input");i.setAttribute("value",e),document.body.appendChild(i),i.select(),document.execCommand("copy"),document.body.removeChild(i),cocoMessage.success("复制成功",2e3)}$("#submit").click(function(){var e={};return e.act="编辑版本"==$("#modal_title").html()?"edit":"add","edit"==e.act&&(e.id=$("#ver_id").val(),e.ver_state=$("#ver_state").prop("checked")?"on":"off",e.ver_off_msg=$("#ver_off_msg").val()),e.ver_val=$("#ver_val").val(),e.ver_name=$("#ver_name").val(),e.ver_key=$("#ver_key").val(),e.ver_new_url=$("#ver_new_url").val(),e.ver_new_content=$("#ver_new_content").val(),e.mi_state=$("#mi_state").prop("checked")?"on":"off",e.mi_sign=$("#mi_sign").prop("checked")?"on":"off",e.mi_type=$("#mi_rsa").prop("checked")?"rsa":$("#mi_aes").prop("checked")?"aes":"rc4",e.mi_time=$("#mi_time").val(),e.mi_rc4_key=$("#mi_rc4_key").val(),e.mi_aes_key=$("#mi_aes_key").val(),e.mi_aes_iv=$("#mi_aes_iv").val(),e.mi_public_key=$("#mi_public_key").val(),e.mi_private_key=$("#mi_private_key").val(),$("#submit").html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>正在提交'),$("#submit").attr("disabled",!0),$.ajax({cache:!1,type:"POST",data:e,dataType:"json",success:function(i){$("#submit").html("提交"),$("#submit").attr("disabled",!1),200==i.code?("edit"==e.act?(cocoMessage.success("更新成功",2e3),add_list(i.data.list)):(cocoMessage.success("添加成功",2e3),add_list(i.data.list)),$("#modal_ver").modal("hide")):cocoMessage.error(i.msg,2e3)},error:function(e,i,a){cocoMessage.error("系统发生错误，请打开浏览器调试模式查看具体原因",2e3),$("#submit").html("提交"),$("#submit").attr("disabled",!1)}}),!1}),$("#del_submit").click(function(){var e=$("#del_id").val();return $("#del_submit").html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>正在删除'),$("#del_submit").attr("disabled",!0),$.ajax({cache:!1,type:"POST",data:{act:"del",id:e},dataType:"json",success:function(i){if($("#del_submit").html("确认删除"),$("#del_submit").attr("disabled",!1),200==i.code)if($("#ver_"+e).remove(),cocoMessage.success(i.msg,2e3),$("#del_ver").modal("hide"),$("#ver_list").children().length>=13)$("#add_ver").attr("hidden",!0);else $("#add_ver").attr("hidden",!1);else cocoMessage.error(i.msg,2e3)}}),!1});
	</script>	
<?php include_once 'footer.php';?>