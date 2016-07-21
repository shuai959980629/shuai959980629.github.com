<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @客户确认 ,合同管理
 * @author zhoushuai
 * @copyright(c) 2016-05-21
 * @version
 */
class Pact_model extends Base_model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('loan_model', 'loan');
        $this->load->model('task_flow_model','task_flow');
        $this->load->model('Loan_item_model', 'loan_item');
    }


    /*合同提交方法*/
    public function Generate_contract($arr){
        /*查找进件数据*/
        $where = array('id'=>$arr['id'],'is_del'=>1);
        $loan = $this->loan_item->getWidgetRow($where);
        if(empty($loan)) return array('status'=>0,'message'=>'未找到该进件！请刷新后再试！');
        /*查找风控数据*/
        $wind = $this->wind_bill->getWidgetRow(array('aid'=>$arr['id'],'cols'=>array('max_money')));
        if($arr['loan_amount'] > $wind['max_money']) return array('status'=>0,'message'=>'放款金额不能大于风控最大金额！');
        /*数据组装*/
        $loan['extend'] = json_decode($loan['extend'],true);        //转换扩展内容
        $loan_time      = strtotime($arr['loan_time']);//贷款时间
        $loan_amount    = $arr['loan_amount']*10000;//贷款金额
        $signing_date   = strtotime("+{$arr['loan_period']} month",$loan_time)-86400;//签约还款时间
        $ln = $this->loan->create_ln($arr['loan_time'],$loan['stores']);//合同编号
        $vin      = strtoupper($loan['car_vin']);
        $plate_no = strtoupper($loan['plate_no']);
        $data = array(
            'ln'            =>$ln,//合同编号
            'aid'           =>$loan['id'],//进件ID
            'loan_time'     =>$loan_time,//贷款时间
            'signing_date'  =>$signing_date,//签约还款时间
            'loan_period'   =>$arr['loan_period'],//贷款周期（天/周/月）
            'employee'      =>$loan['employee'],//员工
            'loan_amount'   =>$loan_amount,//贷款金额
            'rate'          =>$arr['rate'],//利率
            'loan_status'   =>'done',//done已完成
            'car_vin'       =>$vin,//车架号
            'plate_no'      =>$plate_no,//车牌号
            'status'        =>1,//状态[0为删除]
            'stores'        =>$loan['stores'],//贷款所属门店
        );
        /*生成合同，插入数据*/
        $where = array('aid'=>$loan['id']);
        $pactList = $this->loan->getWidgetRow($where);
        if(!empty($pactList)){
            $id = $pactList['id'];
        }else{
            $id = 0;
        }
        /*插入到loan表*/
        $save = $this->loan->save($data,$id);
        if ($save === false){
            return array('status'=>0,'message'=>'数据提交失败！');
        }else{
            /*调第一个接口*/
            $one = $this->task_adapt->taskPrinter(array('aid'=>$arr['id']));
            if($one['status'] == 0) return array('status'=>0,'message'=>'1111');
            /*调第二个接口*/
            $param = array(
                'aid'            => $arr['id'],
                'loan_amount'    => $loan_amount,
                'type'           => 'ckl_salesmanSbmit'
            );
            $this->task_flow->flow($param);
            /*成功后返回数据*/
            return array('status'=>1,'message'=>'提交成功！');
        }

    }






}

