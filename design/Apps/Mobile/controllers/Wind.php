<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @风控审核
 * @author zhoushuai
 * @copyright(c) 2016-05-19
 * @version
 */
class Wind extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('loan_item_model','loan_item');
        $this->load->model('Evaluate_bill_model','evaluate_bill');
        $this->load->model('employee_model', 'employee');
        $this->load->model('wind_bill_model', 'wind_bill');
        $this->load->model('Task_list_model', 'task_list');
    }


    /**
     * @风控
     */
    public function index(){
        $data = array();
        $id   = $this->input->get('id');
        $loan = $this->loan_item->getWidgetRow(array('id'=>$id,'is_del'=>1));
        $evaluate = $this->evaluate_bill->get($loan['evaluate_id']);
        if(empty($loan)||empty($evaluate)){
            redirect('/order');
        }
        $data['deal_flag'] = $loan['deal_flag'];//工单类型
        $created           = $loan['created'];
        $employee          = $loan['employee'];
        $plate_no          = $loan['plate_no'];
        $extend            = json_decode($loan['extend'],true);
        $data['car']            = $extend['car_info'];
        $data['car']['mile']    = $extend['car_api']['mile'];
        $data['car']['regDate'] = $extend['car_api']['regDate'];
        //业务员
        $re = $this->employee->getWidgetRow(array('uid'=>$employee));
        $data['salesman'] = $re;
        $data['salesman']['plate_no'] = $plate_no;
        $data['salesman']['location'] = $data['car']['location'];
        $data['salesman']['kehu_amount'] = $data['car']['kehu_amount'];
        $data['salesman']['comment'] = $data['car']['comment'];
        $data['salesman']['time'] = date('Y-m-d',$created);
        //评估师
        $ping = $this->employee->getWidgetRow(array('uid'=>$evaluate['uid']));
        $data['assess']['person'] = $ping;
        $data['assess']['car_price'] = $evaluate['car_price'];
        $data['assess']['explain'] = $evaluate['explain'];
        $data['assess']['created'] = $evaluate['created'];

        //获取已经审核的信息
        if(!empty($loan['wind_id'])){
            $data['wind']=$this->wind_bill->get($loan['wind_id']);
        }
        if(!$data){
            redirect('/order');
        }
        $data['id'] = $id;
        $data['token']  = $_SESSION['token'] =md5(time());
        $data['extend'] = json_decode($evaluate['car_info'],true);
        $data['extend'] = $data['extend']['assess'];
        $data['extend']['gear_type'] = $data['extend']['model_info']['gear_type'];  //分配变速箱
        $data['extend']['liter'] = $data['extend']['model_info']['liter'];  //分配排量
        $data['extend']['plate_no'] = $plate_no;
        $this->render($data);
    }


    /**
     * @打回
     */
    public function back(){
        $id = $this->input->post('id');
        $reason = $this->input->post('reason');
        if(empty($reason)){
            $this->return_client(0,'', '请输入打回理由！');
        }
        $res = $this->task_list->myTask(array('aid'=>$id));
        if(!$res['status']){
            $this->return_client(0,null,$res['error']);
        }
        $this->load->model('Task_adapt_model','task');
        $result = $this->task->taskBackToAssess(array('aid'=>$id,'reason'=>$reason));

        //业务流程
        $this->load->model('task_flow_model','task_flow');
        $param = array(
            'aid'       => $id,
            'remark'    => $reason,
            'type'      => 'ckl_windBackAssess'
        );
        $res = $this->task_flow->flow($param);
        if($result['status']){
            $this->return_client(1, array('redirect_uri' => '/user'), '进件已打回，请稍后！');
        }else{
            $this->return_client(0,null, '操作失败！');
        }
    }

    /**
     * @拒绝放款
     */
    public function refuz(){
        $id     = $this->input->post('id');
        $reason = $this->input->post('reason');
        if(empty($reason)){
            $this->return_client(0,'', '请输入拒绝放款理由！');
        }
        $res = $this->task_list->myTask(array('aid'=>$id));
        if(!$res['status']){
            $this->return_client(0,null,$res['error']);
        }
        $data = array();
        $data['explain'] = $reason;//拒绝放贷说明
        $data['status']  = 'deny';//deny，拒绝放款,
        $data['aid']     = $id;
        $data['uid']     = $_SESSION['user']['uid'];
        $data['created'] = time();
        $wid  = $this->wind_bill->add($data);
        if($wid) {
            //拒绝放款
            $loan_item = array('wind_id'=>$wid,'status'=>'deny');
            $result = $this->loan_item->save($loan_item,$id);
            if($result){
                //业务流程
                $this->load->model('task_flow_model','task_flow');
                $param = array(
                    'aid'       => $id,
                    'remark'    => $reason,
                    'type'      => 'ckl_windeny'
                );
                $res = $this->task_flow->flow($param);
                //完成自己的任务
                $param = array('aid'=>$id,'type'=>'risktrol');
                $rest  = $this->task_list->done($param);

                //推送通知。 TODO

                $this->return_client(1, array('redirect_uri' => '/user'), '拒绝放款，该信息已推送至业务员！');
            }
        }else{
            $this->return_client(0, array('redirect_uri' => '/order'), '提交失败！');
        }
    }


    /**
     * @同意放款
     */
    public function save(){
        $id           = $this->input->post('id');
        $res = $this->task_list->myTask(array('aid'=>$id));
        if(!$res['status']){
            $this->return_client(0,null,$res['error']);
        }
        $min_amount   = $this->input->post('min_amount');
        $max_amount   = $this->input->post('max_amount');
        $remark       = $this->input->post('remark');
        $data['sug_money'] = $min_amount;
        $data['max_money'] = $max_amount;
        $data['explain']   = $remark; //审核说明
        $data['status']    = 'allow';//allow同意放款
        $data['uid']       = $_SESSION['user']['uid'];
        $data['aid']       = $id;
        $data['created']   = time();
        //保存审核信息
        $wid = $this->wind_bill->add($data);
        if($wid){
            //同意放款。subpact:准备提交合同
            $loan_item = array('wind_id'=>$wid,'status'=>'subpact');
            $result = $this->loan_item->save($loan_item,$id);
            if($result) {
                $this->load->model('Task_adapt_model','task');
                $data = $this->task->taskSalesman(array('aid'=>$id));
                if($data['status']){
                    //业务流程
                    $this->load->model('task_flow_model','task_flow');
                    $param = array(
                        'aid'       => $id,
                        'type'      => 'ckl_windsbmit'
                    );
                    $res = $this->task_flow->flow($param);
                    $this->return_client(1, array('redirect_uri' => '/user'), '同意放款，该信息已推送至业务员！');
                }
            }
        }
        $this->return_client(0, array('redirect_uri' => '/order'), '审核失败！');
    }



}