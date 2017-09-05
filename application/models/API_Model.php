<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class API_Model extends CI_Model {
    private $configs;
    function __construct($table = '', $prefix = '',$colid='id',$status='true') {
        parent::__construct();
        $this->table = $table;
        $this->prefix = $prefix;
        $this->colid = $colid;
        $this->status = $status;
    }
    function select($select = null){
        if($select) $this->db->select($select);
        if($this->_select) $this->db->select($this->_select);
        return $this;
    }
    function limit($page=1,$perpage = 10){
        
        $this->db->limit($perpage, ($page - 1) * $perpage);
        return $this;
    }
    function filter($filter = null){
        if($filter) $this->db
            ->where($filter);
        if($this->_filter) $this->db
            ->where($this->_filter);
        return $this;
    }
    function filter_in($filter_in = null){
        if($filter_in){
            foreach ($filter_in as $key => $value) {
                $this->db->where_in($key,$value);
            }
        }
        return $this;
    }
    function search($search = null){
        if($search){
            foreach ($search as $key => $value) {
                $this->db->like($key,$value);
            }
        }
        return $this;
    }

    function bind(){
        $this->select()
            ->filter()
            ->search();
        if($this->status){
            $this->db->where("{$this->prefix}status",$this->status);
        }
        $query = $this->db
            ->get($this->table);
        $entrys = $query->result();
        if($entrys) foreach ($entrys as $key => $value) {
            $data = unserialize($entrys[$key]->data);
            if($data) foreach ($data as $dkey => $dvalue) {
                $entrys[$key]->$dkey = $dvalue;
            }
            unset($entrys[$key]->data);
        }
        return $entrys;
    }

    function row(){
        $this->select()
            ->filter()
            ->search();
        if($this->status){
            $this->db->where("{$this->prefix}status",$this->status);
        }
        $query = $this->db
            ->get($this->table);
        $row = $query->row();
        $data = unserialize($row->data);
        if($data) foreach ($data as $dkey => $dvalue) {
            $row->$dkey = $dvalue;
        }
        unset($row->data);
        return $row;
    }

    function get($id) {
        $this->filter(array(
            "{$this->prefix}{$this->colid}" => $id
            ));
        return $this->row();
    }
    function get_by_alias($alias) {
        $this->filter(array(
            "{$this->prefix}alias" => $alias
            ));
        return $this->row();
    }
    function get_by_type($type=''){
        $this->filter(array(
            "{$this->prefix}type" => $type
            ));
        return $this->bind();
    }

    function get_by_cid($cid=''){
        $this->filter(array(
            "{$this->prefix}category" => $cid
            ));
        return $this->bind();
    }
    function gets() {
        return $this->bind();
    }

    function delete($id) {
        $where = array("$this->prefix$this->colid" => $id);
        $this->db->delete($this->table, $where);
        $count = $this->db->affected_rows(); //should return the number of rows affected by the last query
        if ($count == 1)
            return true;
        return false;
    }
    function update($id, $params) {
        if(isset($params['data'])) $params['data'] = serialize($params['data']);
        $this->db->set($this->prefix . 'modified', 'NOW()', FALSE);
        $this->db->where("$this->prefix$this->colid", $id);
        @$this->db->update($this->table, $params);
        @$count = $this->db->affected_rows(); //should return the number of rows affected by the last query
        if ($count == 1)
            return true;
        return false;
    }
    function insert($params) {
        if(isset($params['data'])) $params['data'] = serialize($params['data']);
        $this->db->set($this->prefix . 'created', 'NOW()', FALSE);
        $max_sort = $this->get_max_sort_index();
        $this->db->set($this->prefix . 'sorting', $max_sort + 1);
        @$this->db->insert($this->table, $params);
        @$count = $this->db->affected_rows(); //should return the number of rows affected by the last query
        if ($count == 1)
            return true;
        return false;
    }

    function get_max_sort_index(){
        $query = $this->db
            ->select("MAX({$this->prefix}sorting) as sorting",false)
            ->get($this->table);
        $row = $query->row();
        if($row) {
            return $row->sorting;
        }
        return 0;
    }
    function get_min_sort_index(){
        $query = $this->db
            ->select("MIN({$this->prefix}sorting) as sorting",false)
            ->get($this->table);
        $row = $query->row();
        if($row) {
            return $row->sorting;
        }
        return 0;
    }
}