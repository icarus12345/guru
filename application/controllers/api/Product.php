<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Product extends Api_Controller {
    function __construct() {
        parent::__construct('Product');
        
    }

    public $rules = array(
        
    );

    function index(){
        parent::run();
    }

    function get_all(){
        $this->_output['code'] = 1;
        $this->_output['text'] = 'ok';
        $this->_output['message'] = 'success';
        $this->_output['data'] = $this->API_Model
            ->get_by_type('mega');
        $this->display();
    }
}
