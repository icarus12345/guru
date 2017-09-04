<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Product extends Core_Controller {
    function __construct() {
        parent::__construct();
        $this->Module_Model = new Core_Model('tbl_product');
    }
    
    function index(){
        echo 'Welcome API';
    }

    function bind(){
        $this->Module_Model->table_config=array(
            "table"     =>"tbl_product",
            "select"    =>"
                SELECT SQL_CALC_FOUND_ROWS tbl_product.*
                ",
            "from"      => "FROM tbl_product",
            "where"     => "",
            "order_by"  => "",
            "columnmaps"=>array(
                'id' => 'tbl_product.id',
                'created' => 'tbl_product.created',
                'modified' => 'tbl_product.modified',
                'status' => 'tbl_product.status',
            ),
            "filterfields"=>array(

            )
        );
        $output = $this->Module_Model->jqxBinding();
        foreach ($output['rows'] as $key => $value) {
            $data = unserialize($output['rows'][$key]->data);
            foreach ($data as $c => $v) {
                if(strlen($v)<255){
                    $output['rows'][$key]->$c = $v;
                }
            }
            unset($output['rows'][$key]->data);
        }
        $this->output->set_header('Content-type: application/json');
        $this->output->set_output(json_encode($output));
    }

    
}
