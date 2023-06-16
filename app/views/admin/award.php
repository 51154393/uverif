<?php include_once 'header.php';?>

	<div class="row">
		<div class="col-md-12">
			<div class="card-deck">
				<div class="card shadow mb-2">
					<div class="card-header">
						<strong class="card-title">
							<div>
								<label class="mb-0">注册奖励</label>
							</div>
						</strong>
					</div>
					<div class="card-body">
						<form>
							<div class="row">
								<div class="col-6">
									<div class="form-group">
										<label>奖励类型</label>
										<select id="reg_award" class="form-control" onchange="select_award(this.id,this.value)">
											<option value="vip" <?echo $this->app['reg_award']=='vip'?'selected':'';?>>会员</option>
											<option value="fen" <?echo $this->app['reg_award']=='fen'?'selected':'';?>>积分</option>
										</select>
									</div>
								</div>
								<div class="col-6">
									<div class="form-group">
										<label>奖励数值</label>
										<div class="input-group">
											<input type="number" class="form-control" placeholder="用户注册后获得该奖励数" id="reg_award_val" value="<?=$this->app['reg_award_val'];?>">
											<div class="input-group-append">
												<span class="input-group-text" id="reg_award_fen_span" <?echo $this->app['reg_award']=='vip'?'hidden':'';?>>积分</span>
												<select class="form-control" id="reg_award_vip_span"<?echo $this->app['reg_award']=='fen'?'hidden':'';?>>
													<option value="s">秒</option>
													<option value="i">分</option>
													<option value="h">时</option>
													<option value="d">天</option>
												</select>
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
					
					<div class="card-header mt-1">
						<strong class="card-title">
							<div>
								<label class="mb-0">邀请奖励</label>
							</div>
						</strong>
					</div>
					<div class="card-body">
						<form>
							<div class="row">
								<div class="col-6">
									<div class="form-group">
										<label>受邀者奖励类型</label>
										<select id="invitee_award" class="form-control" onchange="select_award(this.id,this.value)">
											<option value="vip" <?echo $this->app['invitee_award']=='vip'?'selected':'';?>>会员</option>
											<option value="fen" <?echo $this->app['invitee_award']=='fen'?'selected':'';?>>积分</option>
										</select>
									</div>
								</div>
								<div class="col-6">
									<div class="form-group">
										<label>受邀者奖励数量</label>
										<div class="input-group">
											<input type="number" class="form-control" placeholder="被邀请注册的用户获得该奖励数。0则不奖励" id="invitee_award_val" value="<?echo $this->app['invitee_award_val'];?>">
											<div class="input-group-append">
												<span class="input-group-text" id="invitee_award_fen_span" <?echo $this->app['invitee_award']=='vip'?'hidden':'';?>>积分</span>
												<select class="form-control" id="invitee_award_vip_span" <?echo $this->app['invitee_award']=='fen'?'hidden':'';?>>
													<option value="s">秒</option>
													<option value="i">分</option>
													<option value="h">时</option>
													<option value="d">天</option>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="col-6">
									<div class="form-group">
										<label>邀请者奖励类型</label>
										<select id="inviter_award" class="form-control" onchange="select_award(this.id,this.value)">
											<option value="vip" <?echo $this->app['inviter_award']=='vip'?'selected':'';?>>会员</option>
											<option value="fen" <?echo $this->app['inviter_award']=='fen'?'selected':'';?>>积分</option>
										</select>
									</div>
								</div>
								<div class="col-6">
									<div class="form-group">
										<label>邀请者奖励数量</label>
										<div class="input-group">
											<input type="number" class="form-control" placeholder="发起邀请注册的用户获得该奖励数。0则不奖励" id="inviter_award_val" value="<?echo $this->app['inviter_award_val'];?>">
											<div class="input-group-append">
												<span class="input-group-text" id="inviter_award_fen_span" <?echo $this->app['inviter_award']=='vip'?'hidden':'';?>>积分</span>
												<select class="form-control" id="inviter_award_vip_span" <?echo $this->app['inviter_award']=='fen'?'hidden':'';?>>
													<option value="s">秒</option>
													<option value="i">分</option>
													<option value="h">时</option>
													<option value="d">天</option>
												</select>
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
					<div class="card-header mt-1">
						<strong class="card-title">
							<div>
								<label class="mb-0">签到奖励</label>
							</div>
						</strong>
					</div>
					<div class="card-body">
						<form>
							<div class="row">
								<div class="col-6">
									<div class="form-group">
										<label>奖励类型</label>
										<select id="diary_award" class="form-control" onchange="select_award(this.id,this.value)">
											<option value="vip" <?echo $this->app['diary_award']=='vip'?'selected':'';?>>会员</option>
											<option value="fen" <?echo $this->app['diary_award']=='fen'?'selected':'';?>>积分</option>
										</select>
									</div>
								</div>
								<div class="col-6">
									<div class="form-group">
										<label>奖励数量</label>
										<div class="input-group">
											<input type="number" class="form-control" placeholder="签到成功的用户获得该奖励数。0则不奖励" id="diary_award_val" value="<?echo $this->app['diary_award_val'];?>">
											<div class="input-group-append">
												<span class="input-group-text" id="diary_award_fen_span" <?echo $this->app['diary_award']=='vip'?'hidden':'';?>>积分</span>
												<select class="form-control" id="diary_award_vip_span" <?echo $this->app['diary_award']=='fen'?'hidden':'';?>>
													<option value="s">秒</option>
													<option value="i">分</option>
													<option value="h">时</option>
													<option value="d">天</option>
												</select>
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
		function init(a,i={}){if("vip"==$("#reg_award").val()){var r=$("#reg_award_val").val();$("#reg_award_vip_span").val(sTotime(r,!1)),$("#reg_award_val").val(sTotime(r,!0))}if("vip"==$("#invitee_award").val()){var v=$("#invitee_award_val").val();$("#invitee_award_vip_span").val(sTotime(v,!1)),$("#invitee_award_val").val(sTotime(v,!0))}if("vip"==$("#inviter_award").val()){var e=$("#inviter_award_val").val();$("#inviter_award_vip_span").val(sTotime(e,!1)),$("#inviter_award_val").val(sTotime(e,!0))}if("vip"==$("#diary_award").val()){var _=$("#diary_award_val").val();$("#diary_award_vip_span").val(sTotime(_,!1)),$("#diary_award_val").val(sTotime(_,!0))}loading(!1)}function select_award(a,i){"vip"==i?($("#"+a+"_fen_span").attr("hidden",!0),$("#"+a+"_vip_span").attr("hidden",!1)):($("#"+a+"_fen_span").attr("hidden",!1),$("#"+a+"_vip_span").attr("hidden",!0))}$("#submit").click(function(){var a={};return a.reg_award=$("#reg_award").val(),a.reg_award_val="vip"==a.reg_award?timeTos($("#reg_award_val").val(),$("#reg_award_vip_span").val()):$("#reg_award_val").val(),a.invitee_award=$("#invitee_award").val(),a.invitee_award_val="vip"==a.invitee_award?timeTos($("#invitee_award_val").val(),$("#invitee_award_vip_span").val()):$("#invitee_award_val").val(),a.inviter_award=$("#inviter_award").val(),a.inviter_award_val="vip"==a.inviter_award?timeTos($("#inviter_award_val").val(),$("#inviter_award_vip_span").val()):$("#inviter_award_val").val(),a.diary_award=$("#diary_award").val(),a.diary_award_val="vip"==a.diary_award?timeTos($("#diary_award_val").val(),$("#diary_award_vip_span").val()):$("#diary_award_val").val(),$("#submit").html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>正在提交'),$("#submit").attr("disabled",!0),$.ajax({cache:!1,type:"POST",data:a,dataType:"json",success:function(a){$("#submit").html("提交"),$("#submit").attr("disabled",!1),200==a.code?cocoMessage.success("更新成功",2e3):cocoMessage.error(a.msg,2e3)},error:function(a,i,r){cocoMessage.error("系统发生错误，请打开浏览器调试模式查看具体原因",2e3),$("#submit").html("提交"),$("#submit").attr("disabled",!1)}}),!1});
	</script>	
<?php include_once 'footer.php';?>