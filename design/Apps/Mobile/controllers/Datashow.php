<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @业绩
 * @author zhoushuai
 * @copyright(c) 2016-05-19
 * @version
 */
class DataShow extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('datashow_model','datashow');
    }

    /**
     * @今日全国总业绩
     */
    public function today(){
        $stores=$this->input->get("stores");
        $data = $this->datashow->today($stores);
        $this->render($data);
    }

    /*正在做的*/
    public function doing(){
        $page = $this->input->get('page') ? $this->input->get('page') : 1;//页码
        $date = $this->input->get('date') ? $this->input->get('date') : '';//传递过来的最后显示日期
        $stores = $this->input->get('stores') ? $this->input->get('stores') : '';//门店

        switch($_SESSION['user']['office']){
            case 'admin':
                $data=$this->datashow->getDoingList($page,$stores);
                break;
            case 'manager':
                $data=$this->datashow->getDoingList($page,$stores);
                break;
            case 'storemg':
                $data=$this->datashow->getDoingList($page,$stores);
                break;
            default:
                $data=array();
                break;
        }
        $data['office'] = $_SESSION['user']['office'];                //复制判断
        $data['date'] = $date;
        $this->load->view('datashow/doing',$data);
    }

    /*正在做的*/
    public function done(){
        $page = $this->input->get('page') ? $this->input->get('page') : 1;//页码
        $date = $this->input->get('date') ? $this->input->get('date') : '';//传递过来的最后显示日期
        $stores = $this->input->get('stores') ? $this->input->get('stores') : '';//门店
        switch($_SESSION['user']['office']){
            case 'admin':
                $data=$this->datashow->getDongList($page,$stores);
                break;
            case 'manager':
                $data=$this->datashow->getDongList($page,$stores);
                break;
            case 'storemg':
                $data=$this->datashow->getDongList($page,$stores);
                break;
            default:
                $data=array();
                break;
        }
        $data['office'] = $_SESSION['user']['office'];                //复制判断
        $data['date'] = $date;
        $this->load->view('datashow/done',$data);
    }

    /*拒贷*/
    public function deny(){
        $page = $this->input->get('page') ? $this->input->get('page') : 1;//页码
        $date = $this->input->get('date') ? $this->input->get('date') : '';//传递过来的最后显示日期
        $stores = $this->input->get('stores') ? $this->input->get('stores') : '';//门店
        switch($_SESSION['user']['office']){
            case 'admin':
                $data=$this->datashow->getDenyList($page,$stores);
                break;
            case 'manager':
                $data=$this->datashow->getDenyList($page,$stores);
                break;
            case 'storemg':
                $data=$this->datashow->getDongList($page,$stores);
                break;
            default:
                $data=array();
                break;
        }
        $data['office'] = $_SESSION['user']['office'];                //复制判断
        $data['date'] = $date;
        $this->load->view('datashow/deny',$data);
    }


    /**
     * @本月业绩
     */
    public function month(){
        $data['month']=$this->datashow->month();
        $this->render($data);
    }

    /**
     * 今日地区业绩排名
     */
    public function zone(){
        $data=$this->datashow->zone();
        $this->render($data);
    }

    /**
     * @今日销售排行
     */
    public function rank(){
        $data=$this->datashow->rank();
        $this->render($data);
    }


}
