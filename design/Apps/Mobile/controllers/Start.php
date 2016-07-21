<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @开始进件
 * @author zhoushuai
 * @copyright(c) 2016-05-19
 * @version
 */
class Start extends MY_Controller{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('employee_model', 'employee');
		$this->load->library('CarService');
	}

	public function index()
	{
		$city = array('zone'=>$_SESSION['user']['zone']);
		$data = $this->carservice->getProvince($city);
		$data['brand_list'] = $this->carservice->getCarBrandList();
		$where = array('uid'=>$_SESSION['user']['uid']);
		$employee = $this->employee->getEmployeeRow($where);
		$data['uid'] 	  = $employee['uid'];
		$data['realname'] = $employee['realname'];
		$data['region']   = $employee['region'];
		$data['office']   = $employee['office'];
		$data['stores']   = $employee['stores'];
		$this->render($data);
	}
}
