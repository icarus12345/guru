<?php

/*
  Project     : 48c6c450f1a4a0cc53d9585dc0fee742
  Created on  : Mar 16, 2013, 11:29:15 PM
  Author      : Truong Khuong - khuongxuantruong@gmail.com
  Description :
  Purpose of the stylesheet follows.
 */

class Project_Model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
    function insert($params){
        $this->db->set('created', 'NOW()', FALSE);
        if(isset($params['data'])) $params['data'] = serialize($params['data']);
        $this->db->set('status', 1);
        @$this->db->insert('tbl_project',$params);
        @$count = $this->db->affected_rows(); //should return the number of rows affected by the last query
        if ($count == 1)
            return true;
        return false;
    }
    
    function get($id){
        $query = $this->db
            ->where(array(
                'id' => $id
                ))
            ->get('tbl_project');
        $row = $query->row();
        if($row) {
            $data = unserialize($row->data);
            foreach ($data as $key => $value) {
                $row->$key = $value;
            }
            unset($row->data);
        }
        return $row;
    }
    function update($id,$params = null){
        $this->db->set('modified', 'NOW()', FALSE);
        if(isset($params['data'])) $params['data'] = serialize($params['data']);
        @$this->db
            ->where('id',$id)
            ->update('tbl_project',$params);
        @$count = $this->db->affected_rows(); //should return the number of rows affected by the last query
        if ($count == 1)
            return true;
        return false;
    }

    function get_list($uid,$page=1,$perpage=10){
        $query = $this->db
            ->where(array(
                'uid' => $uid
                ))
            ->order_by('created','desc')
            ->limit($perpage, ($page - 1) * $perpage)
            ->get('tbl_project');
        $entrys = $query->result();
        if($entrys) foreach ($entrys as $key => $value) {
            $data = unserialize($entrys[$key]->data);
            foreach ($data as $dkey => $dvalue) {
                $entrys[$key]->$dkey = $dvalue;
            }
            unset($entrys[$key]->data);
        }
        return $entrys;
    }

    function get_shared_by_uid($uid){
        $query=$this->db
            ->select('tbl_shared.*')
            ->from('tbl_shared')
            ->join('auth_account','tbl_shared.email = auth_account.email')
            ->where("auth_account.id", $uid)
            ->get();
        $errordb = $this->db->error();
        $error_message = $errordb['message'];
        if($errordb['code']!==0){
            return null;
        }
        $result = $query->result();
        return $result;
    }

    function get_all_by_uid($uid, $page=1, $perpage=10){
        $shareds = $this->get_shared_by_uid($uid);
        foreach ($shareds as $key => $value) {
            $pids[] = $value->pid;
            $modes[$value->pid] = $value->mode;
        }
        if(empty($pids)) $pids = array(-1);
        $query = $this->db
            ->where('status',1)
            ->group_start()
            ->where_in('id',$pids)
            ->or_where('uid',$uid)
            ->group_end()
            ->order_by('created','desc')
            ->limit($perpage, ($page - 1) * $perpage)
            ->get('tbl_project');
        $result = $query->result();
        foreach ($result as $key => $value) {
            $result[$key]->created = date('Y M d',strtotime($result[$key]->created));
            $result[$key]->alias = convertUrl($result[$key]->title);
            if($result[$key]->uid != $uid){
                $result[$key]->mode = $modes[$result[$key]->id];
            } else {
                $result[$key]->mode = 2;
            }
            $data = unserialize($result[$key]->data);
            foreach ($data as $dkey => $dvalue) {
                $result[$key]->$dkey = $dvalue;
            }
            unset($result[$key]->data);
        }
        return $result;
    }
    function delete($id){
        @$this->db
            ->where('id',$id)
            ->delete('tbl_project');
        @$count = $this->db->affected_rows(); //should return the number of rows affected by the last query
        if ($count == 1)
            return true;
        return false;
    }
}

?>
