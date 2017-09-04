<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class DApi_Controller extends CI_Controller {
    public $assigns;
    public function __construct() {
        parent::__construct();
        $this->CI =& get_instance();
        $this->assigns = array();
        $this->checklogin();
        $this->_table = 'tbl_data';
        $this->prefix = '';
        $this->colid = 'id';
        $this->load->model('dashboard/Auth_Model');
        $this->load->vars(array(
            'users' => $this->Auth_Model
            ->get_all()
        ));
    }

    function checklogin() {
        $user = $this->session->userdata('dasbboard_user');
        if (!$user) {
            $output = array(
                'text' => 'fail',
                'message' => 'Permission denied',
                'code' => -1,
                'data' => null
            );
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(403)
                ->set_output(json_encode($output,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
                ->_display();
            die;
        }
    }

    function bind(){
        $output = array(
            'text' => 'fail',
            'code' => -1,
            'data' => null
        );
        if(!empty($this->input->get_post('table'))){
            $this->_table = $this->input->get_post('table');
        }
        $type = $this->input->get_post('type');
        $showthumb = $this->input->get_post('showthumb');
        $this->Core_Model = new Core_Model($this->_table);
        $this->Core_Model->table_config = array(
            "table"     => "{$this->_table}",
            "select"    => "
                SELECT SQL_CALC_FOUND_ROWS 
                    {$this->_table}.{$this->prefix}id,
                    {$this->_table}.{$this->prefix}title,
                    {$this->_table}.{$this->prefix}created,
                    {$this->_table}.{$this->prefix}sorting,
                    {$this->_table}.{$this->prefix}modified,
                    {$this->_table}.{$this->prefix}status
                ",
            "from"      => "
                FROM `{$this->_table}`
            ",
            "where"     => !empty($type)?"WHERE {$this->_table}.`{$this->prefix}type` = '$type'":'',
            "order_by"  => "ORDER BY {$this->_table}.`{$this->prefix}sorting` DESC",
            "columnmaps"=>array(
                'cattitle'=>'tbl_category.title'
            ),
            "filterfields"=>array(
            ),
        );
        $output = $this->Core_Model->jqxBinding();
        $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($output));
    }
}

// include 'CP_Controller.php';
