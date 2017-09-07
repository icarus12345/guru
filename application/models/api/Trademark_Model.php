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
        
    }
    function get_by_wish(){
        $query = $this->db
            ->select($this->_select)
            ->from('__trademark')
            ->join('__trademark_wish','__trademark.id = __trademark_wish.trademark_id')
            ->where('__trademark_wish.member_id',$this->member->id)
            ->where('__trademark_wish.status','true')
            ->where('__trademark.status','true')
            ->get();
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
