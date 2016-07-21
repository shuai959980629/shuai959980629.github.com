<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @评估师
 * @author zhoushuai
 * @copyright(c) 2016-05-19
 * @version
 */
class Assess extends MY_Controller
{
    public $extra_highlight=array(
        '多功能方向盘',
        '底盘升降',
        '电动后背门',
        '电吸门',
        '导航大屏',
        '导航小屏',
        '抬头显示',
        '无钥匙启动',
        '电动尾门',
        '全车摄像头'
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->model('loan_item_model','loan_item');
        $this->load->model('evaluate_bill_model','evaluate_bill');
        $this->load->model('employee_model', 'employee');
        $this->load->model('Task_adapt_model','task_adapt');
        $this->load->model('Task_allot_model','task_allot');
        $this->load->library('CarService');
    }

    /**
     * @评估
     */
    public function index(){
        $id = $this->input->get('id');
        //判断传递过来的ID是否为空
        if(empty($id)) redirect('/order');
        //判断这个进件是否是正确的
        $where = array('id'=>$id,'is_del'=>1);
        $data = $this->loan_item->getWidgetRow($where);
        if(empty($data)) redirect('/order');
        //转换扩展内容
        $data['extend'] = json_decode($data['extend'],true);
        //判断这个单子是否已经提交了评估
        $evaluate = $this->evaluate_bill->getWidgetRow(array('aid'=>$id));
        if(!empty($evaluate)){
            $car_info = json_decode($evaluate['car_info'],true);
            /*判断assess数据是否为空*/
            if(!empty($car_info['assess'])){
                $data['nature']['color'] = $car_info['assess']['color'];        //传递颜色过去
                $data['nature']['interior'] = $car_info['assess']['interior'];        //传递内饰状况过去
                $data['nature']['surface'] = $car_info['assess']['surface'];        //传递漆面状况过去
                $data['nature']['work_state'] = $car_info['assess']['work_state'];        //传递工况过去
            }
            /*判断是否确认车型*/
            if(!empty($car_info['is_confirm'])) $data['is_confirm'] = $car_info['is_confirm'];
        }
        /*赋值$data['evaluate']，判断是否是续费*/
        if(!empty($data['extend']['car_info'])){
            $data['evaluation'] = $data['extend']['car_info'];
            $data['evaluation']['mile'] = $data['extend']['car_api']['mile'];
        }else{
            $data['xufei'] = 1;
        }
        //赋值该进件的ID
        $data['id'] = $id;
        //查找现在的评估师是否是组长
        $whereassess = array('uid'=>$_SESSION['user']['uid']);
        $assess = $this->employee->getWidgetRow($whereassess);
        if($assess['superior']==0){
            //评估师成员
            $where = array(
                'superior'=>$_SESSION['user']['uid'],
                'office'=>'assess',//评估师
                'status'=>'allow',
                'stores'=>$assess['stores'],
            );
            $data['assesslist'] = $this->employee->getWidgetRows($where);
        }
        //业务员的资料
        $where = array('uid'=>$data['employee']);
        $employee = $this->employee->getEmployeeRow($where);
        $data['salesman']['realname'] = $employee['realname'];
        $data['salesman']['mobile'] = $employee['mobile'];
        $data['salesman']['time'] = date('Y-m-d H:i:s',$data['created']);
        $data['salesman']['location'] = $data['extend']['car_info']['location'];
        $data['salesman']['kehu_amount'] = $data['extend']['car_info']['kehu_amount'];
        $data['salesman']['comment'] = $data['extend']['car_info']['comment'];
        $data['salesman']['plate_no'] = $data['plate_no'];
        /*加载视图*/
        $this->render($data);
    }


    public function detail(){
        if($this->input->get()) $id = $this->input->get('id');
        if($this->input->post()) $id = $this->input->post('id');
        //判断是否有ID传过来
        if(empty($id)) redirect('/order');
        //判断这个进件是否是正确的
        $where = array('id'=>$id,'is_del'=>1);
        $data = $this->loan_item->getWidgetRow($where);
        if(empty($data)) redirect('/order');
        //转换扩展内容
        $data['extend'] = json_decode($data['extend'],true);
        /*这里再判断car_info是否为空，防止没有走上面的程序，直接走到这里*/
        if(empty($data['extend']['car_info'])) redirect('/order');
        if(!empty($_POST)){
            $color = $this->input->post('color');
            $interior = $this->input->post('interior');
            $surface = $this->input->post('surface');
            $work_state = $this->input->post('work_state');
        }else if(!empty($evaluate['car_info']['assess'])){
            $color = $evaluate['car_info']['assess']['color'];
            $interior = $evaluate['car_info']['assess']['interior'];
            $surface = $evaluate['car_info']['assess']['surface'];
            $work_state = $evaluate['car_info']['assess']['work_state'];
        }else{
            $color = '白色';
            $interior = '优';
            $surface = '优';
            $work_state = '优';
        }

        $regDate = $data['extend']['car_api']['regDate'];
        $rgDtArr = explode('-',$regDate);
        if($rgDtArr[0] > $data['extend']['car_info']['maxYear']){
            $data['extend']['car_api']['regDate'] = $data['extend']['car_info']['maxYear'].'-12';
        }
        if($rgDtArr[0] < $data['extend']['car_info']['minYear']){
            $data['extend']['car_api']['regDate'] = $data['extend']['car_info']['minYear'].'-1';
        }

        //系统估值
        $dta1=array(
            'modelId'=>$data['extend']['car_api']['modelId'],
            'regDate'=>$data['extend']['car_api']['regDate'],
            'mile'=> $data['extend']['car_api']['mile'],
            'zone'=> $data['extend']['car_api']['city'],
            'color'=>$color,
            'interior'=>$interior,
            'surface'=>$surface,
            'work_state'=>$work_state,
        );
        $guzhi= $this->carservice->getUsedCarPriceAnalysis($dta1);
        if(!$guzhi['status']) redirect('/order');
        //调取亮点配置
        $this->load->config('highlight');
        $highlight =$this->config->item('highlight');
        $data['highlight'] = $highlight;
        /*默认为0*/
        $is_confirm = 0;
        /*查找evaluate_bill表是否有记录*/
        $evaluate = $this->evaluate_bill->getWidgetRow(array('aid'=>$id));
        $evaluate['car_info'] = json_decode($evaluate['car_info'],true);
        /*如果保存了草稿，这里要追加车辆详情其他参数*/
        if(!empty($evaluate['car_info']['assess'])){
            $data['car_info'] = $evaluate['car_info']['assess'];          //赋值，方便清除evaluate的car_info
            $data['car_details']['vpoints'] = $evaluate['car_info']['assess']['vpoints'];      //分数
            $data['car_details']['violation'] = $evaluate['car_info']['assess']['violation'];      //几个12分
            $data['car_details']['fine'] = $evaluate['car_info']['assess']['fine'];      //罚款多少钱
            $data['car_details']['carkey'] = $evaluate['car_info']['assess']['carkey'];      //几把钥匙
            $data['car_details']['takeoffon'] = $evaluate['car_info']['assess']['takeoffon'];      //是否脱审
            $data['car_details']['annualaudit'] = $evaluate['car_info']['assess']['annualaudit'];      //年审时间
            $data['car_details']['pro_date'] = $evaluate['car_info']['assess']['pro_date'];               //出厂日期
            if(!empty($evaluate['car_info']['assess']['car_credentials'])){
                $data['car_details']['car_credentials'] = $evaluate['car_info']['assess']['car_credentials'];               //出厂日期
            }
        }
        /*把确认车型的状态加上，传到页面，确认数据能正确添加到表里*/
        if(!empty($evaluate['car_info']['is_confirm'])) $is_confirm = 1;
        /*清除car_info*/
        unset($evaluate['car_info']);
        /*清除car_info后，把评估其他数据保存到一个数组，供数据调取*/
        $data['car_evaluate'] = $evaluate;
        /*判断保存的数据是否有亮点配置*/
        if(!empty($data['car_info']['highlight'])) $data['extend']['extra_highlight'] = array_merge($data['car_info']['highlight'],$guzhi['model_info']['highlight_config']);

        /*赋值$data['evaluate']*/
        $data['evaluation'] = $data['extend']['car_info'];
        $data['evaluation']['mile'] = $data['extend']['car_api']['mile'];

        $data['extra_highlight'] = $this->extra_highlight;
        //车辆详情  信息显示
        $data['car_details']['liter'] = $guzhi['model_info']['liter'];                  //排量
        $data['car_details']['gear_type'] = $guzhi['model_info']['gear_type'];          //变速箱
        $data['car_details']['color']=$color;                       //颜色
        $data['car_details']['interior']=$interior;                 //内饰
        $data['car_details']['surface']=$surface;                   //漆面
        $data['car_details']['work_state']=$work_state;             //工况
        $data['car_details']['plate_no'] = $data['plate_no'];               //车牌
        $data['car_details']['car_vin'] = $data['car_vin'];               //车牌
        $data['extend']['guzhi']=$guzhi;          //系统估值数据传递
        $data['id'] = $id;                      //传递ID

        //业务员的资料
        $where = array('uid'=>$data['employee']);
        $employee = $this->employee->getEmployeeRow($where);
        $data['salesman']['realname'] = $employee['realname'];
        $data['salesman']['mobile'] = $employee['mobile'];
        $data['salesman']['time'] = date('Y-m-d H:i:s',$data['created']);
        $data['salesman']['location'] = $data['extend']['car_info']['location'];
        $data['salesman']['kehu_amount'] = $data['extend']['car_info']['kehu_amount'];
        $data['salesman']['comment'] = $data['extend']['car_info']['comment'];
        $data['salesman']['plate_no'] = $data['plate_no'];
        /*传递隐藏的数据过去*/
        $car = array(
            'assess' => array(
                'trend' => $guzhi['trend'],                 //车辆的今两年的价格趋势
                'eval_prices' => $guzhi['eval_prices'],    //车辆估值
                'model_info' => $guzhi['model_info'],      //车辆的亮点配置，变数箱，排量等
                'color' => $color,      //车辆颜色
                'interior' => $interior,    //车辆内饰状况
                'surface' => $surface,        //车辆漆面状况
                'work_state' => $work_state,  //车辆工况状况
            ),
            'is_confirm' => $is_confirm
        );
        $data['car'] = json_encode($car);
        $this->render($data);
    }

    public function draft(){
        $id = $this->input->post('id');             //进件ID
        if(empty($id)) redirect('/order');
        $where = array('id'=>$id,'is_del'=>1);
        $loan = $this->loan_item->getWidgetRow($where);
        if(empty($loan)) $this->return_client(0,array('redirect_uri'=>'/order'), '不存在该进件！');
        $car = $this->input->post('car');                //获得传过来的车辆隐藏数据
        $car_info = json_decode($car,true);
        /*数据重组*/
        $explain = '';                     //评估说明
        $car_price = 0;                     //评估师给的价格
        foreach($_POST['carinfo']  as $key=>$val){
            if($key == 'car_vin' || $key == 'plate_no'){
                $extend[$key] = $val;
            }else if($key == 'explain'){
                $explain = $val;               //评估说明
            }else if($key == 'car_price'){
                $car_price = $val;
            }else{
                $car_info['assess'][$key] = $val;       //内容扩展
            }
        }
        /*插入oa_evaluate_bill表*/
        $search = $this->evaluate_bill->getWidgetRow(array('aid'=>$id));
        if(empty($search)){
            $created = time();
        }else{
            $created = $search['created'];
        }
        $dta2 = array(
            'aid' => $id,
            'car_price' =>  $car_price,               //评估价格
            'car_info' => json_encode($car_info),   //内容扩展
            'explain' => $explain,                    //评估说明
            'status' => 'draft',                    //评估状态
            'uid' => $_SESSION['user']['uid'],      //提交人ID
            'created' => $created,                  //添加时间
            'modify_time' => date('Y-m-d H:i:s'),   //修改时间
        );
        /*先掉接口*/
        $res = $this->task_list->myTask(array('aid'=>$id));
        if(!$res['status']) $this->return_client(0,null,$res['error']);

        if(empty($search)){
            $extend['evaluate_id'] = $this->evaluate_bill->create($dta2);
        }else{
            $this->evaluate_bill->save($dta2,$search['id']);
        }
        $this->loan_item->save($extend,$id);
        $this->return_client(1, array('redirect_uri'=>'/user'), '保存成功！');
    }


    public function saveAssess(){
        $id = $this->input->post('id');             //进件ID
        if(empty($id)) redirect('/order');
        $where = array('id'=>$id,'is_del'=>1);
        $loan = $this->loan_item->getWidgetRow($where);
        if(empty($loan)) $this->return_client(0,array('redirect_uri'=>'/order'), '保存失败！');
        $car = $this->input->post('car');                //获得传过来的车辆隐藏数据
        $car_info = json_decode($car,true);
        /*数据重组*/
        $explain = '';                     //评估说明
        $car_price = 0;                     //评估师给的价格
        foreach($_POST['carinfo']  as $key=>$val){
            if($key == 'car_vin' || $key == 'plate_no'){
                $extend[$key] = $val;
            }else if($key == 'explain'){
                $explain = $val;               //评估说明
            }else if($key == 'car_price'){
                $car_price = $val;
            }else{
                $car_info['assess'][$key] = $val;       //内容扩展
            }
        }
        /*判断是够为空*/
        if(empty($car_price)) $this->return_client(0,null,'请输入评估价格！');
        if(empty($extend['car_vin'])) $this->return_client(0,null,'请输入车辆识别码！');
        if(empty($extend['plate_no'])) $this->return_client(0,null,'请输入车牌号！');
        if(empty($explain))  $this->return_client(0,null,'请输入车况！');

        /*插入oa_evaluate_bill表*/
        $search = $this->evaluate_bill->getWidgetRow(array('aid'=>$id));
        if(empty($search)){
            $created = time();
        }else{
            $created = $search['created'];
        }
        $dta2 = array(
            'aid' => $id,
            'car_price' =>  $car_price,               //评估价格
            'car_info' => json_encode($car_info),   //内容扩展
            'explain' => $explain,                    //评估说明
            'status' => 'done',                    //评估状态
            'uid' => $_SESSION['user']['uid'],      //提交人ID
            'created' => $created,                  //添加时间
            'modify_time' => date('Y-m-d H:i:s'),   //修改时间
        );
        /*先掉接口*/
        $res = $this->task_list->myTask(array('aid'=>$id));
        if(!$res['status']) $this->return_client(0,null,$res['error']);
        /*事务开始*/
        if(empty($search)){
            $extend['evaluate_id'] = $this->evaluate_bill->create($dta2);
        }else{
            $this->evaluate_bill->save($dta2,$search['id']);
        }
        $this->loan_item->save($extend,$id);
        //完成自己的任务
        $param = array('aid'=>$id,'type'=>'appraise');
        $rest  = $this->task_list->done($param);
        if($rest===false){
            $this->return_client(0, array('redirect_uri'=>'/user'),'保存失败！');
        }else{
            $this->task_adapt->taskWind(array('aid'=>$id));
            //业务流程
            $this->load->model('task_flow_model','task_flow');
            $param = array(
                'aid'   => $id,
                'type'  => 'ckl_assessbmit'
            );
            $res = $this->task_flow->flow($param);
            $this->return_client(1, array('redirect_uri'=>'/user'), '保存成功！');
        }


    }


    public function light(){
        $lght=$this->input->post('highlight');
        $this->load->config('highlight');
        $highlight =$this->config->item('highlight');
        $data = array();
        foreach($lght as $key=>$list){
            $data[]=array(
                'name'=>$list,
                'code'=>$highlight[$list]
            );
        }
        $this->return_client(1,$data,'亮点配置！');
    }

    /*分配进件*/
    public function fenpei(){
        $arr = $this->input->post();
        $data['aid'] = $arr['id'];
        $res = $this->task_list->myTask(array('aid'=>$arr['id']));
        if(!$res['status']) $this->return_client(0,null,$res['error']);
        $assess = json_decode($arr['assess'],true);
        $data['uid'] = $assess['uid'];
        if(empty($data['uid']) || empty($data['aid'])) $this->return_client(0,'','进件ID丢失，或者分配的评估师ID丢失！');
        $one = $this->task_allot->allotTaskAssess($data);
        if($one['status'] == 1){
            //业务流程
            $this->load->model('task_flow_model','task_flow');
            $param = array(
                'aid'       => $arr['id'],
                'realname'  => $assess['realname'],
                'type'      => 'ckl_asessAllot'
            );
            $res = $this->task_flow->flow($param);
            $this->return_client($one['status'],'','分配成功！');
        }else{
            $this->return_client($one['status'],'',$one['error']);
        }

    }

    /*确认车型*/
    public function car_confirm(){
        $id = $this->input->post('id');
        $assess = $this->evaluate_bill->getWidgetRow(array('aid'=>$id));
        /*掉接口*/
        $res = $this->task_list->myTask(array('aid'=>$id));
        if(!$res['status']) $this->return_client(0,null,$res['error']);
        /*事务开始*/
        $this->db->trans_begin();
        if(!empty($assess)){
            $assess['car_info'] = json_decode($assess['car_info'],true);
            $assess['car_info']['is_confirm'] = 1;
            $assess['car_info'] = json_encode($assess['car_info']);
            $this->evaluate_bill->save($assess,$assess['id']);
            $loan_item['evaluate_id'] = $assess['id'];
        }else{
            $car_info = array('is_confirm'=>1);
            $data = array(
                'car_price' => 0,
                'aid'=>$id,
                'car_info' => json_encode($car_info),
                'status' => 'draft',
                'uid' => $_SESSION['user']['uid'],
                'created' => time(),
                'modify_time' => date('Y-m-d H:i:s'),
            );
            $loan_item['evaluate_id'] = $this->evaluate_bill->create($data);
        }
        $this->loan_item->save($loan_item,$id);

        //推送通知给业务员。未完待续。。。。 TODO


        //业务流程
        $this->load->model('task_flow_model','task_flow');
        $param = array(
            'aid'   => $id,
            'type'  => 'ckl_asessconfirm'
        );
        $res = $this->task_flow->flow($param);
        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $this->return_client(0);
        }else{
            $this->db->trans_commit();
            $this->return_client(1);
        }
    }

    /**
     * @评估师。补单。修改车型。
     */
    public function car(){
        $data  = array();
        $id    = $this->input->get('id');
        $where = array('id'=>$id,'is_del'=>1);
        $data  = $this->loan_item->getWidgetRow($where);
        if(empty($data)){
            redirect('/order');
        }
        $extend   = json_decode($data['extend'],true);
        $data['car_info'] = $extend['car_info'];
        $data['car_api']  = $extend['car_api'];
        $city = array('zone'=>$_SESSION['user']['zone']);
        $dta = $this->carservice->getProvince($city);
        $data['brand_list'] = $this->carservice->getCarBrandList();
        $data = array_merge($data,$dta);
        $where = array('uid'=>$_SESSION['user']['uid']);
        $employee = $this->employee->getEmployeeRow($where);
        $data['uid'] = $employee['uid'];
        $data['realname'] = $employee['realname'];
        $data['region'] = $employee['region'];
        $data['office'] = $employee['office'];
        $data['stores'] = $employee['stores'];
        $this->render($data);
    }


    public function savecar(){
        $car      = $this->input->post();
        $plate_no = $car['plate_no'];
        $id       = $car['id'];
        $res = $this->task_list->myTask(array('aid'=>$id));
        if(!$res['status']){
            $this->return_client(0,null,$res['error']);
        }
        if(empty($plate_no)){
            $this->return_client(0,null,'请输入车牌号！');
        }
        $plate_no = strtoupper($plate_no);
        $result=$this->carservice->validate_plate($plate_no);
        if(!$result){
            $this->return_client(0,null,'请输入正确的车牌号！');
        }

        $where = array('id'=>$id,'is_del'=>1);
        $data  = $this->loan_item->getWidgetRow($where);
        if(empty($data)){
            $this->return_client(0,null,'进件不存在！');
        }
        $extend = json_decode($data['extend'],true);
        $maxYear = !empty($car['maxYear'])?$car['maxYear']:$extend['car_info']['maxYear'];
        $minYear = !empty($car['minYear'])?$car['minYear']:$extend['car_info']['minYear'];
        $regDate = $car['regDate'];
        $rgDtArr = explode('-',$car['regDate']);
        if($rgDtArr[0] > $maxYear){
            $car['regDate'] = $maxYear.'-12';
        }
        if($rgDtArr[0] < $minYear){
            $car['regDate'] = $minYear.'-1';
        }
        $result= $this->carservice->getUsedCarPrice($car);
        if(!$result['status']){
            $msg = isset($result['msg'])?$result['msg']:'上牌日期超出范围！';
            $this->return_client(0,null,$msg);
        }
        $extend   = json_decode($data['extend'],true);

        $car_info = $extend['car_info'];
        $car_api  = $extend['car_api'];

        $car = array_merge($car,$result);
        $car['regDate']      = $regDate;
        $car['registerDate'] = date("Y年m月",strtotime($car['regDate']));
        if($car['provName'] == $car['cityName']){
            $car['zone'] = $car['cityName'];
        }else{
            $car['zone'] = $car['provName'].$car['cityName'];
        }
        //车辆信息
        if(empty($car_info)){
            $car_info = array(
                'brandName'         =>$car['brandName'],
                'seriesName'        =>$car['seriesName'],
                'modelName'         =>$car['modelName'],

                'minYear'           =>$car['minYear'],
                'maxYear'           =>$car['maxYear'],

                'provName'          =>$car['provName'],
                'cityName'          =>$car['cityName'],
                'zone'              =>$car['zone'],

                'eval_price'        =>$car['eval_price'],
                'low_price'         =>$car['low_price'],
                'good_price'        =>$car['good_price'],
                'high_price'        =>$car['high_price'],
                'dealer_buy_price'  =>$car['dealer_buy_price'],
                'individual_price'  =>$car['individual_price'],
                'dealer_price'      =>$car['dealer_price'],

                'price'             =>$car['price'],
                'title'             =>$car['title'],
                'car_logo_url'      =>$car['car_logo_url'],
                'registerDate'      =>$car['registerDate'],

                'location'          =>'',
                'kehu_amount'       =>'',
                'comment'           =>'',
            );
        }else{
            foreach($car_info as $key=>&$list){
                if(array_key_exists($key,$car)){
                    $car_info[$key] = $car[$key];
                }
            }
        }

        //api接口需要的参数
        if(empty($car_api)){
            $car_api=array(
                'modelId'           =>$car['modelId'],
                'brandId'           =>$car['brandId'],
                'seriesId'          =>$car['seriesId'],
                'regDate'           =>$car['regDate'],
                'mile'              =>$car['mile'],
                'prov'              =>$car['prov'],
                'city'              =>$car['city'],
            );
        }else{
            foreach($car_api as $key=>&$list){
                if(array_key_exists($key,$car)){
                    $car_api[$key] = $car[$key];
                }
            }
        }

        $extend = array(
            'car_info'  =>$car_info,
            'car_api'   =>$car_api
        );
        $extend = json_encode($extend);
        $dta1 = array(
            'plate_no'      =>$plate_no,//车牌号
            'extend'        =>$extend,//车辆详细「将内容字段序列化，使用json对象格式保存」
        );
        $result = $this->loan_item->save($dta1,$id);
        if($result) {
            //推送通知给业务员。未完待续。。。。TODO

            //业务流程
            $this->load->model('task_flow_model','task_flow');
            $param = array(
                'aid'   => $id,
                'type'  => 'ckl_asessupdt'
            );
            $res = $this->task_flow->flow($param);
            $this->return_client(1, array('redirect_uri' => '/assess?id='.$id), '修改成功！');
        }else{
            $this->return_client(0,null,'修改失败！');
        }
    }
}
