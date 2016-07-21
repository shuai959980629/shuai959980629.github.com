<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @业务流程
 * @author zhoushuai
 * @category 20160518
 * @version
 */
class Task_flow_model extends Base_model
{
    public function __construct()
    {
        parent::__construct('check_log');
        $this->load->model('loan_model', 'loan');
        $this->load->model('employee_model', 'employee');
        $this->load->model('loan_item_model', 'loan_item');
        $this->load->model('wind_bill_model', 'wind_bill');
        $this->load->model('task_list_model', 'task_list');
        $this->load->model('evaluate_bill_model','evaluate');
    }


    //写入日志
    private function add($dta)
    {
        $data = array(
            'uid'       => $dta['uid'],//业务员uid
            'bizid'     => $dta['bizid'],//业务id
            'flag'      => $dta['flag'],//交易类型「mortgage：抵押，pledge：质押」
            'remark'    => $dta['remark']//业务操作备注
        );
        return parent::create($data);
    }

    /**
     */
    private function verif_loan_item($param){
        $where = array('id'=>$param['aid'],'is_del'=>1);
        $loanAssess = $this->loan_item->getWidgetRow($where);
        if(empty($loanAssess)){
            return false;
        }
        return $loanAssess;
    }

    /**
     * @业务流程。。
     * @param int aid 进件id
     * @param string type 类型
     * @param remark string 备注说明
     * @param realname string 用户名
     * @param loan_amount int 贷款金额
     * @return array;
     */
    public function flow($param){
        $title  = '';
        $con    = '';
        $next   = '';
        $aid    = $param['aid'];
        $type   = $param['type'];
        if(empty($aid)){
            return array('status'=>0,'error'=>'参数错误！');
        }
        //进件验证
        $loanItem = $this->verif_loan_item($param);
        if(!$loanItem){
            return array('status'=>0,'error'=>'没有进件！');
        }

        $uid  = $_SESSION['user']['uid'];
        $role = !empty($_SESSION['user']['roleName'])?$_SESSION['user']['roleName']:$_SESSION['user']['group_name'];
        $name = $_SESSION['user']['realname'];

        //业务扩展--车辆信息
        $extend     = json_decode($loanItem['extend'],true);
        $car_info   = !empty($extend['car_info'])?$extend['car_info']:array();

        switch($type){
            /**
             * @第一步：业务员
             */
            case 'ckl_salesmansbmit'://业务员提交进件
                $title = '业务进件';
                $con   = $car_info['modelName'].'估值：'.$car_info['eval_price'].'万';
                $next  = '等待评估师确认车型';
                break;
            /**
             * @第二步：评估师
             */
            case 'ckl_asessupdt'://评估师修改车辆信息
                $title = '修改车辆信息';
                $con   = $car_info['modelName'].'估值：'.$car_info['eval_price'].'万';
                $next  = '等待评估';
                break;
            case 'ckl_asessconfirm'://评估确认订单。等待调档
                $title	='确认车型';
                $con	='车牌号码：'.$loanItem['plate_no'];
                $next	='等待评估';
                break;
            case 'ckl_asessAllot'://评估师组长分配任务
                $title	='评估组长分配任务';
			    $con	='分配给评估师：'.$param['realname'];
			    $next	='等待评估';
                break;
            case 'ckl_assessbmit'://评估师已经评估,提交评估单-》风控
                $evaluate = $this->evaluate->get($loanItem['evaluate_id']);
                $evaluate_info = json_decode($evaluate['car_info'],true);
                $c2b_price  = $evaluate_info['assess']['eval_prices']['c2b_price'];
                $title	= '评估师已提交评估单';
			    $con	= '系统精确定价：'.$c2b_price.'万，评估师定价：'.$evaluate['car_price'].'万';
                $next	= '等待风控审核';
                break;
            /**
             * @第三步：风控
             */
            case 'ckl_windBackAssess'://风控打回进件
                $title	= '风控打回进件，需要重新评估';
                $con    = '打回说明：'.$param['remark'];
                $next	= '等待评估';
                break;
            case 'ckl_windsbmit'://风控同意放款
                $wind = $this->wind_bill->get($loanItem['wind_id']);
                $strAmount = '';
                if($wind['sug_money']<$wind['max_money']){
                    $strAmount = $wind['sug_money']."~".$wind['max_money'];
                }else{
                    $strAmount = $wind['sug_money'];
                }
                $title	= '风控已经审核同意放款';
			    $con	= '放款额度：'.$strAmount.'万';
			    $next	= '等待客户确认';
                break;
            case 'ckl_windeny'://风控拒绝放款
                $title	= '拒绝放款';
                $con	= '风控审核-拒绝放款：'.$param['remark'];
                $next	= '流程结束';
                break;
            /**
             * @第四步：业务员，等待客户确认，放款完成
             */
            case 'ckl_salesmanBackWind'://业务员打回进件给风控，重新审核
                $title	= '业务员打回进件，需要重新审核';
                $con    = '打回说明：'.$param['remark'];
                $next	= '等待风控给价';
                break;
            case 'ckl_salesmanSbmit':
                $title	= '客户已确认';
			    $con	= "贷款金额：{$param['loan_amount']}元";
			    $next	= '放款完成';
                break;
            case 'ckl_salesmanDelt'://取消业务(客户不满意)
                $title	= '取消业务(客户不满意)';
                $con	= $param['remark'];
                $next	= '流程结束';
                break;
        }
        $remark = array(
            'title'		=>$title,
            'con'		=>$con,
            'next'		=>$next,
            'manager'	=>array(
                'role'		=>$role,
                'name'		=>$name,
                'created'	=>date('Y-m-d H:i:s'),
            )
        );
        $dta2=array(
            'uid'   	=>$uid,
            'bizid' 	=>$loanItem['id'],//业务id
            'flag'  	=>$loanItem['deal_flag'],//交易类型「mortgage：抵押，pledge：质押」
            'remark'	=>json_encode($remark)
        );
        return $this->add($dta2);
    }
}