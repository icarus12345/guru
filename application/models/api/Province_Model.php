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
        $this->_select = array('id','title','code','type','country_id');
    }
    function get_by_name($name){
        return $this
            ->filter(array(
            "title" => $name
            ))
            ->row();
    }
    function get_by_country($country_id){
        return $this
            ->filter(array(
            "country_id" => $country_id
            ))
            ->gets();
    }
}

?>
