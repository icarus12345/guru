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
        $this->load->model('api/Client_Model');
        $this->load->model('api/Account_Model');
        if($_model){
            $this->load->model("api/{$_model}_Model");
            $model = $_model.'_Model';
            $this->API_Model = $this->$model;
        }
        $this->load->model('api/Cate_Model');
        $this->_code = 200;
        $this->_output = array(
            'text' => 'fail',
            'message' => 'Bad request.',
            'code' => -1,
        );
        $this->form_validation->set_error_delimiters('', '');
        $this->_filters = $this->input->get_post('filters');
        $this->_searchs = $this->input->get_post('searchs');
        $this->_page = $this->input->get_post('page');
        $this->_limit = $this->input->get_post('limit');
        $this->_id = $this->input->get_post('id');
        $this->valid_device();
        // $this->valid_token();
    }

    function index(){
        echo 'Welcome API';
    }
    function run(){
        if(!empty($this->_id)) {
            $row = $this->API_Model->get($this->_id);
            $this->_output['data'] = $row;
        } else {
            $data = $this->API_Model->gets();
            $this->_output['data'] = $data;
        }
        $this->_output['code'] = 1;
        $this->_output['text'] = 'ok';
        $this->_output['message'] = 'success';
        $this->display();
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
        $app_id = $this->input->post('app_id');
        // $token = $_SERVER['HTTP_X_CSRF_TOKEN'];
        $token = $this->input->post('token');
        $tok = $this->Token_Model->get_by_token($token);
        $valid = false;
        if($tok){
            $this->Token_Model->update($tok->token_id);
            $valid = true;
            $user = $this->session->userdata('api_user');
            if(!$user){

                $user = $this->Account_Model->get_by_id($tok->token_app_id);
                $this->session->set_userdata('api_user', $user);
            }
            $this->user = $user;

        }
        if(!$valid){
            // $this->_code = 403;
            // $this->_output['message'] = 'Permission denied.';
            // $this->display();
            // die;
        }
    }

    function get_by_cid(){
        $this->_output['code'] = -1;
        $this->_output['text'] = 'fail';
        $this->_output['message'] = 'Bad Request !';
        $cid=$this->input->get_post('cid');
        $c = $this->Cate_Model
            ->select('value')
            ->get($cid);
        if($c){
            $data = [];
            $cates = $this->Cate_Model
                ->search(array('value'=>$c->value))
                ->get_by_type('mega');
            foreach ($cates as $key => $value) {
                $cids[] = $value->id;
            }
            if($cids){
                $data = $this->API_Model
                ->filter_in(array('category'=>$cids))
                ->get_by_type('mega');
            }
            $this->_output['data'] = $data;
            $this->_output['code'] = 1;
            $this->_output['text'] = 'ok';
            $this->_output['message'] = 'success';
        }else{
            $this->_output['message'] = 'Category not exists !';
        }

        
        $this->display();
    }

    function get(){
        $this->_output['code'] = -1;
        $this->_output['text'] = 'fail';
        $this->_output['message'] = 'Bad Request !';
        $id=$this->input->get_post('id');
        $data = $this->API_Model->get($id);
        $this->_output['data'] = $data;
        $this->_output['code'] = 1;
        $this->_output['text'] = 'ok';
        $this->_output['message'] = 'success';
        
        $this->display();
    }
}
