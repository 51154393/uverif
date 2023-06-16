<?php if(!defined('U_INSTALL')){exit;}?>
<!doctype html>
<html lang="zh-cn">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="icon" href="favicon.ico">
		<title>
			数据库安装 - 欢迎使用<?=c('app.APP_NAME')?>
		</title>
		<link rel="shortcut icon" href="/assets/images/logo.svg" type="image/x-icon" />
		<link rel="stylesheet" href="/assets/css/app-light.css" id="lightTheme">
		<link rel="stylesheet" href="/assets/css/app-dark.css" id="darkTheme" disabled>
		<link rel="stylesheet" href="/assets/css/icons.css">
		<style>
		  .steps {position: relative;counter-reset: step; width: 100%; margin: 0 0 0 -20px;}
		  .steps li {list-style-type: none;font-size: 12px;text-align: center;width: 33.33%;position: relative; color: #999;z-index: 2;float: left;}
		  .steps li:before { display: block; content: counter(step);counter-increment: step; width:24px; height: 24px;background-color: #fff;line-height:24px;border-radius:50%;   font-size: 14px;color: #999;text-align: center;font-weight: 700;margin: 0 auto 8px auto; border: 1px #e5e5e5 solid}
		  .steps li:after {content: '';width: 100%;height: 2px;background-color: #0303033b; position: absolute;left: 0;top: 10px; z-index: -1;}
		  .steps li:first-child {z-index: 3;}
		  .steps li:last-child { z-index: 1;}
		  .steps li:first-child:after {width: 50%;left: 50%;}
		  .steps li:last-child:after {width: 50%;}
		  .steps li.active:before {color: #fff}
		  .steps li.active:before, .steps li.active:after { background-color: #1b68ff;}
		</style>
	</head>
	<body class="light ">
		<div class="wrapper vh-100">
			<div class="row align-items-center h-100" style="margin-right: 0px;margin-left: 0px;">
				<form class="col-lg-6 col-md-8 col-10 mx-auto">
					<div class="mx-auto text-center my-4">
						<a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="/index">
							<img src="/assets/images/logo.svg" style="width: 40px;">
						</a>
						<h2 class="my-3">
							<?=c('app.APP_NAME')?>数据库安装
						</h2>
					</div>
					<div class="form-row mt-4 mb-4">
						<ul class="steps">
							<li class="active" id="li_step_1">环境检测</li>
							<li id="li_step_2">数据库配置</li>
							<li id="li_step_3">完成安装</li>
						</ul>
					</div>
					<div id="div_step_1">
						<table class="table table-striped table-borderless mb-4">
							
							<tbody>
								<tr>
									<td class="text-center w-10">
										<span class="fe fe-globe fe-24">
										</span>
										<br />
										当前域名
									</td>
									<th scope="row" class="w-50 text-center">
										<?echo $_SERVER['SERVER_NAME'];?>
									</th>
									<td class="text-muted w-25  text-center">
										<span class="dot dot-lg bg-success mr-2"></span>正常
									</td>
								</tr>
								<tr>
									<td class="text-center w-10">
										<span class="fe fe-server fe-24">
										</span>
										<br />
										服务器环境
									</td>
									<th scope="row" class="w-50 text-center">
										<?echo $_SERVER["SERVER_SOFTWARE"];?>
										<br>
										<small class="text-muted">Nginx/Apache</small>
									</th>
									<td class="text-muted w-25  text-center">
										<span class="dot dot-lg bg-success mr-2"></span>正常
									</td>
								</tr>
								
								<tr>
									<td class="text-center w-25">
										<span class="fe fe-feather fe-24">
										</span>
										<br />
										PHP版本
									</td>
									<th scope="row" class="w-50 text-center">
										<?echo PHP_VERSION;?>
										<br>
										<small class="text-muted">>=5.6 且 &lt;=8.0</small>
									</th>
									<td class="text-muted w-25  text-center">
										<? if(PHP_VERSION>=5.6 && PHP_VERSION<=8.026):?><span class="dot dot-lg bg-success mr-2"></span>正常<? else:?><span class="dot dot-lg bg-danger mr-2"></span>异常<? endif; ?>
									</td>
								</tr>
								
								<tr>
									<td class="text-center w-25">
										<span class="fe fe-database fe-24">
										</span>
										<br />
										MYSQL数据库
									</td>
									<th scope="row" class="w-50 text-center">
										<?echo function_exists('mysqli_connect')?"支持":"不支持";?>
										<br>
										<small class="text-muted">支持</small>
									</th>
									<td class="text-muted w-25  text-center">
										<? if(function_exists('mysqli_connect')):?><span class="dot dot-lg bg-success mr-2"></span>正常<? else:?><span class="dot dot-lg bg-danger mr-2"></span>异常<? endif; ?>
									</td>
								</tr>
								<tr>
									<td class="text-center w-25">
										<span class="fe fe-monitor fe-24">
										</span>
										<br />
										服务器系统
									</td>
									<th scope="row" class="w-50 text-center">
										<? echo PHP_OS;?>
										<br>
										<small class="text-muted">WINNT/LINUX</small>
									</th>
									<td class="text-muted w-25  text-center">
										<? if(strtoupper(PHP_OS)=='WINNT' or strtoupper(PHP_OS)=='LINUX'):?><span class="dot dot-lg bg-success mr-2"></span>正常<? else:?><span class="dot dot-lg bg-danger mr-2"></span>异常<? endif; ?>
									</td>
								</tr>
								
							</tbody>
						</table>
						<button class="btn btn-lg btn-primary btn-block" type="button" onclick="next_step(1,'<?echo htmlentities(json_encode([PHP_VERSION,function_exists('mysqli_connect'),strtoupper(PHP_OS)]));?>')">下一步</button>
					</div>
					<div id="div_step_2" hidden>
						<div class="form-row">
							<div class="form-group col-md-6">
								<label>
									数据库地址
								</label>
								<input type="text" class="form-control" id="mysql_site" value="127.0.0.1" placeholder="如果是本地数据库则默认127.0.0.1">
							</div>
							<div class="form-group col-md-6">
								<label>
									数据库端口
								</label>
								<input type="number" class="form-control" id="mysql_port" value="3306" placeholder="数据库默认端口3306">
							</div>
							<div class="form-group col-md-6">
								<label>
									数据库名称
								</label>
								<input type="text" id="mysql_name" class="form-control" placeholder="请填写数据库名称">
							</div>
							<div class="form-group col-md-6">
								<label>
									数据表前缀
								</label>
								<input type="text" id="mysql_pre" class="form-control" placeholder="可自定义数据表前缀：1~8位字母+数字" value="u">
							</div>
							<div class="form-group col-md-6">
								<label>
									数据库账户
								</label>
								<input type="text" id="mysql_user" class="form-control" placeholder="请填写数据账号">
							</div>
							<div class="form-group col-md-6">
								<label>
									数据库密码 
								</label>
								<input type="text" id="mysql_psw" class="form-control" placeholder="请填写数据密码">
							</div>
						</div>
						<hr class="my-4">
						<div class="row mb-4">
							<div class="col-md-6">
								<div class="form-group">
									<label>
										管理员账号
									</label>
									<input type="text" class="form-control" id="admin_user" placeholder="请填写管理员账号">
								</div>
								<div class="form-group">
									<label>
										管理员密码
									</label>
									<input type="password" class="form-control" id="admin_psw" placeholder="请填写管理员密码">
								</div>
							</div>
							<div class="col-md-6">
								<p class="mb-2">
									安装说明
								</p>
								<p class="small text-muted mb-2">
									请在安装完数据库后，检查并操作以下文件：
								</p>
								<ul class="small text-muted pl-4 mb-0">
									<li>
										1、删除根目录中的install.php、install.sql文件
									</li>
									<li>
										2、及时修改管理员后台入口文件：admin.php
									</li>
									<li>
										3、加入官方QQ群：791336849
									</li>
									<li>
										4、若安装失败或无法正常使用该系统，建议使用皆网云服务器搭建
									</li>
								</ul>
							</div>
						</div>
						<button class="btn btn-lg btn-primary btn-block" type="button" onclick="next_step(2,'')" id="install">确定安装</button>
					</div>
					<div id="div_step_3" class="text-center" hidden>
						<svg t="1657280740275" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="8876" width="100" height="100"><path d="M512 512m-512 0a512 512 0 1 0 1024 0 512 512 0 1 0-1024 0Z" fill="#67EBB2" opacity=".15" p-id="8877"></path><path d="M512 814.545455a302.545455 302.545455 0 0 1-213.934545-516.48 302.545455 302.545455 0 1 1 427.86909 427.86909A300.555636 300.555636 0 0 1 512 814.545455z m-124.148364-328.052364a36.072727 36.072727 0 0 0-25.6 61.486545l92.997819 93.730909a29.917091 29.917091 0 0 0 42.46109 0l165.853091-166.74909a29.928727 29.928727 0 0 0-40.226909-44.218182l-127.418182 104.808727a29.905455 29.905455 0 0 1-38.597818-0.488727l-45.905454-39.761455a36.002909 36.002909 0 0 0-23.563637-8.808727z" fill="#20D76D" p-id="8878"></path></svg>
						
						<h5 class="my-3" style="color: #3ad29f;">
							安装成功
						</h5>
						<p class="w-75 mb-2 mt-2 mx-auto"><?=c('admin.APP_NAME')?>安装完成，你可以开始使用本系统了。若访问首页任然继续跳转至安装跳转，请自行在install/目录下创建一个 install.lock 空文档即可</p>
						<a type="button" class="btn mb-2 btn-primary mr-2" href="/"><span class="fe fe-globe fe-16 mr-2"></span>返回首页</a>
						<a type="button" class="btn mb-2 btn-success" href="/admin.php"><span class="fe fe-arrow-right fe-16 mr-2"></span>开始使用</a>
					</div>	
					<p class="mt-5 mb-3 text-muted text-center">
						<script>document.write(new Date().getFullYear())</script> © 版权所有：www.uverif.com 本系统框架由Uephp提供
					</p>
				</form>
			</div>
		</div>
		<script src="/assets/js/coco-message.js"></script>
		<script>
			function next_step(id,ojb) {
				if(id == 1){
					var data = JSON.parse(ojb);
					if(parseFloat(data[0]) < 5.6 || parseFloat(data[0]) > 8.0){
						cocoMessage.error('PHP版本过低或过高，无法正常运行该程序', 2000);
						return false;
					}
					if(data[1] != true){
						cocoMessage.error('当前系统无法支持使用MYSQL数据库', 2000);
						return false;
					}
					if(data[2] != 'WINNT' && data[2] != 'LINUX'){
						cocoMessage.error('请使用WINNT/LINUX系统运行本程序', 2000);
						return false;
					}
				}
				$("#div_step_1").attr("hidden",true);
				$("#li_step_2").addClass("active");
				$("#div_step_2").attr("hidden",false);
				if(id == 2){
					var mysql_site = $("#mysql_site").val();
					var mysql_port = $("#mysql_port").val();
					var mysql_pre = $("#mysql_pre").val();
					var mysql_name = $("#mysql_name").val();
					var mysql_user = $("#mysql_user").val();
					var mysql_psw = $("#mysql_psw").val();
					var admin_user = $("#admin_user").val();
					var admin_psw = $("#admin_psw").val();
					
					$("#install").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>正在安装');
					$("#install").attr("disabled",true);
					$.ajax({
						cache: false,
						type: "POST",//请求的方式
						data : {mysql_site,mysql_port,mysql_pre,mysql_name,mysql_user,mysql_psw,admin_user,admin_psw},
						dataType : 'json',
						success : function(data) {
							$("#install").html('确定安装');
							$("#install").attr("disabled",false);
							if(data.code == 200){
								cocoMessage.success(data.msg, 2000);
								$("#li_step_3").addClass("active");
								$("#div_step_2").attr("hidden",true);
								$("#div_step_3").attr("hidden",false);
							}else{
								cocoMessage.error(data.msg,2000);
							}
						},
						error: function (XMLHttpRequest, textStatus, errorThrown) {
							cocoMessage.error('当前服务器环境不兼容当前程序，建议使用皆网云部署或打开浏览器调试模式查看具体错误原因',2000);
							$("#install").html('确定安装');
							$("#install").attr("disabled",false);
						}
					});
					return false;
				}
				
				return false;
			}
		</script>
		<script src="/assets/js/jquery.min.js"></script>
	</body>
</html>