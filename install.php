<?php
/**
 * 系统安装入口文件
 * @link      http://www.Uephp.com
 * @copyright Copyright (c) 2022-2032 
 * @author    阳光男孩
 * @version   1.0.0
**/
$_GET['s'] = 'index/install';//操作页面 
define('U_INSTALL',TRUE); // 开启安装
define('U_DEBUG',TRUE); //开启运行报错
include 'Ue/Ue.php';