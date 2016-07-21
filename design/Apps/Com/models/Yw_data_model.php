<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 业务列表统计,进件列表，业务列表
 * @package	MODEL
 * @author	zhoushuai
 */
class Yw_data_model extends Base_model
{
	private $limit = 10;
    private $offset = 0;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Loan_model', 'loan');
        $this->load->model('Employee_model', 'employee');
        $this->load->model('Loan_item_model','loan_item');
        $this->load->model('Wind_bill_model','wind_bill');
        $this->load->model('Evaluate_bill_model','evaluate_bill');
        $this->load->model('Task_list_model','task_list');
    }
    /**
     * [get_urldetail 获取角色详细内容链接]
     * 'subpact','risktrol','storage','getcar','appraise'
     * @return [type] [description]
     **/
    public function get_urldetail($task_type = ''){
    	$url = '';
    	switch ($task_type) {
    		case 'appraise':
    			$url ='/assess/index?id=';
    			break;
    		case 'risktrol':
    			$url ='/wind/index?id=';
    			break;
    		case 'subpact':
    			$url ='/pact/index?id=';
    			break;
			default:
				$url = '/order/detail?id=';
				break;
    	}
    	return $url;
    }
	//获取进件列表数据
	/*
	*$param = array('employee'=>'业务员ID','page'=>'分页数量','page'=>'分页数量','status'=>'状态类型'（pending,done）)
	*/
	public function getAslist($param = array()){
		$page = 1;
		if(empty($param['employee'])){
			return NULL;	
		}
		if(!empty($param['page'])){
			$page = $param['page'];	
		}
		$where  = array(
			'employee'=>$param['employee'],
			'is_del'=>1,
		);
		if(!empty($param['status']) && $param['status']=='pending'){
			$where['status!='] = 'done';	
		}//进行中---------
		if(!empty($param['status']) && $param['status']=='done'){
			$where['status'] = 'done';	
		}//已完成-----
		$where['order'] = array('created'=>'desc');
		$count = $this->loan_item->getWidgetTotal($where);
		$offset = ($page - 1) * $this->limit;//分页偏移量
		$num   = ceil($count/$this->limit);
        $res_data = $this->loan_item->getWidgetPages($where,$this->limit,$offset);
		if(empty($res_data)){
			return NULL;	//判断查询不到数据，直接返回NULL;
		}else{
			foreach ($res_data as &$v) {
				$v['aid'] = $v['id'];	
				$v['model'] = json_decode($v['extend'])->car_info->modelName;//车型
				$v['show_last_price'] = '系统毛估：'.json_decode($v['extend'])->car_info->eval_price;//粗略估值价格;
				//$v['price'] = json_decode($v['extend'])->car_info->price;//裸车价格
				$v['car_logo'] = json_decode($v['extend'])->car_info->car_logo_url;//图片
				$v['status_show'] = $this->loan_item->status[$v['status']];//状态
				$v['created'] = date("Y-m-d H-i-s",$v['created']);
				/*查找门店姓名*/
				$v['store'] = $this->employee->stores[$v['stores']];//门店
				$employee = $this->employee->getWidgetRow(array('uid'=>$v['employee'],'cols'=>array('realname')));//业务员姓名
				$v['realname'] = $employee['realname'];
				//判断评估师给价
				if(!empty($v['evaluate_id'])){
					$res_evaluate = $this->	evaluate_bill->getWidgetRow(array('id'=>$v['evaluate_id'],'cols'=>array('car_price')));
					if(!empty($res_evaluate['car_price']) && $res_evaluate['car_price']>0){
						$v['show_last_price'] = '评估师估价：'.$res_evaluate['car_price'];	
					}
				}//评估师给价显示----------
				if(!empty($v['wind_id'])){
					$res_wind = $this->	wind_bill->getWidgetRow(array('id'=>$v['wind_id'],'cols'=>array('sug_money','max_money')));
					if(!empty($res_wind['sug_money']) || !empty($res_wind['max_money'])){
						$v['show_last_price'] = '风控定价：'.$res_wind['sug_money'].'-'.$res_wind['max_money'];
					}
				}//-----------风控给价显示
				//显示放款金额
				$res_loan = $this->loan->getWidgetRow(array('aid'=>$v['id'],'status'=>1,'cols'=>array('loan_amount')));
				if(!empty($res_loan)){
						$v['show_last_price'] = '贷款金额：'.$res_loan['loan_amount']/10000;
				}
				$v['icon'] = '';
				if($v['deal_flag']=='pledge'){
					$v['icon']='<i class="icon-zhi">质</i>';
				}else{
					$v['icon']='<i class="icon-di">抵</i>';
				}
				//详情链接
				$v['url_detail'] = '/order/detail?id='.$v['aid'];
				//通过状态显示颜色
				switch($v['status']){//'done','subpact','risktrol','deny','appraise'
					case 'appraise':
						$v['color'] = 'orage';
						break;
					case 'risktrol':
						$v['color'] = 'purple';
						break;
					case 'subpact':
						$v['color'] = 'blue';
						break;
					case 'deny':
						$v['color'] = 'red';
						break;
					case 'done':
						$v['color'] = 'gray';							
					break;
				}
			}	
		}
        /*判断是否加载更多*/
        if($count > $this->limit) $data['cnt'] = ceil($count/$this->limit);
        $data['data'] = $res_data;
        $data['number'] = $count;
        $data['num'] = $num;
		return $data;
	}
	//获取业务列表数据
	/*
	*$param = array('uid'=>'任务ID','page'=>'分页数量','page'=>'分页数量','type'=>'任务类型array','status'=>'待处理，已完成'（wait,done）)
	*/
	public function getYwlist($param = array()){
		$page = 1;
		if(empty($param['uid'])){
			return NULL;	
		}
		if(!empty($param['page'])){
			$page = $param['page'];	
		}
		$where  = array(
			'uid'=>$param['uid'],
		);
		if(!empty($param['status'])){
			$where['status'] = $param['status'];	
		}
		if(!empty($param['type'])){
			$where['in']['type'] = $param['type'];	
		}
		$where['order'] = array('created'=>'desc');
		$count = $this->task_list->getWidgetTotal($where);
		$offset = ($page - 1) * $this->limit;//分页偏移量
		$num   = ceil($count/$this->limit);
        $res_data = $this->task_list->getWidgetPages($where,$this->limit,$offset);	
		if(empty($res_data)){
			return NULL;	
		}else{
			foreach ($res_data as &$v) {
				$res_item = $this->loan_item->get($v['aid']);//获取进件详细;
				if(!empty($res_item)){
					$v['model'] = json_decode($res_item['extend'])->car_info->modelName;//车型
					$v['show_last_price'] = '系统毛估：'.json_decode($res_item['extend'])->car_info->eval_price;//粗略估值价格;
					//$v['price'] = json_decode($res_item['extend'])->car_info->price;//裸车价格
					$v['car_logo'] = json_decode($res_item['extend'])->car_info->car_logo_url;//图片
					$v['status_show'] = $this->loan_item->status[$res_item['status']];//状态
					$v['created'] = date("Y-m-d H-i-s",$res_item['created']);
					$v['modify_time'] = $res_item['modify_time'];
					/*查找门店姓名*/
					$v['store'] = $this->employee->stores[$res_item['stores']];//门店
					$employee = $this->employee->getWidgetRow(array('uid'=>$res_item['employee'],'cols'=>array('realname')));//业务员姓名
					$v['realname'] = $employee['realname'];	
					//判断评估师给价
					if(!empty($res_item['evaluate_id'])){
						$res_evaluate = $this->	evaluate_bill->getWidgetRow(array('id'=>$res_item['evaluate_id'],'cols'=>array('car_price')));
						if(!empty($res_evaluate['car_price']) && $res_evaluate['car_price']>0){
							$v['show_last_price'] = '评估师估价：'.$res_evaluate['car_price'];	
						}
					}//评估师给价显示----------
					if(!empty($res_item['wind_id'])){
						$res_wind = $this->	wind_bill->getWidgetRow(array('id'=>$res_item['wind_id'],'cols'=>array('sug_money','max_money')));
						if(!empty($res_wind['sug_money']) || !empty($res_wind['max_money'])){
							$v['show_last_price'] = '风控定价：'.$res_wind['sug_money'].'-'.$res_wind['max_money'];
						}
					}//-----------风控给价显示
					//显示放款金额
					$res_loan = $this->loan->getWidgetRow(array('aid'=>$res_item['id'],'status'=>1,'cols'=>array('loan_amount')));
					if(!empty($res_loan)){
							$v['show_last_price'] = '贷款金额：'.$res_loan['loan_amount']/10000;
					}
					$v['icon'] = '';
					if($res_item['deal_flag']=='pledge'){
						$v['icon']='<i class="icon-zhi">质</i>';
					}else{
						$v['icon']='<i class="icon-di">抵</i>';
					}

					$v['url_detail'] = '/order/detail?id='.$v['aid'];
					if($v['status']=='wait'){
						$v['url_detail'] = self::get_urldetail($v['type']).$v['aid'];
					}
					//通过状态显示颜色
					switch($res_item['status']){//'done','subpact','risktrol','deny','appraise'
						case 'appraise':
							$v['color'] = 'orage';
							break;
						case 'risktrol':
							$v['color'] = 'purple';
							break;
						case 'subpact':
							$v['color'] = 'blue';
							break;
						case 'deny':
							$v['color'] = 'red';
							break;
						case 'done':
							$v['color'] = 'gray';							
						break;
					}
				}
			}	
		}	
		/*判断是否加载更多*/
        if($count > $this->limit) $data['cnt'] = ceil($count/$this->limit);
        $data['data'] = $res_data;
        $data['number'] = $count;
         $data['num'] = $num;
		return $data;	
	}

}
