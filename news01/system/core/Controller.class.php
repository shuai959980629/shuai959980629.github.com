<?php
class Controller
{
    private $control;
    private $action;
    protected $db;


    public function __construct()
    {
        require_once(ROOT_PATH."/models/Db.model.php");
        $this->db = new Db();
    }

    public function Run()
    {
        $this->Analysis(); //开始解析URL获得请求的控制器和方法
        //这里构造出控制器文件的路径
        $controlFile = ROOT_PATH . '/Controllers/' . $this->control . '.class.php';
        if(!file_exists($controlFile)) //如果文件不存在提示错误, 否则引入
        {
            exit('控制器不存在' . $controlFile);
        }
        include($controlFile);
        $class = ucwords($this->control); //将控制器名称中的每个单词首字母大写,来当作控制器的类名
        if(!class_exists($class))  //判断类是否存在, 如果不存在提示错误
        {
            exit('为定义的控制器类' . $class);
        }
        $instance = new $class(); //否则创建实例
        if(!method_exists($instance, $this->action)) //判断实例$instance中是否存在$action方法, 不存在则提示错误
        {
            exit('不存在的方法' . $this->action);
        }
        $action = $this->action;
        $instance->$action();
    }
    private function Analysis()
    {
        global $C;  //包含全局配置数组, 这个数组是在Config.ph文件中定义的
        if($C['URL_MODE'] == 1) //如果URL模式为1 那么就在GET中获取控制器, 也就是说url地址是这种的http://localhost/index.php?c=控制器&a=方法
        {
            $this->control = !empty($_GET['c']) ? trim($_GET['c']) : '';
            $this->action = !empty($_GET['a']) ? trim($_GET['a']) : '';
        }
        else if($C['URL_MODE'] == 2) //如果为2 那么就是使用PATH_INFO模式, 也就是url地址是这样的 http://localhost/index.php/控制器/方法/其他参数
        {
            if(isset($_SERVER['PATH_INFO']))
            {
                $path  = trim($_SERVER['PATH_INFO'], '/');
                $paths  = explode('/', $path);
                $this->control = array_shift($paths);
                $this->action = array_shift($paths);
            }
        }
        $this->control = !empty($this->control) ? $this->control : $C['DEFAULT_CONTROL'];
        $this->action  = !empty($this->action) ? $this->action : $C['DEFAULT_ACTION'];
    }

    /**
     * 返回客户端信息通用函数
     * @param number $status 返回状态
     * @param string $data	包含的数据
     * @param string $msg	状态说明
     */
    protected function return_client($status = 0, $data = null, $msg = null)
    {
        header('Content-type: application/json;charset=utf-8');
        $result = array('status' => $status, 'data' => empty($data) ? null : $data, 'msg' => empty($msg) ? null : $msg);
        $json = json_encode($result);
        die($json);
    }



}