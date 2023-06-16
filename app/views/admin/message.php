<?php include_once 'header.php';?>
	<div class="row">
		<div class="col-md-12">
			
			<div class="card shadow">
				<div class="card-body">
					<div class="toolbar">
						<form class="form">
							<div class="form-row">
								<div class="form-group col-auto mr-auto">
									<select class="form-control" id="state" name="so">
										<option value="-1">全部</option>
										<option value="0">待回复</option>
										<option value="1">已回复</option>
										<option value="2">已解决</option>
									</select>
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
										标题/消息摘要
									</th>
									<th class="wd-20">
										用户
									</th>
									<th class="wd-20">
										创建时间/最后回复
									</th>
									<th class="wd-20">
										状态
									</th>
									<th class="wd-20">
										操作
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
	<div class="modal fade modal-right modal-slide" id="modal-reply" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-plus" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="reply-title">消息标题</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body bg-light">
					<div class="row">
						<div class="col-12">
							<div class="card">
								<div id="modal_message_content" class="card-body min-vh-message" style="height: 300px;overflow-y: auto;overflow-x: hidden;">
									
								</div>
								<div class="card-body">
									<div class="border rounded">
										<form class="comment-area-box">
											<input type="number" class="form-control" id="reply_id" placeholder="1" hidden>
											<textarea rows="4" class="form-control border-0 resize-none" placeholder="说点啥吧....." id="reply_content"></textarea>
											<div class="p-2 bg-light d-flex justify-content-between align-items-center">
												<a href="javascript:modal_image(true);" class="text-muted ml-1 text-decoration-none">
													<i class="fe fe-image fe-16"></i>上传图片（<span id="message_file_num">0</span>）
												</a>
												<button type="submit" class="btn btn-sm btn-success" id="submit_reply"><i class="fe fe-navigation mr-1"></i>回复</button>
											</div>
										</form>
									</div>
								</div> 
							</div> <!-- end card-->
						</div><!-- end col-->
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div id="modal-image" class="modal fade" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h6 class="modal-title">上传图片</h6>
					<button type="button" class="close" onclick="modal_image(false)">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="card-body">
					<div id="image_file"></div>
				</div>
				
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
	<div id="del" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-body p-4">
					<div class="text-center">
						<h4 class="mt-2">删除警告</h4>
						<p class="mt-3">确认删除留言：<strong class="text-danger me-1" id="del_name">null</strong> ？<br>删除后不可恢复！请慎重操作</p>
						<input type="number" class="form-control" id="del_id" placeholder="1" hidden>
						<button type="button" class="btn btn-danger mr-2 my-2" id="del_submit">确认删除</button>
						<button type="button" class="btn btn-light" data-dismiss="modal">取消删除</button>
					</div>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
	<script src="/assets/js/cupload.js"></script>
	<script src="/assets/js/postbird-img-glass.min.js"></script>
	<script>
		function init(i,n={}){return initSubmit=i,$.ajax({cache:!1,type:"POST",data:i,dataType:"json",success:function(i){initSuccess(i,n)},error:function(i,t,c){initError(t,n)}}),initUpimage();!1}
		var upImage;
		function initUpimage(){upImage=new Cupload({ele:"#image_file",name:"image_file",num:5,width:80,height:80,minSize:1,maxSize:1024,error:function(e){cocoMessage.error(e,2e3)},fileAdd:function(){var e=$("#message_file_num").html();$("#message_file_num").html(Number(e)+1)},fileDel:function(){var e=$("#message_file_num").html();$("#message_file_num").html(Number(e)-1)}})}function add_list(e,t=!1){t&&$("#tbody_list").empty();for(var s=0;s<e.length;s++){var a="<td>"+e[s].id+"</td>";a+='<td class="text-left"><p class="mb-0">'+e[s].title+'</p><small class="mb-0 text-muted">'+e[s].content+"</small></td>",a+="<td>"+e[s].user+"</td>",a+='<td><p class="mb-0">'+timeToDate(e[s].time)+'</p><small class="mb-0 text-muted" id="tbody_list_last_time">'+(isEmpty(e[s].last_time)?"NULL":timeToDate(e[s].last_time))+"</small></td>",a+='<td  id="tbody_list_state"><span class="mr-1 dot dot-lg bg-'+(0==e[s].state?"warning":2==e[s].state?"success":"info")+'"></span>'+(0==e[s].state?"待回复":2==e[s].state?"已解决":"已回复")+"</td>",a+='<td><button type="button" class="btn btn-primary btn-sm mr-1" onclick="reply('+e[s].id+",'"+e[s].title+'\');"><i class="fe fe-twitch fe-12 mr-2"></i><span class="small">回复</span></button><button type="button" class="btn btn-danger btn-sm" onclick="del('+e[s].id+",'"+e[s].title+'\');"><i class="fe fe-trash-2 fe-12 mr-2"></i><span class="small">删除</span></button></td>',$("#list_"+e[s].id).length>0?$("#list_"+e[s].id).html(a):t?$("#tbody_list").append('<tr id="list_'+e[s].id+'">'+a+"</tr>"):$("#tbody_list").prepend('<tr id="list_'+e[s].id+'">'+a+"</tr>")}}function reply(e,t){return loading(!0),$("#reply-title").html(t),$("#reply_id").val(e),$("#reply_content").val(""),$.ajax({cache:!1,type:"POST",data:{act:"get_msg",id:e},dataType:"json",success:function(e){loading(!1),200==e.code?add_message(e.data.list,!0):cocoMessage.error(e.msg,2e3),$("#message_file_num").html("0"),upImage.emptyImage(),$("#modal-reply").modal("show")},error:function(e,t,s){cocoMessage.error("系统发生错误，请打开浏览器调试模式查看具体原因",2e3),loading(!1)}}),!1}function add_message(e,t=!1){t&&$("#modal_message_content").empty();for(var s=0;s<e.length;s++){if("管理员"==e[s].user){var a='<div class="row"><div class="col-12 py-3"><div class="row float-right"><div class="col text-right"><span class="dot dot-lg bg-success mr-1"></span>';if(a+='<strong class="mb-1">管理员</strong><p class="small text-muted mb-1">'+timeToDate(e[s].time)+"</p>",a+='<div class="alert alert-success mb-1" role="alert">'+e[s].content+(2==e[s].state?'<i class="fe fe-check fe-16 text-success ml-2"></i>':"")+"</div>",!isEmpty(e[s].file)){for(var l=JSON.parse(e[s].file),i='<div class="row avatar avatar-md"><div class="col-auto img-container ml-auto">',d=0;d<l.length;d++)i+='<img src="/'+l[d]+'" class="img-fluid rounded">';a+=i+"</div></div>"}a+='</div><div class="col-auto pl-0"><div class="avatar avatar-md"><img src="/assets/avatars/face.jpg" class="avatar-img rounded-circle"></div></div></div></div></div>'}else{a='<div class="row"><div class="col-12 py-3"><div class="row float-left"><div class="col-auto pr-0"><div class="avatar avatar-md"><img src="'+e[s].avatars+'" class="avatar-img rounded-circle" onerror="javascript:this.src=\'/assets/avatars/default.svg\';"></div></div>';if(a+='<div class="col"><strong class="mb-1">'+e[s].user+'</strong><span class="dot dot-lg bg-success ml-1"></span><p class="small text-muted mb-1">'+timeToDate(e[s].time)+"</p>",a+='<div class="alert alert-primary mb-1" role="alert">'+e[s].content+'<i class="fe fe-check fe-16 text-success ml-2"></i></div>',!isEmpty(e[s].file)){for(l=JSON.parse(e[s].file),i='<div class="row avatar avatar-md"><div class="col-auto img-container">',d=0;d<l.length;d++)i+='<img src="/'+l[d]+'" class="img-fluid rounded mr-1">';a+=i+"</div></div>"}a+="</div></div></div></div>"}$("#modal_message_content").append(a)}var m=document.getElementById("modal_message_content");setTimeout(function(){m.scrollTop=m.scrollHeight},200),PostbirdImgGlass.init({domSelector:".img-container img",animation:!0})}function modal_image(e){e?($("#modal-reply").css("z-index","1040"),$("#modal-image").modal("show")):($("#modal-reply").css("z-index","1050"),$("#modal-image").modal("hide"))}function del(e,t){$("#del_name").html(t),$("#del_id").val(e),$("#del").modal("show")}$("#submit_reply").click(function(){var e=new FormData;for(key in e.append("act","reply"),e.append("id",$("#reply_id").val()),e.append("content",$("#reply_content").val()),upImage.opt.fileData)e.append(key,upImage.opt.fileData[key]);return $("#submit_reply").html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>提交中'),$("#submit_reply").attr("disabled",!0),$.ajax({cache:!1,type:"POST",data:e,dataType:"json",processData:!1,contentType:!1,success:function(e){$("#submit_reply").html('<i class="fe fe-navigation mr-1"></i>回复'),$("#submit_reply").attr("disabled",!1),200==e.code?(cocoMessage.success(e.msg,3e3),$("#message_file_num").html("0"),upImage.emptyImage(),add_message(e.data.list),$("#reply_content").val(""),$("#tbody_list_state").html('<span class="mr-1 dot dot-lg bg-info"></span>已回复'),$("#tbody_list_last_time").html(timeToDate())):cocoMessage.error(e.msg,3e3)},error:function(e,t,s){cocoMessage.error("系统发生错误，请打开浏览器调试模式查看具体原因",3e3),$("#submit_reply").html("确认添加"),$("#submit_reply").attr("disabled",!1)}}),!1}),$("#del_submit").click(function(){var e=$("#del_id").val();return $("#del_submit").html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>正在删除'),$("#del_submit").attr("disabled",!0),$.ajax({cache:!1,type:"POST",data:{act:"del",id:e},dataType:"json",success:function(t){($("#del_submit").html("确认删除"),$("#del_submit").attr("disabled",!1),200==t.code)?($("#list_"+e).remove(),cocoMessage.success(t.msg,2e3),$("#del").modal("hide"),$("#list").children().length<1&&init(initSubmit)):cocoMessage.error(t.msg,2e3)},error:function(e,t,s){cocoMessage.error("系统发生错误，请打开浏览器调试模式查看具体原因",2e3),$("#del_submit").html("确认删除"),$("#del_submit").attr("disabled",!1)}}),!1});
	</script>	
<?php include_once 'footer.php';?>