<?php if(!defined('U_VER')) exit;?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>出错了 (:</title>
<style type="text/css">
*{margin:0; padding:0; font-size:15px;}
body{background:#FFFFFF; font-family:"微软雅黑"; color:#323233; padding:25px;}
.pg-title{font-size:32px; line-height:1.2em; padding-bottom:30px; border-bottom: 1px dashed #D1D1D1;}
.pg-title span{font-size:50px;}
.pg-content{line-height:2.5em; margin-top:28px;}
.pg-content span{color:#666666;}
.pg-copy-right{margin-top:28px; text-align:center;}
.pg-copy-right sup{font-size:10px;}
a{color:#3688ff;}
.pg-wrap{width:800px; margin:0 auto; margin-top:150px; background:#F8F8F8; padding:38px; border-radius:3px; border-top:5px solid #004eff;}
</style>
</head>
<body>
<?php $errors = json_decode(systemErrors,true);?>
<div class="pg-wrap">
	<div class="pg-title">
		<span style="padding-right:20px;">:(</span>出错了!
	</div>
	<div class="pg-content">
		<span>错误遍码 : </span><?=$errors['code'];?><br />
		<span>错误信息 : </span><?=$errors['msg'];?><br />
		<span>相关文件 : </span><?=$errors['file'];?><br />
		<span>错误位置 : </span><?=$errors['line'];?>行<br />
	</div>
	<div class="pg-copy-right">
		<a href="http://www.Uephp.com" target="_blank">power by Uephp.com</a>
	</div>
</div>
</body>
</html>