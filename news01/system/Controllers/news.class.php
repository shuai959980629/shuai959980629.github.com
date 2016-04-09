<?php

class news extends Controller{
    public function __construct() {
       parent::__construct();
    }

    public function index(){
        $mysql = "select * from `category`";
        $res = $this->db->select($mysql);
        if($res){
            $data = $this->db->fetch_assoc();
            foreach($data as $key=>&$list){
                $list['name']  = stripslashes($list['name']);
                $list['created'] =date('Y-m-d H:i:s',$list['created']);
            }
            $this->return_client(1,$data,'');
        }
        $this->return_client(0,'','');
    }


    public function  add(){
        $created = time();
        if(empty($_POST['name'])){
            $this->return_client(0,null,'保存失败！');
        }
        $name  = addslashes($_POST['name']);
        $mysql = "INSERT INTO `category`(`name`,created) VALUES ('".$name."',".$created.")";

        $res = $this->db->insert($mysql);
        if($res){
            $this->return_client(1);
        }else{
            $this->return_client(0,null,'保存失败！');
        }
    }


    public function addNews(){
        $created = time();
        if(empty($_POST['cat_id'])||empty($_POST['title'])||empty($_POST['introduction'])){
            $this->return_client(0,null,'保存失败！');
        }
        $cat_id  = intval($_POST['cat_id']) ;
        $title  = addslashes($_POST['title']);
        $introduction  = addslashes($_POST['introduction']);
        $id = intval($_POST['id']);
        $result1 = true;
        $result2 = true;
        if(!empty($id)){
            $sql  = "UPDATE `newslist` SET `cat_id` = {$cat_id}, `title` = '{$title}',`introduction`='{$introduction}' WHERE `id` = {$id}";
            $result1 = $this->db->update($sql);
        }else{
            $mysql = "INSERT INTO `newslist`(cat_id,title,introduction,created) VALUES ({$cat_id},'{$title}','{$introduction}',{$created})";
            $result2 = $this->db->insert($mysql);
        }
        if($result1 && $result2){
            $this->return_client(1);
        }else{
            $this->return_client(0,null,'保存失败！');
        }
    }


    public function newslist(){
        $mysql = "select * from `newslist`";
        if(!empty($_GET)){
            if($_GET['cat_id']){
                $mysql .= " where `cat_id` =".$_GET['cat_id'];
            }
            $mysql .=" order by id DESC ";
            if($_GET['page']){
                $pagesize = 4;
                $offset = ($_GET['page']-1)*$pagesize;
                $mysql .= " limit {$offset},{$pagesize}";
            }
        }else{
            $mysql .=" order by id DESC ";
        }
        $res = $this->db->select($mysql);
        if($res){
            $data = $this->db->fetch_assoc();
            if(!empty($data)){
                foreach($data as $key=>&$list){
                    $tmp = $list['created'];
                    $list['created'] =date('Y-m-d H:i:s',$tmp);
                    $list['title']  = stripslashes($list['title']);
                    $list['introduction']  = stripslashes($list['introduction']);
                    $list['tmp'] = get_show_time($tmp);
                }
                $this->return_client(1,$data,'');
            }
        }
        $this->return_client(0,'','');
    }


    public function delItem(){
        if(empty($_POST['id'])||empty($_POST['type'])){
            $this->return_client(0,null,'删除失败！');
        }
        $table = trim($_POST['type']);
        $id    = intval($_POST['id']);
        $result1 = true;
        if($table=='category'){
            $sql = "DELETE FROM `newslist` WHERE `cat_id` = {$id}";
            $result1 = $this->db->delete($sql);
        }
        $mysql  = "DELETE FROM `{$table}` WHERE `id` = {$id}";
        $result2 =  $this->db->delete($mysql);
        if($result1 && $result2){
            $this->return_client(1);
        }
        $this->return_client(0,null,'删除失败！');
    }

    public function getNewsItem(){
        $id    = intval($_GET['id']);
        $mysql = "select * from `newslist` WHERE `id` = {$id}";
        $res = $this->db->select($mysql);
        if($res){
            $data = $this->db->fetch_assoc();
            if(!empty($data)){
                foreach($data as $key=>&$list){
                    $tmp = $list['created'];
                    $list['created'] =date('Y-m-d H:i:s',$tmp);
                    $list['title']  = stripslashes($list['title']);
                    $list['introduction']  = stripslashes($list['introduction']);
                    $list['tmp'] = get_show_time($tmp);
                }
                $this->return_client(1,$data[0],'');
            }
        }
    }











}
