<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| 角色配置文件
| -------------------------------------------------------------------
*/
$config['role']    = array(
	'guest' => array(                         //角色的唯一标签符
		'modules'       => array('login','home'),      //可使用的功能列表
		'title'         => '游客',              //角色名称（显示用）
		'description'   => '',                  //备注
	),
	'member' => array(                         //角色的唯一标签符
		'modules'       => array('*'),         //可使用的功能列表
		'title'         => '会员',              //角色名称（显示用）
		'description'   => '',                  //备注
	)
);