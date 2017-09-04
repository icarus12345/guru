<?php

/*
  Project     : 48c6c450f1a4a0cc53d9585dc0fee742
  Created on  : Mar 16, 2013, 11:29:15 PM
  Author      : Truong Khuong - khuongxuantruong@gmail.com
  Description :
  Purpose of the stylesheet follows.
 */

class Content_Model extends API_Model {

    function __construct()
    {
        parent::__construct('tbl_data');
        $this->_select = array('id','title','data');
    }
    
    // function get($id){
    //     $query = $this->db
    //         ->where(array(
    //             'id' => $id,
    //             'type'=>'risk_content'
    //             ))
    //         ->get('tbl_data');
    //     $row = $query->row();
    //     if($row) {
    //         $data = unserialize($row->data);
    //         foreach ($data as $key => $value) {
    //             $row->$key = $value;
    //         }
    //         unset($row->data);
    //     }
    //     return $row;
    // }
    
}

?>
