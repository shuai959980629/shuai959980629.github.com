<?php
class Db{
    private $conn; //数据库连接标识;
    private $result;//结果集
    private $sql;
    /*构造函数*/
    public function __construct() {
        $this->connect();
    }

    /*数据库连接*/
    public function connect(){
        global $db;
        $this->conn = mysqli_connect($db['hostname'].':'.$db['port'],$db['username'],$db['password']);
        if(!$this->conn){
            die("数据库连接失败：".mysqli_error($this->conn));
        }
        if (!mysqli_select_db($this->conn,$db['database'])) {
            die("数据库不可用：".$db['database']);
        }
        mysqli_query($this->conn,"SET NAMES {$db['char_set']}");
    }


    public function query($sql) {
        if ($sql == "") {
           die("SQL语句错误：SQL查询语句为空");
        }
        $this->sql = $sql;
        $result    = mysqli_query($this->conn,$this->sql);
        if (!$result) {
            die("错误SQL语句：".$this->sql);
        } else {
            $this->result = $result;
        }
        return $this->result;
    }

    //简化插入insert
    public function insert($sql) {
        if ($this->query($sql)) {
           return mysqli_insert_id($this->conn);
        }else{
            return false;
        }
    }

    //简化修改update
    public function update($sql) {
        if ($this->query($sql)) {
            return mysqli_affected_rows($this->conn);
        }else{
            return false;
        }
    }

    //简化删除del
    public function delete($sql){
        if ($this->query($sql)){
            return mysqli_affected_rows($this->conn);
        }else{
            return false;
        }
    }


    //简化查询select
    public function select($sql) {
        return $this->query($sql);
    }


    public function fetch_assoc() {
        $return = array();
        while($row = mysqli_fetch_assoc($this->result))
        {
           $return[] = $row;
        }
        return $return;
    }

    public function fetch_row() {
        $return = array();
        while($row = mysqli_fetch_row($this->result))
        {
            $return[] = $row;
        }
        return $return;
    }


    public function fetch_Object() {
        $return = array();
        while($row = mysqli_fetch_object($this->result))
        {
            $return[] = $row;
        }
        return $return;
    }


    public function total() {
        return mysql_num_rows($this->result);
    }


    public function free(){
        @mysqli_free_result($this->result);
    }


    //析构函数，自动关闭数据库,垃圾回收机制
    public function __destruct() {
        if (!empty ($this->result)) {
            $this->free();
        }
        mysqli_close($this->conn);
    }
}
