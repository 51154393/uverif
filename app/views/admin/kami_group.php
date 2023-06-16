<?php include_once 'header.php';?>
	<div class="row">
		<div class="col-md-12">
			
			<div class="card shadow">
				<div class="card-body">
					<div class="toolbar">
						<form class="form">
							<div class="form-row">
								<div class="form-group col-auto mr-auto">
									<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal" onclick="modal_show()"><i class="fe fe-plus fe-12 mr-2"></i><span class="small">添加卡密组</span></button>
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
										组名称
									</th>
									<th class="wd-20">
										定价
									</th>
									<th class="wd-20">
										面值
									</th>
									<th class="wd-20">
										卡密数/已使用
									</th>
									<th class="wd-20">
										组类型
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
						添加卡密组
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form>
						<input type="number" class="form-control" id="modal_id" placeholder="1" hidden>
						<div class="form-group">
							<label>卡密组类型</label>
							<select id="modal_type" class="form-control" onchange="select_type(this.value)">
								<option value="vip">会员卡</option>
								<option value="fen">积分卡</option>
								<option value="addmc">设备增绑卡</option>
							</select>
						</div>
						
						<div class="form-group">
							<label>卡密组名称</label>
							<input type="text" class="form-control" id="modal_name" placeholder="如：天卡">
						</div>
						
						<div class="form-group">
							<label>卡密面值</label>
							<div class="input-group">
								<input type="number" class="form-control" placeholder="1" id="modal_val">
								<div class="input-group-append">
									<span class="input-group-text" id="modal_type_fen_span" hidden>积分</span>
									<select class="form-control" id="modal_type_vip_span">
										<option value="s">秒</option>
										<option value="i">分</option>
										<option value="h">时</option>
										<option value="d">天</option>
									</select>
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<label>定价</label>
							<div class="input-group">
								<input type="number" class="form-control" placeholder="1" id="modal_price">
								<div class="input-group-append">
									<span class="input-group-text">元</span>
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
						<p class="mt-3">确认删除卡密组：<strong class="text-danger me-1" id="del_name">null</strong> ？<br>删除后不可恢复！请慎重操作</p>
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
		
		function modal_show(t=null){if(null!=t){var a=base64_decode(t),e=JSON.parse(a);$("#modal_title").html("编辑卡密组"),$("#modal_id").val(e.id),$("#modal_name").val(e.name),$("#modal_price").val(e.price),$("#modal_type").val(e.type),"vip"==e.type&&($("#modal_type_vip_span").val(sTotime(e.val,!1)),"long"==sTotime(e.val,!1)?$("#modal_val").attr("readonly",!0):$("#modal_val").attr("readonly",!1)),select_type(e.type),$("#modal_val").val(sTotime(e.val,!0))}else $("#modal_type_vip_span").val("d"),$("#modal_val").attr("readonly",!1),$("#modal_id").val(""),$("#modal_name").val(""),$("#modal_title").html("添加卡密组"),$("#modal_type").val("vip"),select_type("vip"),$("#modal_val").val(""),$("#modal_price").val("")}function add_list(t,a=!1){a&&$("#tbody_list").empty();for(var e=0;e<t.length;e++){var l="<td>"+t[e].id+"</td>";l+='<td class="text-left">'+t[e].name+"</td>",l+="<td>"+(null==t[e].price?0:t[e].price)+" 元</td>",l+="<td>"+("vip"==t[e].type?valTotype(t[e].val):t[e].val+("fen"==t[e].type?"积分":"台"))+"</td>",l+='<td><span class="text-success">'+t[e].km_num+'</span>/<span class="text-danger">'+t[e].km_uses+"</span></td>",l+='<td><small class="badge badge-'+("vip"==t[e].type?"danger":"fen"==t[e].type?"warning":"info")+'-lighten">'+("vip"==t[e].type?"会员卡":"fen"==t[e].type?"积分卡":"设备卡")+"</small></td>",l+='<td><button type="button" class="btn btn-primary btn-sm mr-1" data-toggle="modal" data-target="#modal" onclick="modal_show(\''+base64_encode(JSON.stringify(t[e]))+'\');"><i class="fe fe-edit fe-12 mr-2"></i><span class="small">编辑</span></button><button type="button" class="btn btn-danger btn-sm" onclick="del_list('+t[e].id+",'"+t[e].name+'\');"><i class="fe fe-trash-2 fe-12 mr-2"></i><span class="small">删除</span></button></td>',$("#list_"+t[e].id).length>0?$("#list_"+t[e].id).html(l):a?$("#tbody_list").append('<tr id="list_'+t[e].id+'">'+l+"</tr>"):$("#tbody_list").prepend('<tr id="list_'+t[e].id+'">'+l+"</tr>")}}function del_list(t,a){$("#del_name").html(a),$("#del_id").val(t),$("#del").modal("show")}function select_type(t){"vip"==t?($("#modal_type_fen_span").attr("hidden",!0),$("#modal_type_vip_span").attr("hidden",!1)):("addmc"==t?$("#modal_type_fen_span").html("台"):$("#modal_type_fen_span").html("积分"),$("#modal_type_fen_span").attr("hidden",!1),$("#modal_type_vip_span").attr("hidden",!0))}function select_val_type(t){"long"==t?($("#modal_val").attr("readonly",!0),$("#modal_val").val("9999999999")):($("#modal_val").attr("readonly",!1),9999999999==(t=$("#modal_val").val())&&$("#modal_val").val(""))}function valTotype(t){return 9999999999==t?"永久":isInteger(t/60/60/24)?t/60/60/24+"天":isInteger(t/60/60)?t/60/60+"时":isInteger(t/60)?t/60+"分":t+"秒"}$("#submit").click(function(){var t={};return t.act="编辑卡密组"==$("#modal_title").html()?"edit":"add","edit"==t.act&&(t.id=$("#modal_id").val()),t.type=$("#modal_type").val(),t.name=$("#modal_name").val(),t.val="vip"==t.type?timeTos($("#modal_val").val(),$("#modal_type_vip_span").val()):$("#modal_val").val(),t.price=$("#modal_price").val(),$("#submit").html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>正在提交'),$("#submit").attr("disabled",!0),$.ajax({cache:!1,type:"POST",data:t,dataType:"json",success:function(a){$("#submit").html("提交"),$("#submit").attr("disabled",!1),200==a.code?("edit"==t.act?(cocoMessage.success("更新成功",2e3),add_list(a.data.list)):(cocoMessage.success("添加成功",2e3),add_list(a.data.list)),$("#modal").modal("hide")):cocoMessage.error(a.msg,2e3)},error:function(t,a,e){cocoMessage.error("系统发生错误，请打开浏览器调试模式查看具体原因",2e3),$("#submit").html("提交"),$("#submit").attr("disabled",!1)}}),!1}),$("#del_submit").click(function(){var t=$("#del_id").val();return $("#del_submit").html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>正在删除'),$("#del_submit").attr("disabled",!0),$.ajax({cache:!1,type:"POST",data:{act:"del",id:t},dataType:"json",success:function(a){($("#del_submit").html("确认删除"),$("#del_submit").attr("disabled",!1),200==a.code)?($("#list_"+t).remove(),cocoMessage.success(a.msg,2e3),$("#del").modal("hide"),$("#tbody_list").children().length<1&&init(initSubmit)):cocoMessage.error(a.msg,2e3)},error:function(t,a,e){cocoMessage.error("系统发生错误，请打开浏览器调试模式查看具体原因",2e3),$("#del_submit").html("确认删除"),$("#del_submit").attr("disabled",!1)}}),!1});
	</script>	
<?php include_once 'footer.php';?>