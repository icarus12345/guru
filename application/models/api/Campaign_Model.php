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
    function join_trademark(){
        $this->db
            ->select('__trademark.logo',false)
            ->join('__trademark',"`__trademark`.`id` = `__campaign`.`trademark_id`",'LEFT');
    }
    function join_wish(){
        if($this->member){
            $member_id = $this->member->id;
        }else{
            $member_id = -1;
        }
        $this->db
            ->select('IF(__campaign_wish.status="true",1,0) as like_status',false)
            ->join('__campaign_wish',"( `__campaign`.`id` = `__campaign_wish`.`campaign_id` AND `__campaign_wish`.`member_id` = $member_id)",'LEFT');
                
        return $this;
    }
    function in_shops($shops){
        if ($shops == -1){
            $this->db->where('1=2');
        }else{
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
    function get_actived($page = null, $perpage = 10){
        if($page){
            $this->db
                ->select('SQL_CALC_FOUND_ROWS __campaign.id',false)
                ->limit($perpage, ($page - 1) * $perpage);
        }
        $query = $this->db
            ->select($this->_select)
            ->from('__campaign')
            
            ->where('__campaign.start_date < NOW()')
            ->where('__campaign.end_date > NOW()')
            ->where('__campaign.status','true');
        $this->join_wish();
        $this->join_trademark();
        $query = $this->db->get();
        $entrys = $query->result();
        return $entrys;
    }

    function get_by_today(){
        $query = $this->db
            ->select($this->_select)
            ->from('__campaign')
            ->where('__campaign.start_date < NOW()')
            ->where('YEAR(__campaign.end_date) = YEAR(NOW())')
            ->where('MONTH(__campaign.end_date) = MONTH(NOW())')
            ->where('DAY(__campaign.end_date) = DAY(NOW())')
            ->where('__campaign.status','true');
        $this->join_wish();
        $this->join_trademark();
        $query = $this->db->get();
        $entrys = $query->result();
        return $entrys;
    }

    function get_by_like(){
        $query = $this->db
            ->select($this->_select)
            ->from('__campaign')
            ->where('__campaign.start_date < NOW()')
            // ->where('DAY(end_date) = DAY(NOW())')
            ->where('__campaign.end_date > NOW()')
            ->where('__campaign.status','true')
            ->where('__campaign_wish.status','true');
        $this->join_wish();
        $this->join_trademark();
        $query = $this->db->get();
        $entrys = $query->result();
        return $entrys;
    }
}

?>
