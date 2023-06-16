<?php
/**
 * 系统后台入口文件
 * @link      http://www.Uephp.com
 * @copyright Copyright (c) 2022-2032 
 * @author    阳光男孩
 * @version   1.0.0
**/
$_GET['s'] = 'admin/'.(isset($_GET['p']) && !empty($_GET['p']) ? addslashes($_GET['p']) : 'index');//操作页面
define('U_ADMIN',TRUE); //开启入口
define('U_DEBUG',TRUE); //开启运行报错
define('U_SESSION_START', true);
include 'Ue/Ue.php';