<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cate extends Api_Controller {
    function __construct() {
        parent::__construct('Cate');
        
    }

    public $rules = array(
    );

    function index(){
        echo 'Welcome API';
    }

    function get_all(){
        $data = $this->Cate_Model
            ->get_by_type('mega');
        $data = $this->Cate_Model->buildTree($data);
        $this->_output['data'] = $data;

        $this->_output['code'] = 1;
        $this->_output['text'] = 'ok';
        $this->_output['message'] = 'success';
        $this->display();
    }

    function get_by_tid(){
        $tid=$this->input->get_post('tid');
        $data = $this->Cate_Model
            ->filter(array('tid'=>$tid))
            ->get_by_type('mega');
        $data = $this->Cate_Model->buildTree($data);
        $this->_output['data'] = $data;

        $this->_output['code'] = 1;
        $this->_output['text'] = 'ok';
        $this->_output['message'] = 'success';
        $this->display();
    }
    function get_by_pid(){
        $pid=$this->input->get_post('pid');
        $c = $this->Cate_Model
            ->select('value')
            ->get($pid);
        if($c){
            $data = $this->Cate_Model
                ->search(array('value'=>$c->value))
                ->get_by_type('mega');
            $data = $this->Cate_Model->buildTree($data);
            $this->_output['data'] = $data;
        }

        $this->_output['code'] = 1;
        $this->_output['text'] = 'ok';
        $this->_output['message'] = 'success';
        $this->display();
    }

}
