<?php

/*
  Project     : 48c6c450f1a4a0cc53d9585dc0fee742
  Created on  : Mar 16, 2013, 11:29:15 PM
  Author      : Truong Khuong - khuongxuantruong@gmail.com
  Description :
  Purpose of the stylesheet follows.
 */

class Province_Model extends API_Model {
    function __construct() {
        parent::__construct('__province');
        $this->_select = array(
            '__province.id','__province.title','__province.code','__province.type','__province.country_id',
            'sorting'
            );
    }
    function get_by_name($name){
        return $this
            ->filter(array(
            "__province.title" => $name
            ))
            ->row();
    }
    function get_by_country($country_id){
        $this->db
            ->order_by('__province.type','ASC')
            ->order_by('__province.sorting','DESC')
            ->order_by('__province.alias','ASC');
        return $this
            ->filter(array(
            "country_id" => $country_id
            ))
            ->gets();
    }
}

?>
