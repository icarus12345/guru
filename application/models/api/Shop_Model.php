<?php

/*
  Project     : 48c6c450f1a4a0cc53d9585dc0fee742
  Created on  : Mar 16, 2013, 11:29:15 PM
  Author      : Truong Khuong - khuongxuantruong@gmail.com
  Description :
  Purpose of the stylesheet follows.
 */

class Shop_Model extends API_Model {
    function __construct() {
        parent::__construct('__shop');
        $this->_select = array(
            '__shop.id',
            '__shop.province_id',
            '__shop.trademark_id',
            '__shop.title',
            '__shop.address',
            '__shop.image',
            '__shop.lat',
            '__shop.lon'
            );
    }
    function get_by_province($province_id){
        $query = $this->db
            ->select($this->_select)
            ->from('__shop')
            ->where('__shop.province_id',$province_id)
            ->where('__shop.status','true')
            ->get();
        $result = $query->result();
        return $result;
    }
}

?>
