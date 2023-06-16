<?php if(!defined('U_ADMIN')){exit;}?>
<!doctype html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>
			管理员登录 - <?echo $this->appConf['APP_NAME'];?>
		</title>
		<link rel="stylesheet" href="/assets/css/app-light.css" id="lightTheme">
		<link rel="stylesheet" href="/assets/css/app-dark.css" id="darkTheme" disabled>
	</head>
	<body class="light ">
		<div class="wrapper vh-100">
			<div class="row align-items-center h-75" style="margin-right: 0px;margin-left: 0px;">
				<form class="col-lg-3 col-md-4 col-10 mx-auto text-center">
					<a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="./">
						<img src="/assets/images/logo.svg" style="width: 30px;">
					</a>
					<h1 class="h6 mb-3">
						<?echo $this->appConf['APP_NAME'].$this->appConf['APP_VERSION'];?>
					</h1>
					<div class="form-group">
						<label class="sr-only">
							账号
						</label>
						<input type="email" id="user" class="form-control form-control-lg" placeholder="账号">
					</div>
					<div class="form-group">
						<label class="sr-only">
							密码
						</label>
						<input type="password" id="password" class="form-control form-control-lg" placeholder="密码">
					</div>
					<div class="checkbox mb-3">
						<label>
							<input type="checkbox" value="remember-me">
							记住我
						</label>
					</div>
					<button class="btn btn-lg btn-primary btn-block" type="submit" id="submit_logon">立即登录</button>
					<p class="mt-5 mb-3 text-muted">
						<script>document.write(new Date().getFullYear())</script> © 版权所有：Jienet.com 本服务由皆网云提供
					</p>
				</form>
			</div>
		</div>
		<script src="/assets/js/coco-message.js"></script>
		<script src="/assets/js/jquery.min.js"></script>
		<script>
			$("#submit_logon").click(function(){var user=$("#user").val();var password=$("#password").val();$("#submit_logon").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>正在登录');$("#submit_logon").attr('disabled',true);$.ajax({cache:false,type:"POST",data:{user,password},dataType:'json',success:function(data){$("#submit_logon").html('立即登录');$("#submit_logon").attr('disabled',false);if(data.code==200){cocoMessage.success(data.msg,2000);location.reload()}else{cocoMessage.error(data.msg,2000)}},error:function(XMLHttpRequest,textStatus,errorThrown){cocoMessage.error(textStatus,2000)}});return false});
		</script>
	</body>

</html>