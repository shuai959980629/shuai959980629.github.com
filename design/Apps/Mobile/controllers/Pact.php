<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @业务员提交合同
 * @author zhoushuai
 * @copyright(c) 2016-05-19
 * @version
 */
class Pact extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('employee_model', 'employee');
        $this->load->model('loan_model', 'loan');
        $this->load->model('evaluate_bill_model', 'evaluate_bill');
        $this->load->model('loan_item_model', 'loan_item');
        $this->load->model('wind_bill_model', 'wind_bill');
        $this->load->model('task_adapt_model', 'task_adapt');
        $this->load->model('Task_list_model', 'task_list');
        $this->load->model('Pact_model', 'pact');
        $this->load->library('form_validation');
    }


    public function index(){
        $id = $this->input->get('id');
        /*查询该进件是不是到了打合同的时候*/
        $data['item'] = $this->loan_item->getWidgetRow(array('id'=>$id,'is_del'=>1));
        if(empty($data['item'])) redirect('/order');
        $data['item']['extend'] = json_decode($data['item']['extend'],true);            //扩展类容转换
        /*查询评估师评估的信息*/
        $data['assess'] = $this->evaluate_bill->getWidgetRow(array('aid'=>$id));
        $data['assess']['car_info'] = json_decode($data['assess']['car_info'],true);            //扩展类容转换
        $data['assess']['person'] = $this->employee->getWidgetRow(array('uid'=>$data['assess']['uid']));
        /*查询风控的信息*/
        $data['wind'] = $this->wind_bill->getWidgetRow(array('aid'=>$id));
        $data['wind']['person'] = $this->employee->getWidgetRow(array('uid'=>$data['wind']['uid']));
        /*车辆信息*/
        $data['car'] = $data['item']['extend']['car_info'];
        $data['car']['mile'] = $data['item']['extend']['car_api']['mile'];
        $data['id'] = $id;
        /*查找是否存在改合同*/
        $data['hetong'] = $this->loan->getWidgetRow(array('aid'=>$id));
        /*加载视图*/
        $this->render($data);
    }

    /*取消业务*/
    public function delte(){
        //判断是否能操作该任务
        $id = $this->input->post('id');
        $comment = $this->input->post("comment");
        if(empty($comment)){
            $this->return_client(0,'', '请输入取消理由！');
        }
        $res = $this->task_list->myTask(array('aid'=>$id));
        if(!$res['status']){
            $this->return_client(0,null,$res['error']);
        }
        $loanItem = $this->loan_item->get($id);
        $extend   = json_decode($loanItem['extend'],true);
        $extend['del_reason'] = $comment;
        $extend = json_encode($extend);
        if($this->loan_item->save(array('is_del'=>0,'extend'=>$extend),$id)){
            //完成自己的任务[subpact:  正在提交合同]
            $param = array('aid'=>$id,'type'=>'subpact');
            $rest  = $this->task_list->done($param);
            //业务流程
            $this->load->model('task_flow_model','task_flow');
            $param = array(
                'aid'       => $id,
                'remark'    => $comment,
                'type'      => 'ckl_salesmanDelt'
            );
            $res = $this->task_flow->flow($param);
            $this->return_client(1, array('redirect_uri'=>'/order'), '取消业务,操作成功！');
        }else{
            $this->return_client(0,'', '操作失败！');
        }
    }

    public function add_loan(){
        $arr = $this->input->post();                            //获得传递过来的参数
        $id = $arr['id'];                                       //获得进件ID
        /*调接口验证*/
        $res = $this->task_list->myTask(array('aid'=>$id));
        if(!$res['status']) $this->return_client(0,null,$res['error']);
        self::_verifydata_creat();
        /*进入model方法里*/
        $result = $this->pact->Generate_contract($arr);
        $this->return_client($result['status'],'',$result['message']);
    }

    private function _verifydata_creat(){
        $config = array(
            array(
                'field' => 'loan_amount',
                'label' => '放款金额',
                'rules' => 'trim|required|numeric',
            ),
            array(
                'field' => 'rate',
                'label' => '贷款利率',
                'rules' => 'trim|required|numeric',
            ),
            array(
                'field' => 'loan_period',
                'label' => '贷款期限',
                'rules' => 'trim|required|integer',
            ),
            array(
                'field' => 'loan_time',
                'label' => '贷款时间',
                'rules' => 'trim|required',
            )
        );
        $this->form_validation->set_rules($config);
        if ($this->form_validation->run() === FALSE) {
            $error = join(',' , $this->form_validation->error_array());
            $this->return_client(0,$this->form_validation->error_array(),'验证错误');
        }
    }

    /*风控推送*/
    public function wind_push(){
        $id = $this->input->post('id');
        $comment = $this->input->post("comment");
        if(empty($comment)){
            $this->return_client(0,'', '请输入打回理由！');
        }
        $res = $this->task_list->myTask(array('aid'=>$id));
        if(!$res['status']){
            $this->return_client(0,null,$res['error']);
        }
        $rel = $this->task_adapt->taskBackToWind(array('aid'=>$id,'comment'=>$comment));
        if($rel['status'] == 1){
            //业务流程
            $this->load->model('task_flow_model','task_flow');
            $param = array(
                'aid'       => $id,
                'remark'    => $comment,
                'type'      => 'ckl_salesmanBackWind'
            );
            $res = $this->task_flow->flow($param);
            $this->return_client(1,array('redirect_uri'=>'/order'),'已发给风控，请等待！');
        }else{
            $this->return_client(0,'',$rel['error']);
        }
    }






}