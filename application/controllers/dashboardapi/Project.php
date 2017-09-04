<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Project extends Core_Controller {
    function __construct() {
        parent::__construct();
        $this->Module_Model = new Core_Model('tbl_project');
    }
    
    function index(){
        echo 'Welcome API';
    }

    function bind(){
        $this->Module_Model->table_config=array(
            "table"     =>"tbl_project",
            "select"    =>"
                SELECT SQL_CALC_FOUND_ROWS tbl_project.id,tbl_project.title,tbl_project.created,tbl_project.modified,auth_account.email,tbl_project.status
                ",
            "from"      => "FROM tbl_project INNER JOIN auth_account ON tbl_project.uid = auth_account.id",
            "where"     => "",
            "order_by"  => "",
            "columnmaps"=>array(
                'id' => 'tbl_project.id',
                'title' => 'tbl_project.title',
                'created' => 'tbl_project.created',
                'modified' => 'tbl_project.modified',
                'status' => 'tbl_project.status',
            ),
            "filterfields"=>array(

            )
        );
        $output = $this->Module_Model->jqxBinding();
        $this->output->set_header('Content-type: application/json');
        $this->output->set_output(json_encode($output));
    }

    
}
