<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Device extends Core_Controller {
    function __construct() {
        parent::__construct();
        $this->Module_Model = new Core_Model('auth_device');
    }
    
    function index(){
        echo 'Welcome API';
    }

    function bind(){
        $this->Module_Model->table_config=array(
            "table"     =>"tbl_project",
            "select"    =>"
                SELECT SQL_CALC_FOUND_ROWS auth_device.*
                ",
            "from"      => "FROM auth_device",
            "where"     => "",
            "order_by"  => "",
            "columnmaps"=>array(
                'id' => 'auth_device.id',
                'created' => 'auth_device.created',
                'modified' => 'auth_device.modified',
                'status' => 'auth_device.status',
            ),
            "filterfields"=>array(

            )
        );
        $output = $this->Module_Model->jqxBinding();
        foreach ($output['rows'] as $key => $value) {
            $data = unserialize($output['rows'][$key]->data);
            foreach ($data as $c => $v) {
                $output['rows'][$key]->$c = $v;
            }
            unset($output['rows'][$key]->data);
            if($value->uid>0){
                $output['rows'][$key]->logged = 'Yes';
            }
        }
        $this->output->set_header('Content-type: application/json');
        $this->output->set_output(json_encode($output));
    }

    
}
