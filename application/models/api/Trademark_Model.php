<?php

/*
  Project     : 48c6c450f1a4a0cc53d9585dc0fee742
  Created on  : Mar 16, 2013, 11:29:15 PM
  Author      : Truong Khuong - khuongxuantruong@gmail.com
  Description :
  Purpose of the stylesheet follows.
 */

class Trademark_Model extends API_Model {
    function __construct() {
        parent::__construct('__trademark');
        $this->_select = array('__trademark.id','__trademark.title','__trademark.logo','__trademark.image');
        $this->_selectAs = array();
        
    }
    function join_wish(){
        if($this->member){
            $member_id = $this->member->id;
        }else{
            $member_id = -1;
        }
        $this->db
            ->select('IF(__trademark_wish.status="true",1,0) as like_status',false)
            ->join('__trademark_wish',"( `__trademark`.`id` = `__trademark_wish`.`trademark_id` AND `__trademark_wish`.`member_id` = $member_id)",'LEFT');
        return $this;
    }
    function get_actived($page, $perpage){
        if($page){
            $this->db
                ->select('SQL_CALC_FOUND_ROWS __trademark.id',false)
                ->limit($perpage, ($page - 1) * $perpage);
        }
        $this->db
            ->select($this->_select);
        if($this->_selectAs) foreach ($this->_selectAs as $selectStr) {
            $this->db
            ->select($selectStr,false);
        }
        $query = $this->db
            ->group_by($this->_select)
            ->from('__trademark')
            ->where('__trademark.status','true');
        $this->join_wish();
        $query = $this->db->get();
        $entrys = $query->result();
        return $entrys;
    }
    
    function get_by_wish(){
        $query = $this->db
            ->select($this->_select)
            ->from('__trademark')
            ->where('__trademark_wish.status','true')
            ->where('__trademark.status','true');
        $this->join_wish();
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    function get_by_wish_detail($trademark_id){
        $query = $this->db
            ->where('__trademark_wish.trademark_id',$trademark_id)
            ->where('__trademark_wish.member_id',$this->member->id)
            ->get('__trademark_wish');
        $result = $query->row();
        return $result;
    }
    function _update_wish($trademark_id,$params){
        $this->db->set($this->prefix . 'modified', 'NOW()', FALSE);
        $this->db->where("trademark_id", $trademark_id);
        $this->db->where("member_id", $this->member->id);
        @$this->db->update('__trademark_wish', $params);
        @$count = $this->db->affected_rows(); //should return the number of rows affected by the last query
        if ($count == 1)
            return true;
        return false;
    }
    function _insert_wish($params){
        $this->db->set($this->prefix . 'created', 'NOW()', FALSE);
        $params['member_id'] = $this->member->id;
        @$this->db->insert('__trademark_wish', $params);
        @$count = $this->db->affected_rows(); //should return the number of rows affected by the last query
        if ($count == 1)
            return true;
        return false;
    }
}

?>
