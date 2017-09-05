<?php

/*
  Project     : 48c6c450f1a4a0cc53d9585dc0fee742
  Created on  : Mar 16, 2013, 11:29:15 PM
  Author      : Truong Khuong - khuongxuantruong@gmail.com
  Description :
  Purpose of the stylesheet follows.
 */

class Member_Model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    
    function get_by_username($username){
        $query = $this->db
            ->where(array(
                'username' => $username
                ))
            ->get('__member');
        $row = $query->row();
        return $row;
    }
    function get_by_email($email){
        $query = $this->db
            ->where(array(
                'email' => $email
                ))
            ->get('__member');
        $row = $query->row();
        return $row;
    }
    function get_by_id($id){
        $query = $this->db
            ->where(array(
                'id' => $id
                ))
            ->get('__member');
        $row = $query->row();
        return $row;
    }
    function _insert($params){
        $this->db->set('created', 'NOW()', FALSE);
        @$this->db->insert('__member',$params);
        @$count = $this->db->affected_rows(); //should return the number of rows affected by the last query
        if ($count == 1)
            return true;
        return false;
    }
    function _update($id,$params){
        $this->db->set('modified', 'NOW()', FALSE);
        @$this->db
            ->where('id',$id)
            ->update('__member',$params);
        @$count = $this->db->affected_rows(); //should return the number of rows affected by the last query
        if ($count == 1)
            return true;
        return false;
    }
}

?>
