<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @进件表model
 * @author zhoushuai
 * @category 20160518
 * @version
 */
class Loan_item_model extends Base_model
{

    private $limit = 10;
    private $offset = 0;

    public function __construct()
    {
        parent::__construct('loan_item');

        $this->load->model('loan_model','loan');
        $this->load->model('employee_model','employee');
        $this->load->model('task_list_model','task_list');
        $this->load->model('Evaluate_bill_model','Evaluate_bill');
        $this->load->model('wind_bill_model','wind_bill');
    }

    public $status  = array(
        'appraise'      =>'正在评估',
        'risktrol'      =>'风控审核',
        'deny'          =>'拒绝放款',
        'subpact'       =>'提交合同',
        'done'          =>'放款完成'
    );





    //车辆信息 api接口需要的参数
    public $extend = array(
        'car_info' =>array(
            'brandName'         =>'品牌名称',
            'seriesName'        =>'车系名称',
            'modelName'         =>'车型名称',

            'minYear'           =>'上牌最小年份',
            'maxYear'           =>'上牌最大年份',

            'provName'          =>'省份名称',
            'cityName'          =>'城市名称',
            'zone'              =>'所在地区',

            'eval_price'        =>'估值结果',
            'low_price'         =>'车况一般的估值',
            'good_price'        =>'车况良好的估值',
            'high_price'        =>'车况优秀的估值',
            'dealer_buy_price'  =>"车商收购价",
            'individual_price'  =>"个人交易价",
            'dealer_price'      =>"车商零售价",

            'price'             =>"车型指导价",
            'title'             =>'车型名称',
            'car_logo_url'      =>"品牌的图片地址",
            'registerDate'      =>"上牌时间",

            'location'          =>'停放位置',
            'kehu_amount'       =>'资金需求',
            'comment'           =>'业务说明',
        ),
        'car_api'  =>array(
            'modelId'           =>'车型ID',
            'brandId'           =>'品牌ID',
            'seriesId'          =>'车系ID',
            'regDate'           =>'上牌时间',
            'mile'              =>'行驶里程',
            'prov'              =>'省份ID',
            'city'              =>'城市ID',
        ),
        'del_reason'            =>'取消业务理由',
    );

    public function loan_item_list($where,$offset){
        $loan_item = $this->getWidgetPages($where,$this->limit,$offset);
        if(!empty($loan_item)){
            $count = $this->getWidgetTotal($where);
            $num   = ceil($count/$this->limit);
            foreach($loan_item as $key=>&$list){
                $list['time']     = date('Y-m-d',$list['created']);
                $list['extend']   = json_decode($list['extend'],true);
                $list['car_logo_url'] = !empty($list['extend']['car_info']['car_logo_url'])?$list['extend']['car_info']['car_logo_url']:'/assets/images/pic/cbdefault.png';
                $list['modelName']    = !empty($list['extend']['car_info']['modelName'])?$list['extend']['car_info']['modelName']:'';
                $list['store']    = $this->employee->stores[$list['stores']];
                $employee         = $this->employee->getWidgetRow($list['employee']);
                $list['realname'] = $employee['realname'];
                if($list['is_del']==0){
                    $list['state']        = 'state-red';
                    $wind                 = $this->wind_bill->getWidgetRow(array('aid'=>$list['id']));
                    $list['ui_list_info'] = '<h5><b class="fcolor_red">风控价：¥'.$wind['sug_money'].'~¥'.$wind['max_money'].'万</b></h5>';
                    $list['status']       = '已回收';
                }else{
                    switch($list['status']){
                        case 'appraise':
                            $list['state']        = 'state-orage';
                            $list['ui_list_info'] = '<h5 class="ui-nowrap"><b class="fcolor_orage">估值价：¥'.$list['extend']['car_info']['eval_price'].'万元</b></h5>';
                            break;
                        case 'risktrol':
                            $list['state']        = 'state-purple';
                            $evaluate             = $this->evaluate_bill->getWidgetRow(array('aid'=>$list['id']));
                            $list['ui_list_info'] = '<h5 class="ui-nowrap"><b class="fcolor_purple">评估价：¥'.$evaluate['car_price'].'万元</b></h5>';
                            break;
                        case 'subpact':
                            $list['state']        = 'state-blue';
                            $wind                 = $this->wind_bill->getWidgetRow(array('aid'=>$list['id']));
                            $list['ui_list_info'] = '<h5><b class="fcolor_blue">风控价：¥'.$wind['sug_money'].'~¥'.$wind['max_money'].'万</b></h5>';
                            break;
                        case 'done':
                            $list['state']        = 'state-green';
                            $loan                 = $this->loan->getWidgetRow(array('aid'=>$list['id']));
                            $list['ui_list_info'] = '<h5><b class="fcolor_green">放款金额：¥'.FormatMoney($loan['loan_amount']).'万</b></h5>';
                            break;
                        case 'deny':
                            $list['state']        = 'state-red';
                            $evaluate             = $this->evaluate_bill->getWidgetRow(array('aid'=>$list['id']));
                            $list['ui_list_info'] = '<h5 class="ui-nowrap"><b class="fcolor_red">评估价：¥'.$evaluate['car_price'].'万元</b></h5>';
                            break;
                    }
                    $list['status']   = $this->status[$list['status']];
                }
                switch($list['deal_flag']){
                    case 'pledge':
                        $list['deal_flag'] = '<i class="icon-zhi">质</i>';
                        break;
                    case 'mortgage':
                        $list['deal_flag'] = '<i class="icon-di">抵</i>';
                        break;
                };
                $b[] = date('Y-m-d',$list['created']);
            }
            $b = array_unique($b);
            foreach($b as $v){
                foreach($loan_item as $value){
                    if($value['time'] == $v){
                        $c['message'][$v][] = $value;
                    }
                }
            }
            $c['cnt'] = $count;
            $c['num'] = $num;
            return $c;
        }else{
            return array();
        }
    }



    /*
    *@风控个人中心
    */
    public function cntWind(&$data){
        //本月已完成数据
        $where = array(
            'uid'=>$_SESSION['user']['uid'],
            'like'=>array('from_unixtime(created,"%Y-%m")'=>date("Y-m")),
        );
        $data['month'] = $this->wind_bill->getWidgetTotal($where);

        //拒绝的数据
        $where = array(
            'status'=>'deny',
            'uid'=>$_SESSION['user']['uid'],
        );
        $data['deny'] = $this->wind_bill->getWidgetTotal($where);

        //今日已完成数据
        $where = array(
            'uid'=>$_SESSION['user']['uid'],
            'like'=>array('from_unixtime(created,"%Y-%m-%d")'=>date("Y-m-d")),
        );
        $data['today'] = $this->wind_bill->getWidgetTotal($where);

        //所有已完成数据
        $where = array(
            'uid'=>$_SESSION['user']['uid'],
        );
        $data['all'] = $this->wind_bill->getWidgetTotal($where);

        //待处理数据
        $where = array(
            'uid'=>$_SESSION['user']['uid'],
            'in' => array('type'=>array('risktrol','subpact')),
            'status'=>'wait',
        );
        $data['wait'] = $this->task_list->getWidgetTotal($where);
        return $data;
    }
    //风控共用方法
    public function windPublic($flag,$page = 1){
        switch($flag){
            /*本月已完成*/
            case 1:
                $where = array(
                    'uid'=>$_SESSION['user']['uid'],
                    'like'=>array('from_unixtime(created,"%Y-%m")'=>date("Y-m")),
                    'cols'=>array('aid','created'),
                    'order' => array('created'=>'DESC')
                );
                break;
            /*今日审核*/
            case 3:
                $where = array(
                    'uid'=>$_SESSION['user']['uid'],
                    'like'=>array('from_unixtime(created,"%Y-%m-%d")'=>date("Y-m-d")),
                    'cols'=>array('aid','created'),
                    'order' => array('created'=>'DESC')
                );
                break;
            /*拒绝审核*/
            case 4:
                $where = array(
                    'status'=>'deny',
                    'uid'=>$_SESSION['user']['uid'],
                    'cols'=>array('aid','created'),
                    'order' => array('created'=>'DESC')
                );
                break;
            /*所有已完成审核*/
            case 5:
                $where = array(
                    'uid'=>$_SESSION['user']['uid'],
                    'cols'=>array('aid','created'),
                    'order' => array('created'=>'DESC')
                );
                break;
            default:
                show_404();
                break;
        }
        $count = $this->wind_bill->getWidgetTotal($where);          //符合该条件的数量
        $offset = ($page - 1) * $this->limit;                      //分页偏移量
        $res = $this->wind_bill->getWidgetPages($where,$this->limit,$offset);
        foreach ($res as &$v) {
            $where = array(
                'id'=>$v['aid'],
                'cols'=>array('extend,stores,employee,status'),
            );
            $result = $this->getWidgetRow($where);
            $v['zone'] = json_decode($result['extend'])->car_info->cityName;
            $v['model'] = json_decode($result['extend'])->car_info->modelName;
            $v['car_logo'] = json_decode($result['extend'])->car_info->car_logo_url;
            $v['status'] = $this->status[$result['status']];
            /*查找门店姓名*/
            $v['store'] = $this->employee->stores[$result['stores']];
            $employee = $this->employee->getWidgetRow(array('uid'=>$result['employee'],'cols'=>array('realname')));
            $v['realname'] = $employee['realname'];
        }
        /*判断是否加载更多*/
        if($count > $this->limit) $data['cnt'] = ceil($count/$this->limit);
        $data['data'] = $res;
        $data['number'] = $count;
        return $data;
    }
    //待处理数据列表-风控
    public function windWaitnews($page = 1){
        $where = array(
            'uid'=>$_SESSION['user']['uid'],
            'in' => array('type'=>array('risktrol','subpact')),
            'status'=>'wait',
            'cols'=>array('aid,type,created'),
            'order' => array('created'=>'DESC')
        );
        $count = $this->task_list->getWidgetTotal($where);          //符合该条件的数量
        $offset = ($page - 1) * $this->limit;                      //分页偏移量
        $task_list = $this->task_list->getWidgetPages($where,$this->limit,$offset);
        foreach($task_list as $key=>&$list){
            $loan_item  = $this->loan_item->getWidgetRow(array('id'=>$list['aid']));
            $list['zone'] = json_decode($loan_item['extend'])->car_info->cityName;
            $list['model'] = json_decode($loan_item['extend'])->car_info->modelName;
            $list['car_logo'] = json_decode($loan_item['extend'])->car_info->car_logo_url;
            $list['status'] = $this->status[$loan_item['status']];
            /*查找门店姓名*/
            $list['store'] = $this->employee->stores[$loan_item['stores']];
            $employee = $this->employee->getWidgetRow(array('uid'=>$loan_item['employee'],'cols'=>array('realname')));
            $list['realname'] = $employee['realname'];

        }
        /*判断是否加载更多*/
        if($count > $this->limit) $data['cnt'] = ceil($count/$this->limit);
        $data['data'] = $task_list;
        $data['number'] = $count;
        return $data;
    }

    /*
    *@评估师个人中心
    */
    public function cntAssess(&$data){
        //本月已完成数据
        $where = array(
            'status'=>'done',
            'uid'=>$_SESSION['user']['uid'],
            'like'=>array('from_unixtime(created,"%Y-%m")'=>date("Y-m")),
        );
        $data['month'] = $this->Evaluate_bill->getWidgetTotal($where);

        //等待处理的业务
        $where = array(
            'uid'=>$_SESSION['user']['uid'],
            'in' => array('type'=>array('appraise','subpact')),
            'status'=>'wait'
        );
        $data['wait'] = $this->task_list->getWidgetTotal($where);

        //今日已完成数据
        $where = array(
            'status'=>'done',
            'uid'=>$_SESSION['user']['uid'],
            'like'=>array('from_unixtime(created,"%Y-%m-%d")'=>date("Y-m-d")),
        );
        $data['today'] = $this->Evaluate_bill->getWidgetTotal($where);

        //所有已完成数据
        $where = array(
            'status'=>'done',
            'uid'=>$_SESSION['user']['uid'],
        );
        $data['all'] = $this->Evaluate_bill->getWidgetTotal($where);
        return $data;
    }
    //评估师公共方法
    public function assessPublic($flag,$page = 1){
        switch($flag){
            /*评估总数据*/
            case 1:
                $where = array(
                    'status'=>'done',
                    'uid'=>$_SESSION['user']['uid'],
                    'cols'=>array('id','aid','created','car_info'),
                    'order'=>array('created'=>'desc')
                );
                break;
            /*今日评估量*/
            case 3:
                $where = array(
                    'status'=>'done',
                    'uid'=>$_SESSION['user']['uid'],
                    'like'=>array('from_unixtime(created,"%Y-%m-%d")'=>date("Y-m-d")),
                    'cols'=>array('id','aid','created','car_info'),
                    'order'=>array('created'=>'desc')
                );
                break;
            /*本月评估数据*/
            case 4:
                $where = array(
                    'status'=>'done',
                    'uid'=>$_SESSION['user']['uid'],
                    'like'=>array('from_unixtime(created,"%Y-%m")'=>date("Y-m")),
                    'cols'=>array('id','aid','created','car_info'),
                    'order'=>array('created'=>'desc')
                );
                break;
            default:
                show_404();
                break;
        }
        $count = $this->Evaluate_bill->getWidgetTotal($where);          //符合该条件的数量
        $offset = ($page - 1) * $this->limit;                      //分页偏移量
        $res = $this->Evaluate_bill->getWidgetPages($where,$this->limit,$offset);
        foreach ($res as &$v) {
            $loan_item = $this->loan_item->getWidgetRow(array('id'=>$v['aid']));
            $v['car_logo'] = json_decode($loan_item['extend'])->car_info->car_logo_url;
            $v['city_name'] = json_decode($loan_item['extend'])->car_info->cityName;
            $v['price'] = json_decode($loan_item['extend'])->car_info->price;
            $v['title'] = json_decode($loan_item['extend'])->car_info->modelName;
            $v['regDate'] = json_decode($loan_item['extend'])->car_info->registerDate;
            /*查找门店姓名*/
            $v['store'] = $this->employee->stores[$loan_item['stores']];
            $employee = $this->employee->getWidgetRow(array('uid'=>$loan_item['employee'],'cols'=>array('realname')));
            $v['realname'] = $employee['realname'];
        }
        /*判断是否加载更多*/
        if($count > $this->limit) $data['cnt'] = ceil($count/$this->limit);
        $data['data'] = $res;
        $data['number'] = $count;
        return $data;
    }

    //评估师-待处理评估数据
    public function assessWaitnews($page = 1){
        $where = array(
            'uid'=>$_SESSION['user']['uid'],
            'in' => array('type'=>array('appraise','subpact')),
            'status'=>'wait',
            'cols'=>array('aid,created,type'),
            'order'=>array('created'=>'desc')
        );
        $count = $this->task_list->getWidgetTotal($where);          //符合该条件的数量
        $offset = ($page - 1) * $this->limit;                      //分页偏移量
        $res = $this->task_list->getWidgetPages($where,$this->limit,$offset);
        foreach ($res as &$v) {
            $loan_item     = $this->loan_item->getWidgetRow(array('id'=>$v['aid']));
            $v['plate_no'] = $loan_item['plate_no'];
            $extend        =  json_decode($loan_item['extend'],true);
            $v['car_info'] = '';
            if(!empty($extend)){
                $v['car_info']  = $extend['car_info'];
                $v['car_logo']  = $extend['car_info']['car_logo_url'];
                $v['city_name'] = $extend['car_info']['cityName'];
                $v['price']     = $extend['car_info']['price'];
                $v['title']     = $extend['car_info']['modelName'];
                $v['regDate']   = $extend['car_info']['registerDate'];
            }
            /*查找门店姓名*/
            $v['store'] = $this->employee->stores[$loan_item['stores']];
            $employee = $this->employee->getWidgetRow(array('uid'=>$loan_item['employee'],'cols'=>array('realname')));
            $v['realname'] = $employee['realname'];
        }
        /*判断是否加载更多*/
        if($count > $this->limit) $data['cnt'] = ceil($count/$this->limit);
        $data['data'] = $res;
        $data['number'] = $count;
        return $data;
    }

    /**
     * @业务员统计
     */
    public function cntSalesman(&$data){
        /*
        *@业务员个人中心-本月总额
        */
        $where = array(
            'in'=>array('loan_status'=>array('pending','done','advance')),
            'employee'=>$data['uid'],
            'like'=>array('from_unixtime(loan_time,"%Y-%m")'=>date("Y-m")),
            'sum'=>'loan_amount'
        );
        $res = $this->loan->getWidgetRow($where);
        $data['curMonth'] = $res['loan_amount'];

        /*
        *@业务员个人中心-排行榜
        */
        $where = array(
            'office'=>'salesman',
        );
        $salemans = $this->employee->getWidgetRows($where);
        $salemansid = array();
        foreach ($salemans as $v) {
            array_push($salemansid,$v['uid']);
        }
        $where = array(
            'in'=>array('employee'=>$salemansid),
            'group'=>array('employee'),
            'sum'=>'loan_amount',
            'in'=>array('loan_status'=>array('pending','done','advance')),
            'cols'=>array('employee'),
            'order'=>array('loan_amount'=>'desc'),
        );
        $res = $this->loan->getWidgetRows($where);
        $rank = 1;
        foreach ($res as $v) {
            $cuser = $v['employee'];
            if($cuser == $data['uid']){break;}else{$rank++;}
        }
        $data['top'] = $rank;

        /*
        *@业务员个人中心-待处理业务
        */
        $where = array(
            'uid'=>$data['uid'],
            'type'=>'subpact',
            'status'=>'wait'
        );
        $res = $this->task_list->getWidgetTotal($where);
        $data['wait'] = $res;

        /*
        *@业务员个人中心-已完成业务
        */
        $where = array(
            'employee'=>$data['uid'],
            'in'=>array('loan_status'=>array('pending','done','advance')),
            'like'=>array('from_unixtime(loan_time,"%Y-%m")'=>date("Y-m"))
        );
        $res = $this->loan->getWidgetTotal($where);
        $data['done'] = $res;

        /*
        *@业务员个人中心-流单
        */
        $where = array(
            'employee'=>$data['uid'],
            'is_del'=>0
        );
        $res = $this->getWidgetTotal($where);
        $data['draft'] = $res;

        return $data;
    }

    /**
     * @业务员详情列表数据-排行榜
     */
    public function tops(){
        $where = array(
            'office'=>'salesman',
        );
        $salemans = $this->employee->getWidgetRows($where);
        $salemansid = array();
        foreach ($salemans as $v) {
            array_push($salemansid,$v['uid']);
        }
        $where = array(
            'in'=>array('employee'=>$salemansid),
            'group'=>array('employee'),
            'sum'=>'loan_amount',
            'in'=>array('loan_status'=>array('pending','done','advance')),
            'cols'=>array('employee'),
            'order'=>array('loan_amount'=>'desc'),
            'limit'=>10
        );
        $res = $this->loan->getWidgetRows($where);
        foreach ($res as &$v) {
            $where = array(
                'uid'=>$v['employee'],
                'cols'=>array('realname')
            );
            $result = $this->employee->getWidgetRow($where);
            $v['employee'] = $result['realname'];
        }
        return $res;
    }

    /**
     * @业务员详情列表数据-销售额统计
     */
    public function totalSale(){
        $where = array(
            'in'=>array('loan_status'=>array('pending','done','advance')),
            'employee'=>$_SESSION['user']['uid'],
            'like'=>array('from_unixtime(loan_time,"%Y-%m")'=>date("Y-m")),
            'sum'=>'loan_amount'
        );
        $res = $this->loan->getWidgetRow($where);
        $data['month'] = $res['loan_amount'];
        $data['month'] = $data['month']!=null?$data['month']:"本月尚无";

        $where = array(
            'in'=>array('loan_status'=>array('pending','done','advance')),
            'employee'=>$_SESSION['user']['uid'],
            'like'=>array('from_unixtime(loan_time,"%Y-%m")'=>date("Y-m")),
            'cols'=>array('created','loan_amount')
        );
        $res = $this->loan->getWidgetPages($where,$this->limit,$this->offset);
        $data['monthlist'] = $res;

        //今日销售额
        $where = array(
            'in'=>array('loan_status'=>array('pending','done','advance')),
            'employee'=>$_SESSION['user']['uid'],
            'like'=>array('from_unixtime(loan_time,"%Y-%m-%d")'=>date("Y-m-d")),
            'sum'=>'loan_amount'
        );
        $res = $this->loan->getWidgetRow($where);
        $data['today'] = $res['loan_amount'];
        $data['today'] = $data['today']!=null?$data['today']:"今日尚无";

        $where = array(
            'in'=>array('loan_status'=>array('pending','done','advance')),
            'employee'=>$_SESSION['user']['uid'],
            'like'=>array('from_unixtime(loan_time,"%Y-%m-%d")'=>date("Y-m-d")),
            'cols'=>array('created','loan_amount')
        );
        $res = $this->loan->getWidgetPages($where,$this->limit,$this->offset);
        $data['todaylist'] = $res;

        $data['nd'] = date('Y年m月');
        return $data;
    }

    /**
     * @业务员详情列表数据-待处理、已完成、流单
     */
    public function getsalesmanwaitlist(){
        $where = array(
            'uid'=>$_SESSION['user']['uid'],
            'type'=>'subpact',
            'status'=>'wait',
            'cols'=>array('aid')
        );
        $res = $this->task_list->getWidgetRows($where);
        if(empty($res)){
            return null;
        }
        $salemansid = array();
        foreach ($res as $v) {
            array_push($salemansid,$v['aid']);
        }
        $where = array(
            'in'=>array('id'=>$salemansid),
            'cols'=>array()
        );
        $res = $this->getWidgetPages($where,$this->limit,$this->offset);
        foreach ($res as &$v) {
            $where = array(
                'uid'=>$v['employee'],
                'cols'=>array('realname')
            );
            $result = $this->employee->getWidgetRow($where);
            $v['realname'] = $result['realname'];
            $wind = $this->wind_bill->getWidgetRow(array('aid'=>$v['id'],'cols'=>array('sug_money','max_money')));
            $v['sug_money'] = $wind['sug_money'];
            $v['max_money'] = $wind['max_money'];
            $v['zone'] = json_decode($v['extend'])->car_info->cityName;
            $v['model'] = json_decode($v['extend'])->car_info->modelName;
            $v['car_logo'] = json_decode($v['extend'])->car_info->car_logo_url;
            $v['state']        = 'state-blue';
            $v['ui_list_info'] = '<h5><b class="fcolor_blue">风控价：¥'.$wind['sug_money'].'~¥'.$wind['max_money'].'万</b></h5>';
            $v['status']   = $this->status[$v['status']];
            switch($v['deal_flag']){
                case 'pledge':
                    $v['deal_flag'] = '<i class="icon-zhi">质</i>';
                    break;
                case 'mortgage':
                    $v['deal_flag'] = '<i class="icon-di">抵</i>';
                    break;
            };
            /*查找门店姓名*/
            $v['store'] = $this->employee->stores[$v['stores']];
        }
        return $res;
    }

    public function getsalesmandonelist(){
        $where = array(
            'employee'=>$_SESSION['user']['uid'],
            'in'=>array('loan_status'=>array('pending','done','advance')),
            'cols'=>array('aid')
        );
        $res = $this->loan->getWidgetRows($where);
        if(empty($res)){
            return null;
        }
        $salemansid = array();
        foreach ($res as $v) {
            array_push($salemansid,$v['aid']);
        }
        $where = array(
            'in'=>array('id'=>$salemansid),
            'cols'=>array('id','created','extend','employee'),
        );
        $res = $this->getWidgetRows($where);
        foreach ($res as &$v) {
            $where = array(
                'uid'=>$v['employee'],
                'cols'=>array('realname')
            );
            $result = $this->employee->getWidgetRow($where);
            $v['employee'] = $result['realname'];
            $v['zone'] = json_decode($v['extend'])->car_info->cityName;
            $v['model'] = json_decode($v['extend'])->car_info->modelName;
            $v['car_logo'] = json_decode($v['extend'])->car_info->car_logo_url;
        }
        return $res;
    }

    public function getsalesmanfralist(){
        $where = array(
            'employee'=>$_SESSION['user']['uid'],
            'is_del'=>0,
            'cols'=>array('id','created','extend','employee'),
        );
        $res = $this->getWidgetRows($where);
        foreach ($res as &$v) {
            $where = array(
                'uid'=>$v['employee'],
                'cols'=>array('realname')
            );
            $result = $this->employee->getWidgetRow($where);
            $v['employee'] = $result['realname'];
            $v['zone'] = json_decode($v['extend'])->car_info->cityName;
            $v['model'] = json_decode($v['extend'])->car_info->modelName;
            $v['car_logo'] = json_decode($v['extend'])->car_info->car_logo_url;
        }
        return $res;
    }

}
