<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Module extends Core_Controller {
    function __construct() {
        parent::__construct();
        $this->Module_Model = new Core_Model('tbl_module');
        
        
    }
    function _check_permission(){
        $user = $this->session->userdata('dasbboard_user');
        $user_privileges = explode(',',$user->ause_authority);
        $privileges = array('1');
        $check = !!array_intersect($user_privileges,$privileges);
        if(!$check){
            $this->load->view('dashboard/auth/accessdenied',null);
            $this->output->_display();
            die();
        }
    }
    function index(){
        $this->view(null);
    }

    function view($type=null){
        $this->_check_permission();
        $this->assigns['type'] = $type;
        $this->render('dashboard/module',null);
    }

    function basic($type=null){
        $this->_check_permission();
        $this->assigns['basic_module'] = 1;
        $this->assigns['type'] = $type;
        $this->render('dashboard/module',null);
    }
}
