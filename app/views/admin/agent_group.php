<?php include_once 'header.php';?>
	<div class="row">
		<div class="col-md-12">
			
			<div class="card shadow">
				<div class="card-body">
					<div class="toolbar">
						<form class="form">
							<div class="form-row">
								<div class="form-group col-auto mr-auto">
									<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal" onclick="modal_show()"><i class="fe fe-plus fe-12 mr-2"></i><span class="small">添加代理组</span></button>
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
										组ID
									</th>
									<th class="text-left">
										代理组名称
									</th>
									<th class="wd-20">
										充值分成
									</th>
									<th class="wd-20">
										开卡折扣
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
	
	<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modal_title">
						添加代理组
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form>
						<input type="number" class="form-control" id="modal_id" placeholder="1" hidden>
						<div class="form-group">
							<label>代理组名称</label>
							<input type="text" class="form-control" id="modal_name" placeholder="如：铂金代理">
						</div>
						
						<div class="form-group">
							<label>充值分成<span class="fe fe-help-circle ml-1" data-toggle="tooltip" title="例如：代理邀请的用户充值100元，充值分成为30%，那么代理就可以获得30元"></span></label>
							<div class="input-group">
								<input type="number" class="form-control" placeholder="如：30" id="modal_pay_divide">
								<div class="input-group-append">
									<span class="input-group-text">%</span>
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<label>开卡折扣<span class="fe fe-help-circle ml-1" data-toggle="tooltip" title="例如：卡密组“月卡”为10元，那么代理在后台生成1张月卡卡密则需要7元"></span></label>
							<div class="input-group">
								<input type="number" class="form-control" placeholder="如：7" id="modal_km_discount">
								<div class="input-group-append">
									<span class="input-group-text">折</span>
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
	
	<div id="del" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-body p-4">
					<div class="text-center">
						<h4 class="mt-2">删除警告</h4>
						<p class="mt-3">确认删除代理组：<strong class="text-danger me-1" id="del_name">null</strong> ？<br>删除后不可恢复！请慎重操作</p>
						<input type="number" class="form-control" id="del_id" placeholder="1" hidden>
						<button type="button" class="btn btn-danger mr-2 my-2" id="del_submit">确认删除</button>
						<button type="button" class="btn btn-light" data-dismiss="modal">取消删除</button>
					</div>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<script>
		function init(i,n={}){return initSubmit=i,$.ajax({cache:!1,type:"POST",data:i,dataType:"json",success:function(i){initSuccess(i,n)},error:function(i,t,c){initError(t,n)}}),!1}
		
		function modal_show(t=null){if(null!=t){var d=base64_decode(t),a=JSON.parse(d);$("#modal_title").html("编辑代理组"),$("#modal_id").val(a.id),$("#modal_name").val(a.name),$("#modal_pay_divide").val(a.pay_divide),$("#modal_km_discount").val(a.km_discount)}else $("#modal_id").val(""),$("#modal_name").val(""),$("#modal_title").html("添加代理组"),$("#modal_pay_divide").val(""),$("#modal_km_discount").val("")}function add_list(t,d=!1){d&&$("#tbody_list").empty();for(var a=0;a<t.length;a++){var e="<td>"+t[a].id+"</td>";e+='<td class="text-left">'+t[a].name+"</td>",e+="<td>"+(isEmpty(t[a].pay_divide)?0:t[a].pay_divide)+" %</td>",e+="<td>"+(isEmpty(t[a].km_discount)?0:t[a].km_discount)+"折</td>",e+='<td><button type="button" class="btn btn-primary btn-sm mr-1" data-toggle="modal" data-target="#modal" onclick="modal_show(\''+base64_encode(JSON.stringify(t[a]))+'\');"><i class="fe fe-edit fe-12 mr-2"></i><span class="small">编辑</span></button><button type="button" class="btn btn-danger btn-sm" onclick="del_list('+t[a].id+",'"+t[a].name+'\');"><i class="fe fe-trash-2 fe-12 mr-2"></i><span class="small">删除</span></button></td>',$("#list_"+t[a].id).length>0?$("#list_"+t[a].id).html(e):d?$("#tbody_list").append('<tr id="list_'+t[a].id+'">'+e+"</tr>"):$("#tbody_list").prepend('<tr id="list_'+t[a].id+'">'+e+"</tr>")}}function del_list(t,d){$("#del_name").html(d),$("#del_id").val(t),$("#del").modal("show")}$("#submit").click(function(){var t={};return t.act="编辑代理组"==$("#modal_title").html()?"edit":"add","edit"==t.act&&(t.id=$("#modal_id").val()),t.name=$("#modal_name").val(),t.pay_divide=$("#modal_pay_divide").val(),t.km_discount=$("#modal_km_discount").val(),$("#submit").html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>正在提交'),$("#submit").attr("disabled",!0),$.ajax({cache:!1,type:"POST",data:t,dataType:"json",success:function(d){$("#submit").html("提交"),$("#submit").attr("disabled",!1),200==d.code?("edit"==t.act?(cocoMessage.success("更新成功",3e3),add_list(d.data.list)):(cocoMessage.success("添加成功",3e3),add_list(d.data.list)),$("#modal").modal("hide")):cocoMessage.error(d.msg,2e3)},error:function(t,d,a){cocoMessage.error("系统发生错误，请打开浏览器调试模式查看具体原因",3e3),$("#submit").html("提交"),$("#submit").attr("disabled",!1)}}),!1}),$("#del_submit").click(function(){var t=$("#del_id").val();return $("#del_submit").html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>正在删除'),$("#del_submit").attr("disabled",!0),$.ajax({cache:!1,type:"POST",data:{act:"del",id:t},dataType:"json",success:function(d){($("#del_submit").html("确认删除"),$("#del_submit").attr("disabled",!1),200==d.code)?($("#list_"+t).remove(),cocoMessage.success(d.msg,2e3),$("#del").modal("hide"),$("#tbody_list").children().length<1&&init(initSubmit)):cocoMessage.error(d.msg,2e3)},error:function(t,d,a){cocoMessage.error("系统发生错误，请打开浏览器调试模式查看具体原因",2e3),$("#del_submit").html("确认删除"),$("#del_submit").attr("disabled",!1)}}),!1});
	</script>	
<?php include_once 'footer.php';?>