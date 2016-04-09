<?php

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

if ( ! function_exists('debug'))
{
    function debug($data)
    {
        echo '<pre>';
        print_r($data);
    }
}


