<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 业绩统计
 * @package	MODEL
 * @author	zhoushuai
 */
class DataShow_model extends Base_model
{

    private $limit = 10;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('loan_model', 'loan');
        $this->load->model('employee_model', 'employee');
        $this->load->model('loan_item_model','loan_item');
        $this->load->model('wind_bill_model','wind_bill');
        $this->load->model('evaluate_bill_model','evaluate_bill');
        $this->load->model('task_list_model','task_list');
    }

    /*管理员数据调取*/
    public function AdminData($data){
        /*这里判断是不是片区经理*/
        if($_SESSION['user']['office']== 'manager' && !empty($_SESSION['user']['regionIndex'])){
            $where['in']['stores'] = $_SESSION['user']['regionIndex'];
        }else if($_SESSION['user']['office']== 'storemg'){
            $where['stores'] = $_SESSION['user']['stores'];
        }
        //今日业绩
        $today = date('Y-m-d');
        $where['in']['loan_status'] = array('pending','done','advance');
        $where['status'] = 1;
        $where["FROM_UNIXTIME(loan_time,'%Y-%m-%d')"] = $today;
        $data['doneToday'] = $this->loan->getWidgetTotal($where);
        $total = $this->loan->total($where);
        $data['todaySum'] = $total['sum'];
        //本月业绩
        unset($where["FROM_UNIXTIME(loan_time,'%Y-%m-%d')"]);                   //先把之前的时间查询清除
        $curMonth = date('Y-m');
        $where["FROM_UNIXTIME(loan_time,'%Y-%m')"] = $curMonth;
        $data['doneCurMonth'] = $this->loan->getWidgetTotal($where);
        //本月地区排名：
        $total = $this->loan->total($where);
        $data['curMonth'] 	= $total['sum'];
        $where['order']		= array('sum'=>'DESC');
        //地区排名
        $where['group']	= array('stores');
        $where['cols']		= array("SUM(loan_amount) as sum,stores");
        $data['zone']		= $this->loan->zoneRank($where,1,1);
        //个人排名
        $where['group']		= array('employee');
        $where['cols']		= array("SUM(loan_amount) as sum,stores,employee");
        $data['person']		= $this->loan->rank($where,1,1);
        return $data;
    }

    /**
     * @今日全国总业绩
     */
    public function today($stores = ''){
        //今天业务总额
        $today = date('Y-m-d');
        $where = array(
            "in"=>array('loan_status'=>array('pending','done','advance')),
            "FROM_UNIXTIME(loan_time,'%Y-%m-%d')" =>"{$today}",
            'status'=>1
        );
        /*判断如果是业务经理，查看他的片区*/
        if($_SESSION['user']['office']=='manager' && !empty($_SESSION['user']['regionIndex'])){
            $where['in'] = array('loan_status'=>array('pending','done','advance'),"stores"=>$_SESSION['user']['regionIndex']);
        }else if($_SESSION['user']['office']== 'storemg'){
            $where['stores'] = $_SESSION['user']['stores'];
        }
        $total = $this->loan->total($where);
        $data['todaySum'] = $total['sum'];
        //当天地区排名：
        $where['order']		= array('sum'=>'DESC');
        $where['group']		= array('stores');
        $where['cols']		= array("SUM(loan_amount) as sum,stores");
        $zonelist           = $this->loan->zoneRank($where,0);
        $data['zone'][] = array(
                'sum'       => $data['todaySum'],
                'stores'    => 0,
                'storesName'=> '全部'
        );
        foreach($zonelist as $key=>$list){
            $data['zone'][] =   array(
                'sum'       => $list['sum'],
                'stores'    => $list['stores'],
                'storesName'=> $list['storesName']
            );
        }
        $data['stores']     = $data['zone'][0]['stores'];
        $data['storesName'] = $data['zone'][0]['storesName'];
        foreach($data['zone'] as $key=>$list){
            if(!empty($stores)&&$list['stores']==$stores){
                $data['stores'] = $list['stores'];
                $data['storesName'] = $list['storesName'];
            }
        }
        $data['doing']  = $this->getDoingList(1,$stores);           //正在做
        $data['done'] = $this->getDongList(1,$stores);              //已完成
        $data['deny'] = $this->getDenyList(1,$stores);              //已完成
        return $data;
    }

    /**
     * @正在做
     */
    public function getDoingList($page,$stores){
        $where = array(
            'not_in' => array('status'=>array('deny','done')),
            'order'=>array('created'=>'DESC'),
            'is_del'=>1,
        );
        if(empty($stores)){
            if($_SESSION['user']['office']=='manager' && !empty($_SESSION['user']['regionIndex'])){
                $where['in']['stores'] =$_SESSION['user']['regionIndex'];
            }else if($_SESSION['user']['office']== 'storemg'){
                $where['stores'] = $_SESSION['user']['stores'];
            }
        }else{
            $where['stores'] = $stores;
        }
        $offset = ($page - 1) * $this->limit;
        $data = $this->loan_item->loan_item_list($where,$offset);
        return $data;
    }

    /**
     * @已完成
     */
    public function getDongList($page,$stores){
        /*先给条件*/
        $where = array(
            'order'=>array('loan_time'=>'DESC'),
            'status'=>1,
            'cols' => array('id,loan_amount,aid,loan_time,stores,employee')
        );
        $where['in']['loan_status'] = array('pending','done','advance');
        if(empty($stores)){
            if($_SESSION['user']['office']=='manager' && !empty($_SESSION['user']['regionIndex'])){
                $where['in']['stores'] =$_SESSION['user']['regionIndex'];
            }else if($_SESSION['user']['office']== 'storemg'){
                $where['stores'] = $_SESSION['user']['stores'];
            }
        }else{
            $where['stores'] = $stores;
        }
        /*再查询数据*/
        $offset = ($page - 1) * $this->limit;
        $loan = $this->loan->getWidgetPages($where,$this->limit,$offset);
        if(!empty($loan)){
            $count = $this->loan->getWidgetTotal($where);
            $num = ceil($count/$this->limit);
            foreach($loan as $key=>&$list){
                $list['time']     = date('Y-m-d',$list['loan_time']);
                $list['store']    = $this->employee->stores[$list['stores']];
                $employee         = $this->employee->getWidgetRow($list['employee']);
                $list['realname'] = $employee['realname'];
                $loan_item        = $this->loan_item->getWidgetRow(array('id'=>$list['aid']));
                $extend             = json_decode($loan_item['extend'],true);
                $list['car_logo_url'] = !empty($extend['car_info']['car_logo_url'])?$extend['car_info']['car_logo_url']:'/assets/images/pic/cbdefault.png';
                $list['modelName']    = !empty($extend['car_info']['modelName'])?$extend['car_info']['modelName']:'';
                $list['state']    = 'state-green';
                $list['status']   = '放款完成';
                $loan_amount      = FormatMoney($list['loan_amount']);
                $list['ui_list_info']   = '<h5><b class="fcolor_green">放款金额：¥'.$loan_amount.'万</b></h5>';
                switch($loan_item['deal_flag']){
                    case 'pledge':
                        $list['deal_flag'] = '<i class="icon-zhi">质</i>';
                        break;
                    case 'mortgage':
                        $list['deal_flag'] = '<i class="icon-di">抵</i>';
                        break;
                };
                $b[] = date('Y-m-d',$list['loan_time']);
            }
            $b = array_unique($b);
            foreach($b as $v){
                foreach($loan as $value){
                    if($value['time'] == $v){
                        $c['message'][$v][] = $value;
                    }
                }
            }
            $c['cnt'] = $count;
            $c['num'] = $num;
            return $c;
        }else{
            return '';
        }
    }
    /*拒贷*/
    public function getDenyList($page,$stores){
        /*先给条件*/
        $where = array(
            'order'=>array('created'=>'DESC'),
            'is_del'=>1,
            'status'=> 'deny'
        );
        if(empty($stores)){
            if($_SESSION['user']['office']=='manager' && !empty($_SESSION['user']['regionIndex'])){
                $where['in']['stores'] =$_SESSION['user']['regionIndex'];
            }else if($_SESSION['user']['office']== 'storemg'){
                $where['stores'] = $_SESSION['user']['stores'];
            }
        }else{
            $where['stores'] = $stores;
        }
        /*再查询数据*/
        $offset = ($page - 1) * $this->limit;
        $data = $this->loan_item->loan_item_list($where,$offset);
        return $data;
    }

    /**
     * @本月全国所有地区。业绩排名
     */
    public function zone(){
        $curMonth = date('Y-m');
        $where = array(
            "in"        => array('loan_status'=>array('pending','done','advance')),
            "FROM_UNIXTIME(loan_time,'%Y-%m')" =>"{$curMonth}",
            'status'    =>1
        );
        if($_SESSION['user']['office']=='manager' && !empty($_SESSION['user']['regionIndex'])){
            $where['in'] = array('loan_status'=>array('pending','done','advance'),"stores"=>$_SESSION['user']['regionIndex']);
        }else if($_SESSION['user']['office']== 'storemg'){
            $where['stores'] = $_SESSION['user']['stores'];
        }
        $total              = $this->loan->total($where);
        $data['total']      = $total['sum'];
        $where['order']		= array('sum'=>'DESC');
        $where['group']		= array('stores');
        $where['cols']		= array("SUM(loan_amount) as sum,stores");
        $data['zone']       = $this->loan->zoneRank($where,0,1);
        return $data;
    }


    /**
     * 每月的业绩
     */
    public function month(){
        $where = array(
            "in"        =>array('loan_status'=>array('pending','done','advance')),
            'cols'      =>array('SUM(loan_amount) as total',"FROM_UNIXTIME(`loan_time`,'%Y-%m-%d') AS sdate"),
            'group'		=>array("FROM_UNIXTIME(`loan_time`,'%Y-%m-%d')"),
            'status'    =>1,
            'order'     =>array("FROM_UNIXTIME(`loan_time`,'%Y-%m-%d')"=>'DESC'),
        );
        if($_SESSION['user']['office']=='manager' && !empty($_SESSION['user']['regionIndex'])){
            $where['in'] = array('loan_status'=>array('pending','done','advance'),"stores"=>$_SESSION['user']['regionIndex']);
        }else if($_SESSION['user']['office']== 'storemg'){
            $where['stores'] = $_SESSION['user']['stores'];
        }
        $data=$this->loan->perMonth($where,0,1);
        return $data;
    }

    /**
     * 当月销售排行
     */
    public function rank(){
        $curMonth = date('Y-m');
        $where = array(
            "in"=>array('loan_status'=>array('pending','done','advance')),
            "FROM_UNIXTIME(loan_time,'%Y-%m')" =>"{$curMonth}",
            'status'=>1
        );
        if($_SESSION['user']['office']=='manager' && !empty($_SESSION['user']['regionIndex'])){
            $where['in'] = array('loan_status'=>array('pending','done','advance'),"stores"=>$_SESSION['user']['regionIndex']);
        }else if($_SESSION['user']['office']== 'storemg'){
            $where['stores'] = $_SESSION['user']['stores'];
        }
        //个人排名
        $where['order']		= array('sum'=>'DESC');
        $where['group']		= array('employee');
        $where['cols']		= array("SUM(loan_amount) as sum,stores,employee");
        $data['rank']=$this->loan->rank($where,10,1);
        return $data;
    }


}
