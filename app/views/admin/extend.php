<?php include_once 'header.php';?>
	
	<div class="row">
		<div class="col-md-12">
			<div class="card shadow">
				<div class="card-body">
					<div class="toolbar">
						<form class="form">
							<div class="form-row">
								<div class="form-group col-auto mr-auto">
									<button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal" onclick="modal_show()"><i class="fe fe-plus fe-12 mr-2"></i><span class="small">添加扩展</span></button>
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
									<th class="wd-20">
										扩展名
									</th>
									<th class="wd-20">
										扩展键
									</th>
									<th class="text-left">
										扩展值
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
					<nav class="mb-0" id="poge_list_nav">
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
						添加扩展
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form>
						<input type="number" class="form-control" id="extend_id" placeholder="1" hidden>
						<div class="form-group row">
							<label class="col-sm-4 col-form-label">扩展名</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="extend_name" placeholder="如：客服QQ">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-4 col-form-label">扩展键</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="extend_key" placeholder="如：qq">
							</div>
						</div>
						
						<div class="form-group row">
							<label class="col-sm-4">扩展值</label>
							<div class="col-sm-8">
								<textarea class="form-control" id="extend_val" rows="4" placeholder="如：51154393"></textarea>
							</div>
						</div>
						
						<div class="form-group row">
							<label class="col-sm-4 col-form-label">是否全局</label>
							<div class="col-sm-8">
								<select id="extend_all" class="form-control">
									<option value="n">当前应用</option>
									<option value="y">全局应用</option>
								</select>
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
	
	<div id="del_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-body p-4">
					<div class="text-center">
						<h4 class="mt-2">删除警告</h4>
						<p class="mt-3">确认删除配置：<strong class="text-danger me-1" id="del_name">null</strong> ？<br>删除后不可恢复！请慎重操作</p>
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
		
		function add_list(t,e=!1){e&&$("#tbody_list").empty();for(var a=0;a<t.length;a++){var l="<td>"+t[a].id+"</td><td>"+t[a].name+"</td><td>"+t[a].var_key+'</td><td class="text-left">'+t[a].var_val+"</td>";l+='<td><button type="button" class="btn btn-primary btn-sm mr-1" data-toggle="modal" data-target="#modal" onclick="modal_show(\''+base64_encode(JSON.stringify(t[a]))+'\');"><i class="fe fe-edit fe-12 mr-2"></i><span class="small">编辑</span></button><button type="button" class="btn btn-danger btn-sm" onclick="del_list('+t[a].id+",'"+t[a].name+'\');"><i class="fe fe-trash-2 fe-12 mr-2"></i><span class="small">删除</span></button></td>',$("#list_"+t[a].id).length>0?$("#list_"+t[a].id).html(l):e?$("#tbody_list").append('<tr id="list_'+t[a].id+'">'+l+"</tr>"):$("#tbody_list").prepend('<tr id="list_'+t[a].id+'">'+l+"</tr>")}}function modal_show(t=null){if(null!=t){var e=base64_decode(t),a=JSON.parse(e);$("#modal_title").html("编辑扩展"),$("#extend_id").val(a.id),$("#extend_name").val(a.name),$("#extend_key").val(a.var_key),$("#extend_val").val(a.var_val),$("#extend_all").val(isEmpty(a.appid)?"y":"n")}else $("#extend_id").val(""),$("#extend_name").val(""),$("#extend_val").val(""),$("#extend_key").val(""),$("#extend_all").val("n"),$("#modal_title").html("添加扩展")}function del_list(t,e){$("#del_name").html(e),$("#del_id").val(t),$("#del_modal").modal("show")}$("#submit").click(function(){var t={};return t.act="编辑扩展"==$("#modal_title").html()?"edit":"add","edit"==t.act&&(t.id=$("#extend_id").val()),t.name=$("#extend_name").val(),t.var_key=$("#extend_key").val(),t.var_val=$("#extend_val").val(),t.all=$("#extend_all").val(),$("#submit").html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>正在提交'),$("#submit").attr("disabled",!0),$.ajax({cache:!1,type:"POST",data:t,dataType:"json",success:function(e){$("#submit").html("提交"),$("#submit").attr("disabled",!1),200==e.code?("edit"==t.act?(cocoMessage.success("更新成功",2e3),add_list([t])):(cocoMessage.success("添加成功",2e3),add_list(e.data.list)),$("#modal").modal("hide")):cocoMessage.error(e.msg,2e3)},error:function(t,e,a){cocoMessage.error("系统发生错误，请打开浏览器调试模式查看具体原因",2e3),$("#submit").html("提交"),$("#submit").attr("disabled",!1)}}),!1}),$("#del_submit").click(function(){var t=$("#del_id").val();return $("#del_submit").html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>正在删除'),$("#del_submit").attr("disabled",!0),$.ajax({cache:!1,type:"POST",data:{act:"del",id:t},dataType:"json",success:function(e){($("#del_submit").html("确认删除"),$("#del_submit").attr("disabled",!1),200==e.code)?($("#list_"+t).remove(),cocoMessage.success(e.msg,2e3),$("#del_modal").modal("hide"),$("#tbody_list").children().length<1&&init(initAct,initPg)):cocoMessage.error(e.msg,2e3)},error:function(t,e,a){cocoMessage.error("系统发生错误，请打开浏览器调试模式查看具体原因",2e3),$("#del_submit").html("确认删除"),$("#del_submit").attr("disabled",!1)}}),!1});
	</script>	
<?php include_once 'footer.php';?>