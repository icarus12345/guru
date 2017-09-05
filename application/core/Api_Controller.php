<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Headers: X-Requested-With');
  header("HTTP/1.1 200 OK");
  die();
}
class Api_Controller extends CI_Controller {
    public $assigns;
    public function __construct($_model = null) {
        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'post') === 0 && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== FALSE) {
            // POST is actually in json format, do an internal translation
            $_POST += json_decode(file_get_contents('php://input'), true);
        }
        parent::__construct();
        // header("Access-Control-Allow-Origin: *");
        // header("Access-Control-Allow-Credentials: true");
        // header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
        // header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        // header('P3P: CP="CAO PSA OUR"'); // Makes IE to support cookies
        // header("Content-Type: application/json; charset=utf-8");
        $this->CI =& get_instance();
        $this->assigns = array();
        $this->load->model('api/Token_Model');
        $this->load->model('api/Device_Model');
        $this->load->model('api/Member_Model');
        if($_model){
            $this->load->model("api/{$_model}_Model");
            $model = $_model.'_Model';
            $this->API_Model = $this->$model;
        }
        $this->_code = 200;
        $this->_output = array(
            'text' => 'fail',
            'message' => 'Bad request.',
            'code' => -1,
        );
        $this->form_validation->set_error_delimiters('', '');
        $this->_params = $this->input->get();
        // $this->_searchs = $this->input->get_post('searchs');
        $this->_page = (int)$this->input->get('page');
        $this->_perpage = (int) $this->input->get('perpage');
        if($this->_perpage <= 0) $this->_perpage = 10;
        if($this->_page <= 0) $this->_page = null; 
        $this->_id = $this->input->get('id');
        $this->_debug = $this->input->get('debug');

        // $this->valid_device();
        $this->valid_token();
    }

    function index(){
        echo 'Welcome API';
    }
    function run(){
        if($this->_page) {
            $this->db->select('SQL_CALC_FOUND_ROWS id',false);
            $this->API_Model
                ->limit($this->_page,$this->_perpage);
        }
        if(!empty($this->_id)) {
            $row = $this->API_Model->get($this->_id);
            $this->_output['data'] = $row;
        } elseif(!empty($this->_params)) {
            if(!empty($this->_filter_allows)) foreach ($this->_filter_allows as $column) {
                $value = $this->_params[$column];
                $filters[$column] = $value;
            }
            if($filters) $this->API_Model->filter($filters);
            $data = $this->API_Model->gets();
            $this->_output['data'] = $data;
        } else {
            $data = $this->API_Model->gets();
            $this->_output['data'] = $data;
        }
        if($this->_debug) $this->_output['query'][] = $this->db->last_query();
        if($this->_page){
            $query = $this->db->query('SELECT FOUND_ROWS() AS `total_rows`;');
            $tmp = $query->row_array();
            $total_rows = (int)$tmp['total_rows'];
            $this->_output['hit'] = $total_rows;
            $this->_output['page'] = $this->_page;
            $this->_output['perpage'] = $this->_perpage;
        }
        $this->_output['code'] = 0;
        $this->_output['text'] = 'ok';
        $this->_output['message'] = 'success';
    }

    

    function display(){
        $this->output
            ->set_content_type('application/json')
            ->set_status_header($this->_code)
            ->set_output(json_encode($this->_output,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
            ->_display();
            die;
    }
    function valid_device(){
        $uuid = $this->input->post('uuid');
        $device = $this->Client_Model->get_device($uuid);
        $valid = false;
        if($device) {
            $user = $this->Account_Model->get_by_id($device->uid);
            if($user){
                $valid = true;
                unset($user->password);
                $this->user = $user;
            }
        }
        if(!$valid){
            $this->_code = 403;
            $this->_output['message'] = 'Permission denied.';
            $this->display();
            die;
        }
    }
    function valid_token() {
        // $token = $_SERVER['HTTP_X_CSRF_TOKEN'];
        $stoken = $this->input->get_post('token');
        $token = $this->Token_Model->get_by_token($stoken);
        $valid = false;
        if($token){
            $this->Token_Model->update($token->token);
            $member = $this->Member_Model->get_by_id($token->member_id);
            $this->member = $member;

        }
    }

    

    function get(){
        $this->_output['code'] = -1;
        $this->_output['text'] = 'fail';
        $this->_output['message'] = 'Bad Request !';
        $id=$this->input->get_post('id');
        $data = $this->API_Model->get($id);
        $this->_output['data'] = $data;
        $this->_output['code'] = 0;
        $this->_output['text'] = 'ok';
        $this->_output['message'] = 'success';
        
        $this->display();
    }
}
