<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @叮叮任务分配
 * @author zhoushuai
 * @category 20160420
 * @version
 */
class Task_allot_model extends Base_model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('loan_item_model', 'loan_item');
        $this->load->model('employee_model',  'employee');
        $this->load->model('task_list_model', 'task_list');
    }


    private function verif_loan_item($param){
        $where = array('id'=>$param['aid'],'is_del'=>1);
        $loanAssess = $this->loan_item->getWidgetRow($where);
        if(empty($loanAssess)){
            return false;
        }
        return $loanAssess;
    }

    /**
     * @评估师组长分配任务给其他评估师
     * @param int aid 进件id
     * @param int uid 评估师uid
     * @return array;
     */
    public function allotTaskAssess($param){
        $aid    = $param['aid'];
        $uid    = $param['uid'];
        if(empty($aid)||empty($uid)){
            return array('status'=>0,'error'=>'参数错误！');
        }

        $assess = $this->employee->get($uid);
        if(empty($assess)){
            return array('status'=>0,'error'=>'找不到评估师！');
        }

        //进件验证
        $loanAssess = $this->verif_loan_item($param);
        if(!$loanAssess){
            return array('status'=>0,'error'=>'没有进件！');
        }
        //正在评估
        if($loanAssess['status']!='appraise'){
            return array('status'=>0,'error'=>'任务发送失败！');
        }


        //写进任务列表
        $where = array(
            'aid'   =>$aid,
            'type'  =>'appraise',
            'uid'   =>$_SESSION['user']['uid'],
        );
        $taskList = $this->task_list->getWidgetRow($where);

        $item = array(
            'aid'       => $aid,
            'uid'       => $uid,
        );
        $result = $this->task_list->save($item,$taskList['id']);
        if(!$result){
            return array('status'=>0,'error'=>'分配任务失败！');
        }

        //修改评估信息--uid
        if(!empty($loanAssess['evaluate_bill'])){
            $evalu = array('uid'=>$uid);
            $this->load->model('evaluate_bill_model','evaluate_bill');
            $this->evaluate_bill->save($evalu,$loanAssess['evaluate_bill']);
        }

        //业务扩展--车辆信息
        $extend     = json_decode($loanAssess['extend'],true);
        $car_info   = $extend['car_info'];

        //推送任务 TODO
        return array('status'=>1);
    }







}