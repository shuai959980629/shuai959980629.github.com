<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Acl {
    public function __construct()
    {
        if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
            $_SESSION['user'] = array(
                'uid'       => 0,
                'role'      => 'guest',
                'realname'  => 'guest'
            );
        }
    }

    public function auth()
    {
        $CI   = & get_instance();
        $priv = load_priv_by_role($_SESSION['user']['role']);
        if (empty($priv)) {
            log_message('error', '角色的功能权限为空，请检查config/role.php角色配置文件');
            show_error('系统文件损坏，与管理员系统', 500);
        }
        $purview = $CI->config->item('purview');
        if (empty($purview)) {
            log_message('error', 'config/priv.php权限配置文件取不到值');
            show_error('系统文件损坏，与管理员系统', 500);
        }

        $is_allow_access = $this->is_allow($CI->router->class, $CI->router->method, $priv, $purview);
        if (!$is_allow_access) {
            $url = $_SERVER['REQUEST_URI'];
            if($url=='/' || $url=='/login' || $url=='/login/index' || $url=='/login/index/'){
                $url = '/home';
            }
            $_SESSION['redirect_uri'] = $url;
            header('Location: /login');
        }
    }

    public function is_allow($c, $a, $priv, $purview) {
        $_is_allow = FALSE;
        foreach ($priv as $item) {
            if (empty($purview[$item])) {
                continue;
            }
            $_is_allow = in_array("{$c}/*", $purview[$item]) || in_array("{$c}/{$a}", $purview[$item]);
            if ($_is_allow) {
                break;
            }
        }
        return $_is_allow;
    }
}