<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @任务
 * @author zhoushuai
 * @category 20160520
 * @version
 */
class Task_adapt_model extends Base_model
{

    private $taskHostUrl;

    //借款交易类型「1:pledge：质押,0:mortgage：抵押」
    private $guard_type=array(
        "0"=>"mortgage",
        "1"=>"pledge"
    );

    public function __construct()
    {
        parent::__construct();
        $this->init();
        $this->load->model('loan_model', 'loan');
        $this->load->model('loan_item_model', 'loan_item');
        $this->load->model('push_who_model',  'push_who');
        $this->load->model('task_list_model', 'task_list');
    }

    private function init(){
        $common =$this->config->item('common');
        $this->taskHostUrl = $common['taskHostUrl'];
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
     * 业务员给评估师组长推送评估任务
     * @param int aid 进件id
     * @return array;
     */
    public function taskAssessLeader($param){
        $aid    = $param['aid'];
        if(empty($aid)){
            return array('status'=>0,'error'=>'参数错误！');
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
        //获取评估师组长
        $who = array(
            'stores' =>$loanAssess['stores'],
            'type'   =>'leader'
        );
        $leader = $this->push_who->getAppraiser($who);
        if(empty($leader)){
            return array('status'=>0,'error'=>'没有找到评估师组长，任务发送失败！');
        }
        //写进任务列表
        $item = array(
            'aid'       => $aid,
            'uid'       => $leader['uid'],
            'type'      => 'appraise',
            'remark'    => ''
        );
        $result = $this->task_list->add($item);
        if(!$result){
            return array('status'=>0,'error'=>'添加任务失败！');
        }

        //业务扩展--车辆信息
        $extend     = json_decode($loanAssess['extend'],true);
        $car_info   = !empty($extend['car_info'])?$extend['car_info']:array();

        //推送任务  ..未完待续 TODO

        return array('status'=>1);
    }



    /**
     * 评估师给风控推送任务
     * @param int aid 进件id
     * @return array;
     */
    public function taskWind($param){
        $aid    = $param['aid'];
        if(empty($aid)){
            return array('status'=>0,'error'=>'参数错误！');
        }

        //进件验证
        $loanAssess = $this->verif_loan_item($param);
        if(!$loanAssess){
            return array('status'=>0,'error'=>'没有进件！');
        }

        //评估信息
        $this->load->model('evaluate_bill_model', 'evaluate');
        $where = array('aid'=>$aid,'status'=>'done');
        $evaluate = $this->evaluate->getWidgetRow($where);
        if(empty($evaluate)){
            return array('status'=>1);
        }


        //获取风控
        $who = array(
            'stores' =>1,//总部
            'aid'    =>$param['aid'],
            'type'   =>'leader'
        );
        $leader = $this->push_who->getWinder($who);
        if(empty($leader)){
            return array('status'=>0,'error'=>'没有找到风控，任务发送失败！');
        }

        //写进任务列表
        $item = array(
            'aid'       => $aid,
            'uid'       => $leader['uid'],
            'type'      => 'risktrol',
            'remark'    => ''
        );
        $result = $this->task_list->add($item);
        if(!$result){
            return array('status'=>0,'error'=>'添加任务失败！');
        }

        //修改进件状态。【risktrol：正在风控给价】
        $data = array('status'=>'risktrol');
        $this->loan_item->save($data,$aid);

        //业务进件--车辆信息
        $extend     = json_decode($loanAssess['extend'],true);
        $car_info   = $extend['car_info'];

        //推送任务 TODO

        return array('status'=>1);
    }


    /**
     * 风控打回进件。评估师重新评估
     * @param int aid 进件id
     * @param string reason 打回说明
     * @return array;
     */
    public function taskBackToAssess($param){
        $aid    = $param['aid'];
        $reason = $param['reason'];
        if(empty($aid)){
            return array('status'=>0,'error'=>'参数错误！');
        }
        //进件验证
        $loanAssess = $this->verif_loan_item($param);
        if(!$loanAssess){
            return array('status'=>0,'error'=>'没有进件！');
        }

        $evaluate_id = $loanAssess['evaluate_id'];//评估id
        $this->load->model('evaluate_bill_model','evaluate_bill');

        //修改评估状态为草稿
        $eval = array('status'=>'draft');
        $this->evaluate_bill->save($eval,$evaluate_id);

        //查询评估获取评估师uid
        $evaluate = $this->evaluate_bill->get($evaluate_id);
        //写进任务列表
        $item = array(
            'aid'       => $aid,
            'uid'       => $evaluate['uid'],
            'type'      => 'appraise',
            'remark'    => ''
        );
        $result = $this->task_list->add($item);

        //修改进件状态:[appraise：正在评估]
        $data = array('status'=>'appraise');
        $res  = $this->loan_item->save($data,$aid);

        //风控完成自己的任务
        $type  = $loanAssess['status'];
        $param = array('aid'=>$aid,'type'=>$type);
        $rest  = $this->task_list->done($param);

        //业务扩展--车辆信息
        $extend     = json_decode($loanAssess['extend'],true);
        $car_info   = $extend['car_info'];

        //获取评估师信息
        $this->load->model('employee_model', 'employee');
        $assess=$this->employee->get($evaluate['uid']);

        //推送任务 TODO

        return array('status'=>1);

    }



    /**
     * 风控给业务员推送任务
     * @param int aid 进件id
     * @return array;
     */
    public function taskSalesman($param){
        $aid    = $param['aid'];
        if(empty($aid)){
            return array('status'=>0,'error'=>'参数错误！');
        }
        //进件验证
        $loanAssess = $this->verif_loan_item($param);
        if(!$loanAssess){
            return array('status'=>0,'error'=>'没有进件！');
        }

        //获取业务员
        $who      = array('uid' =>$loanAssess['employee']);
        $salesman = $this->push_who->getSalesman($who);
        if(empty($salesman)){
            return array('status'=>0,'error'=>'没有找到业务员，任务发送失败！');
        }

        //写进任务列表
        $item = array(
            'aid'       => $aid,
            'uid'       => $salesman['uid'],
            'type'      => 'subpact',
            'remark'    => ''
        );
        $result = $this->task_list->add($item);
        if(!$result){
            return array('status'=>0,'error'=>'添加任务失败！');
        }

        //完成自己的任务
        $param = array('aid'=>$aid,'type'=>'risktrol');
        $this->task_list->done($param);

        //业务进件--车辆信息
        $extend     = json_decode($loanAssess['extend'],true);
        $car_info   = $extend['car_info'];

        //风控审核信息
        $this->load->model('wind_bill_model', 'wind');
        $wind = $this->wind->get($loanAssess['wind_id']);

        $dd_amount = '';
        $sug_money = $wind['sug_money'];
        $max_money = $wind['max_money'];
        if($sug_money<$max_money){
            $dd_amount = $sug_money."~".$max_money;
        }else{
            $dd_amount = $sug_money;
        }

        //推送任务 TODO
        return array('status'=>1);
    }

    /**
     * @推送风控重新给价
     * @业务员提交合同之前，打回进件给风控，重新审核。。
     * @param int aid 进件id
     * @param string comment 打回说明
     * @return array;
     */
    public function taskBackToWind($param){
        $aid    = $param['aid'];
        $comment= $param['comment'];
        if(empty($aid)){
            return array('status'=>0,'error'=>'参数错误！');
        }
        //进件验证
        $loanAssess = $this->verif_loan_item($param);
        if(!$loanAssess){
            return array('status'=>0,'error'=>'没有进件！');
        }

        //风控审核信息。获取风控uid
        $this->load->model('wind_bill_model', 'wind_bill');
        $wind = $this->wind_bill->get($loanAssess['wind_id']);

        //写进任务列表
        $item = array(
            'aid'       => $aid,
            'uid'       => $wind['uid'],
            'type'      => 'risktrol'
        );
        $result = $this->task_list->add($item);

        //修改进件状态:[risktrol：正在风控给价]
        $data = array('status'=>'risktrol');
        $res  = $this->loan_item->save($data,$aid);

        //业务员完成自己的任务
        $type  = $loanAssess['status'];
        $param = array('aid'=>$aid,'type'=>$type);
        $rest  = $this->task_list->done($param);

        //业务扩展--车辆信息
        $extend     = json_decode($loanAssess['extend'],true);
        $car_info   = $extend['car_info'];

        //获取风控
        $this->load->model('employee_model', 'employee');
        $wind=$this->employee->get($wind['uid']);

        //推送任务 TODO
        return array('status'=>1);
    }



    /**
     * @业务员提交合同。。
     * @param int aid 进件id
     * @return array;
     */
    public function taskPrinter($param){
        $aid    = $param['aid'];
        if(empty($aid)){
            return array('status'=>0,'error'=>'参数错误！');
        }
        //进件验证
        $loanAssess = $this->verif_loan_item($param);
        if(!$loanAssess){
            return array('status'=>0,'error'=>'没有进件！');
        }

        $data = array('status'=>'done');
        $res  = $this->loan_item->save($data,$aid);

        //完成自己的任务
        $param = array('aid'=>$aid,'type'=>'subpact');
        $rest  = $this->task_list->done($param);
        return array('status'=>1);
    }


}