<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @业务进件
 * @author zhoushuai
 * @copyright(c) 2016-05-19
 * @version
 */
class Car extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('loan_item_model','loan_item');
        $this->load->model('employee_model', 'employee');
        $this->load->model('Task_adapt_model', 'task_adapt');
        $this->load->library(array('CarService'));
    }

    public function series(){
        $brandId = $this->input->get('bid');
        $series  = $this->carservice->getCarSeriesList($brandId);
        die($series);
    }

    public function model(){
        $seriesId = $this->input->get('sid');
        $model   = $this->carservice->getCarModelList($seriesId);
        die($model);
    }

    public function city(){
        $pid  = $this->input->get('pid');
        $city = $this->carservice->getCityByProvId($pid);
        die($city);
    }


    /**
     *
     */
    public function index(){
        $data  = array();
        $data  = $this->input->get();
        $data['aid']   = !empty($data['aid'])?$data['aid']:0;
        $aid     = $data['aid'];
        $regDate = $data['regDate'];
        $rgDtArr = explode('-',$data['regDate']);
        if($rgDtArr[0] > $data['maxYear']){
            $data['regDate'] = $data['maxYear'].'-12';
        }
        if($rgDtArr[0] < $data['minYear']){
            $data['regDate'] = $data['minYear'].'-1';
        }
        $result  = $this->carservice->getUsedCarPrice($data);
        if(!$result['status']){
            redirect('/start');
        }
        $data = array_merge($data,$result);
        $data['regDate']      = $regDate;
        $data['registerDate'] = date("Y年m月",strtotime($data['regDate']));
        if($data['provName']==$data['cityName']){
            $data['zone'] = $data['cityName'];
        }else{
            $data['zone'] = $data['provName'].$data['cityName'];
        }
        $data['car'] = json_encode($data);
        if($aid){
            $loanItem = $this->loan_item->get($data['aid']);
            $extend = json_decode($loanItem['extend'],true);
            $data['plate_no']    = $loanItem['plate_no'];
            $data['deal_flag']   = $loanItem['deal_flag'];
            $data['location']    = $extend['car_info']['location'];
            $data['kehu_amount'] = $extend['car_info']['kehu_amount'];
            $data['comment']     = $extend['car_info']['comment'];
        }
        $data['token'] = $_SESSION['token'] =md5(time());
        $this->render($data);
    }


    /**
     * @提交评估
     */
    public function evaluate(){
        $plate_no = $this->input->post('plate_no');
        $aid      = $this->input->post('aid');
        if(empty($plate_no)){
            $this->return_client(0,null,'请输入车牌号！');
        }
        $plate_no = strtoupper($plate_no);
        $result=$this->carservice->validate_plate($plate_no);
        if(!$result){
            $this->return_client(0,null,'请输入正确的车牌号！');
        }

        $car = $this->input->post('car');
        $extend = json_decode($car,true);
        $where = array('uid'=>$_SESSION['user']['uid']);
        $salesman = $this->employee->getWidgetRow($where);
        $car_vin  = strtoupper($extend['vin']);
        $modelId  = $extend['modelId'];

        $extend = array('car_info' =>array(
            'brandName'         =>$extend['brandName'],
            'seriesName'        =>$extend['seriesName'],
            'modelName'         =>$extend['modelName'],

            'minYear'           =>$extend['minYear'],
            'maxYear'           =>$extend['maxYear'],

            'provName'          =>$extend['provName'],
            'cityName'          =>$extend['cityName'],
            'zone'              =>$extend['zone'],

            'eval_price'        =>$extend['eval_price'],
            'low_price'         =>$extend['low_price'],
            'good_price'        =>$extend['good_price'],
            'high_price'        =>$extend['high_price'],
            'dealer_buy_price'  =>$extend['dealer_buy_price'],
            'individual_price'  =>$extend['individual_price'],
            'dealer_price'      =>$extend['dealer_price'],

            'price'             =>$extend['price'],
            'title'             =>$extend['title'],
            'car_logo_url'      =>$extend['car_logo_url'],
            'registerDate'      =>$extend['registerDate'],

            'location'          =>$_POST['location'],
            'kehu_amount'       =>$_POST['kehu_amount'],
            'comment'           =>$_POST['comment'],
        ),
          'car_api'  =>array(
            'modelId'           =>$extend['modelId'],
            'brandId'           =>$extend['brandId'],
            'seriesId'          =>$extend['seriesId'],
            'regDate'           =>$extend['regDate'],
            'mile'              =>$extend['mile'],
            'prov'              =>$extend['prov'],
            'city'              =>$extend['city'],
          )
        );
        $extend = json_encode($extend);
        $deal_flag = empty($_POST['deal_flag'])?'pledge':trim($_POST['deal_flag']);
        //先业务操作日志 check log
        $dta1 = array(
            'stores'        =>$salesman['stores'],
            'car_vin'       =>$car_vin,//车架号
            'plate_no'      =>$plate_no,//车牌号
            'employee'      =>$salesman['uid'],//业务员
            'deal_flag'     =>$deal_flag,//借款交易类型「mortgage：抵押，pledge：质押」
            'extend'        =>$extend,//车辆详细「将内容字段序列化，使用json对象格式保存」
            'status'        =>'appraise',//appraise 评估定价
            'created'       =>time(),//创建时间
        );
        $result = $this->loan_item->save($dta1,$aid);
        //推送任务。
        $task = $this->task_adapt->taskAssessLeader(array('aid'=>$result));
        if($task['status']==1) {
            //业务流程
            $this->load->model('task_flow_model','task_flow');
            $param = array(
                'aid'   => $result,
                'type'  => 'ckl_salesmansbmit'
            );
            $res = $this->task_flow->flow($param);
            $this->return_client(1, array('redirect_uri' => '/order'), '提交成功，该信息已推送至评估师');
        }else{
            $this->return_client(0,$result,$task['error']);
        }
    }
}
