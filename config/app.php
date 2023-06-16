<?php 
//app基础配置
return [
	'WEB_URL'=>'http://www.user.com/',
	'APP_NAME'=>'U用户管理验证系统',//系统名称
	'APP_PAGE_ENUMS'=>15,//页面数据条数
	'APP_ADM_LOG'=>'on',//记录管理员日志，on=开启，off=关闭
	'APP_USER_LOG'=>'on',//记录用户日志，on=开启，off=关闭
	'API_RUN_COST'=>'on',//运行成本计算，on=开启，off=关闭
	'API_WHITE'=>'pay',//接口白名单
	'API_OUT_TYPE'=>'json',//输出格式可以是json/xml
	'USER_UPFILE_SIZE'=>2,//上传文件最大值M为单位，默认2M
	'USER_TOKENKEY'=>'435a151761d7136993fdecbc23da898d',//用户TOKEN密钥
	'APP_VERSION'=>'2.0',//系统版本
];