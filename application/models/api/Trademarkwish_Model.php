<?php

/*
  Project     : 48c6c450f1a4a0cc53d9585dc0fee742
  Created on  : Mar 16, 2013, 11:29:15 PM
  Author      : Truong Khuong - khuongxuantruong@gmail.com
  Description :
  Purpose of the stylesheet follows.
 */

class Trademarkwish_Model extends API_Model {
    function __construct() {
        parent::__construct('__trademark_wish');
        $this->_select = array('__trademark.id','__trademark.title','__trademark.logo','__trademark.image');
        
    }
    
}

?>
