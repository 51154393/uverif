<?php include_once 'header.php';?>

	<div class="row">
		<div class="col-md-12">
			<div class="card-deck">
				<div class="card shadow mb-2">
					<div class="card-header">
						<strong class="card-title">
							<div>
								<label class="mb-0">验证码控制</label>
							</div>
						</strong>
					</div>
					
					<div class="card-body">
						<form>	
							<div class="row">
								<div class="col-6">
									<div class="form-group">
										<label>验证码长度</label>
										<input type="number" class="form-control" placeholder="发信邮箱密码" id="vc_length" value="<?echo $this->app['vc_length'];?>">
									</div>
								</div>
								<div class="col-6">
									<div class="form-group">
										<label>验证码有效时间</label>
										<div class="input-group">
											<input type="number" class="form-control" placeholder="发信邮箱账号" id="vc_time" value="<?echo $this->app['vc_time'];?>">
											<div class="input-group-append">
												<span class="input-group-text">分钟</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
					
				</div>
			</div>
			<div class="card-deck">
				<div class="card shadow mb-2">
					<div class="card-header">
						<strong class="card-title">
							<div>
								<label class="mb-0">邮箱控制</label>
								<div class="float-right">
									<div class="custom-switch">
										<input type="checkbox" class="custom-control-input" id="smtp_state" onclick="checked_state(this.id);" <?echo $this->app['smtp_state']=='on'?'checked':'';?>>
										<label class="custom-control-label" for="smtp_state"></label>
									</div>
								</div>
							</div>
						</strong>
					</div>
					
					<div class="card-body" id="smtp_state_div" <?echo $this->app['smtp_state']=='off'?'hidden':'';?>>
						<form>
							<div class="row">
								<div class="col-6">
									<div class="form-group">
										<label>邮箱服务器</label>
										<input type="text" class="form-control" placeholder="发信服务器，如腾讯企业邮箱：smtp.exmail.qq.com" id="smtp_host" value="<?echo $this->app['smtp_host'];?>">
									</div>
								</div>
								<div class="col-6">
									<div class="form-group">
										<label>邮箱发信端口</label>
										<input type="number" class="form-control" placeholder="一般发信端口为：25/465" id="smtp_port" value="<?echo $this->app['smtp_port'];?>">
									</div>
								</div>
							</div>	
							<div class="row">
								<div class="col-6">
									<div class="form-group">
										<label>邮箱账户</label>
										<input type="text" class="form-control" placeholder="发信邮箱账号" id="smtp_user" value="<?echo $this->app['smtp_user'];?>">
									</div>
								</div>
								<div class="col-6">
									<div class="form-group">
										<label>邮箱密码</label>
										<input type="text" class="form-control" placeholder="发信邮箱密码" id="smtp_pass" value="<?echo $this->app['smtp_pass'];?>">
									</div>
								</div>
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
								<label class="mb-0">短信控制</label>
								<div class="float-right">
									<div class="custom-switch">
										<input type="checkbox" class="custom-control-input" id="sms_state" onclick="checked_state(this.id);" <?echo $this->app['sms_state']=='on'?'checked':'';?>>
										<label class="custom-control-label" for="sms_state"></label>
									</div>
								</div>
							</div>
						</strong>
					</div>
					
					<div class="card-body" id="sms_state_div" <?echo $this->app['sms_state']=='off'?'hidden':'';?>>
						<form>
							<div class="form-group">
								<label>短信类型</label>
								<select id="sms_type" class="form-control" onchange="select_plug(this.value,'<?echo htmlspecialchars($this->app['sms_config']);?>');">
									<? foreach($this->smsPlug as $plug){ ?>
									<option value="<?echo $plug['id'];?>" <?echo $this->app['sms_type']==$plug['id']?'selected':'';?>><?echo $plug['name'];?></option>
									<?} ?>
								</select>
							</div>
							<div class="row">
								<div class="col-12" id="sms_form_element">
									
								</div>
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
		function init(e,t={}){select_plug('<?=$this->app['sms_type']?>','<?=$this->app['sms_config']?>'),loading(!1)}function select_plug(e,t){var s,r=<?=json_encode($this->smsPlug)?>;isEmpty(t)||(s=JSON.parse(t)),isEmpty(e)&&(e=type),$("#sms_form_element").empty();for(var a=0;a<r.length;a++)if(r[a].id==e)for(key in r[a].form){t="<label>"+r[a].form[key].name+"</label>";"input"==r[a].form[key].type?t+='<input type="text" class="form-control" placeholder="'+r[a].form[key].placeholder+'" id="sms_'+key+'" value="'+(isEmpty(s)||isEmpty(s[key])?"":s[key])+'"></div>':t+='<textarea rows="4" class="form-control" placeholder="'+r[a].form[key].placeholder+'" id="sms_'+key+'">'+(isEmpty(s)||isEmpty(s[key])?"":s[key])+"</textarea>",$("#sms_form_element").append('<div class="form-group">'+t+"</div>")}}function checked_state(e){$("#"+e+"_div").attr("hidden")?$("#"+e+"_div").attr("hidden",!1):$("#"+e+"_div").attr("hidden",!0)}$("#submit").click(function(){var e={};e.vc_time=$("#vc_time").val(),e.vc_length=$("#vc_length").val(),e.smtp_state=$("#smtp_state").prop("checked")?"on":"off",e.sms_state=$("#sms_state").prop("checked")?"on":"off",e.smtp_host=$("#smtp_host").val(),e.smtp_port=$("#smtp_port").val(),e.smtp_user=$("#smtp_user").val(),e.smtp_pass=$("#smtp_pass").val();for(var t=$("#sms_form_element .form-control"),s={},r=0;r<t.length;r++)s[t[r].id.substr(4)]=$("#"+t[r].id).val();return e.sms_config=JSON.stringify(s),$("#submit").html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>正在提交'),$("#submit").attr("disabled",!0),$.ajax({cache:!1,type:"POST",data:e,dataType:"json",success:function(e){$("#submit").html("提交"),$("#submit").attr("disabled",!1),200==e.code?cocoMessage.success("更新成功",2e3):cocoMessage.error(e.msg,2e3)},error:function(e,t,s){cocoMessage.error("系统发生错误，请打开浏览器调试模式查看具体原因",2e3),$("#submit").html("提交"),$("#submit").attr("disabled",!1)}}),!1});
	</script>	
<?php include_once 'footer.php';?>
