<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Campaign extends Api_Controller {
    function __construct() {
        parent::__construct('Campaign');
        $this->load->model('api/Shop_Model');
        $this->load->model('api/Trademark_Model');
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
    // function index(){
    //     $this->db->where('start_date < NOW()');
    //     $this->db->where('end_date > NOW()');
    //     parent::run();
    //     $data = $this->_output['data'];
    //     $this->_output['data'] = $this->_fixdata($data);
    //     $this->display();
    // }
    function filter(){
        $types = $this->input->get('types');
        $categories = $this->input->get('categories');
        $sort = $this->input->get('sort');
        if(!empty($categories)){
            $categories = explode(',', $categories);
            $this->db->where_in('category_id',$categories);
        }
        if(!empty($types)){
            $types = explode(',', $types);
            $this->db->where_in('type_id',$types);
        }
        if($sort == 1){
            $this->db->order_by('count_view','DESC');
        } else {
            $this->db->order_by('end_date','ASC');
        }
        $data = $this->Campaign_Model
            ->get_actived($this->_page,$this->_perpage);
        if($this->_showquery) $this->_output['Queries']['FilterCampaigns'] = $this->db->last_query();
        if($data){
            $query = $this->db->query('SELECT FOUND_ROWS() AS `total_rows`;');
            $tmp = $query->row_array();
            $total_rows = (int)$tmp['total_rows'];
            if($data) $this->_output['ArrData']['hit'] = $total_rows;
            if($this->_page){
                $this->_output['ArrData']['page'] = $this->_page;
                $this->_output['ArrData']['perpage'] = $this->_perpage;
            }
        } else {
            $this->_output['ArrData']['hit'] = 0;
        }
        $this->_output['ArrData']['items'] = $data;
        

        $this->_output['code'] = 1;
        $this->_output['text'] = 'ok';
        $this->_output['message'] = 'success';
        $this->display();
    }
    function index(){
        $province_id = $this->input->get('province_id');
        $shop_data = $this->Shop_Model->get_by_province($province_id);
        if($this->_debug) $this->_output['DEBUG']['ShopByProvince']['items'] = $shop_data;
        if($this->_showquery) $this->_output['Queries']['ShopByProvince'] = $this->db->last_query();
        if($this->member){
            $trademark_data = $this->Trademark_Model->get_by_wish();
            if($this->_debug) $this->_output['DEBUG']['TrademarkByWish']['items'] = $shop_data;
            if($this->_showquery) $this->_output['Queries']['TrademarkByWish'] = $this->db->last_query();
            // if($shop_data && $trademark_data){
                $data = $this->API_Model
                    ->in_shops($shop_data)
                    ->in_trademark($trademark_data)
                    ->get_by_today();
                if($this->_showquery) $this->_output['Queries']['NewCampaigns'] = $this->db->last_query();
                $this->_output['NewData']['items'] = $this->_fixdata($data);
                if($data) $this->_output['NewData']['hit'] = count($data);
                $data = $this->API_Model
                    ->in_trademark($trademark_data)
                    ->in_shops($shop_data)
                    ->get_by_like();
                if($this->_showquery) $this->_output['Queries']['LikeCampaigns'] = $this->db->last_query();
                $this->_output['LikeData']['items'] = $this->_fixdata($data);
                if($data) $this->_output['LikeData']['hit'] = count($data);
            // }
        }
        $data = $this->API_Model
            ->in_shops($shop_data)
            ->get_actived($this->_page,$this->_perpage);
        if($data){
            $query = $this->db->query('SELECT FOUND_ROWS() AS `total_rows`;');
            $tmp = $query->row_array();
            $total_rows = (int)$tmp['total_rows'];
            
            $data = $this->_fixdata($data);
            if($data) $this->_output['ArrData']['hit'] = $total_rows;
            if($this->_page){
                $this->_output['ArrData']['page'] = $this->_page;
                $this->_output['ArrData']['perpage'] = $this->_perpage;
            }
        } else {
            $this->_output['ArrData']['hit'] = 0;
        }
        $this->_output['ArrData']['items'] = $data;

        $this->_output['code'] = 1;
        $this->_output['text'] = 'ok';
        $this->_output['message'] = 'success';
        $this->display();
    }
}
