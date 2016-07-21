<?php
class MY_Controller extends CI_Controller
{
	public $theme          = '';
	public $lay            = 'layout';
	public $expire		   = 60;		//缓存时间

	public function __construct()
	{
		parent::__construct();
		$this->output->enable_profiler(false);
	}



	protected function render($data = array(), $view = '', $content_type = 'auto', $return = FALSE)
	{
		if ($content_type == 'auto') {
			$content_type = $this->input->is_ajax_request() ? 'json' : 'html';
		}
		switch ($content_type) {
			case 'json':
				if ($return === FALSE) {
					$this->output->enable_profiler(FALSE);
					$this->output
						->set_status_header(200)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($data));
				}
				else {
					return json_encode($data);
				}
				break;
			case 'html':
			default:
				if (empty($view)) {
					$view = $this->router->class . '/' . $this->router->method . '.php';
				}
				$data['_COMMON'] = $this->config->item('common');
				return $this->layout->load($data, $this->lay, $view, $this->theme, $return);
				break;
		}
	}
	/**
	 * 返回客户端信息通用函数
	 * @param number $status 返回状态
	 * @param string $data	包含的数据
	 * @param string $msg	状态说明
	 */
	public function return_client($status = 0, $data = null, $msg = null)
	{
		$requesttype = $this->input->is_ajax_request();//ajax请求
		if($requesttype){
			header('Content-type: application/json;charset=utf-8');
			$resp = array(
				'status' => $status,
				'data' => empty($data) ? null : $data,
				'msg' => empty($msg) ? null : $msg,
				'time' => date('Y-m-d H:i:s', time()));//microtime(true) - $starttime);
			$json = json_encode($resp);
			die($json);
		}
	}


}
