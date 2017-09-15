<?php

/*
  Project     : 48c6c450f1a4a0cc53d9585dc0fee742
  Created on  : Mar 16, 2013, 11:29:15 PM
  Author      : Truong Khuong - khuongxuantruong@gmail.com
  Description :
  Purpose of the stylesheet follows.
 */

class Country_Model extends API_Model {
    function __construct() {
        parent::__construct('__countries');
        $this->_select = array('id','title','code');
    }
    function get_by_code($code){
        return $this
            ->filter(array(
            "code" => $code
            ))
            ->row();
    }
}

?>
