<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User extends Core_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model("dashboard/User_Model");
    }
    function _check_permission(){
        $user = $this->session->userdata('dasbboard_user');
        $user_privileges = explode(',',$user->ause_authority);
        $privileges = array('1','2');
        $check = !!array_intersect($user_privileges,$privileges);
        if(!$check){
            $this->load->view('dashboard/auth/accessdenied',null);
            $this->output->_display();
            die();
        }
    }
    function index(){
        $this->_check_permission();
        $this->render('dashboard/user',null);
    }
}