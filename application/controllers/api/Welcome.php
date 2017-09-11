<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Welcome extends Api_Controller {
    function __construct() {
        parent::__construct('Category');
        $this->load->model('api/Type_Model');
    }

    public $rules = array(
        
    );

    function index(){
        $this->_output['code'] = 1;
        $this->_output['text'] = 'ok';
        $this->_output['message'] = 'success';
        $type_data = $this->Type_Model
            ->gets();
        $this->_output['TypeData']['items'] = $type_data;
        $this->_output['TypeData']['hit'] = count($type_data);
        $category_data = $this->Category_Model
            ->gets();
        $this->_output['CategoryData']['items'] = $category_data;
        $this->_output['CategoryData']['hit'] = count($category_data);
        $this->display();
    }
}
