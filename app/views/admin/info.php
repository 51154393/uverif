<?php include_once 'header.php';?>
	<div class="row">
		<div class="col-md-12">
			<div class="card-deck">
				<div class="card shadow mb-4">
					<div class="card-header">
						<strong class="card-title">
							APPID:<?echo $this->app['id'];?>
						</strong>
					</div>
					<div class="card-body">
						<form>
							<div class="form-row">
								<div class="form-group col-md-6">
									<label for="inputEmail4">
										应用图标
									</label>
									<div class="avatar avatar-lg">
										<a href="javascript:upload_logo();">
											<img id="img_applogo" src="/<?echo !empty($this->app['app_logo'])&&file_exists($this->app['app_logo'])?$this->app['app_logo']:'assets/images/add.png' ;?>" class="avatar-img app-logo-add">
										</a>
										<input style="display: none" id="app_logo" type="file" onchange="showlogo(this);" accept="image/*" multiple />
									</div>
								</div>
							</div>
							<div class="form-group">
								<label>应用名称</label>
								<input type="text" class="form-control" id="app_name" placeholder="应用名称" value="<?echo $this->app['app_name'];?>">
							</div>
							<div class="form-group">
								<label>APPKEY</label>
								<div class="input-group">
									<input type="text" class="form-control" id="app_key" placeholder="应用密钥" value="<?echo $this->app['app_key'];?>" readonly>
									<div class="input-group-append">
										<button class="btn btn-light" type="button" id="alter_appkey">更换</button>
										<button class="btn btn-primary" type="button" id="copy_appkey">复制</button>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label>运营模式</label>
								<select id="app_mode" class="form-control">
									<option value="y" <?echo $this->app['app_mode']=='y'?'selected':'';?>>收费</option>
									<option value="n" <?echo $this->app['app_mode']=='n'?'selected':'';?>>免费</option>
								</select>
							</div>
							
							<div class="form-group">
								<label>应用状态</label>
								<select id="app_state" class="form-control" onchange="select_app_state(this.value)">
									<option value="on" <?echo $this->app['app_state']=='on'?'selected':'';?>>正常</option>
									<option value="off" <?echo $this->app['app_state']=='off'?'selected':'';?>>关闭</option>
								</select>
							</div>
							
							<div class="form-group" id="div_app_off_msg" <?echo $this->app['app_state']=='on'?'hidden':'';?>>
								<label>关闭通知</label>
								<textarea class="form-control" id="app_off_msg" rows="4" placeholder="应用关闭后的公告"><?echo $this->app['app_off_msg'];?></textarea>
							</div>
							
							<button type="submit" class="btn btn-primary btn-block" id="submit">提交</button>
						</form>
					</div>
				</div>
			</div>
			
		</div>
	</div>
	<script>
		function select_app_state(a){"on"==a?$("#div_app_off_msg").attr("hidden",!0):$("#div_app_off_msg").attr("hidden",!1)}function upload_logo(){$("#app_logo").click()}function showlogo(a){var e=a.files[0];if(!e)return $("#img_applogo").attr("src","/assets/images/add.png"),$("#img_applogo").removeClass("show"),!1;if(e.size>1048576)return cocoMessage.warning("应用LOGO图片大小不可超过1MB",2e3),!1;var p=new FileReader;p.onload=function(a){console.log(a),$("#img_applogo").attr("src",a.target.result),$("#img_applogo").addClass("show")},p.readAsDataURL(e)}$("#submit").click(function(){var a=new FormData,e=$("#app_logo").prop("files");return e.length>0&&a.append("app_logo",e[0]),a.append("app_name",$("#app_name").val()),a.append("app_key",$("#app_key").val()),a.append("app_mode",$("#app_mode").val()),a.append("app_state",$("#app_state").val()),a.append("app_off_msg",$("#app_off_msg").val()),$("#submit").html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>正在提交'),$("#submit").attr("disabled",!0),$.ajax({cache:!1,type:"POST",data:a,dataType:"json",processData:!1,contentType:!1,success:function(a){$("#submit").html("提交"),$("#submit").attr("disabled",!1),200==a.code?($("#top_app_logo").attr("src",$("#img_applogo").attr("src")),$("#top_app_name").html($("#app_name").val()),cocoMessage.success("更新成功",2e3)):cocoMessage.error(a.msg,2e3)}}),!1}),$("#copy_appkey").click(function(){$("#app_key").select(),document.execCommand("copy"),cocoMessage.success("复制成功",2e3)}),$("#alter_appkey").click(function(){var a=randomString(32);$("#app_key").val(a)});
	</script>	
<?php include_once 'footer.php';?>