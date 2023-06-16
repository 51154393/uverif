<?php if(!defined('U_VER')){exit;}?>
<!doctype html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="icon" href="favicon.ico">
		<title>
			系统出错 - U用户管理验证系统
		</title>
		<link rel="stylesheet" href="/assets/css/app-light.css" id="lightTheme">
		<link rel="stylesheet" href="/assets/css/app-dark.css" id="darkTheme" disabled>
	</head>
	<body class="light ">
		<div class="wrapper vh-100">
			<div class="align-items-center h-100 d-flex w-50 mx-auto">
				<div class="mx-auto text-center">
					<h1 class="display-1 m-0 font-weight-bolder text-muted" style="font-size:80px;">
						<?echo $this->errorInfo['code']?>
					</h1>
					<h1 class="mb-1 text-muted font-weight-bold">
						<?echo $this->errorInfo['msg']?>
					</h1>
					<h6 class="mb-3 text-muted">
						这里出了点问题。
					</h6>
					<a href="/" class="btn btn-lg btn-primary px-5">
						返回首页
					</a>
				</div>
			</div>
		</div>
	</body>

</html>
