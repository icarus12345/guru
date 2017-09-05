<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Type extends Api_Controller {
    function __construct() {
        parent::__construct('Type');
        
    }

    public $rules = array(
        
    );

    function index(){
        parent::run();
        $this->display();
    }

    function get_all(){
        $this->_output['data'] = $this->Type_Model
            ->get_by_type('mega');
        $this->display();
    }
}
