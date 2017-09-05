<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Campaign extends Api_Controller {
    function __construct() {
        parent::__construct('Campaign');
        
    }

    public $rules = array(
        
    );
    function _fixdata($data){
        if($data){
           foreach ($data as $key => $value) {
                $timestamp = strtotime($data[$key]->end_date);
                if(date('d',$timestamp) == date('d')){
                    $data[$key]->end_date = 'Hôm nay';
                } elseif($timestamp < time() + 7 * 24 * 60 * 60){
                    $day = date('N',$timestamp);
                    if($day == 7) {
                        $data[$key]->end_date = 'Chủ Nhật';
                    } else {
                        $data[$key]->end_date = 'Thứ '. ($day+1);
                    }
                    
                } else {
                    $data[$key]->end_date = date('d/m/Y',$timestamp);
                }
            }
        }
        return $data;
    }
    function index(){
        $this->db->where('start_date < NOW()');
        $this->db->where('end_date > NOW()');
        parent::run();
        $data = $this->_output['data'];
        $this->_output['data'] = $this->_fixdata($data);
        $this->display();
    }
    function get_all(){
        $data = $this->API_Model->get_actived($this->_page,$this->_perpage);
        if($data){
            $query = $this->db->query('SELECT FOUND_ROWS() AS `total_rows`;');
            $tmp = $query->row_array();
            $total_rows = (int)$tmp['total_rows'];
            
            $data = $this->_fixdata($data);
            if($this->_page){
                $this->_output['data']['hit'] = $total_rows;
                $this->_output['data']['page'] = $this->_page;
                $this->_output['data']['perpage'] = $this->_perpage;
                $this->_output['data']['items'] = $data;
            } else {
                $this->_output['data'] = $data;
            }
        }
        $this->_output['code'] = 1;
        $this->_output['text'] = 'ok';
        $this->_output['message'] = 'success';
        $this->display();
    }
    function get_news(){
        $data = $this->API_Model->get_by_today();
        $this->_output['data']['news_items'] = $this->_fixdata($data);
        $data = $this->API_Model->get_by_like();
        $this->_output['data']['like_items'] = $this->_fixdata($data);

        $this->_output['code'] = 1;
        $this->_output['text'] = 'ok';
        $this->_output['message'] = 'success';
        $this->display();
    }
}
