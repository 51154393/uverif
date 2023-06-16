<?php include_once 'header.php';?>
	
	<div class="row">
		<div class="col-md-12">
			<div class="card shadow mb-2">
				<div class="card-body">
					<div class="border rounded">
						<form class="comment-area-box">
							<textarea rows="4" class="form-control border-0 resize-none" placeholder="说点啥吧....." id="content"></textarea>
							<div class="p-2 bg-light d-flex justify-content-between align-items-center">
								<div>
								    <select id="all" class="form-control">
									    <option value="n">当前应用</option>
									    <option value="y">全局应用</option>
								    </select>
								</div>
								<button type="submit" class="btn btn-sm btn-success" id="submit"><i class="fe fe-navigation mr-1"></i>发布</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>	
		<div class="col-md-12" id="list">

			
		</div>
		<div class="col-md-12 mt-2">
			<nav class="mb-0" id="poge_list_nav">
				<ul class="pagination justify-content-center mb-0" id="poge_list_ul">
					
				</ul>
			</nav>
		</div>
	</div>
	
	<div id="del_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-body p-4">
					<div class="text-center">
						<h4 class="mt-2">删除警告</h4>
						<p class="mt-3">确认删除公告：<strong class="text-danger me-1" id="del_name">null</strong> ？<br>删除后不可恢复！请慎重操作</p>
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
		
		function add_list(s,t=!1){t&&$("#list").empty();for(var a=0;a<s.length;a++){var e='<div class="card-body pb-1"><div class="d-flex"><div class="row"><div class="col-4 col-md-2 text-center pt-1"><div  class="avatar avatar-sm"><img src="/assets/avatars/face.jpg" class="avatar-img rounded-circle"></div></div></div><div class="row w-100"><div class="col ml-2"><strong class="mb-1">管理员</strong><p class="small text-muted mb-1">'+timeToDate(s[a].time)+'</p></div><div class="col-auto mt-2"><a class="text-muted ml-1 text-decoration-none" href="javascript:;" onclick="del_list('+s[a].id+",'"+s[a].content+'\')" data-toggle="modal" data-target="#del_modal"><i class="fe fe-trash-2 fe-16"></i></a></div></div></div><div class="font-16 my-3">'+s[a].content+'</div><div class="my-1"><span class="text-muted ps-0">'+(isEmpty(s[a].appid)?"全局应用":"当前应用")+'</span><span class="text-muted ps-0 float-right">'+s[a].visit+"人 已阅</span></div></div>";t?$("#list").append('<div class="card mb-2" id="list_'+s[a].id+'">'+e+"</div>"):$("#list").prepend('<div class="card mb-2" id="list_'+s[a].id+'">'+e+"</div>")}}function del_list(s,t){$("#del_name").html(t.substring(0,8)+"..."),$("#del_id").val(s)}$("#submit").click(function(){var s={act:"add"};return s.all=$("#all").val(),s.content=$("#content").val(),$("#submit").html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>正在提交'),$("#submit").attr("disabled",!0),$.ajax({cache:!1,type:"POST",data:s,dataType:"json",success:function(s){$("#submit").html('<i class="fe fe-navigation mr-1"></i>发布'),$("#submit").attr("disabled",!1),200==s.code?($("#content").val(""),cocoMessage.success("发布成功",2e3),add_list(s.data.list)):cocoMessage.error(s.msg,2e3)},error:function(s,t,a){cocoMessage.error("系统发生错误，请打开浏览器调试模式查看具体原因",2e3),$("#submit").html('<i class="fe fe-navigation mr-1"></i>发布'),$("#submit").attr("disabled",!1)}}),!1}),$("#del_submit").click(function(){var s=$("#del_id").val();return $("#del_submit").html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>正在删除'),$("#del_submit").attr("disabled",!0),$.ajax({cache:!1,type:"POST",data:{act:"del",id:s},dataType:"json",success:function(t){($("#del_submit").html("确认删除"),$("#del_submit").attr("disabled",!1),200==t.code)?($("#list_"+s).remove(),cocoMessage.success(t.msg,2e3),$("#del_modal").modal("hide"),$("#list").children().length<1&&init(initSubmit)):cocoMessage.error(t.msg,2e3)},error:function(s,t,a){cocoMessage.error("系统发生错误，请打开浏览器调试模式查看具体原因",2e3),$("#del_submit").html("确认删除"),$("#del_submit").attr("disabled",!1)}}),!1});
	</script>	
<?php include_once 'footer.php';?>