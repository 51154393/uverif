<?php include_once 'header.php';?>
	<div class="row">
		<div class="col-md-12">
			<div class="card shadow">
				<div class="card-body">
					<div class="toolbar">
						<form class="form">
							<div class="form-row">
								<div class="form-group col-auto mr-auto">
									<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal"  onclick="modal_show()"><i class="fe fe-plus fe-12 mr-2"></i><span class="small">添加事件</span></button>
								</div>
								
								<div class="form-group col-auto col-md-3">
									<div class="input-group so">
										<input type="text" class="form-control" placeholder="输入搜索内容" id="so">
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
										事件名称
									</th>
									<th class="wd-20">
										事件类型
									</th>
									<th class="wd-20">
										消耗积分
									</th>
									<th class="wd-20">
										会员免费
									</th>
									<th class="wd-20">
										事件数
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
	
	<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modal_title">
						添加事件
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form>
						<input type="number" class="form-control" id="modal_id" placeholder="1" hidden>
						<div class="form-group">
							<label>事件名称</label>
							<input type="text" class="form-control" id="modal_name" placeholder="如：付费点播">
						</div>
						<div class="form-group">
							<label>事件类型</label>
							<select id="modal_type" class="form-control" onchange="select_type(this.value)">
								<option value="fen">消耗积分</option>
								<option value="vip">兑换会员</option>
							</select>
						</div>
						<div class="form-group">
							<label>消耗积分</label>
							<div class="float-right" id="modal_vip_free_div">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="modal_vip_free">
									<label class="custom-control-label" for="modal_vip_free">会员免费</label>
								</div>
							</div>
							<div class="input-group">
								<input type="number" class="form-control" placeholder="10" id="modal_fen">
								<div class="input-group-append">
									<span class="input-group-text">积分</span>
								</div>
							</div>
						</div>
						
						<div class="form-group" id="modal_vip_div" hidden>
							<label>获得会员</label>
							<div class="input-group">
								<input type="number" class="form-control" placeholder="如：60" id="modal_vip">
								<div class="input-group-append">
									<select id="modal_vip_time" class="form-control">
										<option value="s">秒</option>
										<option value="i">分</option>
										<option value="h">时</option>
										<option value="d">天</option>
									</select>
								</div>
							</div>
						</div>
						<div class="form-group" id="modal_state_div" hidden>
							<label>事件状态</label>
							<div class="float-right">
								<div class="custom-switch">
									<input type="checkbox" class="custom-control-input" id="modal_state" checked>
									<label class="custom-control-label" for="modal_state"></label>
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
						<p class="mt-3">确认删除事件：<strong class="text-danger me-1" id="del_name">null</strong> ？<br>删除后不可恢复！请慎重操作</p>
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
		
		function add_list(e,t=!1){t&&$("#tbody_list").empty();for(var a=0;a<e.length;a++){var d="<td>"+e[a].id+"</td>";if(d+='<td class="text-left">'+e[a].name+"</td>",d+="<td>"+(e[a].vip>0?'<span class="badge badge-pill badge-danger">兑换会员':'<span class="badge badge-pill badge-warning">消耗积分')+"</span></td>",d+="<td>-"+e[a].fen+"</td>",d+="<td>"+("y"==e[a].vip_free?'<span class="fe fe-16 fe-check text-success">':'<span class="fe fe-16 fe-x text-danger">')+"</span></td>",d+="<td>"+(isEmpty(e[a].Fo_num)?0:e[a].Fo_num)+"</td>",d+='<td><span class="dot dot-lg bg-'+("on"==e[a].state?"success":"danger")+'"></span></td>',d+='<td><button type="button" class="btn btn-primary btn-sm mr-1" data-toggle="modal" data-target="#modal" onclick="modal_show(\''+base64_encode(JSON.stringify(e[a]))+'\');"><i class="fe fe-edit fe-12 mr-2"></i><span class="small">编辑</span></button><button type="button" class="btn btn-danger btn-sm" onclick="del_list('+e[a].id+",'"+e[a].name+'\');"><i class="fe fe-trash-2 fe-12 mr-2"></i><span class="small">删除</span></button></td>',$("#list_"+e[a].id).length>0)$("#list_"+e[a].id).html(d);else if(t)$("#tbody_list").append('<tr id="list_'+e[a].id+'">'+d+"</tr>");else $("#tbody_list").prepend('<tr id="list_'+e[a].id+'">'+d+"</tr>"),$("#tbody_list").children().length>=10&&init(initAct,initPg)}}function select_type(e){"fen"==e?($("#modal_name").attr("placeholder","如：付费点播"),$("#modal_vip_free_div").attr("hidden",!1),$("#modal_vip_div").attr("hidden",!0)):($("#modal_name").attr("placeholder","如：兑换会员"),$("#modal_vip_free_div").attr("hidden",!0),$("#modal_vip_div").attr("hidden",!1),$("#modal_vip_free").prop("checked",!1))}function del_list(e,t){$("#del_name").html(t),$("#del_id").val(e),$("#del").modal("show")}function modal_show(e=null){if(null!=e){var t=base64_decode(e),a=JSON.parse(t);$("#modal_title").html("编辑事件"),$("#modal_id").val(a.id),$("#modal_name").val(a.name),$("#modal_fen").val(a.fen),$("#modal_vip_time").val(sTotime(a.vip,!1)),$("#modal_vip").val(sTotime(a.vip,!0)),a.type=a.vip>0?"vip":"fen",$("#modal_type").val(a.type),select_type(a.type),$("#modal_state_div").attr("hidden",!1),$("#modal_state").prop("checked","on"==a.state),$("#modal_vip_free").prop("checked","y"==a.vip_free)}else $("#modal_id").val(""),$("#modal_name").val(""),$("#modal_fen").val(""),$("#modal_vip").val(""),$("#modal_title").html("添加事件"),$("#modal_type").val("fen"),select_type("fen"),$("#modal_vip_time").val("s"),$("#modal_state_div").attr("hidden",!0),$("#modal_vip_free").prop("checked",!1)}$("#submit").click(function(){var e={};return e.act="编辑事件"==$("#modal_title").html()?"edit":"add","edit"==e.act&&(e.id=$("#modal_id").val(),e.state=$("#modal_state").prop("checked")?"on":"off"),e.fen=$("#modal_fen").val(),e.vip_free=$("#modal_vip_free").prop("checked")?"y":"n",e.name=$("#modal_name").val(),e.type=$("#modal_type").val(),"vip"==e.type?e.vip=timeTos($("#modal_vip").val(),$("#modal_vip_time").val()):e.vip=0,$("#submit").html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>正在提交'),$("#submit").attr("disabled",!0),$.ajax({cache:!1,type:"POST",data:e,dataType:"json",success:function(t){$("#submit").html("提交"),$("#submit").attr("disabled",!1),200==t.code?("edit"==e.act?(cocoMessage.success("更新成功",2e3),add_list([e],!1)):(cocoMessage.success("添加成功",2e3),add_list(t.data.list,!1,!0)),$("#modal").modal("hide")):cocoMessage.error(t.msg,2e3)},error:function(e,t,a){cocoMessage.error("系统报错了，请打开浏览器调试模式查看错误原因",2e3),$("#submit").html("提交"),$("#submit").attr("disabled",!1)}}),!1}),$("#del_submit").click(function(){var e=$("#del_id").val();return $("#del_submit").html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>正在删除'),$("#del_submit").attr("disabled",!0),$.ajax({cache:!1,type:"POST",data:{act:"del",id:e},dataType:"json",success:function(t){($("#del_submit").html("确认删除"),$("#del_submit").attr("disabled",!1),200==t.code)?($("#list_"+e).remove(),cocoMessage.success(t.msg,2e3),$("#del").modal("hide"),$("#tbody_list").children().length<1&&get_pg_data(global_ac,global_pg)):cocoMessage.error(t.msg,2e3)}}),!1});
	</script>	
<?php include_once 'footer.php';?>