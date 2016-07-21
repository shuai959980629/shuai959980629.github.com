<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @任务
 * @author zhoushuai
 * @category 20160519
 * @version
 */
class Task_list_model extends Base_model
{

    public function __construct()
    {
        parent::__construct('task_list');
        $this->load->model('loan_item_model', 'loan_item');
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
     * 我的任务
     * @任务归属。当前用户是否能操作该任务
     * @param int aid 进件id
     * @param string type  类型：[storage:车辆入库,getcar：取车]--注：司机任务才传入type
     * @return array
     */
    public function myTask($param){
        $aid    = $param['aid'];
        $uid    = $_SESSION['user']['uid'];
        if(empty($aid)||empty($uid)){
            return array('status'=>0,'error'=>'参数错误！');
        }
        //进件验证
        $loanAssess = $this->verif_loan_item($param);
        if(!$loanAssess){
            return array('status'=>0,'error'=>'没有进件！');
        }
        $type   = !empty($param['type'])?$param['type']:$loanAssess['status'];
        $where = array(
            'aid'   => $aid,
            'type'  => $type
        );
        $result = $this->getWidgetRow($where);
        if(empty($result)){
            return array('status'=>0,'error'=>'没有可执行的任务！');
        }
        if($result['uid']!=$uid){
            return array('status'=>0,'error'=>'当前任务不属于您，操作失败！');
        }
        //'wait'：等待处理,'done'：已经处理
        if($result['status']=='done'){
            return array('status'=>0,'error'=>'当前任务已完成，请勿重复操作！');
        }
        return array('status'=>1);
    }




    /**
     * 添加任务。
     * @param int aid 进件id
     * @param int uid uid
     * @param remark 备注
     * @param type appraise：正在评估；risktrol：正在风控给价；subpact:  正在提交合同
     * @return boolean
     */
    public function add($param){
        $tid    = 0;
        $remark = !empty($param['remark'])?$param['remark']:'';
        $where = array(
            'aid'       => $param['aid'],
            'uid'       => $param['uid'],
            'type'      => $param['type'],
        );
        $task = $this->getWidgetRow($where);
        $item = array(
            'aid'       => $param['aid'],
            'uid'       => $param['uid'],
            'type'      => $param['type'],
            'remark'    => $remark
        );
        if(empty($task)){
            $item['created']     = time();
        }else{
            $tid = $task['id'];
            $item['status']      = 'wait';
            $item['handle_time'] = time();
        }
        $result = $this->save($item,$tid);
        if($result){
            return true;
        }
        return false;
    }

    /**
     * 完成任务。
     * @param int aid[必填] 进件id
     * @param type[可选] appraise：正在评估；risktrol：正在风控给价；subpact:  正在提交合同
     * @return boolean
     */
    public function done($param){
        $aid    = $param['aid'];
        if(empty($aid)){
            return array('status'=>0,'error'=>'参数错误！');
        }
        $where = array('aid'=> $aid);
        if(!empty($param['type'])){
            $where['type'] = $param['type'];
        }
        $task = $this->getWidgetRow($where);
        if(!empty($task)){
            $item = array();
            $item['status']      = 'done';
            $item['handle_time'] = time();
            $result = $this->save($item,$task['id']);
            if(!$result){
                return false;
            }
        }
        return true;
    }

















}