<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @消息推送逻辑封装
 * @author ZHOUSHUAI
 * @category 20160518
 * @version
 */
class Push_who_model extends Base_model
{

    public function __construct()
    {
		parent::__construct();
        $this->load->model('employee_model', 'employee');
		$this->load->model('Loan_item_model', 'loan_item');
    }

	/**
	 * @业务员 业务进件推送给谁；
	 * @param uid 业务员id
	 * @return array
	 */
	public function getSalesman($param){
		$salesman 	= $this->employee->get($param['uid']);
		return $salesman;
	}



	/**
	 * @评估师
	 * @param stores 门店ID
	 * @param type all:全部 leader:组长 crew:组员
	 * @return array
	 */
	public function getAppraiser($param=array()){
		if(empty($param['stores'])){
			return NULL;	
		}
		if(empty($param['type'])){
			$param['type'] = 'leader';
		}
		//获取评估师组长-----------老策略
		$where = array('stores'=>$param['stores'],'office'=>'assess','status'=>'allow');
		$res_row = array();
		switch($param['type']){
			case 'leader' : 
				$where['superior'] = 0;
				$res_row = $this->employee->getWidgetRow($where);
				break;
			case 'all' :
				$res_row = $this->employee->getWidgetRows($where);			
				break;
			case 'crew':
				$where['custom'] = "superior !=0";
				$res_row = $this->employee->getWidgetRows($where);			
				break;
		}
		return $res_row;
	}

	/**
	 * 风控
	 * @param aid 进件id
	 * @param stores 门店ID
	 * @param type all:全部 leader:组长 crew:组员
	 * @return array
	 */
	public function getWinder($param=array()){
		$loanAssess = $this->loan_item->get($param['aid']);
		//deal_flag  借款交易类型「mortgage：抵押，pledge：质押」
		if($loanAssess['deal_flag']=='mortgage'){
			return null;
		}
		$param['stores']=!empty($param['stores'])?$param['stores']:1;
		if(empty($param['type'])){
			$data['type'] = 'leader';
		}
		$where = array('stores'=>$param['stores'],'office'=>'wind','status'=>'allow');
		$res_row = array();
		switch($param['type']){
			case 'leader' :
				$where['superior'] = 0;
				$res_row = $this->employee->getWidgetRow($where);
				break;
			case 'all' :
				$res_row = $this->employee->getWidgetRows($where);
				break;
			case 'crew':
				$where['custom'] = "superior !=0";
				$res_row = $this->employee->getWidgetRows($where);
				break;
		}
		return $res_row;
	}



}
