<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 贷款管理 MODEL
 * @package	MODEL
 * @author	zhoushuai
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Loan_model extends Base_model
{
	/**
	 * 主键ID
	 *
	 * @var string
	 */
	public     $pk    = 'id';

	/**
	 * Class constructor
	 *
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct('loan');
	}
	/*
     * 返回金额发送类型	 金额操作类型[1收到返点金额，2支付返点金额，3收到利息，4收到停车费，5收到流量费，6收到违约金，7收到本金金额],8收到押金金额，9支付押金金额
    */
	public $money_type = array(
		"13"=>"放款",
		"3"=>"收到利息",
		"1"=>"收到业务手续费",
		"2"=>"支付业务手续费",
		"4"=>"收到停车费",
		'17'=>"评估费",
		'18'=>"收到公司手续费",
		"8"=>"收到押金金额",
		"5"=>"收到流量费",
		"6"=>"收到违约金",
		"10"=>"收到保证金", //不押车
		"11"=>"支出保证金",  //不押车
		"12"=>"收到垫资手续费",
		"14"=>"收到逾期罚息",
		"7"=>"收到本金金额",
		"9"=>"支付押金金额",
		'15'=>"还款（垫资还本）",
		'16'=>"放款（垫资未放）",
		'19'=>"收到资产处置金额",
		'20'=>"收到垫资补差",
		'21'=>"收到挂账金额",
		'22'=>"支出挂账金额",
		'23'=>"加班费",
		'24'=>"GPS安装费",
		'25'=>"权证费",
		'26'=>"过户费",
		'27'=>"考察费",
	);
	/*
	*贷款类型 产品类型-------------1,押车（质押）0,不押车（抵押）
	*/
	public  $loan_car_type = array(
		"1"=>"押车",
		"0"=>"不押车",
	);

	//借款交易类型「1:pledge：质押,0:mortgage：抵押」
	public $guard_type=array(
		"pledge"=>"1",
		"mortgage"=>"0",
	);

	/*
	*合同类型 0,默认,1续借,2追加额度
	*/
	public $pact_type = array(
		"0"=>"默认",
		"1"=>"续借",
		"2"=>"追加",
		"3"=>"老客户",
	);
	/*
	*还款方式
	*/
	public  $repayment_type = array(
		"maturity"=>"按月付息，到期还本",
		"equal"=>"等本等息",
	);
	//'maturity，按月付息，到期还本','equal，等额本息'
	public $repayment = array(
		"pledge"=>"maturity",
		"mortgage"=>"equal",
	);
	//放款状态配置
	public $loan_cash_stas = array(
		"wait_first_cash"=>"放头款",
		"wait_fees_cash"=>"收到客户费用",
		"wait_car_in"=>"等待接车",
		"wait_last_cash"=>"放尾款",
		"done"=>"放款完成",
	);

	/*
	*贷款类型
	*/
	public $loan_status_type=array(
		'draft'=>'待签合同',
		'wait'=>'已签待审',
		'loaning'=>'放款中',
		'pending'=>'还款中',
		'done'=>'已完成',
		'advance'=>'资产处置',
	);
	/**
	 * 获取总数
	 *
	 * @param  array $where	//查询条件数组
	 * @access public
	 * @return number
	 */
	public function getTotal($where)
	{
		$this->scope($where, 'loan_time');
		unset($where['scope']);
		$this->db->where($where);
		return $this->db->count_all_results($this->table);
	}
	//通过uid 数组获真实姓名
	public function get_name_by_uidarr($data=array()){
		$this->db->select('id,customer,employee,ln');
		$this->db->where_in("{$this->table}.id", $data);
		$query = $this->db->get($this->table);

		if ($query->num_rows() > 0) {
			//返回一维数组
			$returns = $query->result_array();
			$redata = '';
			foreach($returns as $k => $v){
				$redata[$v['id']]=array('customer'=>$v['customer'],'employee'=>$v['employee'],'ln'=>$v['ln']);
			}
			return $redata;
		}
		else {
			return array();
		}
	}
	/**
	 * 统计字段
	 *
	 * @param  array $where	//查询条件数组
	 * @access public
	 * @return number
	 */
	public function sum($field, $where)
	{
		if(!$field){
			return NULL;
		}
		if($where){
			$this->scope($where, 'loan_time');
			unset($where['scope']);
			$this->db->where($where);
		}
		foreach($field as $v){
			$this->db->select_sum($v);
		}

		$query = $this->db->get($this->table);
		return $query->result_array();
	}
	/**
	 * 分页查询
	 *
	 * @param  array 	$where	//查询条件
	 * @param  integer	$limit	//查询条数
	 * @param  integer 	$offset	//偏移量
	 * @access public
	 * @return array
	 */
	public function search($where = array(), $limit = 20, $offset = 0)
	{
		$this->db->order_by("{$this->table}.{$this->pk} desc");
		$this->db->limit($limit, $offset);
		$this->scope($where, 'loan_time');
		unset($where['scope']);
		if(!empty($where['loan_period'])){
			if($where['loan_period']>=4){
				$this->db->where("{$this->table}.loan_period >=",4);
			}else{
				$this->db->where("{$this->table}.loan_period =",$where['loan_period']);
			}
			unset($where['loan_period']);
		}
		$this->db->where($where);
		$query = $this->db->get($this->table);

		return $query->result_array();
	}

	/**
	 * SQL语句片段组合 scope
	 *
	 * @param  array $where	//条件
	 * @param  string $key	//字段名
	 * @access public
	 * @return void
	 */
	public function scope($where, $key)
	{
		if (isset($where['scope'])) {
			if(!empty($where['scope'][0])){
				$this->db->where("{$this->table}.{$key} >=", strtotime($where['scope'][0]));
			}
			if(!empty($where['scope'][1])){
				$this->db->where("{$this->table}.{$key} <=", strtotime($where['scope'][1]));
			}
		}
	}
	/*
    *生成合同编号  贷款时间：$loan_time
    */
	public function create_ln($loan_time,$stores = 1){
		if(empty($loan_time)){
			return NULL;
		}
		//载入员工model
		$this->load->model('employee_model', 'employee');
		//合同编号
		$where_t['custom'] = "FROM_UNIXTIME(loan_time,'%Y-%m-%d') ='{$loan_time}'";
		$where_t['stores'] = $stores;
		$cnt = $this->getWidgetTotal($where_t);
		$cnt++;
		$prefix = $this->employee->prefix[$stores];
		$ln = $prefix.date('Ymd',strtotime($loan_time)).'-'.$cnt;//合同编号
		return $ln;
	}
	/**
	 * 删除数据 逻辑删除
	 *
	 * @param  integer $id	//自增ID
	 * @access public
	 * @return boolean
	 */
	public function del($id)
	{
		return $this->modify($id, array('status' => 0));
	}

	/**
	 * 获取单条数据
	 *
	 * @param  integer $id
	 * @access public
	 * @return array
	 */
	public function getRow($id)
	{
		$this->db->where('id', $id);
		$result = $this->db->get($this->table);

		return $result->row_array();
	}

	/**
	 * 插入数据
	 *
	 * @param  array $data	//插入数组
	 * @access public
	 * @return boolean
	 */
	public function createRow($data)
	{
		return $this->create($data);
	}

	/**
	 * 修改数据
	 *
	 * @param  integer 	$id		//ID
	 * @param  array 	$data	//插入数组
	 * @access public
	 * @return boolean
	 */
	public function modify($id, $data)
	{
		return $this->update($id, $data);
	}

	//更新所有进行中贷款状态
	public function checkStatus()
	{
		$data = $this->getWidgetRows(array('loan_status'=>'pending','status'=>1));
		if ($data) {
			foreach ($data as $key => $value) {
				$this->upStatus($value['id']);
			}
		}
	}
	//更新放款中状态
	public function checkLoaning(){
		$data = $this->getWidgetRows(array('loan_status'=>'loaning','status'=>1));
		if ($data) {
			foreach ($data as $key => $value) {
				$this->uploanStatus($value['id']);
			}
		}
	}
	//通过流水更新放款中 状态为还款中
	public function uploanStatus($id = 0){
		$this->load->model('interest_model'		, 'interest');
		$this->load->model('Repay_plan_model', 'repay_plan');
		$this->load->model('Online_model', 'online');
		$this->load->model('Loan_item_model', 'loan_item');
		//放款总额
		//$interest_fangkuan = $this->interest->sum(array('money'),array('lid'=>$id,'status'=>1,'type'=>13));
		//$interest_fangkuan_mat = $this->interest->getWidgetRow(array("sum"=>'money',"type"=>16,"lid"=>$id,'status'=>1));
		//新增垫资情况
		$interest_fangkuan_al = $this->interest->sum(array('money'),array('lid'=>$id,'status'=>1,'type'=>13));
		$interest_fangkuan_mat = $this->interest->sum(array('money'),array('lid'=>$id,'status'=>1,'type'=>16));
		$sum_interest = (int)$interest_fangkuan_al[0]['money']+(int)$interest_fangkuan_mat[0]['money'];
		$loan =  $this->get($id);
		if(!empty($loan) && $loan['loan_amount']==$sum_interest && $loan['loan_status']=='loaning'){//更新状态bug
			$result_save = $this->save(array('loan_status'=>'pending','al_loan_money'=>(int)$interest_fangkuan_al[0]['money'],'mat_loan_money'=>(int)$interest_fangkuan_mat[0]['money']),$id);
			if ($result_save !== false) {
				if(!empty($loan['aid'])){
					$this->loan_item->save(array('status'=>'done'),$loan['aid']);//更新新方法------------进件状态修正
				}
				$this->online->sendOnline($id);
				$this->repay_plan->addPlan($id);
			}
		}

	}
	//更新单条贷款状态
	public function upStatus($id = 0)
	{
		//利息总额
		$interest_lixi = $this->interest->sum(array('money'),array('lid'=>$id,'status'=>1,'type'=>3));
		//本金总额
		$interest_benjin = $this->interest->sum(array('money'),array('lid'=>$id,'status'=>1,'type'=>7));
		//新增垫资还本
		$interest_benjin_mat = $this->interest->sum(array('money'),array('lid'=>$id,'status'=>1,'type'=>15));

		$loan = $this->get($id);
		if ($loan) {
			//通过还款计划更新贷款状态
			/*------获取还款计划------*/
			$this->load->model('Repay_plan_model','repay_plan');
			$result_plan_sum = $this->repay_plan->getWidgetTotal(array("status"=>'done','lid'=>$id));
			$period_total = $loan['loan_period']+1;
			if(!empty($loan['loan_period']) && $period_total==$result_plan_sum){
				$this->save(array('loan_status'=>'done','repay_amount'=>(int)$interest_benjin[0]['money'],'mat_repay_amount'=>(int)$interest_benjin_mat[0]['money']),$id);
			}
		}
	}


	public function updte($data,$where){
		$this->db->where($where)->update($this->table, $data);
	}

	/**
	 * @统计。。。。。。。。。。。。。
	 */
	public function cntAdmin(&$data){
		//今天业务总额
		$today = date('Y-m-d');
		$where = array('uid'=>$_SESSION['user']['uid']);
		$employer = $this->employee->getEmployeeRow($where);
		$where_in = array('loan_status'=>array('pending','done','advance'));
		if($employer['role']=='manager' && !empty($employer['regionIndex'])){
			$where_in = array('loan_status'=>array('pending','done','advance'),"stores"=>$employer['regionIndex']);
		}
		$daywhere = array(
			"in"=>$where_in,
			"FROM_UNIXTIME(loan_time,'%Y-%m-%d')" =>"{$today}",
			'status'=>1
		);
		$data['doneToday'] = $this->getWidgetTotal($daywhere);
		$total = $this->total($daywhere);
		$data['todaySum'] = $total['sum'];
		//本月销售总额
		$curMonth = date('Y-m');
		$curwhere = array(
			"in"=>$where_in,
			"FROM_UNIXTIME(loan_time,'%Y-%m')" =>"{$curMonth}",
			'status'=>1
		);
		$data['doneCurMonth'] = $this->getWidgetTotal($curwhere);
		//本月天地区排名：
		$where = array(
			"in"=>$where_in,
			"FROM_UNIXTIME(loan_time,'%Y-%m')" =>"{$curMonth}",
			'status'=>1
		);

		$total = $this->total($where);
		$data['curMonth'] 	= $total['sum'];

		$where['order']		= array('sum'=>'DESC');
		//地区
		$where['group']		= array('stores');
		$where['cols']		= array("SUM(loan_amount) as sum,stores");
		$data['zone']		= $this->zoneRank($where,1,1);
		//个人排名
		$where['group']		= array('employee');
		$where['cols']		= array("SUM(loan_amount) as sum,stores,employee");
		$data['person']		= $this->rank($where,1,1);
		return $data;
	}

	public function total($where){
		$this->db->select('SUM(loan_amount) as sum');
		$this->widgetWhere($where);
		$query = $this->db->get($this->table);
		return $query->row_array();
	}




	/**
	 * @当天
	 * 个人第一名
	 */
	public function rank($where,$limit = 10,$page = 1){
		$offset = $limit * ($page - 1);
		//载入员工model
		$this->load->model('employee_model', 'employee');
		$this->widgetWhere($where);
		if($limit){
			$this->db->limit($limit, $offset);
		}
		$query = $this->db->get($this->table);
		if($limit==1){
			$result = $query->row_array();
			if(!empty($result)){
				$result['storesName']=$this->employee->stores[$result['stores']];
				$result['uid'] = $result['employee'];
				$employee = $this->employee->get($result['employee']);
				$result['employee'] = $employee['realname'];
			}
		}else{
			$result = $query->result_array();
			if(!empty($result)){
				foreach($result as $key=>&$list){
					$result[$key]['storesName']=$this->employee->stores[$list['stores']];
					$result[$key]['uid'] = $list['employee'];
					$employee = $this->employee->get($list['employee']);
					$result[$key]['employee'] = $employee['realname'];
				}
			}
		}
		return $result;
	}



	/**
	 * @地区排名
	 */
	public function zoneRank($where,$limit = 10,$page = 1){
		//载入员工model
		$this->load->model('employee_model', 'employee');
		$offset = $limit * ($page - 1);
		$this->widgetWhere($where);
		if($limit){
			$this->db->limit($limit, $offset);
		}
		$query = $this->db->get($this->table);
		if($limit==1){
			$result = $query->row_array();
			if(!empty($result)){
				$result['storesName']=$this->employee->stores[$result['stores']];
			}
		}else{
			$result = $query->result_array();
			if(!empty($result)){
				foreach($result as $key=>&$list){
					$result[$key]['storesName']=$this->employee->stores[$list['stores']];
				}
			}
		}
		return $result;
	}

	/**
	 * 每月每天统计
	 */
	public function perMonth($where,$limit = 10,$page = 1){
		$offset = $limit * ($page - 1);
		$count  = $this->getWidgetTotal($where);
		$result = $this->getWidgetPages($where,$limit,$offset);
		$return = array();
		foreach($result as $Key=>$list){
			$m = date('Y/m月',strtotime($list['sdate']));
			$return[$m]['list'][] = $list;
		}
		foreach($return as $key=>$list){
			$sum=0;
			foreach($list['list'] as $k=>$val){
				$sum+=$val['total'];
			}
			$return[$key]['sum'] = $sum;
		}
		return $return;
	}
	/**
	 *不押车 终审生成合同----
	 **/
	public function createloan($data=array()){
		$this->load->model('customer_model', 'customer');
		if(empty($data['aid'])){
			return false;
		}
		//获取进件表信息
		$assess = $this->loan_item->get($data['aid']);
		if(empty($assess['customer'])){
			return false;
		}
		//撸出客户资料
		$customer = $this->customer->get($assess['customer']);
		//生成合同
		$data_sv = array(
			'ln'=>$this->create_ln(date("Y-m-d",$data['loan_time']),$assess['stores']),
			'aid'=>$assess['id'],
			'loan_amount'=>$data['loan_amount'],
			'rate'=>$data['rate'],
			'guard_type'=>0,//不押车
			'loan_time'=>$data['loan_time'],
			'loan_period'=>$data['loan_period'],
			'signing_date'=>strtotime("+{$data['loan_period']} month",$data['loan_time'])-86400,
			'repayment'=>$data['repayment'],
			'pro_type'=>$data['pro_type'],//产品类型，车宝宝，普通不押车
			'employee'=>$assess['employee'],//员工
			'customer'=>$assess['customer'],//借款人
			'loan_status'=>'draft',
			'car_vin'=>$assess['car_vin'],
			'cost_con'=>json_encode(array('printpact'=>0)),
			'plate_no'=>(!empty($data['plate_no']))?$data['plate_no']:'',//车牌号
			'mobile'=>(!empty($customer['mobile']))?$customer['mobile']:'',//联系手机号码
			'address'=>(!empty($customer['address']))?$customer['address']:'',//通信地址
			'card_no'=>(!empty($customer['card_no']))?$customer['card_no']:'',//银行账号
			'bank_name'=>(!empty($customer['bank_name']))?$customer['bank_name']:'',//银行名称
			'card_name'=>(!empty($customer['card_name']))?$customer['card_name']:'',//开户人
			'status'=>1,//状态[0为删除]
			'stores'=>$assess['stores'],//贷款所属门店
			'deposit'=>0,
		);
		//生成合同
		$result = $this->save($data_sv);
		if(empty($result)){
			log_message('error', var_export($data_sv,true),'tmp_byc_create_ln');
			return false;
		}else{
			//提示成功
			admin_log("生成合同成功:{$result},{$_SESSION['user']['username']},{$_SESSION['user']['realname']}");
			return $result;
		}
	}

}
?>