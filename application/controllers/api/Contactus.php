<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contactus extends Api_Controller {
    function __construct() {
        parent::__construct('Contactus');
        
    }

    public $rules = array(
        'create' => array(
            'title' => array(
                'field'=>'title',
                'label'=>'Subject',
                'rules'=>'trim|required|min_length[2]|max_length[120]'
                ),
            'name' => array(
                'field'=>'name',
                'label'=>'Name',
                'rules'=>'trim|required|min_length[2]|max_length[120]'
                ),
            'email' => array(
                'field'=>'email',
                'label'=>'Email',
                'rules'=>'trim|valid_email|required',
                'errors' => array (
                    'required' => 'Error Message rule "required" for field Email',
                    'trim' => 'Error message for rule "trim" for field Email',
                    'valid_email' => 'Error message for rule "valid_email" for field Email'
                    )
                ),
            'address' => array(
                'field'=>'address',
                'label'=>'Address',
                'rules'=>'trim|required|max_length[180]'
                ),
            'phone' => array(
                'field'=>'phone', 
                'label'=>'Phone',
                'rules'=>'trim|required|min_length[8]|max_length[12]'
                ),
            'message' => array(
                'field'=>'message',
                'label'=>'Message',
                'rules'=>'trim|required'
                ),
            'service' => array(
                'field'=>'service',
                'label'=>'Service',
                'rules'=>'trim|required'
                ),
            )
    );

    function index(){
        echo 'Welcome API';
    }

    function add(){
        $config['upload_path'] = './data/image/upload';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size']     = '1024';
        $config['max_width'] = '1920';
        $config['max_height'] = '1280';
        $this->load->library('upload',$config);
        // $this->upload->initialize($config);

        $title = $this->input->post('title');
        $name = $this->input->post('name');
        $email = $this->input->post('email');
        $address = $this->input->post('address');
        $phone = $this->input->post('phone');
        $service = $this->input->post('service');
        $message = $this->input->post('message');
        $this->form_validation->set_rules($this->rules['create']);
        if ($this->form_validation->run() == FALSE) {
            $this->_output['validation'] = validation_errors_array();
            $this->_output['message'] = validation_errors();
        } else {
            if (!empty($_FILES['image_left']['name'])) {
                $this->upload->initialize($config);
                if ( ! $this->upload->do_upload('image_left')) {
                    $this->_output['upload_errors']['image_left'] = $this->upload->display_errors('','');
                } else {
                    $upload_data = $this->upload->data();
                    $image_left = base_url("data/image/upload/".$upload_data['file_name']);
                }
            }
            if (!empty($_FILES['image_center']['name'])) {
                $this->upload->initialize($config);
                if ( ! $this->upload->do_upload('image_center')) {
                    $this->_output['upload_errors']['image_center'] = $this->upload->display_errors('','');
                } else {
                    $upload_data = $this->upload->data();
                    $image_center = base_url("data/image/upload/".$upload_data['file_name']);
                }
            }
            if (!empty($_FILES['image_right']['name'])) {
                $this->upload->initialize($config);
                if ( ! $this->upload->do_upload('image_right')) {
                    $this->_output['upload_errors']['image_right'] = $this->upload->display_errors('','');
                } else {
                    $upload_data = $this->upload->data();
                    $image_right = base_url("data/image/upload/".$upload_data['file_name']);
                }
            }
            if(empty($this->_output['upload_errors'])){


                $params = array(
                    'title' => $title,
                    'data'=>array(
                        'name' => $name,
                        'email' => $email,
                        'address' => $address,
                        'phone' => $phone,
                        'service' => $service,
                        'message' => $message,
                        'image_left' => $image_left,
                        'image_center' => $image_center,
                        'image_right' => $image_right,
                        ),
                    'status' => 1
                    );
                $rs = $this->Contactus_Model->insert($params); 
                if($rs){
                    $this->_output['code'] = 1;
                    $this->_output['text'] = 'ok';
                    $this->_output['message'] = 'Success';
                    $this->_output['data'] = array(
                        'name' => $name,
                        'email' => $email,
                        'address' => $address,
                        'phone' => $phone,
                        'service' => $service,
                        'message' => $message,
                        'image_left' => $image_left,
                        'image_center' => $image_center,
                        'image_right' => $image_right,
                        'title' => $title,
                        );
                }
            }
        }
        $this->display();
    }

}
