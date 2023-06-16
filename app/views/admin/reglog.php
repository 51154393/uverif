<?php include_once 'header.php';?>

	<div class="row">
		<div class="col-md-12">
			<div class="card-deck">
				<div class="card shadow mb-2">
					<div class="card-header">
						<strong class="card-title">
							<div>
								<label class="mb-0">注册控制</label>
								<div class="float-right">
									<div class="custom-switch">
										<input type="checkbox" class="custom-control-input" id="reg_state" onclick="checked_state(this.id);" <?echo $this->app['reg_state']=='on'?'checked':'';?>>
										<label class="custom-control-label" for="reg_state"></label>
									</div>
								</div>
							</div>
						</strong>
					</div>
					<div class="card-body">
						<form id="reg_state_on_form" <?echo $this->app['reg_state']=='off'?'hidden':'';?>>
							<div class="form-group">
								<label>注册方式</label>
								<select id="reg_way" class="form-control">
									<option value="phone" <?echo $this->app['reg_way']=='phone'?'selected':'';?>>手机号</option>
									<option value="email" <?echo $this->app['reg_way']=='email'?'selected':'';?>>邮箱</option>
									<option value="wordnum" <?echo $this->app['reg_way']=='wordnum'?'selected':'';?>>自定义账号</option>
								</select>
								<div class="alert alert-warning alert-dismissible fade show mt-2" role="alert">
									<strong>小提示：</strong> 注册方式为手机号或邮箱时，需在发信控制中进行配置后用户才可以正常注册<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
								</div>
							</div>
							<div class="row">
								<div class="col-6">
									<div class="form-group">
										<label>机器码注册间隔<span class="fe fe-help-circle ml-1" data-toggle="tooltip" title="该参数可防止用户恶意注册，如设置为24小时间隔，那么每台设备24小时内只能注册一个账号，可配合IP注册间隔效果更佳"></span></label>
										<div class="input-group">
											<input type="number" class="form-control" placeholder="建议值为：24" id="reg_time_mc" value="<?=$this->app['reg_time_mc']?>">
											<div class="input-group-append">
												<span class="input-group-text">小时</span>
											</div>
										</div>
									</div>
								</div>
								<div class="col-6">
									<div class="form-group">
										<label>IP注册间隔<span class="fe fe-help-circle ml-1" data-toggle="tooltip" title="该参数可防止用户恶意注册，如设置为24小时间隔，那么每个IP24小时内只能注册一个账号，可配合机器码注册间隔效果更佳"></span></label>
										<div class="input-group">
											<input type="number" class="form-control" placeholder="建议值为：24" id="reg_time_ip" value="<?=$this->app['reg_time_ip']?>">
											<div class="input-group-append">
												<span class="input-group-text">小时</span>
											</div>
										</div>
									</div>
								</div>
							</div>	
							
						</form>
						<form id="reg_state_off_form" <?echo $this->app['reg_state']=='on'?'hidden':'';?>>
							
							<div class="form-group">
								<label>关闭注册提示</label>
								<textarea class="form-control" id="reg_off_msg" rows="4" placeholder="关闭注册提示内容，如：软件维护中，暂时关闭注册功能"><?echo $this->app['reg_off_msg'];?></textarea>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="card-deck">
				<div class="card shadow mb-2">
					
					<div class="card-header mt-1">
						<strong class="card-title">
							<div>
								<label class="mb-0">登录控制</label>
								<div class="float-right">
									<div class="custom-switch">
										<input type="checkbox" class="custom-control-input" id="logon_state" onclick="checked_state(this.id);" <?echo $this->app['logon_state']=='on'?'checked':'';?>>
										<label class="custom-control-label" for="logon_state"></label>
									</div>
								</div>
							</div>
						</strong>
					</div>
					<div class="card-body">
						<form id="logon_state_on_form" <?echo $this->app['logon_state']=='off'?'hidden':'';?>>
							<div class="row">
								<div class="col-12 col-lg-6">
									<div class="form-group">
										<label>登录设备数<span class="fe fe-help-circle ml-1" data-toggle="tooltip" title="如果一个账号只允许同时在一台设备中使用，可设置为：1。若设置其他数值，则一个账号可同时登录多个设备使用。超过数值则无法登录，但可以通过退出登录或换绑登录进行更换登录设备"></span></label>
										<div class="input-group">
											<input type="number" class="form-control" placeholder="建议设置为：2" id="logon_mc_num" value="<?=$this->app['logon_mc_num']?>">
											<div class="input-group-append">
												<span class="input-group-text">台</span>
											</div>
										</div>
									</div>
								</div>
								<div class="col-12 col-lg-6">
									<div class="form-group">
										<label>解绑扣费<span class="fe fe-help-circle ml-1" data-toggle="tooltip" title="用户换绑时将扣除设置费用，若为0则不扣"></span></label>
										<div class="input-group">
											<div class="input-group-prepend">
												<select class="form-control" id="logon_mc_unbdeType" onchange="select_type(this.value)">
													<option value="vip" <?echo $this->app['logon_mc_unbdeType']=='vip'?'selected':'';?>>会员</option>
													<option value="fen" <?echo $this->app['logon_mc_unbdeType']=='fen'?'selected':'';?>>积分</option>
												</select>
											</div>
											<input type="number" class="form-control" placeholder="建议设置为：2" id="logon_mc_unbdeVal" value="<?=$this->app['logon_mc_unbdeVal']?>">
											<div class="input-group-append">
												<span class="input-group-text" id="logon_mc_unbdeType_fen_span" <?echo $this->app['logon_mc_unbdeType']=='vip'?'hidden':'';?>>积分</span>
												<select class="form-control" id="logon_mc_unbdeType_vip_span" <?echo $this->app['logon_mc_unbdeType']=='fen'?'hidden':'';?>>
													<option value="s">秒</option>
													<option value="i" selected>分</option>
													<option value="h">时</option>
													<option value="d">天</option>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>	
						</form>
						<form id="logon_state_off_form" <?echo $this->app['logon_state']=='on'?'hidden':'';?>>
							<div class="form-group">
								<label>关闭登录提示</label>
								<textarea class="form-control" id="logon_off_msg" rows="4" placeholder="关闭登录提示内容，如：软件维护中，暂时关闭登录功能"><?=$this->app['logon_off_msg']?></textarea>
							</div>
						</form>
					</div>
				</div>
			</div>
			
			<div class="card-deck">
				<div class="card shadow mb-4">	
					<div class="card-body">
						<form>
							<button type="submit" class="btn btn-primary btn-block" id="submit">提交</button>
						</form>
					</div>
				</div>	
			</div>
			
		</div>
	</div>
		
	<script>
		function checked_state(e){$("#"+e+"_on_form").attr("hidden")?($("#"+e+"_off_form").attr("hidden",!0),$("#"+e+"_on_form").attr("hidden",!1)):($("#"+e+"_on_form").attr("hidden",!0),$("#"+e+"_off_form").attr("hidden",!1))}function select_type(e){"vip"==e?($("#logon_mc_unbdeType_fen_span").attr("hidden",!0),$("#logon_mc_unbdeType_vip_span").attr("hidden",!1)):($("#logon_mc_unbdeType_fen_span").attr("hidden",!1),$("#logon_mc_unbdeType_vip_span").attr("hidden",!0))}function init(e,o={}){if("vip"==$("#logon_mc_unbdeType").val()){var _=$("#logon_mc_unbdeVal").val();$("#logon_mc_unbdeType_vip_span").val(sTotime(_,!1)),$("#logon_mc_unbdeVal").val(sTotime(_,!0))}loading(!1)}$("#submit").click(function(){var e={};return e.reg_state=$("#reg_state").prop("checked")?"on":"off",e.logon_state=$("#logon_state").prop("checked")?"on":"off",e.reg_way=$("#reg_way").val(),e.reg_time_mc=$("#reg_time_mc").val(),e.reg_time_ip=$("#reg_time_ip").val(),e.reg_off_msg=$("#reg_off_msg").val(),e.logon_mc_num=$("#logon_mc_num").val(),e.logon_mc_unbdeType=$("#logon_mc_unbdeType").val(),e.logon_mc_unbdeVal="vip"==e.logon_mc_unbdeType?timeTos($("#logon_mc_unbdeVal").val(),$("#logon_mc_unbdeType_vip_span").val()):$("#logon_mc_unbdeVal").val(),e.logon_off_msg=$("#logon_off_msg").val(),$("#submit").html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>正在提交'),$("#submit").attr("disabled",!0),$.ajax({cache:!1,type:"POST",data:e,dataType:"json",success:function(e){$("#submit").html("提交"),$("#submit").attr("disabled",!1),200==e.code?cocoMessage.success("更新成功",2e3):cocoMessage.error(e.msg,2e3)},error:function(e,o,_){cocoMessage.error("系统发生错误，请打开浏览器调试模式查看具体原因",2e3),$("#submit").html("提交"),$("#submit").attr("disabled",!1)}}),!1});
	</script>	
<?php include_once 'footer.php';?>