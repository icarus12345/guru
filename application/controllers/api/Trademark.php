<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Trademark extends Api_Controller {
    function __construct() {
        parent::__construct('Trademark');
        
    }

    public $rules = array(
        'wish' => array(
                'trademark_id' => array(
                    'field'=>'trademark_id',
                    'label'=>'Trademark ID',
                    'rules'=>'trim|required|integer'
                    ),
                'status' => array(
                    'field'=>'status',
                    'label'=>'Status',
                    'rules'=>'trim|required|integer'
                    ),
                'uuid' => array(
                    'field'=>'uuid',
                    'label'=>'UUID',
                    'rules'=>'trim|min_length[4]|max_length[100]'
                ),
        ),
    );
    function index(){
        if($this->member){
            $trademark_data = $this->Trademark_Model->get_by_wish();
            if($this->_debug) $this->_output['DEBUG']['TrademarkByWish']['items'] = $shop_data;
            if($this->_showquery) $this->_output['Queries']['TrademarkByWish'] = $this->db->last_query();
            $this->_output['LikeData']['items'] = $trademark_data;
            if($trademark_data) $this->_output['LikeData']['hit'] = count($trademark_data);
        }
        $data = $this->Trademark_Model
            ->get_actived();
        $this->_output['ArrData']['items'] = $data;
        if($data) $this->_output['ArrData']['hit'] = count($data);
        else $this->_output['ArrData']['hit'] = 0;

        $this->_output['code'] = 1;
        $this->_output['text'] = 'ok';
        $this->_output['message'] = 'success';
        $this->display();
    }

    function wish_list(){
        if($this->member){
           
            // if($shop_data && $trademark_data){
                $data = $this->Trademark_Model
                    ->get_by_wish();
                if($this->_showquery) $this->_output['Queries']['TrademarkByWish'] = $this->db->last_query();
                $this->_output['ArrData']['items'] = $data;
                $this->_output['ArrData']['hit'] = count($data);
            // }
            $this->_output['ArrData']['items'] = $data;
            $this->_output['code'] = 1;
            $this->_output['text'] = 'ok';
            $this->_output['message'] = 'success';
        } else {
            $this->_output['code'] = -1;
            $this->_output['text'] = 'fail';
            $this->_output['message'] = 'mising login';
        }
        
        $this->display();
    }

    function wish(){
        $this->form_validation->set_rules($this->rules['wish']);
        if ($this->form_validation->run() == FALSE) {
            $this->_output['validation'] = validation_errors_array();
            $this->_output['message'] = validation_errors();
            $this->_output['code'] = -1;
            $this->_output['text'] = 'fail';
        } else {
            if($this->member){
                $trademark_id = $this->input->post('trademark_id');
                $status = (int)$this->input->post('status');
                $trademark = $this->Trademark_Model->get($trademark_id);
                if($trademark){
                    $params = array(
                        'trademark_id' => $trademark_id,
                        'member_id' => $this->member->id,
                        'status' => ($status) ? 'true':'false'
                        );
                    $wish = $this->Trademark_Model->get_by_wish_detail($trademark_id);
                    if($wish){
                        $rs = $this->Trademark_Model->_update_wish($trademark_id,$params);
                    } else {
                        $rs = $this->Trademark_Model->_insert_wish($params);
                    }

                    if($rs){
                        $this->_output['code'] = 0;
                        $this->_output['text'] = 'ok';
                        $this->_output['message'] = 'success';
                    } else {
                        $this->_output['code'] = -1;
                        $this->_output['text'] = 'fail';
                        $this->_output['message'] = 'Cant wish';
                    }
                } else {
                    $this->_output['code'] = -1;
                    $this->_output['text'] = 'fail';
                    $this->_output['message'] = 'Trademark not exists.';
                }
            } else {
                $this->_output['code'] = -1;
                $this->_output['text'] = 'fail';
                $this->_output['message'] = 'Please login.';
            }
        }
        $this->display();
    }
    function index_(){
        
        parent::run();
        $this->display();
    }

    function filter(){
        $has_campaign = $this->input->get_post('has_campaign');
        $q = $this->input->get_post('q');
        
        $this->_output['ArrData']['hit'] = 0;
        if(!empty($q)){
            $this->Trademark_Model->db->like('__trademark.title',$q);
        }
        $this->Trademark_Model->_selectAs[] = 'count(__campaign.trademark_id) as num_campaign';
        $this->db
            ->join('__campaign',"__trademark.id = __campaign.trademark_id AND __campaign.start_date < NOW() AND __campaign.end_date > NOW() AND __campaign.status = 'true'",'LEFT');
        if($has_campaign == 1)
            $this->db->having('num_campaign > 0');
        $data = $this->Trademark_Model
            ->get_actived($this->_page,$this->_perpage);
        if($this->_showquery) $this->_output['Queries']['FilterTrademark'] = $this->db->last_query();
        if($data){
            $query = $this->db->query('SELECT FOUND_ROWS() AS `total_rows`;');
            $tmp = $query->row_array();
            $total_rows = (int)$tmp['total_rows'];
            if($data) $this->_output['ArrData']['hit'] = $total_rows;
            if($this->_page){
                $this->_output['ArrData']['page'] = $this->_page;
                $this->_output['ArrData']['perpage'] = $this->_perpage;
            }
        }
        

        $this->_output['ArrData']['items'] = $data;
        $this->_output['code'] = 1;
        $this->_output['text'] = 'ok';
        $this->_output['message'] = 'success';
        $this->display();
    }
}
