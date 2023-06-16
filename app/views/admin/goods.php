<?php include_once 'header.php';?>
	<div class="row">
		<div class="col-md-12">
			
			<div class="card shadow">
				<div class="card-body">
					<div class="toolbar">
						<form class="form">
							<div class="form-row">
								<div class="form-group col-auto mr-auto">
									<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal" onclick="modal_show()"><i class="fe fe-plus fe-12 mr-2"></i><span class="small">添加商品</span></button>
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
									<th class="text-left">
										商品名称/介绍
									</th>
									<th class="wd-20">
										类型
									</th>
									<th class="wd-20">
										面值
									</th>
									<th class="wd-20">
										金额
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
					<h5 class="modal-title" id="modal-title">添加商品</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form>
						<input type="number" class="form-control" id="modal_id" placeholder="1" hidden>
						<div class="form-group">
							<label>商品名称</label>
							<input type="text" class="form-control" placeholder="如：会员一天" id="modal_name">
						</div>
						<div class="form-group">
							<label>商品类型</label>
							<select class="form-control" id="modal_type" onchange="select_type(this.value)">
								<option value="vip">会员</option>
								<option value="fen">积分</option>
								<option value="agent">代理组</option>
							</select>
						</div>
						
						<div class="form-group" id="div_modal_ag" hidden>
							<label>代理组</label>
							<div class="float-right">
								<a href="javascript:get_ag(true);" id="modal_ag_refresh">刷新</a>
							</div>
							<select class="form-control" id="modal_ag">
								
							</select>
						</div>
						
						<div class="form-group" id="div_modal_vipfen">
							<label id="div_vipfen_label">会员值</label>
							<div class="input-group">
								<input type="number" class="form-control" placeholder="1" id="modal_val">
								<div class="input-group-append">
									<span class="input-group-text" id="modal_type_fen_span" hidden>积分</span>
									<select id="modal_type_vip_span" class="form-control" onchange="select_vip_type(this.value)">
										<option value="s">秒</option>
										<option value="i">分</option>
										<option value="h">时</option>
										<option value="d" selected>天</option>
										<option value="long">永久</option>
									</select>
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<label>商品单价</label>
							<div class="input-group">
								<input type="number" class="form-control" placeholder="1" id="modal_money" value="1">
								<div class="input-group-append">
									<span class="input-group-text">元</span>
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<label>商品介绍</label>
							<textarea class="form-control" id="modal_blurb" rows="3" placeholder="简单的介绍一下商品吧....."></textarea>
						</div>
						
						<div class="form-group" id="div_modal_state" hidden>
							<label>商品状态</label>
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
						<p class="mt-3">确认删除商品：<strong class="text-danger me-1" id="del_name">null</strong> ？<br>删除后不可恢复！请慎重操作</p>
						<input type="number" class="form-control" id="del_id" placeholder="1" hidden>
						<button type="button" class="btn btn-danger mr-2 my-2" id="del_submit">确认删除</button>
						<button type="button" class="btn btn-light" data-dismiss="modal">取消删除</button>
					</div>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<script>
		function init(i,n={}){return initSubmit=i,$.ajax({cache:!1,type:"POST",data:i,dataType:"json",success:function(i){initSuccess(i,n)},error:function(i,t,c){initError(t,n)}}),get_ag();!1}
		
		function add_list(a,t=!1){t&&$("#tbody_list").empty();for(var e=0;e<a.length;e++){var l="<td>"+a[e].id+"</td>";l+='<td class="text-left"><p class="mb-0">'+a[e].name+'</p><small class="mb-0 text-muted">'+a[e].blurb+"</small></td>",l+='<td><span class="badge badge-pill badge-'+("vip"==a[e].type?"danger":"fen"==a[e].type?"warning":"primary")+'">'+("vip"==a[e].type?"会员":"fen"==a[e].type?"积分":"代理组")+"</span></td>",l+="<td>"+("vip"==a[e].type?valTotype(a[e].val):"fen"==a[e].type?a[e].val+"积分":a[e].AGname)+"</td>",l+="<td>￥ "+a[e].money+"</td>",l+='<td><span class="mr-1 dot dot-lg bg-'+("y"==a[e].state?"success":"danger")+'"></span>'+("y"==a[e].state?"正常":"下架")+"</td>",l+='<td><button type="button" class="btn btn-primary btn-sm mr-1" data-toggle="modal" data-target="#modal" onclick="modal_show(\''+base64_encode(JSON.stringify(a[e]))+'\');"><i class="fe fe-edit fe-12 mr-2"></i><span class="small">编辑</span></button><button type="button" class="btn btn-danger btn-sm" onclick="del('+a[e].id+",'"+a[e].name+'\');"><i class="fe fe-trash-2 fe-12 mr-2"></i><span class="small">删除</span></button></td>',$("#list_"+a[e].id).length>0?$("#list_"+a[e].id).html(l):t?$("#tbody_list").append('<tr id="list_'+a[e].id+'">'+l+"</tr>"):$("#tbody_list").prepend('<tr id="list_'+a[e].id+'">'+l+"</tr>")}}function modal_show(a=null){if(null!=a){var t=base64_decode(a),e=JSON.parse(t);$("#modal-title").html("编辑商品"),$("#modal_id").val(e.id),$("#modal_name").val(e.name),$("#modal_type").val(e.type),select_type(e.type),"vip"==e.type?($("#modal_type_vip_span").val(sTotime(e.val,!1)),"long"==sTotime(e.val,!1)?$("#modal_val").attr("readonly",!0):$("#modal_val").attr("readonly",!1),$("#modal_val").val(sTotime(e.val,!0))):"fen"==e.type?$("#modal_val").val(e.val):$("#modal_ag").val(e.val),$("#modal_money").val(e.money),$("#modal_blurb").val(e.blurb),$("#div_modal_state").attr("hidden",!1)}else $("#modal-title").html("添加商品"),select_type("vip"),$("#modal_type_vip_span").val("d"),$("#modal_id").val(""),$("#modal_name").val(""),$("#modal_type").val("vip"),$("#modal_val").val(""),$("#modal_money").val(""),$("#modal_blurb").val(""),$("#div_modal_state").attr("hidden",!0)}function out_select(a){$("#add_out_type_div").attr("hidden",!a)}function checked_state(a){$("#edit_state_label").html(a?"正常":"禁用")}function del(a,t){$("#del_name").html(t),$("#del_id").val(a),$("#del").modal("show")}$("#submit").click(function(){var a={};return a.act="编辑商品"==$("#modal-title").html()?"edit":"add","edit"==a.act&&(a.id=$("#modal_id").val(),a.state=$("#modal_state").prop("checked")?"y":"n"),a.name=$("#modal_name").val(),a.type=$("#modal_type").val(),"vip"==a.type||"fen"==a.type?a.val="vip"==a.type?timeTos($("#modal_val").val(),$("#modal_type_vip_span").val()):$("#modal_val").val():a.val=$("#modal_ag").val(),a.money=$("#modal_money").val(),a.blurb=$("#modal_blurb").val(),$("#submit").html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>正在提交'),$("#submit").attr("disabled",!0),$.ajax({cache:!1,type:"POST",data:a,dataType:"json",success:function(t){$("#submit").html("提交"),$("#submit").attr("disabled",!1),200==t.code?(a.act,cocoMessage.success(t.msg,3e3),add_list(t.data.list),$("#modal").modal("hide")):cocoMessage.error(t.msg,3e3)},error:function(a,t,e){cocoMessage.error("系统发生错误，请打开浏览器调试模式查看具体原因",3e3),$("#submit").html("提交"),$("#submit").attr("disabled",!1)}}),!1}),$("#del_submit").click(function(){var a=$("#del_id").val();return $("#del_submit").html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>正在删除'),$("#del_submit").attr("disabled",!0),$.ajax({cache:!1,type:"POST",data:{act:"del",id:a},dataType:"json",success:function(t){($("#del_submit").html("确认删除"),$("#del_submit").attr("disabled",!1),200==t.code)?($("#list_"+a).remove(),cocoMessage.success(t.msg,3e3),$("#del").modal("hide"),$("#tbody_list").children().length<1&&init(initSubmit)):cocoMessage.error(t.msg,3e3)},error:function(a,t,e){cocoMessage.error("系统发生错误，请打开浏览器调试模式查看具体原因",3e3),$("#add_submit").html("确认添加"),$("#add_submit").attr("disabled",!1)}}),!1});var iniag=!1;function get_ag(a=!1){return!(!a&&iniag)&&($("#modal_ag_refresh").html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>刷新中'),$.ajax({cache:!1,type:"POST",data:{act:"get_ag"},dataType:"json",success:function(a){if(200==a.code){var t=a.data.list;if(t.length>=1){$("#modal_ag").empty();for(var e=0;e<t.length;e++)$("#modal_ag").append('<option value="'+t[e].id+'">'+t[e].name+"</option>");iniag=!0}else $("#modal_ag").append('<option value="0">请先添加代理组</option>')}$("#modal_ag_refresh").html("刷新")},error:function(a,t,e){$("#modal_ag_refresh").html("刷新")}}),!1)}function select_type(a){"vip"==a||"fen"==a?("vip"==a?($("#div_vipfen_label").html("会员值"),$("#modal_type_fen_span").attr("hidden",!0),$("#modal_type_vip_span").attr("hidden",!1)):($("#div_vipfen_label").html("积分值"),$("#modal_type_fen_span").attr("hidden",!1),$("#modal_type_vip_span").attr("hidden",!0)),$("#div_modal_vipfen").attr("hidden",!1),$("#div_modal_ag").attr("hidden",!0)):($("#div_modal_vipfen").attr("hidden",!0),$("#div_modal_ag").attr("hidden",!1))}function select_vip_type(a){"long"==a?($("#modal_val").attr("readonly",!0),$("#modal_val").val("9999999999")):($("#modal_val").attr("readonly",!1),9999999999==(a=$("#modal_val").val())&&$("#modal_val").val(""))}function valTotype(a){return 9999999999==a?"永久":isInteger(a/60/60/24)?a/60/60/24+"天":isInteger(a/60/60)?a/60/60+"时":isInteger(a/60)?a/60+"分":a+"秒"}
    </script>	
<?php include_once 'footer.php';?>