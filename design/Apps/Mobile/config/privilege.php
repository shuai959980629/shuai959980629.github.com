<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| 权限配置文件
| -------------------------------------------------------------------
| 根据业务逻辑，将关联紧密的功能进行分组
| 每个分组需要一个名称作为唯一标识,后接的数组表示，
| 此分组包含的具体功能，使用Controller/Action的方式来
| 表示可以调用的控制器及其中的某个方法，Controller/*
| 表示可以调用此控制器中的所有方法
 */

$config['purview']  = array(
    'home'          => array('home/*'),
    'login'         => array('login/*'),
    'start'         => array('start/*'),
    'user'          => array('user/*'),
    'car'           => array('car/*'),
    'order'         => array('order/*'),
    'assess'        => array('assess/*'),
    'wind'          => array('wind/*'),
    'pact'          => array('pact/*'),
    'datashow'      => array('datashow/*'),
    'yw_list'       => array('yw_list/*'),
);
