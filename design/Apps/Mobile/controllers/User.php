<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @用户中心
 * @author zhoushuai
 * @copyright(c) 2016-05-19
 * @version
 */
class User extends MY_Controller
{
    private $limit = 10;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('employee_model', 'employee');
        $this->load->model('loan_item_model','loan_item');
        $this->load->model('datashow_model','datashow');
    }


    /**
     * @用户
     */
    public function index(){
        switch($_SESSION['user']['office']){
            case 'salesman':
                $data=$this->loan_item->cntSalesman($_SESSION['user']);
                $this->render($data);
                break;
            case 'assess':
                $data=$this->loan_item->cntAssess($_SESSION['user']);
                $this->render($data,'/user/assess');
                break;
            case 'wind':
                $data=$this->loan_item->cntWind($_SESSION['user']);
                $this->render($data,'/user/wind');
                break;
            /*管理员入口*/
            case 'admin':
                $data=$this->datashow->AdminData($_SESSION['user']);
                $data['all'] = 1;                                   //表示管理员
                $data['title'] = '个人中心-管理员';
                $this->render($data,'/user/admin');
                break;
            /*片区经理入口*/
            case 'manager':
                $data=$this->datashow->AdminData($_SESSION['user']);
                $data['title'] = '个人中心-片区经理';
                $this->render($data,'/user/admin');
                break;
            /*店长入口*/
            case 'storemg':
                $data=$this->datashow->AdminData($_SESSION['user']);
                $data['title'] = '个人中心-店长';
                $this->render($data,'/user/admin');
                break;
            default:
                $this->lgout();
                break;
        }
    }

    //风控详情页面跳转
    public function wind_news(){
        switch ($_GET['flag']) {
            case 1:
            $data['list'] = $this->loan_item->windPublic($_GET['flag']);
            $data['type'] = "本月审核总量";
                break;
            case 2:
            $data['list'] = $this->loan_item->windWaitnews();
            $data['type'] = "待处理业务";
                break;
            case 3:
            $data['list'] = $this->loan_item->windPublic($_GET['flag']);
            $data['type'] = "今日已审核车辆";
                break;
            case 4:
            $data['list'] = $this->loan_item->windPublic($_GET['flag']);
            $data['type'] = "拒绝的审核";
                break;
            case 5:
            $data['list'] = $this->loan_item->windPublic($_GET['flag']);
            $data['type'] = "完成的审核";
                break;
            default:
                show_404();
                break;
        }
        $this->render($data);
    }
    /*风控加载更多*/
    public function wind_more(){
        $page = $this->input->post('page') ? $this->input->post('page') : 1;
        $date = $this->input->post('date') ? $this->input->post('date') : date('Y-m-d');
        $flag = $this->input->post('flag') ? $this->input->post('flag') : 1;
        switch ($flag) {
            case 1:
                $data['list'] = $this->loan_item->windPublic($flag,$page);
                break;
            case 2:
                $data['list'] = $this->loan_item->windWaitnews($page);
                break;
            case 3:
                $data['list'] = $this->loan_item->windPublic($flag,$page);
                break;
            case 4:
                $data['list'] = $this->loan_item->windPublic($flag,$page);
                break;
            case 5:
                $data['list'] = $this->loan_item->windPublic($flag,$page);
                break;
            default:
                show_404();
                break;
        }
        $data['date'] = $date;
        $data['flag'] = $flag;
        $this->load->view('user/wind_more',$data);
    }

    //评估师统计页面-评估总量
    public function assess_news(){
        switch ($_GET['flag']) {
            case 1:
            $data['list'] = $this->loan_item->assessPublic($_GET['flag']);
            $data['type'] = "评估总量";
                break;
            case 2:
            $data['list'] = $this->loan_item->assessWaitnews();
            $data['type'] = "待处理业务";
                break;
            case 3:
            $data['list'] = $this->loan_item->assessPublic($_GET['flag']);
            $data['type'] = "今日评估量";
                break;
            case 4:
            $data['list'] = $this->loan_item->assessPublic($_GET['flag']);
            $data['type'] = "本月评估总量";
                break;
            default:
                show_404();
                break;
        }
        $this->render($data);
    }
    /*评估加载更多*/
    public function assess_more(){
        $page = $this->input->post('page') ? $this->input->post('page') : 1;
        $date = $this->input->post('date') ? $this->input->post('date') : date('Y-m-d');
        $flag = $this->input->post('flag') ? $this->input->post('flag') : 1;
        switch ($flag) {
            case 1:
                $data['list'] = $this->loan_item->assessPublic($flag,$page);
                break;
            case 2:
                $data['list'] = $this->loan_item->assessWaitnews($page);
                break;
            case 3:
                $data['list'] = $this->loan_item->assessPublic($flag,$page);
                break;
            case 4:
                $data['list'] = $this->loan_item->assessPublic($flag,$page);
                break;
            default:
                show_404();
                break;
        }
        $data['date'] = $date;
        $data['flag'] = $flag;
        $this->load->view('user/assess_more',$data);
    }

    //业务统计
    public function salescnt(){
        switch ($_GET['flag']) {
            case 1:
            $data['list']=$this->loan_item->getsalesmanwaitlist();
            $data['type']="待处理业务";
                break;
            case 2:
            $data['list']=$this->loan_item->getsalesmandonelist();
            $data['type']="已完成";
                break;
            case 3:
            $data['list']=$this->loan_item->getsalesmanfralist();
            $data['type']="流单";
                break;
            default:
                show_404();
                break;
        }
        $this->render($data);
    }

    //业务员销售排名
    public function tops(){
        $data['list'] = $this->loan_item->tops();
        $this->render($data);
    }

    //业务员当日总额
    public function todaysum(){
        $data = $this->loan_item->totalSale();
        $this->render($data);
    }


    //退出登陆
    public function lgout(){
        unset($_SESSION['user']);
        $this->return_client(1);
    }

}