<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Base_model extends CI_Model {

    public  $table = NULL;
    public     $pk = 'id';

    public function __construct($table = NULL)
    {
        parent::__construct();

        $this->table = $table;
    }

    public function save($data, $id = 0)
    {
        if ((int) $id > 0) {
            return $this->update($id, $data);
        }
        else {
            return $this->create($data);
        }
    }

    public function create($data)
    {
        $this->db->insert($this->table, $data);

        if ($this->db->affected_rows() > 0) {
            return isset($data[$this->pk]) ? $data[$this->pk] : $this->db->insert_id();
        }
        else {
            return NULL;
        }
    }

    public function remove($id)
    {
        if (is_int($id)) {
            $id = array($id);
        }

        $this->db->where_in($this->pk, $id)
            ->delete($this->table);

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        }
        else {
            return FALSE;
        }
    }

    public function update($id, $data)
    {
        $result = $this->db->where($this->pk, (int) $id)
            ->update($this->table, $data);
        if($result === FALSE){
            return FALSE;
        }
        if ($this->db->affected_rows() > 0) {
            return $id;
        }
        else {
            return NULL;
        }
    }

    public function _where($where)
    {
        if (isset($where['order_by']) && !empty($where['order_by'])) {
            $this->db->order_by($where['order_by']);
            unset($where['order_by']);
        }
        else {
            $this->db->order_by("{$this->table}.{$this->pk} desc");
        }
        $this->db->where($where);
    }

    public function count_all($where = array())
    {
        $this->_where($where);
        $this->db->_count_string = "SELECT COUNT({$this->pk}) AS ";
        return $this->db->count_all_results($this->table);
    }

    protected function _select()
    {
        $this->db->select("{$this->table}.*");
    }

    public function search($where = array(), $limit = 20, $offset = 0)
    {
		$where = array_filter($where);
        if ($offset > 0) {
            $ids = $this->_find_ids($where, $limit, $offset);
            $this->db->where_in($this->pk, $ids);
        }
        else {
            $this->db->limit($limit);
            $this->_where($where);
        }

        $this->_select();

        $query = $this->db->get($this->table);

        return $query->result_array();
    }

    public function get($pk)
    {
        $where = array(
            "{$this->table}.{$this->pk}" => (int) $pk,
        );

        $query = $this->db->get_where($this->table, $where);

        return $query->row_array();
    }

    public function all($where = array())
    {
        $this->_select();
        if ($where) {
            $query = $this->db->get_where(
                $this->table,
                $where
            );
        }
        else {
            $query = $this->db->get($this->table);
        }

        return $query->result_array();
    }
    public function sum($field, $where)
    {
        if(!$field){
            return NULL;
        }
        if($where){
            $this->db->where($where);
        }
        foreach($field as $v){
            $this->db->select_sum($v);
        }

        $query = $this->db->get($this->table);
        return $query->result_array();
    }

    public function _find_ids($where, $limit, $offset)
    {
        $this->db->select("{$this->table}.{$this->pk}");
        $this->_where($where);
        if ($offset > 0) {
            $this->db->limit($limit, $offset);
        }
        elseif ($limit > 0) {
            $this->db->limit($limit);
        }

        $query = $this->db->get($this->table);

        $ids = array();

        if ($query->num_rows() > 0) {
            $rows = $query->result_array();
            foreach ($rows as $row) {
                $ids[] = $row['id'];
            }
        }

        return $ids;
    }
    /**
     * where条件生成器
     *
     * @param  array $where //查询条件
     * @access public
     * @copyright 20150729
     * @return void
     */
    public function widgetWhere($where)
    {
        if (isset($where['cols'])) {
            $this->db->select(implode($where['cols'], ','));
            unset($where['cols']);
        }

        if (isset($where['scope'])) {
            foreach ($where['scope'] as $key=>$value) {
                foreach ($value as $k=>$val) {
                    $key == 'lt'  && $this->db->where($k . ' > ' , $val);
                    $key == 'ltt' && $this->db->where($k . ' >= ', $val);
                    $key == 'mt'  && $this->db->where($k . ' < ' , $val);
                    $key == 'mtt' && $this->db->where($k . ' <= ', $val);
                }
            }
            unset($where['scope']);
        }

        if (isset($where['in'])) {
            foreach ($where['in'] as $key=>$value) {
                $this->db->where_in($key, $value);
            }
            unset($where['in']);
        }

        if (isset($where['not_in'])) {
            foreach ($where['not_in'] as $key=>$value) {
                $this->db->where_not_in($key, $value);
            }
            unset($where['not_in']);
        }

        if (isset($where['like'])) {
            foreach ($where['like'] as $key=>$value) {
                //value[1] = before 、 after or both
                if (is_array($value)) {
                    !empty($value[1]) 
                        ? $this->db->like($key, $value[0], $value[1])
                        : $this->db->like($key, $value[0]);
                } else {
                    $this->db->like($key, $value);
                }
            }
            unset($where['like']);
        }
        
        if (isset($where['group'])) {
            foreach ($where['group'] as $key=>$value) {
                $group[] = $value;
            }
            $this->db->group_by(implode($group, ','), false);
            unset($where['group']);
        }

        if (isset($where['sum'])) {
            $this->db->select_sum($where['sum']);
            unset($where['sum']);
        }

        if (isset($where['order'])) {
            $order = array();
            foreach ($where['order'] as $key=>$value) {
                $order[] = $key . ' ' . $value;
            }
            $this->db->order_by(implode($order, ','));
            unset($where['order']);
        }

        if (isset($where['custom'])) {
            $this->db->where($where['custom']);
            unset($where['custom']);
        }
        
        if (isset($where['eq'])) {
            $this->db->where($where['eq']);
            unset($where['eq']);
        }
        
        if (isset($where['limit'])) {
            $this->db->limit($where['limit']);
            unset($where['limit']);
        }

        if (!empty($where)) {
            $this->db->where($where);
        }
    }

    /**
     * 获取数据总数
     *
     * @param  array $where //查询条件
     * @access public
     * @copyright 20150729
     * @return integer
     */
    public function getWidgetTotal($where = array())
    {
        $this->widgetWhere($where);
        return $this->db->count_all_results($this->table);
    }

    /**
     * 分页查询
     *
     * @param  array    $where  //查询条件
     * @param  integer  $limit  //查询条数
     * @param  integer  $offset //偏移量 [页码]
     * @access public
     * @copyright 20150729
     * @return array
     */
    public function getWidgetPages($where = array(), $limit = 20, $offset = 0)
    {
        $this->widgetWhere($where);
        $this->db->limit($limit, $offset);
        $query = $this->db->get($this->table);

        return $query->result_array();
    }
    /**
     * 读取单条数据
     *
     * @param  integer | array $where   //唯一ID 或者 查询条件数组
     * @access public
     * @copyright 20150729
     * @return array
     */
    public function getWidgetRow($where)
    {
        if (is_array($where)) {
            $this->widgetWhere($where);
            $query = $this->db->get($this->table);
        } else {
            $query = $this->db->get_where($this->table, array($this->pk => $where));
        }

        return $query->row_array();
    }
    public function getWidgetRows($where)
    {
        $this->widgetWhere($where);
        $query = $this->db->get($this->table);
        
        return $query->result_array();
    }

    public function delWidgetRows($where)
    {
        $this->widgetWhere($where);

        $this->db->delete($this->table);

        if ($this->db->affected_rows() > 0) {
            return TRUE;
        }
        else {
            return FALSE;
        }
    }
}



