<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Device extends Api_Controller {
    function __construct() {
        parent::__construct('Device');
        
    }

    public $rules = array(
        
    );
    function index(){
        $this->db->where('start_date < NOW()');
        $this->db->where('end_date > NOW()');
        parent::run();
        $data = $this->_output['data'];
        $this->_output['data'] = $this->_fixdata($data);
        $this->display();
    }
    function register(){
        $uuid = $this->input->post('uuid');
        $params = array(
            'uuid' => $uuid
            );
        $device = $this->API_Model->get_device($uuid);
        if($device){
            $this->_output['code'] = 0;
            $this->_output['text'] = 'ok';
            $this->_output['message'] = 'Device already exists.';
        } else {

        $rs = $this->API_Model->insert_device($params);
            if($rs){
                $this->_output['code'] = 0;
                $this->_output['text'] = 'ok';
                $this->_output['message'] = 'success';
            } else {
                $this->_output['code'] = -1;
                $this->_output['text'] = 'fail';
                $this->_output['message'] = 'Cant register device';
            }
        }
        $this->display();
    }
}
