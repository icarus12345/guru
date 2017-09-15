<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Campaign extends Api_Controller {
    function __construct() {
        parent::__construct('Campaign');
        $this->load->model('api/Shop_Model');
        $this->load->model('api/Trademark_Model');
        $this->load->model('api/Province_Model');
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
        $types = $this->input->get_post('types');
        $categories = $this->input->get_post('categories');
        $sort = $this->input->get_post('sort');
        $q = $this->input->get_post('q');
        if(!empty($categories)){
            $categories = explode(',', $categories);
            $this->Campaign_Model->db->where_in('__campaign.category_id',$categories);
        }
        if(!empty($types)){
            $types = explode(',', $types);
            $this->Campaign_Model->db->where_in('__campaign.type_id',$types);
        }
        if(!empty($q)){
            $this->Campaign_Model->db->like('__campaign.title',$q);
        }
        if($sort == 1){
            $this->Campaign_Model->db->order_by('__campaign.count_like','DESC');
            $this->Campaign_Model->db->order_by('__campaign.count_view','DESC');
        } else {
            $this->Campaign_Model->db->order_by('__campaign.end_date','ASC');
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
        $this->_output['ArrData']['items'] = $this->_fixdata($data);;
        

        $this->_output['code'] = 1;
        $this->_output['text'] = 'ok';
        $this->_output['message'] = 'success';
        $this->display();
    }
    function index(){
        $province_id = $this->input->get_post('province_id');
        if($province_id){
            $province = $this->Province_Model->get($province_id);
            $shop_data = $this->Shop_Model->get_by_province($province_id);
            // if(!$province){
            //     $this->_output['code'] = -1;
            //     $this->_output['text'] = 'fail';
            //     $this->_output['message'] = 'Province not exists.';
            //     $this->display();
            //     die;
            // }
            if(!$shop_data) $shop_data = -1;
        }
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
                $this->_output['NewData']['hit'] = count($data);
                $data = $this->API_Model
                    ->in_trademark($trademark_data)
                    ->in_shops($shop_data)
                    ->get_actived();
                if($this->_showquery) $this->_output['Queries']['LikeCampaigns'] = $this->db->last_query();
                $this->_output['LikeData']['items'] = $this->_fixdata($data);
                $this->_output['LikeData']['hit'] = count($data);
            // }
        }
        $data = $this->API_Model
            ->in_shops($shop_data)
            ->get_actived($this->_page,$this->_perpage);
        if($this->_showquery) $this->_output['Queries']['Campaigns'] = $this->db->last_query();
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
    function wish_list(){
        if($this->member){
           
            // if($shop_data && $trademark_data){
                $data = $this->API_Model
                    ->get_by_like();
                if($this->_showquery) $this->_output['Queries']['LikeCampaigns'] = $this->db->last_query();
                $this->_output['ArrData']['items'] = $this->_fixdata($data);
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
}
