<?php

/*
  Project     : 48c6c450f1a4a0cc53d9585dc0fee742
  Created on  : Mar 16, 2013, 11:29:15 PM
  Author      : Truong Khuong - khuongxuantruong@gmail.com
  Description :
  Purpose of the stylesheet follows.
 */

class Campaign_Model extends API_Model {
    function __construct() {
        parent::__construct('__campaign');
        $this->_select = array(
            'id','title','desc','image','trademark_id',
            // 'start_date',
            'end_date',
            'website','hotline','type_id','category_id','count_like','count_view','rating'
            );
    }

    function get_actived($page, $perpage){
        if($page){
            $this->db
                ->select('SQL_CALC_FOUND_ROWS id',false)
                ->limit($perpage, ($page - 1) * $perpage);
        }
        $query = $this->db
            ->select($this->_select)
            ->from('__campaign')
            ->where('start_date < NOW()')
            ->where('end_date > NOW()')
            ->where('status','true')
            ->get();
        $entrys = $query->result();
        return $entrys;
    }

    function get_by_today(){
        $query = $this->db
            ->select($this->_select)
            ->from('__campaign')
            ->where('start_date < NOW()')
            ->where('DAY(end_date) = DAY(NOW())')
            ->where('status','true')
            ->get();
        $entrys = $query->result();
        return $entrys;
    }

    function get_by_like(){
        $query = $this->db
            ->select($this->_select)
            ->from('__campaign')
            ->where('start_date < NOW()')
            // ->where('DAY(end_date) = DAY(NOW())')
            ->where('end_date > NOW()')
            ->where('status','true')
            ->get();
        $entrys = $query->result();
        return $entrys;
    }
}

?>
