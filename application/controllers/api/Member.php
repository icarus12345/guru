<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Member extends Api_Controller {
    function __construct() {
        parent::__construct('Member');
        $this->load->model('api/Token_Model');
    }

    public $rules = array(
        'insert' => array(
                'username' => array(
                    'field'=>'username',
                    'label'=>'Username',
                    'rules'=>'trim|required|min_length[4]|max_length[50]|is_unique[__member.username]'
                    ),
                 'email' => array(
                    'field'=>'email',
                    'label'=>'Email',
                    'rules'=>'trim|valid_email|required|is_unique[__member.email]',
                    'errors' => array (
                        'required' => 'Error Message rule "required" for field email',
                        'trim' => 'Error message for rule "trim" for field email',
                        'valid_email' => 'Error message for rule "valid_email" for field email'
                    )
                ),
                'password' => array(
                    'field'=>'password',
                    'label'=>'Password',
                    'rules'=>'trim|required|min_length[4]|max_length[20]'
                ),
        ),
        'update' => array(
                'username' => array(
                    'field'=>'username',
                    'label'=>'Username',
                    'rules'=>'trim|required|min_length[4]|max_length[50]'
                    ),
                 'email' => array(
                    'field'=>'email',
                    'label'=>'Email',
                    'rules'=>'trim|valid_email|required',
                    'errors' => array (
                        'required' => 'Error Message rule "required" for field email',
                        'trim' => 'Error message for rule "trim" for field email',
                        'valid_email' => 'Error message for rule "valid_email" for field email'
                    )
                ),
                'password' => array(
                    'field'=>'password',
                    'label'=>'Password',
                    'rules'=>'trim|min_length[4]|max_length[20]'
                ),
        ),
        'login' => array(
                'username' => array(
                    'field'=>'username',
                    'label'=>'Username',
                    'rules'=>'trim|required|min_length[4]|max_length[50]'
                    ),
                'password' => array(
                    'field'=>'password',
                    'label'=>'Password',
                    'rules'=>'trim|min_length[4]|max_length[20]'
                ),
                'uuid' => array(
                    'field'=>'uuid',
                    'label'=>'UUID',
                    'rules'=>'trim|min_length[4]|max_length[100]'
                ),
        ),
    );
    function index(){
        $this->db->where('start_date < NOW()');
        $this->db->where('end_date > NOW()');
        parent::run();
        $data = $this->_output['data'];
        $this->_output['data'] = $this->_fixdata($data);
        $this->display();
    }
    function register(){
        $this->form_validation->set_rules($this->rules['insert']);
        if ($this->form_validation->run() == FALSE) {
            $this->_output['validation'] = validation_errors_array();
            $this->_output['message'] = validation_errors();
            $this->_output['code'] = -1;
            $this->_output['text'] = 'fail';
        } else {
            $username = $this->input->post('username');
            $email = $this->input->post('email');
            $phone = $this->input->post('phone');
            $password = md5($this->input->post('password'));
            $params = array(
                'username' => $username,
                'email' => $email,
                'phone' => $phone,
                'password' => $password,
                'status' => 'true'
                );
            $member = $this->API_Model->get_by_username($username);
            if(!$member) $member = $this->API_Model->get_by_email($email);
            if($member){
                $this->_output['code'] = 0;
                $this->_output['text'] = 'ok';
                $this->_output['message'] = 'Account already exists.';
            } else {

                $rs = $this->API_Model->_insert($params);
                if($rs){
                    $this->_output['code'] = 0;
                    $this->_output['text'] = 'ok';
                    $this->_output['message'] = 'success';
                } else {
                    $this->_output['code'] = -1;
                    $this->_output['text'] = 'fail';
                    $this->_output['message'] = 'Cant register account';
                }
            }
        }
        $this->display();
    }
    function update(){
        $this->form_validation->set_rules($this->rules['update']);
        if ($this->form_validation->run() == FALSE) {
            $this->_output['validation'] = validation_errors_array();
            $this->_output['message'] = validation_errors();
            $this->_output['code'] = -1;
            $this->_output['text'] = 'fail';
        } else {
            $username = $this->input->post('username');
            $email = $this->input->post('email');
            $phone = $this->input->post('phone');
            $params = array(
                'phone' => $phone,
                );
            $password = $this->input->post('password');
            if(!empty($password)) $params['password'] = md5($password);
            $member = $this->API_Model->get_by_username($username);
            if(!$member) $member = $this->API_Model->get_by_email($email);
            if($member){
                $rs = $this->API_Model->_update($member->id,$params);
                if($rs){
                    $this->_output['code'] = 0;
                    $this->_output['text'] = 'ok';
                    $this->_output['message'] = 'success';
                } else {
                    $this->_output['code'] = -1;
                    $this->_output['text'] = 'fail';
                    $this->_output['message'] = 'Cant update account';
                }
            } else {
                $this->_output['code'] = -1;
                $this->_output['text'] = 'fail';
                $this->_output['message'] = 'Account does\'t exists.';
            }
        }
        $this->display();
    }

    function login(){
        $this->form_validation->set_rules($this->rules['login']);
        if ($this->form_validation->run() == FALSE) {
            $this->_output['validation'] = validation_errors_array();
            $this->_output['message'] = validation_errors();
            $this->_output['code'] = -1;
            $this->_output['text'] = 'fail';
        } else {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $uuid = $this->input->post('uuid');
            $member = $this->API_Model->get_by_username($username);
            if($member){
                if($member->password == md5($password)){
                    $token = $this->Token_Model->create($uuid,$member->id);
                    if($token){
                        $this->_output['code'] = 0;
                        $this->_output['text'] = 'ok';
                        $this->_output['message'] = 'success';
                        $this->_output['data'] = array(
                            'token'=>$token->token,
                            'expried'=>$token->expried,
                            );
                    } else {
                        $this->_output['code'] = -1;
                        $this->_output['text'] = 'fail';
                        $this->_output['message'] = 'Cant create token';
                    }

                } else {
                    $this->_output['code'] = -1;
                    $this->_output['text'] = 'fail';
                    $this->_output['message'] = 'Password does\'t match';
                }
            } else {
                $this->_output['code'] = -1;
                $this->_output['text'] = 'fail';
                $this->_output['message'] = 'Account does\'t exists.';
            }
        }
        $this->display();
    }
}
