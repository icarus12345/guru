<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (strcasecmp($_SERVER['REQUEST_METHOD'], 'post') === 0 && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== FALSE) {
    // POST is actually in json format, do an internal translation
    $_POST += json_decode(file_get_contents('php://input'), true);
}
class Auth extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model("api/Token_Model");
        $this->load->model("api/Client_Model");
        $this->load->model("api/Account_Model");
        $this->form_validation->set_error_delimiters('', '');
        $this->_code = 200;
    }

    public $rules = array(
        'login' => array(
                'username' => array(
                    'field'=>'username',
                    'label'=>'Username',
                    'rules'=>'trim|required|alpha_numeric|min_length[4]|max_length[50]'
                    ),
                'password' => array(
                    'field'=>'password',
                    'label'=>'Password',
                    'rules'=>'trim|required|min_length[4]|max_length[50]'
                    ),
        ),
        'register' => array(
                'username' => array(
                    'field'=>'username',
                    'label'=>'Username',
                    'rules'=>'trim|required|alpha_numeric|min_length[4]|max_length[50]|is_unique[auth_account.username]'
                    ),
                'email' => array(
                    'field'=>'email',
                    'label'=>'Email',
                    'rules'=>'trim|valid_email|required|is_unique[auth_account.email]',
                    'errors' => array (
                        'required' => 'Error Message rule "required" for field email',
                        'trim' => 'Error message for rule "trim" for field email',
                        'valid_email' => 'Error message for rule "valid_email" for field email'
                    )
                ),
                'first_name' => array(
                    'field'=>'first_name',
                    'label'=>'Fist name',
                    'rules'=>'trim|max_length[50]'
                    ),
                'last_name' => array(
                    'field'=>'last_name',
                    'label'=>'Last name',
                    'rules'=>'trim|max_length[50]'
                    ),
                'password' => array(
                    'field'=>'password',
                    'label'=>'Password',
                    'rules'=>'trim|required|min_length[4]|max_length[50]'
                    ),
        ),
        'get_token' => array(
                'app_id' => array(
                    'field'=>'app_id',
                    'label'=>'App ID',
                    'rules'=>'trim|required'
                ),
                        
                'app_secret' => array(
                    'field'=>'app_secret',
                    'label'=>'App Secret',
                    'rules'=>'trim|required'
                )
        )
    );

    function index(){
        echo 'Welcome API';
    }
    function display(){
        $this->output
            ->set_content_type('application/json')
            ->set_status_header($this->_code)
            ->set_output(json_encode($this->_output,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
            ->_display();
            die;
    }
    function device_info(){
        
        // $app_id = $this->input->post('app_id');
        // $app_secret = $this->input->post('app_secret');
        $uuid = $this->input->post('uuid');
        $device_info = $this->input->post('device_info');
        // $this->form_validation->set_rules($this->rules['get_token']);
        // if ($this->form_validation->run() == FALSE) {
            // $output['validation'] = validation_errors_array();
            // $output['message'] = validation_errors();
        // } else {
            $device = $this->Client_Model->get_device($uuid);
            if($device) {
                $user = $this->Account_Model->get_by_id($device->uid);
                if($user){
                    unset($user->password);
                    $data = array(
                        'user_info' => $user
                        );
                    $this->_output['data'] = $data;
                    $this->_output['code'] = 1;
                    $this->_output['text'] = 'ok';
                    $this->_output['message'] = 'Success';
                    $this->user = $user;
                } else {
                    $this->_output['code'] = -403;
                    $this->_output['message'] = 'Fail to generator token.';
                }
            } else {
                $params = array(
                    'uuid'=>$uuid,
                    'data'=>serialize($device_info)
                    );
                $this->Client_Model->insert_device($params);
                $this->_output['code'] = -403;
                $this->_output['message'] = 'Device does\' exists.';
            }
        // }
        $this->display();
    }

    function get_client(){
        $code = 403;
        $output = array(
            'text' => 'fail',
            'message' => 'Access Denied.',
            'code' => -1,
        );
        $app_id = $this->input->post('app_id');
        $token = $_SERVER['HTTP_X_CSRF_TOKEN'];
        $tok = $this->Token_Model->get($app_id,$token);
        if($tok){
            $code = 200;
            // $output['code'] = -201;
            // $output['message'] = 'Have Another Device Access To Your Account';
            $output['data'] = $tok;
        } else {
            $code = 403;
            $output['message'] = 'Token invalid.';
        }
        $this->output
            ->set_content_type('application/json')
            ->set_status_header($code)
            ->set_output(json_encode($output,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    function register(){
        $code = 403;
        $output = array(
            'text' => 'Access Denied.',
            'code' => -1,
        );
        $app_id = $this->input->post('app_id');
        $username = $this->input->post('username');
        $email = $this->input->post('email');
        $first = $this->input->post('first_name');
        $last = $this->input->post('last_name');
        $password = $this->input->post('password');
        // $token = $_SERVER['HTTP_X_CSRF_TOKEN'];
        $this->form_validation->set_rules($this->rules['register']);
        if ($this->form_validation->run() == FALSE) {
            $code = 200;
            $output['text'] = 'Fail.';
            $output['validation'] = validation_errors_array();
            $output['message'] = validation_errors();
        } else {
            // $tok = $this->Token_Model->get($app_id,$token);
            // if($tok){
                $params = array(
                    'username' => $username,
                    'email' => $email,
                    'password' => md5($password),
                    'first_name' => $first,
                    'last_name' => $last,
                    );
                $rs = $this->Account_Model->create($params);
                if($rs){
                    $user = $this->Account_Model->get_by_username($username);
                    $code = 200;
                    $output['code'] = 1;
                    $output['text'] = 'Success.';
                    $output['data'] = $user;
                } else {
                    $code = 200;
                    $output['text'] = 'Can\'t register account.';
                    $output['text'] = 'Fail.';
                }
            // } else {
                // $code = 403;
                // $output['message'] = 'Token invalid.';
            // }
        }
        $this->output
            ->set_content_type('application/json')
            ->set_status_header($code)
            ->set_output(json_encode($output,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    function login(){
        $code = 403;
        $output = array(
            'text' => 'fail',
            'message' => 'Access Denied.',
            'code' => -1,
        );
        $uuid = $this->input->post('uuid');
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        // $token = $_SERVER['HTTP_X_CSRF_TOKEN'];
        $this->form_validation->set_rules($this->rules['login']);
        if ($this->form_validation->run() == FALSE) {
            $code = 200;
            $output['text'] = 'Fail.';
            $output['validation'] = validation_errors_array();
            $output['message'] = validation_errors();
        } else {
            // $tok = $this->Token_Model->get($app_id,$token);
            // if($tok){
                
                    $code = 200;
                    $user = $this->Account_Model->get_by_username($username);
                    if($user){
                        if($user->password == md5($password)){
                            if($user->status==1){
                                unset($user->password);
                                // $output['code'] = 1;
                                // $output['text'] = 'Success.';
                                $output['code'] = 1;
                                $output['text'] = 'Success';
                                
                                $output['data'] = array(
                                    'user_info' => $user
                                    );
                                $this->Account_Model->update($user->id,array(
                                    'last_login' => date('Y-m-d H:i:s')
                                    ));
                                $this->Client_Model->update_device($uuid,array(
                                    'uid' => $user->id
                                    ));
                            } else {
                                $output['message'] = 'Your account have been deleted.';
                            }
                        } else {
                            $output['message'] = 'Login failed password did not match that for the login provided.';
                            
                        }
                    } else {
                        $output['message'] = 'User does\'t exists.';
                    }
                
            // } else {
                // $code = 403;
                // $output['message'] = 'Token invalid.';
            // }
        }
        $this->output
            ->set_content_type('application/json')
            ->set_status_header($code)
            ->set_output(json_encode($output,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    
    function logout(){
        $code = 200;
        $output = array(
            'text' => 'fail',
            'code' => -1,
        );
        // $user = $this->session->userdata('api_user');
        $uuid = $this->input->post('uuid');
        $device = $this->Client_Model->get_device($uuid);
        if($device){
            $rs = $this->Client_Model->update_device($uuid,array(
                'uid' => null
                ));
            // if($rs){
                $output['code'] = 1;
                $output['text'] = 'success.';
                $output['message'] = 'Success';
            // } else {
                // $output['message'] = 'Can\'t logout.';
            // }
        } else {
            $output['message'] = 'Device doest exists.';
        }
        $this->output
            ->set_content_type('application/json')
            ->set_status_header($code)
            ->set_output(json_encode($output,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
}
