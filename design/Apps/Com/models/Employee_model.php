<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @员工
 * @author zhoushuai
 * @category 20160515
 * @version
 */
class Employee_model extends Base_model 
{
	/**
	 * 主键ID
	 * @var string
	 */
	public     $pk    = 'uid';
	//表
	public     $table;
	//区域配置
	public $region = array("成都","重庆","遂宁","武汉","昆明","贵阳","南充","乐山","泸州","眉山","自贡","达州");
	/**
	 * @access public----------门店配置
	 * @return void
	 */
	public $stores = array(
		'1'=>'总部',
		'2'=>'成都',
		'3'=>'重庆',
		'4'=>'武汉',
		'5'=>'遂宁',
		'6'=>'南充',
		'7'=>'乐山',
		'8'=>'泸州',
		'9'=>'眉山',
		'10'=>'自贡',
		'11'=>'达州',
		'12'=>'温江',
		'13'=>'大邑',
		'14'=>'合川',
		'15'=>'广安',
		'16'=>'内江',
		'17'=>'西安',
		'18'=>'巴中',
		'19'=>'绵阳',
		'20'=>'资阳',
	);
	/**
	 * @城市----------通过门店找城市
	 */
	public $zone = array(
		'1'=>'成都',
		'2'=>'成都',
		'3'=>'重庆',
		'4'=>'武汉',
		'5'=>'遂宁',
		'6'=>'南充',
		'7'=>'乐山',
		'8'=>'泸州',
		'9'=>'眉山',
		'10'=>'自贡',
		'11'=>'达州',
		'12'=>'温江',
		'13'=>'大邑',
		'14'=>'合川',
		'15'=>'广安',
		'16'=>'内江',
		'17'=>'西安',
		'18'=>'巴中',
		'19'=>'绵阳',
		'20'=>'资阳',
	);
	//区域英文缩写-------门店合同编号开头
	public $prefix = array(
		"1"=>"ZB",
		"2"=>"CD",
		"3"=>"CQ",
		"4"=>"WH",
		"5"=>"SN",
		"6"=>"NC",
		"7"=>"LS",
		"8"=>"LZ",
		"9"=>"MS",
		"10"=>"ZG",
		"11"=>"DZ",
		"12"=>"WJ",
		"13"=>"DY",
		'14'=>'HC',
		'15'=>'GA',
		'16'=>'NJ',
		'17'=>'XA',
		'18'=>'BZ',
		'19'=>'MY',
		'20'=>'ZY',
	);

	//员工角色配置--------员工角色配置
	public $office = array(
		'salesman'	=> '业务员',
		'assess'	=> '评估师',
		'admin'		=> '超级管理员',
		'wind'		=> '风控',
		'driver'	=> '司机',
	);

	public function __construct()
    {
        parent::__construct();
		 $this->table = 'employee';
    }

	public function getEmployeeRow($where){
		$employer = parent::getWidgetRow($where);
		if(!empty($employer)){
			$region = explode(',',$employer['region']);
			$stores = $employer['stores'] ;
			$employer['regionIndex'] = $region;
			$employer['role']	= $employer['office'];
			$employer['office'] = $this->office[$employer['office']];
			$employer['stores'] = $this->stores[$employer['stores']];
			$employer['zone']	= $this->zone[$stores];//所在城市
			return $employer;
		}else{
			return array();
		}
	}


	/**
	 * @用户登陆-
	 * @param mobile  手机号码
	 * @return boolean
	 */
	public function autoLogin($param){
		$where = array(
			'mobile'	=>$param['mobile'],
			'status'	=>'allow'
		);
		$user = parent::getWidgetRow($where);
		if(!empty($user)){
			$result = array(
				'uid' 			=> $user['uid'],
				'office' 		=> $user['office'],
				'role' 			=> 'member',
				'superior' 		=> $user['superior'],//所属上级
				'roleName'		=> $this->office[$user['office']],
				'region'		=> $user['region'],
				'regionIndex'	=> explode(',',$user['region']),
				'realname'		=> $user['realname'],//姓名
				'mobile'		=> $user['mobile'],//手机号码
				'stores' 		=> $user['stores'],//所属门店
				'zone' 			=> $this->zone[$user['stores']],//所在城市
				'storesName'	=> $this->stores[$user['stores']],//所属门店
			);
			$_SESSION['user'] = $result;
			return true;
		}
		return false;
	}










}