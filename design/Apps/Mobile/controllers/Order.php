<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @业务列表
 * @author zhoushuai
 * @copyright(c) 2016-05-19
 * @version
 */
class Order extends MY_Controller
{
    private $limit=10;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('employee_model', 'employee');
        $this->load->model('Loan_item_model', 'loan_item');
        $this->load->model('Evaluate_bill_model', 'evaluate_bill');
        $this->load->model('Wind_bill_model', 'wind_bill');
    }


    /**
     * @列表
     */
    public function index(){
        $data['array']=$this->GetSalesmanList(1);
        $this->render($data);
    }


    /*业务员list列表*/
    public function GetSalesmanList($page){
        $offset = ($page - 1) * $this->limit;
        $where['employee'] = $_SESSION['user']['uid'];
        $where['order'] = array('created'=>'desc');
        $data = $this->loan_item->loan_item_list($where,$offset);
        return $data;
    }
    /*更新数据*/
    public function todolst(){
        $page = $this->input->get('page') ? $this->input->get('page') : 1;//页码
        $date = $this->input->get('date') ? $this->input->get('date') : '';//传递过来的最后显示日期
        $data=$this->GetSalesmanList($page);
        $data['office'] = $_SESSION['user']['office'];                //复制判断
        $data['date'] = $date;
        $this->load->view('widgets/lists',$data);
    }

    /*进件详细页面*/
    public function detail(){
        $id = $this->input->get('id');
        /*查找进件表*/
        $one = $this->loan_item->getWidgetRow(array('id'=>$id,'is_del'=>1));
        /*判断有进件记录没*/
        if(empty($one)){
            $data['no'] = 1;
        }else{
            /*扩展转换*/
            $one['extend'] = json_decode($one['extend'],true);
            /*车辆信息调取出来*/
            $data['car'] = $one['extend']['car_info'];
            $data['car']['mile'] = $one['extend']['car_api']['mile'];
            /*工单类型*/
            $data['deal_flag'] = $one['deal_flag'];
            /*当前进度*/
            $data['status'] = $one['status'];
            /*车子的估值*/
            $data['eval_price'] = $one['extend']['car_info']['eval_price'];
            /*业务员的信息调取出来*/
            $salesman = $this->employee->getWidgetRow(array('uid'=>$one['employee'],'cols'=>array('realname,mobile')));
            $data['salesman'] = array(
                'realname' => $salesman['realname'],            //业务员名字
                'mobile' => $salesman['mobile'],                //业务员电话
                'plate_no' => $one['plate_no'],                //车牌号
                'location' => $one['extend']['car_info']['location'],      //停放位置
                'kehu_amount' => $one['extend']['car_info']['kehu_amount'],      //资金需求
                'comment' => $one['extend']['car_info']['comment'],      //业务说明
                'time' => date('Y-m-d H:i:s',$one['created'])             //提交日期
            );
            //调取所有的亮点配置信息
            $this->load->config('highlight');
            $highlight =$this->config->item('highlight');
            $data['liangdian']['highlight'] = $highlight;
            /*评估师的信息调取出来*/
            if(!empty($one['evaluate_id'])){
                $evaluate = $this->evaluate_bill->getWidgetRow(array('id'=>$one['evaluate_id']));     //找数据
                $evaluate['car_info'] = json_decode($evaluate['car_info'],true);                           //扩展转换
                $evaluateman = $this->employee->getWidgetRow(array('uid'=>$evaluate['uid'],'cols'=>array('realname,mobile')));   //查找评估师信息
                /*组装评估所需要的数据*/
                $data['evaluate'] = array(
                    'person' => $evaluateman,                            //个人信息赋值
                    'car_price' => $evaluate['car_price'],             //评估价格
                    'explain' => $evaluate['explain'],             //评估说明
                    'created' => $evaluate['created']              //评估时间
                );
                /*车辆详情*/
                if(!empty($evaluate['car_info']['assess'])){
                    if(!empty($evaluate['car_info']['assess']['car_credentials'])){
                        $car_credentials = $evaluate['car_info']['assess']['car_credentials'];
                    }else{
                        $car_credentials = 0;
                    }
                    $data['condition'] = array(
                        'plate_no'          => $one['plate_no'],                                   //车牌号
                        'pro_date'          => $evaluate['car_info']['assess']['pro_date'],         //车牌号的出厂日期
                        'gear_type'         => !empty($evaluate['car_info']['assess']['model_info']['gear_type'])?$evaluate['car_info']['assess']['model_info']['gear_type']:'无',         //变速箱
                        'liter'             => $evaluate['car_info']['assess']['model_info']['liter'],         //排量
                        'color'             => $evaluate['car_info']['assess']['color'],         //颜色
                        'interior'          => $evaluate['car_info']['assess']['color'],         //内饰
                        'surface'           => $evaluate['car_info']['assess']['surface'],         //漆面
                        'work_state'        => $evaluate['car_info']['assess']['work_state'],         //工况
                        'vin'               => $one['car_vin'],                                         //车vin码
                        'vpoints'           => $evaluate['car_info']['assess']['vpoints'],         //违章分数
                        'violation'         => $evaluate['car_info']['assess']['violation'],         //几个12分
                        'fine'              => $evaluate['car_info']['assess']['fine'],                //罚款多少
                        'carkey'            => $evaluate['car_info']['assess']['carkey'],         //几把钥匙
                        'car_credentials'   => $car_credentials,         //是否有车辆登记证书
                        'takeoffon'         => $evaluate['car_info']['assess']['takeoffon'],         //该车辆是否脱审，1表示是，0表示否
                        'annualaudit'       => $evaluate['car_info']['assess']['annualaudit'],         //该车辆的年审日期
                    );
                    /*车辆 亮点配置*/
                    if(!empty($evaluate['car_info']['assess']['model_info']['highlight_config']) && !empty($evaluate['car_info']['assess']['highlight'])){
                        $data['liangdian']['have_highlight'] = array_merge($evaluate['car_info']['assess']['model_info']['highlight_config'],$evaluate['car_info']['assess']['highlight']);
                    }else if(!empty($evaluate['car_info']['assess']['model_info']['highlight_config']) && empty($evaluate['car_info']['assess']['highlight'])){
                        $data['liangdian']['have_highlight'] = $evaluate['car_info']['assess']['model_info']['highlight_config'];
                    }
                    /*如果有评估单，就把价格分布和走势重置，用评估单里的分布和走势*/
                    $data['fenbu']['trend'] = $evaluate['car_info']['assess']['trend'];
                    $data['fenbu']['eval_prices'] = $evaluate['car_info']['assess']['eval_prices'];
                    /*车辆精确定价*/
                    $data['c2b_price'] = $evaluate['car_info']['assess']['eval_prices']['c2b_price'];
                }
            }
            /*风控信息*/
            if(!empty($one['wind_id'])){
                $data['wind'] = $this->wind_bill->getWidgetRow(array('id'=>$one['wind_id']));                       //查找风控信息
                $data['wind']['person'] = $this->employee->getWidgetRow(array('uid'=>$data['wind']['uid'],'cols'=>array('realname,mobile'))); //风控人员
            }
            /*传递进件ID*/
            $data['id'] = $id;
        }

        $this->render($data);
    }

    /**
     *@业务流程图
     */
    public function flow(){
        $id = $this->input->get('id');
        $this->load->model('task_flow_model','task_flow');
        $where = array(
            'bizid' =>$id,
            'order' => array('id'=>'ASC')
        );
        $flow = array();
        $list = $this->task_flow->getWidgetRows($where);
        foreach($list as $key=>$value){
            $flow[] = json_decode($value['remark'],true);
        }
        $data['flow'] =$flow;
        $this->render($data);
    }


}