<?php

/*
  Project     : 48c6c450f1a4a0cc53d9585dc0fee742
  Created on  : Mar 16, 2013, 11:29:15 PM
  Author      : Truong Khuong - khuongxuantruong@gmail.com
  Description :
  Purpose of the stylesheet follows.
 */

class Token_Model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
    function create($uuid='',$member_id,$time = 60*60*24*365){
        $this->delete_expried_token();
        $token = random_string('alnum', 32);
        $this->db->set('uuid', $uuid);
        $this->db->set('member_id', $member_id);
        $this->db->set('token', $token);
        $this->db->set('created', 'NOW()', FALSE);
        $this->db->set('expried', "NOW() + INTERVAL {$time} SECOND", FALSE);
        @$this->db->insert('__token');
        @$count = $this->db->affected_rows(); //should return the number of rows affected by the last query
        if ($count == 1)
            return $this->get_by_token($token);
        return false;
    }
    function get_by_token($token){
        $query = $this->db
            ->where(array(
                'token' => $token,
                ))
            ->where('expried > NOW()')
            ->get('__token');
        $row = $query->row();
        return $row;
    }
    function get_by_uuid($uuid){
        $query = $this->db
            ->where(array(
                'uuid' => $uuid,
                ))
            ->get('__token');
        $row = $query->row();
        return $row;
    }
    function delete_expried_token(){
        @$this->db
            ->where('expried <','NOW()',FALSE)
            ->delete('__token');
    }
    function update($token,$time = 60*60*24*365){
        $this->db->set('modified', 'NOW()', FALSE);
        $this->db->set('expried', "NOW() + INTERVAL {$time} SECOND", FALSE);
        @$this->db
            ->where('token',$token)
            ->update('__token');
        @$count = $this->db->affected_rows(); //should return the number of rows affected by the last query
        if ($count == 1)
            return true;
        return false;
    }
}

?>
