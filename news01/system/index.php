<?php
header("Content-type:text/html;charset=utf-8");
!defined('ROOT_PATH') && define('ROOT_PATH', str_replace('\\', '/', dirname(__FILE__)));
require ROOT_PATH . '/core/Config.php';  //引入配置文件
require ROOT_PATH . '/core/Controller.class.php'; //引入控制器类文件
require ROOT_PATH . '/helpers/unit_helper.php'; //工具
require ROOT_PATH . '/config/database.php';//数据库配置
$control = new Controller();
$control->Run();
