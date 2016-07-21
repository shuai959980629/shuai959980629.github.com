<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *
 * @package		Libraries
 * @author		Glen.luo
 * @since		Version 1.0
 */

// ------------------------------------------------------------------------

/**
 * 将二维数组转换成多维树状数组.
 *
 * @access	public
 * @param	array	节点数组.
 * @param	int	    指定的树层数.
 * @return	array
 */
if ( ! function_exists('array_to_tree'))
{
    function array_to_tree($nodes, $parent_id = 0)
    {
        $tree = array();
        $position = 0;
        for($i = 0, $cnt = count($nodes); $i < $cnt; $i++) {
            if($nodes[$i]['superior'] == $parent_id) {
                $tree[$position]                     = $nodes[$i];
                $tree[$position++]['children']       = array_to_tree($nodes, $nodes[$i]['uid']);
            }
        }
        return $tree;
    }
}

/**
 * 遍历子树ID.
 *
 * @access	public
 * @param	array	节点数组.
 * @param	int	    指定的树层数.
 * @return	array
 */
if ( ! function_exists('get_children'))
{
    function get_children($nodes, $parent_id = 0)
    {
        $children = array();
        for($i = 0, $cnt = count($nodes); $i < $cnt; $i++) {
            if($nodes[$i]['parent_id'] == $parent_id) {
                array_push($children, $nodes[$i]['id']);
                $children = array_merge($children, get_children($nodes, $nodes[$i]['id']));
            }
        }
        return $children;
    }
}

/**
 * 生成树状菜单(select).
 *
 * @access	public
 * @param	array	    树形结构的多维数组.
 * @param	string	    select标签中显示内容的键名.
 * @param	string	    select标签中value的键名.
 * @param	string	    每个option的显示名是否需要前缀.
 * @param	array	    单个的节点数组.
 * @param	mix	        选中的值.
 *
 * @return	str
 */
if ( ! function_exists('build_select_tree'))
{
    function build_select_tree($tree, $disply_name = 'title', $val_name = 'id', $selected = '', $level = 0)
    {
        if (empty($tree)) {
            return '';
        }

        $opt_tag = '';

        foreach ($tree as $node) {
            $indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level);
            if ($selected == $node[$val_name]) {
                $format = "<option value=\"%s\" selected>%s%s%s</option>\n";
            }
            else {
                $format = "<option value=\"%s\">%s%s%s</option>\n";
            }

            /*$prefix = empty($node['children']) ? '' : '-';*/
            $prefix = '';
            $opt_tag .= sprintf($format, $node[$val_name], $indent, $prefix, $node[$disply_name]);
            if (!empty($node['children'])) {
                $opt_tag .= build_select_tree($node['children'], $disply_name, $val_name, $selected, ($level + 1));
            }
        }

        return $opt_tag;

    }
}
/**
 * 业务员select.
 *
 * @access	public
 * @param	array	    树形结构的多维数组.
 * @return	array
 */
if ( ! function_exists('employee_prefix_tree'))
{
    function employee_prefix_tree($tree, $disply_name = 'title', $val_name = 'id', $selected = '')
    {
        if (empty($tree)) {
            return '';
        }

        $cars_select_tree = array();

        $prefix = array();
        foreach ($tree as $brand) {
			$cars_select_tree[] = array(
				'uid'    => $brand['uid'],
				'realname' => "{$brand['realname']}>".$brand['mobile'],
            );
        }
		
        $opt_tag = '';
        if (!is_array($selected)) {
            $selected = array($selected);
        }

        foreach ($cars_select_tree as $node) {
            if ( in_array($node[$val_name], $selected) ) {
                $format = "<option value=\"%s\" selected>%s</option>\n";
            }
            else {
                $format = "<option value=\"%s\">%s</option>\n";
            }

            $opt_tag .= sprintf($format, $node[$val_name], $node[$disply_name]);
        }

        return $opt_tag;
    }
}
/**
 * 客户select.
 *
 * @access	public
 * @param	array	    树形结构的多维数组.
 * @return	array
 */
if ( ! function_exists('customer_prefix_tree'))
{
    function customer_prefix_tree($tree, $disply_name = 'title', $val_name = 'id', $selected = '')
    {
        if (empty($tree)) {
            return '';
        }

        $cars_select_tree = array();

        $prefix = array();
        foreach ($tree as $brand) {
			$cars_select_tree[] = array(
				'uid'    => $brand['uid'],
				'idcard' => $brand['realname'].">{$brand['idcard']}",
            );
        }
		
        $opt_tag = '';
        if (!is_array($selected)) {
            $selected = array($selected);
        }

        foreach ($cars_select_tree as $node) {
            if ( in_array($node[$val_name], $selected) ) {
                $format = "<option value=\"%s\" selected>%s</option>\n";
            }
            else {
                $format = "<option value=\"%s\">%s</option>\n";
            }

            $opt_tag .= sprintf($format, $node[$val_name], $node[$disply_name]);
        }

        return $opt_tag;
    }
}
if ( ! function_exists('customer_new_prefix_tree'))
{
    function customer_new_prefix_tree($tree, $disply_name = 'title', $val_name = 'id', $selected = '')
    {
        if (empty($tree)) {
            return '';
        }

        $cars_select_tree = array();

        $prefix = array();
        foreach ($tree as $brand) {
			$cars_select_tree[] = array(
				'uid'    => $brand['idcard'],
				'idcard' => $brand['realname'].">{$brand['idcard']}",
            );
        }
		
        $opt_tag = '';
        if (!is_array($selected)) {
            $selected = array($selected);
        }

        foreach ($cars_select_tree as $node) {
            if ( in_array($node[$val_name], $selected) ) {
                $format = "<option value=\"%s\" selected>%s</option>\n";
            }
            else {
                $format = "<option value=\"%s\">%s</option>\n";
            }

            $opt_tag .= sprintf($format, $node[$val_name], $node[$disply_name]);
        }

        return $opt_tag;
    }
}

/**
 * 得到指定结点的路径.
 *
 * @access	public
 * @param	array	    树形结构的多维数组.
 * @param	array	    节点数组.
 *
 * @return	array
 */
if ( ! function_exists('get_way'))
{
    function get_way($tree, $node)
    {
        $path = array();

        foreach ($tree as $_node) {
            $path[] = $_node;

            if ($_node['id'] == $node['id']) {
                break;
            }

            if (!empty($_node['children'])) {
                $_path = get_way($_node['children'], $node);
                if (empty($_path)) {
                    array_pop($path);
                }
                else {
                    $path = array_merge($path, $_path);
                }
            }
            else {
                array_pop($path);
            }
        }

        return $path;
    }
}

/**
 * 遍历树，将树状数组转换成使用使用的二维数组.
 *
 * @access	public
 * @param	array	    tree形数组.
 * @return	array
 */
if ( ! function_exists('travel_tree'))
{
    function travel_tree($tree)
    {
        if (empty($tree)) {
            return NULL;
        }

        $data = array();
        $nodes = array();
        while (!empty($tree)) {
            $nodes[] = array_pop($tree);
            $level = 0;
            while (!empty($nodes)) {
                $node = array_pop($nodes);
                $_children = $node['children'];
                unset($node['children']);
                $data[] = array(
                    'node' => $node,
                    'level' => $level,
                );
                if (!empty($_children)) {
                    $level++;
                    $nodes = array_merge($nodes, $_children);
                }
            }
        }

        return $data;
    }
}

/**
 * @日志记录
 * @author zhoushuai
 * @param log 日志格式
 * @param data 记录需要的数据
 */
if (!function_exists('debug_log')) {
    function debug_log($log, $data)
    {
        $starttime = $_SERVER['REQUEST_TIME'];
        $uri = $_SERVER['REQUEST_URI'];
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        $log = sprintf($log, microtime(true) - $starttime, $uri, $useragent, var_export($data, true));
        $path = APPPATH . 'logs' . DIRECTORY_SEPARATOR;
        if (is_really_writable($path) && is_dir($path)) {
            $message = '';
            $filepath = $path . 'debug-log-' . date('Y-m-d') . '.php';
            if (!file_exists($filepath)) {
                $message .= "<?php if (!defined('BASEPATH'))exit('No direct script access allowed');\n\n";
            }
            $fp = @fopen($filepath, FOPEN_WRITE_CREATE);
            $message .= '#' . date('Y-m-d H:i:s', time()) . '****************华丽的分割线********************' . "\n{$log}\n";
            flock($fp, LOCK_EX);
            fwrite($fp, $message);
            flock($fp, LOCK_UN);
            fclose($fp);
            @chmod($filepath, FILE_WRITE_MODE);
        }
    }
}


/**
 * @调试
 * @author zhoushuai
 * @param data 调试需要的数据
 */
if (!function_exists('debug')) {
    function debug($data){
        header('Content-Type:text/html;charset=utf-8');
        echo '<pre />';
        print_r($data);
    }
}


/**
 * @不转移json
 * @author zhoushuai
 * @param arr JSONECODE 数组
 * @return json
 */
if (!function_exists('json_unescaped')) {
    function json_unescaped($arr)
    {
        array_walk_recursive($arr, function (&$item, $key)
        {
            if (is_string($item))$item = mb_encode_numericentity($item, array(
                0x80,
                0xffff,
                0,
                0xffff), 'UTF-8'); }
        );
        return str_replace("\\/", "/", mb_decode_numericentity(json_encode($arr), array
        (
            0x80,
            0xffff,
            0,
            0xffff), 'UTF-8'));
    }
}

/**
 * @https请求（支持GET和POST）
 * @param url string 请求的地址
 * @param data  array 发送的数据
 */
if (!function_exists('httpsRequest')) {
    function httpsRequest($url, $data = null)
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
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
}


/**
 *@gpg 转百度坐标 google 谷歌
 *@lat<纬度>,
 *@lng<经度> 根据经纬度坐标获取地址
 *@param lats <纬度>
 *@param lng  <经度>
 *@return array
 */
if (!function_exists('getgps')) {
    function getgps($lats, $lngs, $gps = false, $google = false)
    {
        $lat = $lats;
        $lng = $lngs;
        if ($gps)
            $c = file_get_contents("http://api.map.baidu.com/ag/coord/convert?from=0&to=4&x=$lng&y=$lat");
        else if ($google)
            $c = file_get_contents("http://api.map.baidu.com/ag/coord/convert?from=2&to=4&x=$lng&y=$lat");
        else
            return array($lat, $lng);
        $arr = (array)json_decode($c);
        if (!$arr['error']) {
            $lat = base64_decode($arr['y']);
            $lng = base64_decode($arr['x']);
        }
        return array($lat, $lng);
    }
}


/**
 * @概率
 * @author zhoushuai
 * @param  $proArr =array('a'=>20,'b'=>30,'c'=>50);
 * @return string 结果
 */
if (!function_exists('get_rand')) {
    function get_rand($proArr) {
        $result = '';
        //概率数组的总概率精度
        $proSum = array_sum($proArr);//100
        //概率数组循环
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);             //抽取随机数1,100
            if ($randNum <= $proCur) {
                $result = $key;                         //得出结果
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset ($proArr);
        return $result;
    }
}



/**
 * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
 * @param $para 需要拼接的数组
 * @return 拼接完成以后的字符串
 * @author zhoushuai
 */
if (!function_exists('createLinkstring')) {
    function createLinkstring($para)
    {
        $arg = "";
        while (list($key, $val) = each($para)) {
            $arg .= $key . "=" . $val . "&";
        }
        //去掉最后一个&字符
        $arg = substr($arg, 0, count($arg) - 2);
        //如果存在转义字符，那么去掉转义
        if (get_magic_quotes_gpc()) {
            $arg = stripslashes($arg);
        }
        return $arg;
    }
}
/**
 * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串，并对字符串做urlencode编码
 * @param $para 需要拼接的数组
 * return 拼接完成以后的字符串
 * @author zhoushuai
 */
if (!function_exists('createLinkstringUrlencode')) {
    function createLinkstringUrlencode($para)
    {
        $arg = "";
        while (list($key, $val) = each($para)) {
            $arg .= $key . "=" . urlencode($val) . "&";
        }
        //去掉最后一个&字符
        $arg = substr($arg, 0, count($arg) - 2);
        //如果存在转义字符，那么去掉转义
        if (get_magic_quotes_gpc()) {
            $arg = stripslashes($arg);
        }
        return $arg;
    }
}
/**
 * 对数组排序
 * @param $para 排序前的数组
 * return 排序后的数组
 * @author zhoushuai
 */
if (!function_exists('argSort')) {
    function argSort($para)
    {
        ksort($para);
        reset($para);
        return $para;
    }
}

/**
 * 成都今日车辆限行
 * @param plate_no 车牌号
 * return boolean
 * @author zhoushuai
 */
if (!function_exists('resDriv')) {
    function resDriv($plate_no)
    {
        $retun = false;
        $nu=substr($plate_no,-1);
        $weekarray=array("日","一","二","三","四","五","六");
        $week="星期".$weekarray[date("w")];
        if(!mb_stristr($plate_no,'川') || mb_stristr($plate_no,'川A')){
            switch($week){
                case '星期一':
                    if(in_array($nu,array(1,6))) $retun = true;
                    break;
                case '星期二':
                    if(in_array($nu,array(2,7))) $retun = true;
                    break;
                case '星期三':
                    if(in_array($nu,array(3,8))) $retun = true;
                    break;
                case '星期四':
                    if(in_array($nu,array(4,9))) $retun = true;
                    break;
                case '星期五':
                    if(in_array($nu,array(5,0))) $retun = true;
                    break;
            }
        }
        return $retun;
    }
}















