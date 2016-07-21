<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @登录
 * @author zhoushuai
 * @copyright(c) 2016-05-19
 * @version
 */
class Login extends MY_Controller{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('employee_model', 'employee');
		$this->load->library('form_validation');
		$this->lay='';
	}



	public function index()
	{
		if (isset($_SESSION['user']) && !empty($_SESSION['user']['uid'])){
			header('Location: '.$_SESSION['redirect_uri']);
		}
		$this->render();
	}

	public function wait(){
		if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
			header('Location: /login');
		}
		$this->render();
	}

	public function fail(){
		$this->render();
	}


	public function sms_login_verify(){
		$mobile = $this->input->post('mobile');
	    $reg    = '/^13[0-9]{9}$|14[0-9]{9}|15[0-9]{9}$|17[0-9]{9}$|18[0-9]{9}$/';
	    if(!preg_match($reg,$mobile)){
			$this->return_client(0,'请输入正确的手机号码！');
		}
		$_SESSION['code'] = mt_rand(111111, 999999);
		$data['code'] = $_SESSION['code'];
		$this->return_client(1,$data,'send successfuly');
	}

	/**
	 * @登录操作
	 */
	public function lgin(){
		self::_verifydata_login();
		self::_verify_sms();
		$data = array();
		$result = $this->employee->autoLogin(array('mobile'=>$this->input->post('mobile')));
		if($result){
			$data['redirect_uri']='/login/wait';
			$this->return_client(1,$data,'登录成功！');
		}
		$data['redirect_uri']='/login/fail';
		$this->return_client(0,$data,'帐号不存在，请联系管理员！');
	}

	//验证数据是否合法
	private  function _verifydata_login(){
		$config = array(
			array(
				'field' => 'mobile',
				'label' => '手机',
				'rules' => 'trim|required|integer|exact_length[11]',
			),
			array(
				'field' => 'code',
				'label' => '验证码',
				'rules' => 'trim|required|integer|exact_length[6]',
			),
		);
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() === FALSE) {
			$error = join(',' , $this->form_validation->error_array());
			$this->return_client(0,null,$error);
		}
	}

	//短信校验
	private function _verify_sms(){
		if(empty($_SESSION['code']) || $_SESSION['code']!=$this->input->post('code')){
			$this->return_client(0,null,'短信验证码错误！');
		}
		unset($_SESSION['code']);
	}


	public function auto(){
		if (isset($_SESSION['user']) && !empty($_SESSION['user']['uid'])){
			header('Location: '.$_SESSION['redirect_uri']);
		}
		$this->render();
	}

	/**
	 * 开发环境登陆
	 */
	private function devLogin($role){
		switch($role){
			case 0:
				$mobile='17761298110';//admin
				break;
			case 1:
				$mobile='18628394110';//salesman
				break;
			case 2:
				$mobile='18628017110';//assess
				break;
			case 3:
				$mobile='18980647110';//wind
				break;
			case 4:
				$mobile='13540192110';//assess
				break;
		}
		$result = $this->employee->autoLogin(array('mobile'=>$mobile));
		if($result){
			$url='/login/wait';
		}else{
			$url='/login/fail';
		}
		header('Location: '.$url);
	}

	public function lgon()
	{
		$role = $this->input->get('r');
		$this->devLogin($role);
	}


}
