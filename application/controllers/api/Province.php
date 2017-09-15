<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Province extends Api_Controller {
    function __construct() {
        parent::__construct('Province');
        $this->_filter_allows = array('country_id');
    }

    public $rules = array(
        
    );

    function index(){
        $country_id = $this->input->get_post('country_id');
        $this->_output['code'] = 1;
        $this->_output['text'] = 'ok';
        $this->_output['message'] = 'success';
        $data =  $this->API_Model
            ->get_by_country($country_id);
        if($data){
            array_unshift($data , (object)array(
                "id"=> "",
                "title"=> "Toàn Quốc",
                "code"=> "",
                "type"=> "",
                "country_id"=> ""
                ));
        }
        $this->_output['ArrData']['hit'] = count($data);
        $this->_output['ArrData']['items'] = $data;
        $this->display();
    }
}
