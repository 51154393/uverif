<?php include_once 'header.php';?>

	<div class="row">
		<div class="col-md-12">
			
			<div class="card-deck">
				<div class="card shadow mb-2">
					<div class="card-header">
						<strong class="card-title">
							<div>
								<label class="mb-0"><i class="ali-icon mr-2"></i>支付宝控制</label>
								<div class="float-right">
									<div class="custom-switch">
										<input type="checkbox" class="custom-control-input" id="pay_ali_state" onchange="checked_state(this.id);" <?echo $this->app['pay_ali_state']=='on'?'checked':'';?>>
										<label class="custom-control-label" for="pay_ali_state"></label>
									</div>
								</div>
							</div>
						</strong>
					</div>
					
					<div class="card-body" id="pay_ali_state_div" <?echo $this->app['pay_ali_state']=='off'?'hidden':'';?>>
						<form>
							<div class="row">
								<div class="col-12">
									<div class="form-group">
										<label for="message-text" class="col-form-label">
											支付引擎
										</label>
										<select class="form-control"  id="pay_ali_type" onchange="select_plug('ali',this.value,'<?echo htmlspecialchars($this->app['pay_ali_config']);?>')">
											<? foreach($this->plug as $plug){if($plug['type'] == 'wx'){continue;}?>
											<option value="<?echo $plug['id'];?>" <?echo $this->app['pay_ali_type']==$plug['id']?'selected':'';?>><?echo $plug['name'];?></option>
											<?} ?>
										</select>
									</div>
								</div>
								<div class="col-12" id="ali_form_element">
									
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
								<label class="mb-0"><i class="wx-icon mr-2"></i>微信支付控制</label>
								<div class="float-right">
									<div class="custom-switch">
										<input type="checkbox" class="custom-control-input" id="pay_wx_state" onchange="checked_state(this.id);" <?echo $this->app['pay_wx_state']=='on'?'checked':'';?>>
										<label class="custom-control-label" for="pay_wx_state"></label>
									</div>
								</div>
							</div>
						</strong>
					</div>
					
					<div class="card-body" id="pay_wx_state_div" <?echo $this->app['pay_wx_state']=='off'?'hidden':'';?>>
						<form>
							<div class="row">
								<div class="col-12">
									<div class="form-group">
										<label for="message-text" class="col-form-label">
											支付引擎
										</label>
										<select class="form-control" id="pay_wx_type" onchange="select_plug('wx',this.value,'<?echo htmlspecialchars($this->app['pay_wx_config']);?>')">
											<? foreach($this->plug as $plug){if($plug['type'] == 'ali'){continue;}?>
											<option value="<?echo $plug['id'];?>" <?echo $this->app['pay_wx_type']==$plug['id']?'selected':'';?>><?echo $plug['name'];?></option>
											<?} ?>
										</select>
									</div>
								</div>
								<div class="col-12" id="wx_form_element">
									
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
		function init(e,t={}){select_plug("ali",$("#pay_ali_type").val(),'<?=$this->app['pay_ali_config']?>'),select_plug("wx",$("#pay_wx_type").val(),'<?=$this->app['pay_wx_config']?>'),loading(!1)}function select_plug(e,t,a){var i,r=<?=json_encode($this->plug)?>;isEmpty(a)||(i=JSON.parse(a)),isEmpty(t)&&(t=e),$("#"+e+"_form_element").empty();for(var o=0;o<r.length;o++)if(r[o].id==t)for(key in r[o].form){a="<label>"+r[o].form[key].name+"</label>";"input"==r[o].form[key].type?a+='<input type="text" class="form-control" placeholder="'+r[o].form[key].placeholder+'" id="'+e+"_"+key+'" value="'+(isEmpty(i)||isEmpty(i[key])?"":i[key])+'"></div>':a+='<textarea rows="4" class="form-control" placeholder="'+r[o].form[key].placeholder+'" id="'+e+"_"+key+'">'+(isEmpty(i)||isEmpty(i[key])?"":i[key])+"</textarea>",$("#"+e+"_form_element").append('<div class="form-group">'+a+"</div>")}}function checked_state(e){$("#"+e+"_div").attr("hidden")?$("#"+e+"_div").attr("hidden",!1):$("#"+e+"_div").attr("hidden",!0)}$("#submit").click(function(){for(var e=$("#ali_form_element .form-control"),t=$("#wx_form_element .form-control"),a={},i={},r={},o=0;o<e.length;o++)r[e[o].id.substr(4)]=$("#"+e[o].id).val();a.pay_ali_config=JSON.stringify(r);for(o=0;o<t.length;o++)i[t[o].id.substr(3)]=$("#"+t[o].id).val();return a.pay_wx_config=JSON.stringify(i),a.pay_ali_state=$("#pay_ali_state").prop("checked")?"on":"off",a.pay_wx_state=$("#pay_wx_state").prop("checked")?"on":"off",a.pay_ali_type=$("#pay_ali_type").val(),a.pay_wx_type=$("#pay_wx_type").val(),$("#submit").html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>正在提交'),$("#submit").attr("disabled",!0),$.ajax({cache:!1,type:"POST",data:a,dataType:"json",success:function(e){$("#submit").html("提交"),$("#submit").attr("disabled",!1),200==e.code?cocoMessage.success("更新成功",2e3):cocoMessage.error(e.msg,2e3)},error:function(e,t,a){cocoMessage.error("系统发生错误，请打开浏览器调试模式查看具体原因",2e3),$("#submit").html("提交"),$("#submit").attr("disabled",!1)}}),!1});
	</script>	
<?php include_once 'footer.php';?>
