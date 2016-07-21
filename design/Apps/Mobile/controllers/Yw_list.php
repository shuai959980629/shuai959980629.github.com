<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @我的业务（任务：自己的单子或其他人的单子 ），我的进件（自己做的单子）
 * @author zhoushuai
 * @copyright(c) 2016-05-19
 * @version
 */
class Yw_list extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Employee_model', 'employee');
        $this->load->model('Loan_item_model', 'loan_item');
        $this->load->model('Evaluate_bill_model', 'evaluate_bill');
        $this->load->model('Wind_bill_model', 'wind_bill');
		$this->load->model('Yw_data_model', 'yw_data');
    }
	

    public function more(){
        $page = $this->input->get('page') ? $this->input->get('page') : 1;
        $type = $this->input->get('type') ? $this->input->get('type') : '';
        switch($type){
            case 'as_pending':
                $data=$this->yw_data->getAslist(array('employee'=>$_SESSION['user']['uid'],'status'=>'pending','page'=>$page));
                break;
            case 'as_done':
                $data=$this->yw_data->getAslist(array('employee'=>$_SESSION['user']['uid'],'status'=>'done','page'=>$page));
                break;
            case 'yw_wait':
                $data = $this->yw_data('wait',$page);
                break;
            case 'yw_done':
                $data = $this->yw_data('done',$page);
                break;
            default:
                $data=array();
                break;
        }
        $this->load->view('yw_list/more',$data);
    }

    //我的业务入口 //进行中业务默认
    public function as_pending(){
        $data=$this->yw_data->getAslist(array('employee'=>$_SESSION['user']['uid'],'status'=>'pending'));
        $this->render($data,'/yw_list/list');
    }

	public function as_done(){//已完成业务
		$data=$this->yw_data->getAslist(array('employee'=>$_SESSION['user']['uid'],'status'=>'done'));
		$this->render($data,'/yw_list/list');
	}


	public function yw_wait(){//待处理业务
		$data = $this->yw_data('wait');
		$this->render($data,'/yw_list/list');	
	}


	public function yw_done(){//已完成业务
        $data = $this->yw_data('done');
		$this->render($data,'/yw_list/list');	
	}



    private function yw_data($status,$page=1){
        $data = array();
        switch($_SESSION['user']['office']){
             case 'salesman':
                $data=$this->yw_data->getYwlist(array('uid'=>$_SESSION['user']['uid'],'status'=>$status,'page'=>$page,'type'=>array('subpact')));
                break;
            case 'assess':
                 $data=$this->yw_data->getYwlist(array('uid'=>$_SESSION['user']['uid'],'status'=>$status,'page'=>$page,'type'=>array('appraise','subpact')));
                break;
            case 'wind':
               $data=$this->yw_data->getYwlist(array('uid'=>$_SESSION['user']['uid'],'status'=>$status,'page'=>$page,'type'=>array('risktrol','subpact')));
                break;
        }
        return $data;
    }


}