<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @首页
 * @author zhoushuai
 * @copyright(c) 2016-05-19
 * @version
 */
class Home extends MY_Controller{

	public function __construct()
	{
		parent::__construct();
		$this->lay='';
	}

	public function index()
	{
		$this->render();
	}
}
