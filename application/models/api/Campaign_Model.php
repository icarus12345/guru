<?php

/*
  Project     : 48c6c450f1a4a0cc53d9585dc0fee742
  Created on  : Mar 16, 2013, 11:29:15 PM
  Author      : Truong Khuong - khuongxuantruong@gmail.com
  Description :
  Purpose of the stylesheet follows.
 */

class Campaign_Model extends API_Model {
    function __construct() {
        parent::__construct('__campaign');
        $this->_select = array(
            '__campaign.id',
            '__campaign.title',
            '__campaign.desc',
            '__campaign.image',
            '__campaign.trademark_id',
            // '__campaign.start_date',
            '__campaign.end_date',
            '__campaign.website',
            '__campaign.hotline',
            '__campaign.type_id',
            '__campaign.category_id',
            '__campaign.count_like',
            '__campaign.count_view',
            '__campaign.rating'
            );
    }
    function calc_found_row(){
        
    }
    function in_shops($shops){
        if(!empty($shops)){
            $this->db->group_start();
            foreach ($shops as $key => $shop) {
                if($key == 0)
                    $this->db->like('__campaign.shop_ids',$shop->id);
                else
                    $this->db->or_like('__campaign.shop_ids',$shop->id);
            }
            $this->db->group_end();
        }
        return $this;
    }
    function in_trademark($trademarks){
        if(!empty($trademarks)){

            foreach ($trademarks as $key => $trademark) {
                $trademark_ids[] = $trademark->id;
            }
            $this->db->where_in('__campaign.trademark_id',$trademark_ids);
        }
        return $this;
    }
    function get_actived($page, $perpage){
        if($page){
            $this->db
                ->select('SQL_CALC_FOUND_ROWS id',false)
                ->limit($perpage, ($page - 1) * $perpage);
        }
        $query = $this->db
            ->select($this->_select)
            ->from('__campaign')
            // ->join('__shop','__campaign.shop_ids')
            ->where('__campaign.start_date < NOW()')
            ->where('__campaign.end_date > NOW()')
            ->where('__campaign.status','true')
            ->get();
        $entrys = $query->result();
        return $entrys;
    }

    function get_by_today(){
        $query = $this->db
            ->select($this->_select)
            ->from('__campaign')
            ->where('start_date < NOW()')
            ->where('YEAR(end_date) = YEAR(NOW())')
            ->where('MONTH(end_date) = MONTH(NOW())')
            ->where('DAY(end_date) = DAY(NOW())')
            ->where('status','true')
            ->get();
        $entrys = $query->result();
        return $entrys;
    }

    function get_by_like(){
        $query = $this->db
            ->select($this->_select)
            ->from('__campaign')
            ->where('start_date < NOW()')
            // ->where('DAY(end_date) = DAY(NOW())')
            ->where('end_date > NOW()')
            ->where('status','true')
            ->get();
        $entrys = $query->result();
        return $entrys;
    }
}

?>
