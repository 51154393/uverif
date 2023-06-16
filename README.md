# uverif
U验证网络用户管理系统

#### 介绍
网络验证用户管理系统

#### 软件架构
此软件采用Uephp框架开发，简单易上手。轻量快速


#### 安装教程

1.  下载文件，将文件上传至服务器，然后进行解压
2.  解压后设置伪静态规则：

```
location ~* (Ue|app|config)/{
	return 403;
}
location / {
	if (!-e $request_filename){
		rewrite  ^(.*)$  /index.php?s=$1  last;   break;
	}
}
```

3.  访问网站进入安装程序

#### 使用说明

1.  打赏地址：https://user.uverif.com/
2.  交流群：791336849

