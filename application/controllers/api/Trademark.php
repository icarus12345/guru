<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Trademark extends Api_Controller {
    function __construct() {
        parent::__construct('Trademark');
        
    }

    public $rules = array(
        
    );

    function index(){
        
        parent::run();
        $this->display();
    }

    function get_all(){
        $this->_output['code'] = 1;
        $this->_output['text'] = 'ok';
        $this->_output['message'] = 'success';
        $this->_output['data'] = $this->API_Model
            ->gets();
        $this->display();
    }
}
