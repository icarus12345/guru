<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Debug extends CI_Controller {
    function __construct() {
        parent::__construct();
        
    }
    function index(){
    }
    function data(){
        $this->load->model('api/Province_Model');
        $data =  $this->Province_Model
            ->gets();
        foreach ($data as $key => $value) {
            $this->db->where('id',$value->id)
            ->update('__province',array('alias'=>convertUrl($value->title)));
        }
    }
}
