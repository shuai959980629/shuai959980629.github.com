<?php
/**
 *
 * @package		Libraries
 * @author		Jhou
 * @since		Version 1.0
 */

// ------------------------------------------------------------------------

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Template Layout Class
 *
 * A layout defines the base look for your website. It is the foundation that
 * your other views get rendered in. They make it easy to provide consistent
 * look throughout your application.
 *
 */
class Layout {

	private $CI = NULL;

	/**
	 * Constructor
	 */
    public function __construct()
    {
		$this->CI =& get_instance();
		log_message('debug', "Layout Class Initialized");
    }

    public function load($vars = NULL, $layout = '', $view = '', $theme = '', $return = FALSE)
    {
        if (empty($view)) {
            $view = $this->CI->router->class . '/' . $this->CI->router->method . '.php';
        }
        else {
            $ext = pathinfo($view, PATHINFO_EXTENSION);
            empty($ext) && $view .= '.php';
        }

        /*$layout = empty($layout) ? 'layout.php' : $layout;*/
        if (!empty($layout)) {
            $ext = pathinfo($layout, PATHINFO_EXTENSION);
            empty($ext) && $layout .= '.php';
        }

        if (!empty($theme)) {
            $layout = empty($layout) ? '' : "{$theme}/{$layout}";
            $view   = "{$theme}/{$view}";
        }

        if (file_exists( APPPATH . "views/{$view}" ) === FALSE) {
		    log_message('error', 'Template files does not exist：' . APPPATH . "views/{$view}");
            show_error("Unable to load the template file: views/{$view}");
        }
        elseif (!empty($layout) && file_exists( APPPATH . "views/{$layout}" ) === FALSE) {
		    log_message('error', 'Layout files does not exist：' . APPPATH . "views/{$layout}");
            show_error("Unable to load the layout file: views/{$layout}");
        }
        elseif (empty($layout)) {
		    log_message('debug', 'Load view file：' . APPPATH . $view);
            return $this->CI->load->view($view, $vars, $return);
        }
        else {
            $vars = array(
                'ci_body' => $this->CI->load->view($view, $vars, TRUE)
            );
            return $this->CI->load->view($layout, $vars, $return);
        }
    }
}
