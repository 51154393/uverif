<?php if(!defined('U_ADMIN')){exit;}?>
<!doctype html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="shortcut icon" href="/assets/images/logo.svg" type="image/x-icon" />
		<title>
			<?echo $this->title;?>
		</title>
		<link rel="shortcut icon" href="/assets/images/logo.svg" type="image/x-icon" />
		<link rel="stylesheet" href="/assets/css/app-light.css" id="lightTheme" disabled>
		<link rel="stylesheet" href="/assets/css/app-dark.css" id="darkTheme">
		<link rel="stylesheet" href="/assets/css/icons.css">
		<link rel="stylesheet" href="/assets/css/u.min.css">
		<style> div{font-family:myFirstFont;}</style>
		<script src="/assets/js/jquery.min.js"></script>
	</head>
	<body class="horizontal light ">
		<div class="wrapper">
			<nav class="navbar navbar-expand-lg navbar-light bg-white flex-row border-bottom shadow">
				<a class="navbar-brand mx-lg-1 mr-0" href="/admin">
					<img src="/assets/images/logo.svg" style="width: 30px;">
				</a>
				
				<form class="form-inline ml-md-auto d-none d-lg-flex text-muted">
				</form>
				<ul class="navbar-nav d-flex flex-row">
					<li class="nav-item">
						<a class="nav-link text-muted my-2" href="javascript:;" id="modeSwitcher" data-mode="light">
							<i class="fe fe-sun fe-16">
							</i>
						</a>
					</li>
					
					<li class="nav-item dropdown ml-lg-0">
						<a class="nav-link dropdown-toggle text-muted" href="javascript:;" id="navbarDropdownMenuLink"
						role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<span class="avatar avatar-sm mt-2">
								<img src="/assets/avatars/face.jpg" class="avatar-img rounded-circle">
							</span>
						</a>
						<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
							<a class="dropdown-item" href="javascript:;" data-toggle="modal" data-target="#adm_cap">修改密码</a>
							<a class="dropdown-item" href="/admin/logout">退出登录</a>
						</ul>
					</li>
				</ul>
			</nav>
			<main role="main" class="main-content">
				<div class="container-fluid">
					<div class="row justify-content-center">
						<div class="col-12 col-xl-10 col-xlx-8">
							<div class="row align-items-center">
								<div class="col">
									<h2 class="page-title">
										应用列表
									</h2>
								</div>
								<div class="col-auto">
									<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_app">
										<span class="fe fe-plus fe-16 mr-2"></span>
										创建应用
									</button>
								</div>
							</div>
							
							<div class="mt-4 align-items-center" id="div_app_state" hidden>
								<div class="card shadow mb-4">
									<div class="card-body text-center mt-5 mb-5 ">
										<div class="avatar avatar-lg my-5">
											<img src="/assets/images/default/appAlter.svg" class="avatar-img" style="width: 150px;">
										</div>
										<div class="card-text">
											<strong class="card-title my-0" id="div_app_state_msg">
												暂无应用，请先创建应用
											</strong>
											<p class="small text-muted mb-5">
												There's a little problem
											</p>
										</div>
									</div>
								</div>
							</div>
							
							<div class="col-md-12 text-center my-5" id="div_app_loading">
								<div class="spinner-border text-primary mt-5" role="status">
									<span class="sr-only">Loading...</span>
								</div>
								<p class="mt-2 h6 mb-5">
									数据加载中
								</p>
							</div>
							
							
							<div class="row mt-4" id="div_app_list">
								
							</div>
							
							<nav class="mb-0">
								<ul class="pagination justify-content-center mb-0" id="poge_list_ul">
								</ul>
							</nav>
						</div>
					</div>
				</div>
			</main>
			<div class="modal fade" id="add_app" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">
								添加应用
							</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<form>
								<div class="card-body text-center">
									<div class="avatar avatar-lg">
										<a href="javascript:upload_logo();">
											<img id="img_applogo" src="/assets/images/plus.svg" class="avatar-img app-logo-add">
										</a>
										<input style="display: none" id="add_applogo" type="file" onchange="showlogo(this);" accept="image/*" multiple />
									</div>
									
									<div class="card-text">
										<label>
											应用图标
										</label>
									</div>
								</div>
								<div class="form-group">
									<label>
										应用名称：
									</label>
									<input type="text" class="form-control" id="add_name" placeholder="应用名称">
								</div>
								<div class="form-group">
									<label>
										版本号：
									</label>
									<input type="number" class="form-control" id="add_bb" placeholder="1.0">
								</div>
								
								<div class="form-group" id="modal_app_appid" hidden>
									<label>
										配置继承：
									</label>
									<select class="form-control" id="add_appid">
										<option value="0">不继承</option>
									</select>
								</div>
							</form>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">
								取消
							</button>
							<button type="button" class="btn mb-2 btn-primary" id="add_submit">
								确认添加
							</button>
						</div>
					</div>
				</div>
			</div>
			
			<div id="modal_appkey" class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog modal-xl" role="document">
					<div class="modal-content">
						<div class="toast-header">
							<strong class="mr-auto" id="modal_appkey_name">
								
							</strong>
							<button type="button" class="ml-2 mb-1 close" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">
									×
								</span>
							</button>
						</div>
						<div class="modal-body">
							AppKey：<input type="text" id="modal_see_appkey" class="modal-see-appkey" readOnly><a href="javascript:copy_appkey();" style="text-decoration: none;"> <i class="fe fe-copy fe-16 mr-1"></i>复制</a>
						</div>
					</div>
				</div>
			</div>
			
			<div id="del_app" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-body p-4">
							<div class="text-center">
								<h4 class="mt-2">删除警告</h4>
								<p class="mt-3">确认删除应用：<strong class="text-danger me-1" id="del_name">null</strong> ？<br>删除应用的同时将清空此应用的所有数据，且不可恢复！请慎重操作</p>
								<table class="table table-bordered table-centered mb-0">
									<tbody>
										<tr>
											<td>APPID</td>
											<td id="del_id">0</td>
										</tr>
										<tr>
											<td>用户数</td>
											<td id="del_unum_total">0</td>
										</tr>
										<tr>
											<td>卡密数</td>
											<td id="del_kmnum_total">0</td>
										</tr>
									</tbody>
								</table>
								<button type="button" class="btn btn-danger mr-2 my-2" id="del_submit">确认删除</button>
								<button type="button" class="btn btn-light" data-dismiss="modal">取消删除</button>
							</div>
						</div>
					</div><!-- /.modal-content -->
				</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->
			
			<div class="modal fade" id="adm_cap" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="modal_title">
								管理员登录信息修改
							</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<form>
								<div class="form-group">
									<label>账号</label>
									<input type="text" class="form-control" value="<?echo $this->admConf['ADM_USER'];?>" id="adm_cap_user">
								</div>
								<div class="form-group">
									<label>当前密码</label>
									<input type="text" class="form-control" placeholder="请输入当前密码" id="adm_cap_pwd">
								</div>
								<div class="form-group">
									<label>新密码</label>
									<input type="text" class="form-control" placeholder="请设置新密码" id="adm_cap_newpwd">
								</div>
								<div class="form-group mb-2">
									<button type="submit" class="btn btn-primary btn-block" id="adm_submit">确认修改</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script>
			$(function(){init('get',1)});
			function init(a,e){return $.ajax({cache:!1,type:"POST",data:{act:a,pg:e},dataType:"json",success:function(a){200==a.code?(add_list(a.data.list,!0),a.data.pageList&&page_list(a.data.pageList,a.data.currentPage,a.data.maxPage)):loading(!1,a.msg)},error:function(a,e,t){loading(!1,e)}}),!1}function del_app(a,e,t,s){$("#del_name").html(e),$("#del_id").html(a),$("#del_unum_total").html(t),$("#del_kmnum_total").html(s)}function add_list(a,e=!1){e&&$("#div_app_list").empty();for(var t=0;t<a.length;t++){var s='<div class="card-header mt-1"><span class="card-title h6"><span class="dot dot-lg bg-'+("on"==a[t].app_state?"success":"danger")+' mr-2"></span>'+a[t].app_name+'</span><div class="float-right"><a class="text-muted ml-1 text-decoration-none" href="javascript:;" onclick="del_app('+a[t].id+",'"+a[t].app_name+"',"+a[t].u_total+","+a[t].k_total+')" data-toggle="modal" data-target="#del_app"><i class="fe fe-trash-2 fe-16"></i></a></div></div>',d='<div class="card-body my-n2"><div class="row align-items-center"><div class="col-2"><span class="circle circle-md"><img src="/'+(isEmpty(a[t].app_logo)?"assets/images/defaultapp.png":a[t].app_logo)+'" class="img-fluid rounded app-logo-state '+a[t].app_state+'" onerror="javascript:this.src=\'/assets/images/defaultapp.png\';"></span></div><div class="col-6" style="white-space: nowrap;"><p class="mb-1">AppId：'+a[t].id+'</p><p class="text-muted mb-0">Key：'+a[t].app_key.replace(a[t].app_key.substring(3,29),"*****")+' <a class="text-muted ml-1" href="javascript:;" data-toggle="modal" data-target="#modal_appkey" style="text-decoration: none;" onclick="see_appkey(\''+a[t].app_name+"','"+a[t].app_key+'\')"><i class="fe fe-eye fe-16"></i></a></p></div><div class="col-4"><a class="float-right small btn btn-primary" href="javascript:to_app('+a[t].id+');">进入</a></div></div></div>';e?$("#div_app_list").append('<div class="col-md-6 col-xl-4 mb-4" id="app_'+a[t].id+'"><div class="card shadow">'+s+d+"</div></div>"):$("#div_app_list").prepend('<div class="col-md-6 col-xl-4 mb-4 app" id="app_'+a[t].id+'"><div class="card shadow">'+s+d+"</div></div>"),$("#add_appid").append('<option value="'+a[t].id+'" id="option_'+a[t].id+'">'+a[t].app_name+"</option>")}$("#div_app_list").children().length>0?loading(!1):loading(!1,"暂无应用，请先创建应用")}function page_list(a,e,t){if($("#poge_list_ul").empty(),a.length>1){$page_pre='<li class="page-item"><a class="page-link" id="page_pre" href="javascript:doPage(\'pre\','+(e-1)+')">上一页</a></li>',$page_next='<li class="page-item"><a class="page-link" id="page_next" href="javascript:doPage(\'next\','+(e+1>t?e:e+1)+')">下一页</a></li>',$page_data="";for(var s=0;s<a.length;s++)a[s]==e?$page_data+='<li class="page-item active"><a class="page-link" id="page_'+a[s]+'" href="javascript:doPage(\''+a[s]+"',"+a[s]+')">'+a[s]+"</a></li>":$page_data+='<li class="page-item"><a class="page-link" id="page_'+a[s]+'" href="javascript:doPage(\''+a[s]+"',"+a[s]+')">'+a[s]+"</a></li>";$("#poge_list_ul").append($page_pre+$page_data+$page_next)}}function doPage(a,e){return e<1?(cocoMessage.error("您当前已经处于第一页了",2e3),!1):($("#page_"+a).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'),$("#page_"+a).attr("disabled",!0),$.ajax({cache:!1,type:"POST",data:{pg:e,act:"get"},dataType:"json",success:function(a){200==a.code?(add_list(a.data.list,!0),page_list(a.data.pageList,a.data.currentPage,a.data.maxPage)):cocoMessage.error(a.msg,2e3)},error:function(a,e,t){cocoMessage.error(e,2e3)}}),!1)}function to_app(a){setCookie("appid",a,7),window.location.href="/admin/home"}function see_appkey(a,e){$("#modal_appkey_name").html(a),$("#modal_see_appkey").val(e)}function copy_appkey(){$("#modal_see_appkey").select(),document.execCommand("copy"),cocoMessage.success("复制成功",2e3)}function upload_logo(){$("#add_applogo").click()}function showlogo(a){var e=a.files[0];if(!e)return $("#img_applogo").attr("src","/assets/images/plus.svg"),$("#img_applogo").removeClass("show"),!1;if(e.size>1048576)return cocoMessage.warning("应用LOGO图片大小不可超过1MB",2e3),!1;var t=new FileReader;t.onload=function(a){console.log(a),$("#img_applogo").attr("src",a.target.result),$("#img_applogo").addClass("show")},t.readAsDataURL(e)}function loading(a,e=null){return $("#div_app_loading").attr("hidden",!0),a?$("#div_app_state").attr("hidden",a):($("#div_app_state").attr("hidden",!1),isEmpty(e)?$("#div_app_state").attr("hidden",!0):$("#div_app_state_msg").html(e)),!1}$("#add_submit").click(function(){var a=new FormData,e=$("#add_applogo").prop("files"),t=$("#add_name").val(),s=$("#add_bb").val(),d=$("#add_appid").val();return $("#add_submit").html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>正在添加'),$("#add_submit").attr("disabled",!0),a.append("act","add"),e.length>0&&a.append("file_applogo",e[0]),a.append("name",t),a.append("bb",s),a.append("appid",d),$.ajax({cache:!1,type:"POST",data:a,dataType:"json",processData:!1,contentType:!1,success:function(a){$("#add_submit").html("确认添加"),$("#add_submit").attr("disabled",!1),200==a.code?(add_list(a.data.list),cocoMessage.success(a.msg,2e3),$("#add_app").modal("hide"),$("#add_name").val(""),$("#add_bb").val(""),$("#add_applogo").val(""),$("#img_applogo").attr("src","/assets/images/add.png"),$("#img_applogo").removeClass("show")):cocoMessage.error(a.msg,2e3)},error:function(a,e,t){cocoMessage.error("操作失败，可能是系统报错了，建议打开F12查看错误信息",2e3),$("#add_submit").html("确认添加"),$("#add_submit").attr("disabled",!1)}}),!1}),$("#del_submit").click(function(){var a=$("#del_id").html(),e=$("#del_user").prop("checked")?"y":"n",t=$("#del_kami").prop("checked")?"y":"n";return $("#del_submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>正在删除'),$("#del_submit").attr("disabled",!0),$.ajax({cache:!1,type:"POST",data:{act:"del",del_id:a,del_user:e,del_kami:t},dataType:"json",success:function(e){$("#del_submit").html("确认删除"),$("#del_submit").attr("disabled",!1),200==e.code?($("#app_"+a).remove(),$("#option_"+a).remove(),cocoMessage.success(e.msg,2e3),$("#del_app").modal("hide"),$("#div_app_list").children().length<=0&&($("#div_app_loading").attr("hidden",!1),init("get",1))):cocoMessage.error(e.msg,2e3)},error:function(a,e,t){cocoMessage.error("操作失败，可能是系统报错了，建议打开F12查看错误信息",2e3),$("#del_submit").html("确认添加"),$("#del_submit").attr("disabled",!1)}}),!1}),$("#adm_submit").click(function(){var a={};return a.user=$("#adm_cap_user").val(),a.pwd=$("#adm_cap_pwd").val(),a.newpwd=$("#adm_cap_newpwd").val(),$("#adm_submit").html('<i class="spinner-ui fe fe-rotate-cw fe-12 mr-2"></i>修改中'),$("#adm_submit").attr("disabled",!0),$.ajax({cache:!1,type:"POST",url:"/admin/cap",data:a,dataType:"json",success:function(a){$("#adm_submit").html("确认修改"),$("#adm_submit").attr("disabled",!1),200==a.code?(cocoMessage.success(a.msg,2e3),location.reload()):cocoMessage.error(a.msg,2e3)},error:function(a,e,t){cocoMessage.error("操作失败，可能是系统报错了，建议打开F12查看错误信息",2e3),$("#adm_submit").html("确认修改"),$("#adm_submit").attr("disabled",!1)}}),!1});
		</script>
		<script src="/assets/js/coco-message.js"></script>
		<script src="/assets/js/app.js" type="text/javascript"></script>
		<script src="/assets/js/ue.min.js" type="text/javascript"></script>
	</body>

</html>