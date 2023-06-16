<?php include_once 'header.php';?>
	<div class="row" id="count">
		<div class="col-md-4 mb-4">
			<div class="card shadow">
				<div class="card-body">
					<div class="row align-items-center">
						<div class="col">
							<span class="h2 mb-0" id="user_count">
								0
							</span>
							<p class="small text-muted mb-0">
								用户数
							</p>
							<span class="badge badge-pill badge-success">
								签到：<span id="user_diary_count">0</span>
							</span>
						</div>
						<div class="col-auto">
							<span class="fe fe-32 fe-user text-muted mb-0">
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-4 mb-4">
			<div class="card shadow">
				<div class="card-body">
					<div class="row align-items-center">
						<div class="col">
							<span class="h2 mb-0" id="kami_count">
								0
							</span>
							<p class="small text-muted mb-0">
								卡密数
							</p>
							<span class="badge badge-pill badge-danger">
								已使用：<span id="kami_use_count">0</span>
							</span>
						</div>
						<div class="col-auto">
							<span class="fe fe-32 fe-credit-card text-muted mb-0">
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-4 mb-4">
			<div class="card shadow">
				<div class="card-body">
					<div class="row align-items-center">
						<div class="col">
							<span class="h2 mb-0" id="message_wait_count">
								0
							</span>
							<p class="small text-muted mb-0">
								待回复留言
							</p>
							<span class="badge badge-pill badge-warning">
								总留言：<span id="message_count">0</span>
							</span>
						</div>
						<div class="col-auto">
							<span class="fe fe-32 fe-twitch text-muted mb-0">
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card shadow mb-4" id="charts">
		<div class="card-body">
			<div class="row align-items-center my-4">
				<div class="col-md-4">
					<div class="mx-4">
						<strong class="mb-0 text-uppercase text-muted">
							今日收益
						</strong>
						<br />
						<h3>
							¥ <span id="order_day_money">0.00</span>
						</h3>
						<p class="text-muted">
							君子爱财，取之有道，视之有度，用之有节
						</p>
					</div>
					<div class="row align-items-center">
						<div class="col-6">
							<div class="p-4">
								<p class="small text-uppercase text-muted mb-0">
									总收益
								</p>
								<span class="h2 mb-0">
									¥ <span id="order_total_money">0.00</span>
								</span>
							</div>
						</div>
						<div class="col-6">
							<div class="p-4">
								<p class="small text-uppercase text-muted mb-0">
									订单总数
								</p>
								<span class="h2 mb-0" id="order_all_os">
									0
								</span>
							</div>
						</div>
					</div>
					<div class="row align-items-center">
						<div class="col-6">
							<div class="p-4">
								<p class="small text-uppercase text-muted mb-0">
									支付宝订单数
								</p>
								<span class="h2 mb-0" id="order_ali_os">
									0
								</span>
							</div>
						</div>
						<div class="col-6">
							<div class="p-4">
								<p class="small text-uppercase text-muted mb-0">
									微信支订单数
								</p>
								<span class="h2 mb-0" id="order_wx_os">
									0
								</span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-8">
					<div class="mr-4">
						<div id="revenue-chart" class="apex-charts mt-3" data-colors="#727cf5,#0acf97">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row" id="log">
		<div class="col-md-12">
			<div class="card shadow eq-card">
				<div class="card-header">
					<strong class="card-title">
						操作日志
					</strong>
					<a class="float-right small text-muted" href="#!">
						更多
					</a>
				</div>
				<div class="card-body">
					<div class="p-4" id="logs_state">
						<div class="text-center mt-5 mb-5">
							<div class="avatar avatar-lg">
								<img src="/assets/images/default/nolog.svg" class="avatar-img" style="width: 100px;">
							</div>
							<div class="card-text">
								<strong class="card-title my-0">
									暂无日志
								</strong>
								<p class="small text-muted mb-5">
									There's a little problem
								</p>
							</div>
						</div>
					</div>
					<div class="table-responsive mb-0">
						<table class="table table-hover table-borderless table-striped" id="table_logs" hidden>
							<thead>
								<tr>
									<th style="width: 50px;">
										ID
									</th>
									<th>
										用户
									</th>
									<th>
										操作类型
									</th>
									<th>
										时间/IP
									</th>
									<th style="width: 50px;">
										状态
									</th>
								</tr>
							</thead>
							<tbody id="tbody_logs">
								
							</tbody>
						</table>
					</div>	
				</div>
			</div>
		</div>
		
	</div>
	<div class="row" id="server">
		<div class="col-md-12">
			<div class="card shadow">
				<div class="card-header">
					<strong class="card-title">
						服务器信息
					</strong>
				</div>
				<div class="card-body">
					<p>当前版本：<span id="sys_ver">2.0</span>
						<span id="ver_data">
							<a id="ver_url" href="javascript:void(0);" onclick="check_ver()"><span class="badge badge-pill badge-success" id="ver">最新</span></a>
						</span>	
					</p>
					<p>当前域名：<span id="domain">www.user.com</span></p>
					<p>PHP版本：<span id="php_ver">0.0</span></p>
					<p>MySQL版本：<span id="mysql_ver">0.0</span></p>
					<p>服务器环境：<span id="serverapp">null</span></p>
					<p>授权到期时间：<span id="ukey_exp">9999-99-99 99:99</span></p>
					<p>服务器空间允许上传最大文件：<span id="uploadfile_maxsize">2M</span></p>
				</div>
			</div>
		</div>
	</div>
	<script src="/assets/js/apexcharts.min.js"></script>
	<script>
		function init(e,t={}){return $.ajax({cache:!1,type:"POST",data:e,dataType:"json",success:function(e){200==e.code?(census(e.data.census),loading(!1),$("#sys_ver").html(e.data.census.server_info.sys_ver),$("#domain").html(e.data.census.server_info.domain),$("#php_ver").html(e.data.census.server_info.php_ver),$("#mysql_ver").html(e.data.census.server_info.mysql_ver),$("#serverapp").html(e.data.census.server_info.serverapp),$("#uploadfile_maxsize").html(e.data.census.server_info.uploadfile_maxsize)):loading(!1)},error:function(e,t,s){loading(!1)}}),!1}function census(e){$("#user_count").html(e.user.count),$("#user_diary_count").html(e.user.diary_count),$("#kami_count").html(e.kami.count),$("#kami_use_count").html(e.kami.use_count),$("#message_wait_count").html(e.message.wait_count),$("#message_count").html(e.message.count),$("#order_day_money").html(e.order.day_money),$("#order_total_money").html(e.order.total_money),$("#order_all_os").html(e.order.all_os),$("#order_ali_os").html(e.order.ali_os),$("#order_wx_os").html(e.order.all_os-e.order.ali_os),order_census(e.order.census),logs(e.logs)}function logs(e){e.length>0?($("#table_logs").attr("hidden",!1),$("#logs_state").attr("hidden",!0)):($("#logs_state").attr("hidden",!1),$("#table_logs").attr("hidden",!0)),$("#tbody_logs").empty();for(var t=0;t<e.length;t++){var s="<tr><td>"+e[t].id+"</td><td>"+e[t].user+'<br /><span class="small text-muted">'+e[t].person+'</span></td><th scope="col">'+e[t].type+'<br /><span class="small text-muted">操作类型</span></th><td>'+e[t].time+'<br /><span class="small text-muted">'+e[t].ip+'</span></td><td><span class="dot dot-lg bg-'+("y"==e[t].state?"success":"danger")+'"></span></td></tr>';$("#tbody_logs").append(s)}}function order_census(e){var t=e.date,s={chart:{height:350,type:"line",zoom:{enabled:!1},toolbar:{show:!1},dropShadow:{enabled:!0,opacity:.2,blur:7,left:-7,top:7}},dataLabels:{enabled:!1},stroke:{curve:"smooth",width:4},series:[{name:"订单数",data:e.all},{name:"成功数",data:e.success}],colors:["#727cf5","#0acf97","#fa5c7c","#ffbc00"],zoom:{enabled:!1},legend:{show:!1},xaxis:{type:"string",categories:t,tooltip:{enabled:!1},axisBorder:{show:!1}},yaxis:{labels:{formatter:function(e){return e+"笔"},offsetX:-15}}};new ApexCharts(document.querySelector("#revenue-chart"),s).render()}
	</script>
<?php include_once 'footer.php';?>