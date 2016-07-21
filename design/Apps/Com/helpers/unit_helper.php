<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *
 * @package		Libraries
 * @author		JHOU
 * @since		Version 1.0
 */

// ------------------------------------------------------------------------

/**
 * 为图片添加水印.
 *
 * @access	public
 * @param	string 图片存放路径
 * @return	string
 */
if ( ! function_exists('create_wm_image'))
{
    function create_wm_image($file_path, $wm_pos = array('bottom', 'right'))
    {
        if (file_exists($file_path) === FALSE) {
            return FALSE;
        }

        $wm_font_path = realpath(BASEPATH . '../frameworks/fonts/zhs.ttf');
        if (file_exists($wm_font_path) === FALSE) {
            log_message('error', "not found font file: {$wm_font_path}");
            return FALSE;
        }

        $CI = & get_instance();

        $config['image_library'] = 'GD2';
        $config['source_image'] = $file_path;
        $config['wm_text'] = 'Copyright 2014 - xxx';
        $config['wm_type'] = 'text';
        $config['quality'] = 100;
        $config['dynamic_output'] = FALSE;
        $config['wm_font_path'] = $wm_font_path;
        $config['wm_font_size'] = '16';
        /*$config['wm_font_color'] = '018077';*/
        $config['wm_font_color'] = 'b2b2b2';
        $config['wm_vrt_alignment'] = $wm_pos[0];
        $config['wm_hor_alignment'] = $wm_pos[1];
        $config['wm_hor_offset'] = 10;
        $config['wm_vrt_offset'] = 2;
        /*$config['wm_padding'] = '6';*/

        /*$config['image_library'] = 'gd2';
        $config['source_image'] = $file_path;
        $config['dynamic_output'] = FALSE;
        $config['quality'] = 100;
        $config['wm_type'] = 'overlay';
        $config['wm_padding'] = '0';
        $config['wm_vrt_alignment'] = $wm_pos[0];
        $config['wm_hor_alignment'] = $wm_pos[1];
        $config['wm_hor_offset'] = 6;
        $config['wm_vrt_offset'] = 6;
        $config['wm_overlay_path'] = $wm_overlay_path;
        $config['wm_opacity'] = 90;
        $config['wm_x_transp'] = '4';
        $config['wm_y_transp'] = '4';*/
        $CI->load->library('image_lib');
        $CI->image_lib->initialize($config);
        $result = $CI->image_lib->watermark();
        if (!$result) {
            log_message('error', strip_tags($CI->image_lib->display_errors()));
        }
        $CI->image_lib->clear();
    }
}

/**
 * 标题截取.
 *
 * @access	public
 * @param	string
 * @return	string
 */
if ( ! function_exists('get_short_title'))
{
    function get_short_title($title, $len = 10, $suffix = '...')
    {
        $title = preg_replace('/\s|&nbsp;+/m', '', $title);

        if (mb_strlen($title, 'utf-8') > $len) {
            return mb_substr($title, 0 , $len, 'utf-8') . $suffix;
        }

        return $title;
    }
}

/**
 * 加载组件片断.
 *
 * @access	public
 * @param	string 组件名称
 * @param	array  组件所需数据
 * @return	string
 */
if ( ! function_exists('load_widget'))
{
    function load_widget($template, $data = array())
    {
        if (empty($template)) {
            return '';
        }

        $CI = & get_instance();
        return $CI->load->view($template, $data, TRUE);
    }
}

/**
 * 得到用于展示的时间.
 *
 * @access	public
 * @return	string
 */
if ( ! function_exists('get_show_time'))
{
    function get_show_time($time, $format = 'date')
    {
		$result = '';
		$current_time = time();
		$sub_time = $current_time - $time;
		if(60 > $sub_time)
		{
			$result = '刚刚';
		}
		if(60 <= $sub_time && 3600 >= $sub_time)
		{
			$result = floor($sub_time/60);
			$result = $result.' 分钟前';
		}
		if(3600 <= $sub_time && 86400 >= $sub_time)
		{
			$result = floor($sub_time/3600);
			$result = $result.' 小时前';
		}
		if(86400 < $sub_time)
        {
            if ($format == 'date') {
			    $result = date('m月d日', $time);
            }
            else {
			    $result = floor($sub_time/86400).'天前';
            }
		}
		return $result;
    }
}

/**
 * 广告组件.
 *
 * @access	public
 * @param	int	the ad position id.
 * @return	array
 */
if ( ! function_exists('ad_widget') )
{

	function ad_widget($id = 0)
    {
        $id = (int) $id;

        if (empty($id)) {
            return '';
        }

        $CI = & get_instance();

        $CI->load->model('ad_position_model');
        $position = $CI->ad_position_model->get_entity_by_id($id);
        if (empty($position)) {
            return '';
        }

        $CI->load->model('ad_model');
        $ads = $CI->ad_model->get_ads_by_position($id);
        if (empty($ads)) {
            return '';
        }

        foreach ($ads as $k => $v) {
            if ($v['enabled'] != 1) {
                unset($ads[$k]);
            }
            elseif (time() < $v['start_time']) {
                unset($ads[$k]);
            }
            elseif (time() > $v['end_time']) {
                unset($ads[$k]);
            }
            /*elseif($v['media_type'] == 'img' || $v['media_type'] == 'flash') {
                $ads[$k]['content'] = $ads[$k]['content'];
            }*/
        }

        $data = array(
            'ad_entries' => array_values($ads)
        );

        $_tempate_file = VIEWPATH . "widgets/adp_{$position['id']}.php";

        $CI->load->helper('file');
        $_tempate_file_info = get_file_info($_tempate_file);

        if ($_tempate_file_info === FALSE || $_tempate_file_info['date'] < strtotime($position['created'])) {
            $result = write_file($_tempate_file, $position['template']);
            if ($result === FALSE) {
                return NULL;
            }
        }

        $ads_html = $CI->load->view("widgets/adp_{$position['id']}", $data, TRUE);
        return $ads_html;
    }
}

/**
 * 加载指定角色的用户权限.
 *
 * @access	public
 * @param	array
 * @return	string
 */
if ( ! function_exists('load_priv_by_role'))
{
    function load_priv_by_role($role , $exrole = array())
    {
        $CI = & get_instance();

        $priv = array();

        $purview = $CI->config->item('purview');
        if ($purview === FALSE) {
            log_message('error', 'config/priv.php权限配置文件取不到值');
            return NULL;
        }

        $role_cfg = $CI->config->item('role');

        if ($role_cfg === FALSE || array_key_exists($role, $role_cfg) === FALSE) {
            log_message('error', "不存在的用户角色:{$role}，请检查config/role.php角色配置文件");
            return NULL;
        }

        if (count($role_cfg[$role]['modules']) == 1 && $role_cfg[$role]['modules'][0] == '*' ) {
            $priv = array_keys($purview);
        }
        else {
            $priv = array_unique(array_merge($role_cfg[$role]['modules'],$exrole));
        }
        return $priv;
    }
}

/**
 * 生成唯一主键
 *
 * @access	public
 * @param	null
 * @return	int 63bits
 */
if ( ! function_exists('unique_id'))
{
    function unique_id()
    {
        $CI        = & get_instance();

        $server_id = (int) $CI->config->item('server_id');
        $our_epoch = (int) $CI->config->item('our_epoch');
        if (function_exists('posix_getpid')) {
            $pid = posix_getpid();
        }
        elseif (function_exists('getmypid')) {
            $pid = getmypid();
        }
        else {
            $pid = mt_rand(1, 32768);
        }

        $rand_num  = mt_rand(0, 4);

        /*$uuid  = (int) microtime(TRUE) * 1000;*/
        $mt  = explode(' ', microtime());
        $uuid = $mt[1] * 1000 + (int) substr($mt[0], 2, 3);
        $uuid  -= $our_epoch;
        $uuid  = $uuid << 22;
        $uuid  |= ($server_id << 17);
        $uuid  |= ($pid << 2);
        $uuid  |= $rand_num;

        return sprintf("%u", $uuid);
    }
}
/**
 * 用户密码加密算法
 * @access	public
 * @return	<string>
 * @author wangchuan
 */
if (!function_exists('md5_passwd')) {
    function md5_passwd($salt, $password)
    {
        return md5(md5($password).$salt);
    }
}
/**
 * 获取客户端请求地IP
 * @access	public
 * @return	<string>
 * @author wangchuan
 */
if (!function_exists('get_client_ip')) {
    function get_client_ip()
    {
        if (getenv('HTTP_CLIENT_IP') and strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $onlineip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR') and strcasecmp(getenv('HTTP_X_FORWARDED_FOR'),
        'unknown')) {
            $onlineip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('REMOTE_ADDR') and strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $onlineip = getenv('REMOTE_ADDR');
        } elseif (isset($_SERVER['REMOTE_ADDR']) and $_SERVER['REMOTE_ADDR'] and
        strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $onlineip = $_SERVER['REMOTE_ADDR'];
        }
        preg_match("/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/", $onlineip, $match);
        return $onlineip = $match[0] ? $match[0] : 'unknown';
    }
}
/**
 * 检查身份证号码是否合法
 * @access	public
 * @return	<string>
 * @author wangchuan
 */
if (!function_exists('isIdCard')) {
	function isIdCard($vStr){
    $vCity = array(
        '11','12','13','14','15','21','22',
        '23','31','32','33','34','35','36',
        '37','41','42','43','44','45','46',
        '50','51','52','53','54','61','62',
        '63','64','65','71','81','82','91'
    );

    if (!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $vStr)) return false;

    if (!in_array(substr($vStr, 0, 2), $vCity)) return false;

    $vStr = preg_replace('/[xX]$/i', 'a', $vStr);
    $vLength = strlen($vStr);

    if ($vLength == 18)
    {
        $vBirthday = substr($vStr, 6, 4) . '-' . substr($vStr, 10, 2) . '-' . substr($vStr, 12, 2);
    } else {
        $vBirthday = '19' . substr($vStr, 6, 2) . '-' . substr($vStr, 8, 2) . '-' . substr($vStr, 10, 2);
    }

    if (date('Y-m-d', strtotime($vBirthday)) != $vBirthday) return false;
    if ($vLength == 18)
    {
        $vSum = 0;

        for ($i = 17 ; $i >= 0 ; $i--)
        {
            $vSubStr = substr($vStr, 17 - $i, 1);
            $vSum += (pow(2, $i) % 11) * (($vSubStr == 'a') ? 10 : intval($vSubStr , 11));
        }

        if($vSum % 11 != 1) return false;
    }

    return true;
	}
}


/**
 * 发送短信
 * @access	public
 * @return	<string>
 * @author wangchuan
 */
if (!function_exists('send_sms')) {
    function send_sms($mobile,$data=array(),$ismobile = true){
        //获取配置
        $CI = & get_instance();
        $CI ->load->config('sms_layout');
        if(empty($data['template'])){
            return false;
        }


        //发送短息消息
        $sms_cfg_tem = $CI->config->item('sms_content');
        if ($ismobile) {
            if(empty($sms_cfg_tem[$data['template']])){
                log_message('error', '获取不到模板内容，请检查application/config/sms_layout.php');
                return false;
            }
            $sys_content = $sms_cfg_tem[$data['template']];   
        }else{
            if(empty($sms_cfg_tem[$data['template']]['content'])){
                log_message('error', '获取不到模板内容，请检查application/config/sms_layout.php');
                return false;
            }
            $sys_content = $sms_cfg_tem[$data['template']]['content']; 
        }
        foreach($data as $k=>$v){
            if($k!='template'){
                $sys_content = str_replace($k,$v,$sys_content);
            }
        }
        //选择发送通道
        $sms_cfg_way = $CI->config->item('switch_sms_way');
        switch($sms_cfg_way[$data['template']]){
            case "yunxin_sms":
                $sms_api = 'http://api.sms.cn/mt/?uid=zdxrchina';
                $sms_api .= '&pwd=5bb4c0b757de293564aeb7c4bc0beb62';
                $sms_api .= "&mobile={$mobile}";
                $sms_api .= "&content=" . $sys_content;
                $sms_api .= "&encode=utf8";
                $result = file_get_contents($sms_api);
                if (strpos($result, 'stat=100') !== FALSE ) return true; else return false;
                break;
            case "open189_sms":
                $CI->load->model('Api_sms_model');
                //获取用户短信模板ID
                $sms_template_id = $CI->config->item('sms_template_id');
                $item_sms_template_id = $sms_template_id[$data['template']];
                unset($data['template']);
                $result = $CI->Api_sms_model->send_template_sms($mobile,$item_sms_template_id,$data);
                if(!empty($result) && $result==true)return true;else return false;
                break;
            case "chuangnan_sms":
                $url='http://222.73.117.156/msg/HttpBatchSendSM?';
                $CI->load->model('Api_sms_model');
                $send = "account=qhtthl&pswd=Qhtthl123&mobile={$mobile}&msg={$sys_content}";
                $result = $CI->Api_sms_model->curl_post($url,$send);
                log_message('error', 'parm:'.$send.'result'.$result,'sms_send_logs');
                if (strpos($result, ',0') !== FALSE ) return true; else return false;
                break;
            case "chuangnan_other":
                $url='http://222.73.117.169/msg/HttpBatchSendSM';
                $CI->load->model('Api_sms_model');
                $postArr = array (
                          'account' => 'ttd888',
                          'pswd' => 'Ttd123456',
                          'msg' => $sys_content,
                          'mobile' => $mobile,
                          'needstatus' => false,
                          'product' => '',
                          'extno' => ''
                     );
                $result = $CI->Api_sms_model->curl_post($url,http_build_query($postArr));
                log_message('error', 'parm:'.var_export($postArr,true).'result'.$result,'sms_send_logs');
                if (strpos($result, ',0') !== FALSE ) return true; else return false;
                break;
        }//switch 结束
    }//dend_sms 方法结束
}



/**
 * 金额转换
 * @access	public
 * @return	<string>
 * @author wangchuan
 */
if (!function_exists('money_change')) {
	function money_change($money){
		if(empty($money)){
			return '0万';	
		}
		$wan_money = $money/10000;
		return $wan_money."万";
	}
 
}
/**
 * @author zhoushuai
 * @access	public
 * @https请求（支持GET和POST）
 * @param url string 请求的地址
 * @param data  <array,xml> 发送的数据
 */
if(!function_exists('https_request')){
    function https_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT,5);
        $ch = curl_init();
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
}

//到期还本，按月付息
if(!function_exists('EqualEndMonth')){
	 function EqualEndMonth ($data = array()){
		//借款的月数
		if (isset($data['period']) && $data['period']>0){
			$period = $data['period'];
		}
		//借款的总金额
		if (isset($data['account']) && $data['account']>0){
			$account = $data['account'];
		}else{
			return "";
		}
		//借款的年利率
		if (isset($data['apr']) && $data['apr']>0){
			$year_apr = $data['apr'];
		}else{
			return "";
		}
		//借款的时间
		if (isset($data['time']) && $data['time']>0){
			$borrow_time = $data['time'];
		}else{
			$borrow_time = time();
		}
		//月利率
		$month_apr = $year_apr/(12*100);
		//$re_month = date("n",$borrow_time);
		$_yes_account = 0 ;
		$repayment_account = 0;//总还款额
		$interest = round($account*$month_apr,2);//利息等于应还金额乘月利率
		$interest= ceil($interest);	
		for($i=0;$i<=$period;$i++){
			$capital = 0;
			if ($i == $period){
				$capital = $account;//本金只在第三个月还，本金等于借款金额除季度
				$interest = 0;
			}
			$_result[$i]['account_all'] = $interest+$capital;
			$_result[$i]['account_interest'] = $interest;
			$_result[$i]['account_capital'] = $capital;
			$_result[$i]['repay_time'] =get_times(array("time"=>$borrow_time,"num"=>$i));
		}
			return $_result;
	}
}
//等额本息法
//贷款本金×月利率×（1+月利率）还款月数/[（1+月利率）还款月数-1]
//a*[i*(1+i)^n]/[(1+I)^n-1]
//（a×i－b）×（1＋i）
if(!function_exists('EqualMonth')){
    function EqualMonth ($data = array()){
		if(empty($data['account']) || empty($data['apr']) || empty($data['period'])){
			return NULL;
		}
		if (isset($data['time']) && $data['time']>0){
			$time = $data['time'];
		}else{
			$time = time();
		}
		$account = $data['account'];
        $period = $data['period'];		
        $month_apr = $data['apr']/(12*100);
		
		$interest = round($account*$month_apr,2);
		$interest =ceil($interest);
		$capital = round($account/$data['period'],2);
		$capital = ceil($capital);
        $_result = array();
        for($i=0;$i<=$period;$i++){
			if($i == 0){
				$_capital = 0;
			}else{
				$_capital =	$capital;
			}
			if ($i == $period){
				$interest = 0;	
				//$_capital = $data['account']- ($period-1)*$capital; //最后1期本金不平账
			}
			$_result[$i]['account_capital'] =$_capital;
            $_result[$i]['account_interest'] = $interest;
            $_result[$i]['account_all'] =  $_capital + $interest;
            $_result[$i]['repay_time'] = get_times(array("time"=>$time,"num"=>$i));
        }
        return $_result;
    }
}
//到期还本，按月付息----------不押车合同
if(!function_exists('EqualEndMonthPact')){
	 function EqualEndMonthPact ($data = array()){
		//借款的月数
		if (isset($data['period']) && $data['period']>0){
			$period = $data['period'];
		}
		//借款的总金额
		if (isset($data['account']) && $data['account']>0){
			$account = $data['account'];
		}else{
			return "";
		}
		//借款的年利率
		if (isset($data['apr']) && $data['apr']>0){
			$year_apr = $data['apr'];
		}else{
			return "";
		}
		//借款的时间
		if (isset($data['time']) && $data['time']>0){
			$borrow_time = $data['time'];
		}else{
			$borrow_time = time();
		}
		//月利率
		$month_apr = $year_apr/(12*100);
		//$re_month = date("n",$borrow_time);
		$_yes_account = 0 ;
		$repayment_account = 0;//总还款额
		$interest = round($account*$month_apr,2);//利息等于应还金额乘月利率
		$interest= ceil($interest);	
		$sumFlow  = sumFlow(array("loan_amount"=>$data['account'],'id'=>$data['id']));//流量费
		$_result    = array();
		for($i=0;$i<$period;$i++){
			$capital = 0;
			if ($i == $period-1){
				$capital = $account;//本金只在第三个月还，本金等于借款金额除季度
				$interest = 0;
				$sumFlow = 0;
			}
			$_result[$i]['account_capital']  = $capital;
            $_result[$i]['account_interest'] = $interest;
            $_result[$i]['account_all']      = $capital+$interest+$sumFlow;
            $_result[$i]['account_all_dx']   = (!empty($_result[$i]['account_all']))?toCNcap($_result[$i]['account_all']):'';
            $_result[$i]['repay_time']       = get_times(array("time"=>$borrow_time,"num"=>$i+1));
		}
		return $_result;
	}
}
/**
 * 合同还款记录，-等额本息法
 * 贷款本金×月利率×（1+月利率）还款月数/[（1+月利率）还款月数-1]
 * a*[i*(1+i)^n]/[(1+I)^n-1]
 * （a×i－b）×（1＋i）
 * @param string id 贷款id
 * @param acount 贷款金额
 * @param apr 年利率
 * @param period 期限
 * @param time 贷款开始时间
 */
if(!function_exists('EqualMonthPact')){
    function EqualMonthPact($data = array()){
        if(empty($data['account']) || empty($data['apr']) || empty($data['period'])){
            return NULL;
        }
        if (isset($data['time']) && $data['time']>0){
            $time = $data['time'];
        }else{
            $time = time();
        }
        $account    = $data['account'];
        $year_apr   = $data['apr'];
        $period     = $data['period'];
        $month_apr  = $year_apr/(12*100);
        //利息
        $interest   = round($account*$month_apr,2);
        $interest   = ceil($interest);
        //每期本金
        $capital    = round($account/$data['period'],2);
        $capital    = ceil($capital);
        $_result    = array();
		$sumFlow  = sumFlow(array("loan_amount"=>$data['account'],'id'=>$data['id']));//流量费
        for($i=0;$i<$period;$i++){
            $_capital =	$capital;  
            if ($i == $period-1){
               //$_capital = $data['account']- ($period-1)*$capital; //最后1期，本金不平账
                $_account_all = $_capital;
            }else{
                $_account_all =  $_capital +$interest+$sumFlow;
            }
            $_result[$i]['account_capital']  = $_capital;
            $_result[$i]['account_interest'] = $interest;
            $_result[$i]['account_all']      = $_account_all;
            $_result[$i]['account_all_dx']   = (!empty($_result[$i]['account_all']))?toCNcap($_result[$i]['account_all']):'';
            $_result[$i]['repay_time']       = get_times(array("time"=>$time,"num"=>$i+1));
        }
        return $_result;
    }
}



//计算日期-------------
if(!function_exists('get_times')){
	function get_times($data=array()){
		if (isset($data['time']) &&$data['time']!=""){
			$time = $data['time'];
		}elseif (isset($data['date']) &&$data['date']!=""){
			$time = strtotime($data['date']);
		}else{
			$time = time();
		}
		if (isset($data['type']) &&$data['type']!=""){
			$type = $data['type'];
		}else{
			$type = "month";
		}
		if (isset($data['num'])){
			$num = $data['num'];
		}else{
			$num = 1;
		}
		if ($type=="month"){
			$month = date("m",$time);
			$year = date("Y",$time);
			$_result = strtotime("$num month",$time);
			$_month = (int)date("m",$_result);
			if ($month+$num>12){
				$_num = $month+$num-12;
				$year = $year+1;
			}else{
				$_num = $month+$num;
			}
			if ($_num!=$_month){
				$_result = strtotime("-1 day",strtotime("{$year}-{$_month}-01"));
			}
		}
		if ($type=="30day_of_month"){
			$month = date("m",$time);
			$year = date("Y",$time);
			$days_num = $num * 30;
			$_result = strtotime("$days_num days",$time);
			$_month = (int)date("m",$_result);
			if ($month+$num>12){
				$_num = $month+$num-12;
				$year = $year+1;
			}else{
				$_num = $month+$num;
			}
			if ($_num!=$_month){
				$_result = strtotime("-1 day",strtotime("{$year}-{$_month}-01"));
			}
		}
		else {
			$_result = strtotime("$num $type",$time);
		}
		if (isset($data['format']) &&$data['format']!=""){
			return date($data['format'],strtotime("-1 day",$_result));
		}else{
			if(!empty($data['num'])){
				return strtotime("-1 day",$_result);
			}else{
				return $_result;
			}
		}
	}
}

/**
 * 取得memcache数据
 * @author zhoushuai
 * @param string $key
 * @return \array:
 */
if (!function_exists('getMmemData'))
{
    function getMmemData($key){
        $CI = & get_instance();
        $CI->load->driver('cache');
        $return = array();
        if($CI->cache->memcached->is_supported() === true){
            $key = md5($key);
            $cache = $CI->cache->memcached->get($key);
            return $cache;
        }
        return $return;
    }
}

/**
 * 保存memcache数据
 * @author zhoushuai
 * @param string $key
 * @return boolean
 */
if (!function_exists('setMmemData'))
{
    function setMmemData($key, $data, $replace = false,$ttl = 60){
        $CI = & get_instance();
        $CI->load->driver('cache');
        if ($CI->cache->memcached->is_supported() === true) {
            $key = md5($key);
            if (!$replace) {
                $CI->cache->memcached->save($key, $data, $ttl);
            } else {
                $CI->cache->memcached->replace($key, $data, $ttl);
            }
            return true;
        }
        return false;
    }
}


/**
 * 删除memcache数据
 * @author zhoushuai
 * @param string $key
 * @return boolean
 */
if (!function_exists('delMmemData'))
{
    function delMmemData($key){
        $CI = & get_instance();
        $CI->load->driver('cache');
        if ($CI->cache->memcached->is_supported() === true) {
            $key = md5($key);
            $CI->cache->memcached->delete($key);
            return true;
        }
        return false;
    }
}
/**
 * 小写人民币转换为大写
 * @author zhoushuai
 * @param string $key
 * @return boolean
 */
if (!function_exists('toCNcap')){
	function toCNcap($data) {  
		$capnum=array("零","壹","贰","叁","肆","伍","陆","柒","捌","玖");  
		$capdigit=array("","拾","佰","仟");  
		$subdata=explode(".",$data);  
		$yuan=$subdata[0];  
		$j=0; $nonzero=0;  
		for($i=0;$i<strlen($subdata[0]);$i++) {  
		  if(0==$i) { //确定个位  
			 if(isset($subdata[1])) {  
				$cncap=(substr($subdata[0],-1,1)!=0)?"元":"元零";  
			 }else{  
				$cncap="元";  
			 }  
		  }    
		  if(4==$i) { $j=0; $nonzero=0; $cncap="万".$cncap; } //确定万位  
		  if(8==$i) { $j=0; $nonzero=0; $cncap="亿".$cncap; } //确定亿位  
		  $numb=substr($yuan,-1,1); //截取尾数  
		  $cncap=($numb)?$capnum[$numb].$capdigit[$j].$cncap:(($nonzero)?"零".$cncap:$cncap);  
		  $nonzero=($numb)?1:$nonzero;  
		  $yuan=substr($yuan,0,strlen($yuan)-1); //截去尾数    
		  $j++;  
		}  
		$chiao = $cent = "";  
		$zhen = "整";  
		if(isset($subdata[1])) {  
		 $chiao=(substr($subdata[1],0,1))?$capnum[substr($subdata[1],0,1)]."角":"零";  
		 $cent=(substr($subdata[1],1,1))?$capnum[substr($subdata[1],1,1)]."分":"零分";  
		 $zhen="";  
		}  
		
		$cncap .= $chiao.$cent.$zhen;  
		$cncap=preg_replace("/(零)+/","\\1",$cncap); //合并连续"零"  
		return $cncap;  
	}

}
/**
 * 不押车流量费计算方法
 * @author zhoushuai
 * @param string id 贷款id
 * @param string loan_amount 贷款金额
 * @return int
 */
if (!function_exists('sumFlow'))
{
    function sumFlow($param = array()){
        $repay_flow  = 0;//流量费
        $id 	     = !empty($param['id'])?$param['id']:0;//贷款id
        $loan_amount = (int) $param['loan_amount'];//贷款金额
		if(!empty($param['loan_amount'])){
            if($id==0){
                $repay_flow = $loan_amount*0.002;
                if($repay_flow<50){
                    $repay_flow = 50;
                }
                if($repay_flow>500){
                    $repay_flow =  500;
                }
                $repay_flow = ceil($repay_flow);
            }else{
                //加载loan_model
                $CI = & get_instance();
                $CI->load->model('loan_model', 'loan');
                $where	        = array('id'=>$id);
                $loan 	        = $CI->loan->getWidgetRow($where);
                $plate_no       = $loan['plate_no'];
                $plate_no_pre   = mb_substr($plate_no,0,2);
                $plate_pre_arr  = array('川A','渝A','渝B','渝C','渝D','鄂A');
                //先息后本或车牌前两位不在['川A','渝A','渝B','渝C','渝D']
                if(!in_array($plate_no_pre,$plate_pre_arr) || $loan['repayment']=='maturity'){
                    $repay_flow = 150;
                }elseif($loan['repayment']=='equal'){
                    //等本等息
                    $repay_flow = $loan_amount*0.002;
                    if($repay_flow<50){
                        $repay_flow = 50;
                    }
                    if($repay_flow>500){
                        $repay_flow =  500;
                    }
                    $repay_flow = ceil($repay_flow);
                }
            }
		}
        return $repay_flow;
	}
}

/**
 * @GPS安装费
 * @author zhoushuai
 * @param string loan_amount 贷款金额 单位：元
 * @return int
 */
if (!function_exists('installCharge'))
{
    function installCharge($loan_amount){
        return ($loan_amount>=300000)?1200:600;
    }
}

/**
 * @等本等息逾期保证金收取标准为：贷款金额*3%
 * @author zhoushuai
 * @param string id 贷款id
 * @return int
 */
if(!function_exists('margin')){
    function margin($param){
        $id = $param['id'];
        $margin = 0;
        $margin_dx = '零';
        $CI = & get_instance();
        $CI->load->model('loan_model', 'loan');
        $where	        = array('id'=>$id);
        $loan 	        = $CI->loan->getWidgetRow($where);
        $plate_no       = $loan['plate_no'];
        $loan_amount    = $loan['loan_amount'];
        $plate_no_pre   = mb_substr($plate_no,0,2);
        $plate_pre_arr  = array('川A','渝A','渝B','渝C','渝D');
        if(in_array($plate_no_pre,$plate_pre_arr)){
            $margin    = $loan_amount*0.03;
            $margin_dx = toCNcap($margin);
        }
        return array('margin'=>$margin,'margin_dx'=>$margin_dx);
    }
}





/**
 * 清空memcache数据
 * @author zhoushuai
 * @param string $key
 * @return boolean
 */
if (!function_exists('cleanMmemData'))
{
    function cleanMmemData(){
        $CI = & get_instance();
        $CI->load->driver('cache');
        if ($CI->cache->memcached->is_supported() === true) {
            $CI->cache->memcached->clean();
        }
        return false;
    }
}

/**
 * 数字格式
 *
 * @param 	string	$number		//数字
 * @param 	integer $strlen		//小数位长度
 * @param 	boolean	$round		//是否进行四舍五入
 * @param 	boolean	$format		//格式化[科学计数法，默认不开启]
 * @access	public
 * @author  LEE
 * @return	integer | float
 */
if ( ! function_exists('numberFormat'))
{
    function numberFormat($number, $strlen = 0, $round = false, $format = false)
    {
        if ($number === null) {
            $number = 0;
        }

        if (!is_numeric($number)) {
            return $number;
        }

        if (!strpos($number, '.')) {
            if ($strlen > 0 && !$round) {
                $decimal = str_pad("0", $strlen, "0");
                $number  = $number . '.' . $decimal;
            }

            $format && $number = number_format($number);

            return (string) $number;
        }

        $num  = $decimal = '';

        $nums = explode('.', $number);
        $lens = $strlen ? $strlen : strlen($nums[1]);

        $decimal = substr($nums[1], 0, $lens);

        if (!$round && strlen($decimal) < $strlen) {
            $decimal = (string) str_pad($decimal, $strlen, "0");
            $num 	 = $nums[0] . '.' . $decimal;
        } else {
            $num 	 = $nums[0] . '.' . $decimal;
            $round && $num = round((float) $num, $strlen);
//			!$strlen && !$round && $num = $nums[0];
            !$strlen && $num = (float) $num;

            $format && $num = number_format($num, $strlen);
        }

        return (string) $num;
    }
}

/**
 * 返回门店名称
 * @access	public
 * @return	<string>
 * @author wangchuan
 */
if (!function_exists('get_stores')) {
	function get_stores($stores_id){
		$stores_id = (int) $stores_id;
		if(empty($stores_id)){
			return '未知';	
		}
		//加载employee_modle
		$CI = & get_instance();
		$CI->load->model('employee_model');
        $stores_arr = $CI->employee_model->stores;
		if(!empty($stores_arr[$stores_id])){
			return 	$stores_arr[$stores_id];
		}else{
			return '未知';	
		}
	}
 
}

/**
 * @调档状态
 */
if (!function_exists('shiftingstate')) {
    function shiftingstate($data){
        $stateArr = array_filter(explode('/',$data));
        $red = array();
        $normal = array();
        $standard = array('违法未处理','正常');
        foreach($stateArr as $key=>$val){
            if(in_array($val,$standard)){
                $normal[] = $val;
            }else{
                $red[] = $val;
            }
        }
        $redStr=implode('/',$red);
        $norStr=implode('/',$normal);
        $return = array(
            'redStr'=>$redStr,
            'norStr'=>$norStr
        );
        return $return;
    }

}

/**
 * @金额。以万单位 ，格式化
 */
if (!function_exists('FormatMoney')) {
    function FormatMoney($money){
        return sprintf("%.2f", $money/10000);
    }
}
