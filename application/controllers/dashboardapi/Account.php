<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Account extends Core_Controller {
    function __construct() {
        parent::__construct();
        $this->Module_Model = new Core_Model('auth_account');
    }
    
    function index(){
        echo 'Welcome API';
    }

    function bind(){
        $this->Module_Model->table_config=array(
            "table"     =>"tbl_project",
            "select"    =>"
                SELECT SQL_CALC_FOUND_ROWS *
                ",
            "from"      => "FROM auth_account",
            "where"     => "",
            "order_by"  => "",
            "columnmaps"=>array(
            ),
            "filterfields"=>array(

            )
        );
        $output = $this->Module_Model->jqxBinding();
        $this->output->set_header('Content-type: application/json');
        $this->output->set_output(json_encode($output));
    }

    
}
