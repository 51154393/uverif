<?php if(!defined('U_ADMIN')){exit;}?>
<!doctype html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="icon" href="/<?echo !empty($this->app['app_logo'])&&file_exists($this->app['app_logo'])?$this->app['app_logo']:'assets/images/defaultapp.png';?>"/>
		<title>
			<?echo $this->title;?>
		</title>
		<link rel="shortcut icon" href="/assets/images/logo.svg" type="image/x-icon" />
		<link rel="stylesheet" href="/assets/css/app-light.css" id="lightTheme">
		<link rel="stylesheet" href="/assets/css/app-dark.css" id="darkTheme" disabled>
		<link rel="stylesheet" href="/assets/css/icons.css">
		<link rel="stylesheet" href="/assets/css/u.min.css">
		
		<style> div{font-family:myFirstFont;}</style>
		<script src="/assets/js/jquery.min.js"></script>
	</head>
	<body class="vertical light">
		<div class="wrapper">
			<nav class="topnav navbar navbar-light">
				<button type="button" class="navbar-toggler text-muted mt-2 p-0 mr-3 collapseSidebar">
					<i class="fe fe-menu navbar-toggler-icon"></i>
				</button>
				<ul class="nav">
					<li class="nav-item">
						<a class="nav-link text-muted my-2" href="/doc" data-toggle="tooltip" title="开发文档">
							<i class="fe fe-file-text fe-16">
							</i>
						</a>
					</li>
					
					<li class="nav-item">
						<a class="nav-link text-muted my-2" href="javascript:modal('set');" data-toggle="tooltip" title="设置">
							<i class="fe fe-settings fe-16">
							</i>
						</a>
					</li>
					
					<li class="nav-item">
						<a class="nav-link text-muted my-2" href="javascript:;" id="modeSwitcher" data-mode="light" data-toggle="tooltip" title="开/关灯">
							<i class="fe fe-sun fe-16">
							</i>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link text-muted my-2" href="/admin/index" data-toggle="tooltip" title="应用列表">
							<i class="fe fe-grid fe-16"></i>
						</a>
					</li>
					
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle text-muted pr-0" href="#" id="admact" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<span class="avatar avatar-sm mt-2">
								<img src="/assets/avatars/face.jpg" class="avatar-img rounded-circle">
							</span>
						</a>
						<div class="dropdown-menu dropdown-menu-right" aria-labelledby="admact">
							<a class="dropdown-item" href="javascript:;" data-toggle="modal" data-target="#adm_cap">
								修改密码
							</a>
							<a class="dropdown-item" href="/admin/logout">
								退出登录
							</a>
						</div>
					</li>
				</ul>
			</nav>
			<aside class="sidebar-left border-right bg-white shadow" id="leftSidebar" data-simplebar>
				<a href="#" class="btn collapseSidebar toggle-btn d-lg-none text-muted ml-2 mt-3" data-toggle="toggle">
					<i class="fe fe-x">
						<span class="sr-only"></span>
					</i>
				</a>
				<nav class="vertnav navbar navbar-light">
					<div class="w-100 mb-2 d-flex">
						<a class="navbar-brand mx-auto mt-2 flex-fill text-center mb-0 avatar-sm" href="/admin/index">
							<img id="top_app_logo" src="/<?echo $this->app['app_logo'];?>" onerror="javascript:this.src='/assets/images/defaultapp.png';">
							<p id="top_app_name" class="item-text mb-0 mt-2 h6"><?=$this->app['app_name']?></p>
						</a>
					</div>
					<div class="w-100">
						<ul class="navbar-nav flex-fill w-100 mb-2">
							<li class="nav-item w-100">
								<a class="nav-link" href="home"  id="nav-home">
									<i class="fe fe-home fe-16"></i>
									<span class="ml-3 item-text">
										总览
									</span>
								</a>
							</li>
						</ul>
						<p class="text-muted nav-heading mt-2 mb-1">
							<span>
								USER
							</span>
						</p>
						<ul class="navbar-nav flex-fill w-100 mb-2">
							<li class="nav-item w-100">
								<a class="nav-link" href="user">
									<i class="fe fe-user fe-16"></i>
									<span class="ml-3 item-text">
										用户管理
									</span>
								</a>
							</li>
							<li class="nav-item dropdown">
								<a href="#kami" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
									<i class="fe fe-credit-card fe-16"></i>
									<span class="ml-3 item-text">
										卡密管理
									</span>
								</a>
								<ul class="collapse list-unstyled pl-4 w-100" id="kami">
									<li class="nav-item">
										<a class="nav-link pl-3" href="kami_list">
											<span class="ml-1 item-text">
												卡密列表
											</span>
										</a>
									</li>
									<li class="nav-item">
										<a class="nav-link pl-3" href="kami_group">
											<span class="ml-1 item-text">
												卡密分组
											</span>
										</a>
									</li>
									
								</ul>
							</li>
							<li class="nav-item w-100">
								<a class="nav-link" href="message">
									<i class="fe fe-twitch fe-16"></i>
									<span class="ml-3 item-text">
										留言互动
									</span>
								</a>
							</li>
						</ul>
						
						<p class="text-muted nav-heading mt-2 mb-1">
							<span>
								AGENT
							</span>
						</p>
						<ul class="navbar-nav flex-fill w-100 mb-2">
							<li class="nav-item dropdown">
								<a href="#agent" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
									<i class="fe fe-users fe-16"></i>
									<span class="ml-3 item-text">
										代理管理
									</span>
								</a>
								<ul class="collapse list-unstyled pl-4 w-100" id="agent">
									<li class="nav-item">
										<a class="nav-link pl-3" href="agent_list">
											<span class="ml-1 item-text">
												代理列表
											</span>
										</a>
									</li>
									<li class="nav-item">
										<a class="nav-link pl-3" href="agent_group">
											<span class="ml-1 item-text">
												代理分组
											</span>
										</a>
									</li>
									
								</ul>
							</li>
							<li class="nav-item w-100">
								<a class="nav-link" href="agent_cash">
									<i class="fe fe-pocket fe-16"></i>
									<span class="ml-3 item-text">
										提现管理
									</span>
								</a>
							</li>
						</ul>
						<p class="text-muted nav-heading mt-2 mb-1">
							<span>
								SHOP
							</span>
						</p>
						<ul class="navbar-nav flex-fill w-100 mb-2">
							<li class="nav-item w-100">
								<a class="nav-link" href="goods">
									<i class="fe fe-shopping-bag fe-16"></i>
									<span class="ml-3 item-text">
										商品管理
									</span>
								</a>
							</li>
							<li class="nav-item w-100">
								<a class="nav-link" href="fen">
									<i class="fe fe-stop-circle fe-16"></i>
									<span class="ml-3 item-text">
										积分事件
									</span>
								</a>
							</li>
							<li class="nav-item w-100">
								<a class="nav-link" href="order">
									<i class="fe fe-shopping-cart fe-16"></i>
									<span class="ml-3 item-text">
										订单管理
									</span>
								</a>
							</li>
						</ul>
						<p class="text-muted nav-heading mt-4 mb-1">
							<span>
								APP
							</span>
						</p>
						<ul class="navbar-nav flex-fill w-100 mb-2">
							<li class="nav-item w-100">
								<a class="nav-link" href="info">
									<i class="fe fe-box fe-16"></i>
									<span class="ml-3 item-text">
										应用信息
									</span>
								</a>
							</li>
							<li class="nav-item w-100">
								<a class="nav-link" href="ver">
									<i class="fe fe-git-commit fe-16"></i>
									<span class="ml-3 item-text">
										版本控制
									</span>
								</a>
							</li>
							
							<li class="nav-item w-100">
								<a class="nav-link" href="reglog">
									<i class="fe fe-user-check fe-16"></i>
									<span class="ml-3 item-text">
										注册登录
									</span>
								</a>
							</li>
							<li class="nav-item w-100">
								<a class="nav-link" href="award">
									<i class="fe fe-gift fe-16"></i>
									<span class="ml-3 item-text">
										奖励控制
									</span>
								</a>
							</li>
							
							<li class="nav-item w-100">
								<a class="nav-link" href="extend">
									<i class="fe fe-copy fe-16"></i>
									<span class="ml-3 item-text">
										扩展配置
									</span>
								</a>
							</li>
							<li class="nav-item w-100">
								<a class="nav-link" href="notice">
									<i class="fe fe-volume-2 fe-16"></i>
									<span class="ml-3 item-text">
										通知公告
									</span>
								</a>
							</li>
							<li class="nav-item w-100">
								<a class="nav-link" href="pay">
									<i class="fe fe-dollar-sign fe-16"></i>
									<span class="ml-3 item-text">
										支付控制
									</span>
								</a>
							</li>
							<li class="nav-item w-100">
								<a class="nav-link" href="send">
									<i class="fe fe-mail fe-16"></i>
									<span class="ml-3 item-text">
										发信控制
									</span>
								</a>
							</li>
						</ul>
						<p class="text-muted nav-heading mt-4 mb-1">
							<span>
								SYSTEM
							</span>
						</p>
						<ul class="navbar-nav flex-fill w-100 mb-2">
							<li class="nav-item w-100">
								<a class="nav-link" href="logs">
									<i class="fe fe-cpu fe-16"></i>
									<span class="ml-3 item-text">
										运行日志
									</span>
								</a>
							</li>
							<li class="nav-item w-100">
								<a class="nav-link" href="http://www.uverif.com/doc" target="_blank">
									<i class="fe fe-file-text fe-16"></i>
									<span class="ml-3 item-text">
										开发文档
									</span>
								</a>
							</li>
						</ul>
						<p class="text-muted nav-heading mt-4 mb-1">
							<span>
								EXTEND
							</span>
						</p>
						<ul class="navbar-nav flex-fill w-100 mb-2">
							<li class="nav-item w-100">
								<a class="nav-link" href="http://www.uverif.com/addon" target="_blank">
									<i class="fe fe-codesandbox fe-16"></i>
									<span class="ml-3 item-text">
										应用中心
									</span>
								</a>
							</li>
						</ul>
					</div>
					<div class="btn-box w-100 mt-4 mb-1">
						<a type="button" class="btn mb-2 btn-primary btn-lg btn-block" href="http://www.uverif.com/community" target="_blank">
							<i class="fe fe-link fe-12 mr-2"></i>
							<span class="small">
								前往交流社区
							</span>
						</a>
					</div>
				</nav>
			</aside>
			
			<main role="main" class="main-content">
				<div class="container-fluid" id="div_container">
					<div class="row justify-content-center">
						<div class="col-12">
							<div class="row align-items-center mb-2">
								<div class="col">
									<h2 class="h5 page-title">
										<?echo $this->pName;?>
									</h2>
								</div>
								<div class="col-auto">
									<form class="form-inline">
										<div class="form-group">
											<a type="button" class="btn btn-sm" href="javascript:location.reload();">
												<span class="fe fe-refresh-ccw fe-16 text-muted"></span>
											</a>
										</div>
									</form>
								</div>
							</div>