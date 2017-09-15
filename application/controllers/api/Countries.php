<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Countries extends Api_Controller {
    function __construct() {
        parent::__construct('Country');
        
    }

    public $rules = array(
        
    );

    function index(){
        $this->_output['code'] = 1;
        $this->_output['text'] = 'ok';
        $this->_output['message'] = 'success';
        $data =  $this->API_Model
            ->gets();
        $this->_output['ArrData']['hit'] = count($data);
        $this->_output['ArrData']['items'] = $data;
        $this->display();
    }
}
