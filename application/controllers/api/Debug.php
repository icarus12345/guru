<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Debug extends CI_Controller {
    function __construct() {
        parent::__construct();
        
    }
    function index(){
    }
    function data(){
        // create company
//         delete from __shop where id > 1008;
// delete from __campaign where id > 8;
// delete from __trademark where id > 4;
// delete from __company where id > 1004;
// delete from auth_users where ause_id > 10029;
        echo '<pre>';
        for($i=61;$i <= 81; $i++){
            $company_title = 'Company Name' . $i;
            $params = array(
                'title' => $company_title,
                'desc' => 'Company Desciption' . $i,
                'status' => 'true',
                'created' => date('Y-m-d H:i:s'),
                'sorting' => $i,
                'author' => 10001,
                );
            $rs = $this->db->insert('__company',$params);
            if($rs){
                echo 'Add Company <br/>';
                $company = $this->db->where('title',$company_title)
                    ->get('__company')
                    ->row();
                $username = 'admin_'.$i;
                $password = '123abc';
                $ause_secretkey = '123';
                $params = array(
                    'ause_key' => '123',
                    'ause_secretkey' => '123',
                    'ause_salt' => '123',
                    'ause_position' => '2',
                    'ause_authority' => '2,3,1003,1002',
                    'ause_company_id' => $company->id,
                    'ause_username' => $username,
                    'ause_email' => $username .'@gmail.com',
                    'ause_password' => md5($username . $password . $ause_secretkey),
                    'ause_status' => 'true',
                    'ause_created' => date('Y-m-d H:i:s'),
                    'ause_sorting' => $i,    
                    'ause_level' => 3,
                    );
                $rs = $this->db->insert('auth_users',$params);
                if($rs){
                    echo 'Add User <br/>';

                    $user = $this->db->where('ause_username',$username)
                        ->get('auth_users')
                        ->row();

                    $trademark_title = "Trademark Title ".$i;
                    $params = array(
                        'company_id' => $company->id,
                        'title' => $trademark_title,
                        'logo' => 'https://placeholdit.imgix.net/~text?txtsize=33&txt=Logo&w=240&h=240&bg=fabc12',
                        'image' => 'https://placeholdit.imgix.net/~text?txtsize=33&txt=Image&w=240&h=240&bg=fabc12',
                        'desc' => 'Trademark Desciption' . $i,
                        'status' => 'true',
                        'created' => date('Y-m-d H:i:s'),
                        'sorting' => $i,
                        'author' => $user->ause_id,
                        );
                    $rs = $this->db->insert('__trademark',$params);
                    if($rs){
                        echo 'Add Trademark <br/>';
                        $trademark = $this->db->where('title',$trademark_title)
                            ->get('__trademark')
                            ->row();
                        for($s = 2;$s<=rand(3,10);$s++){
                            $shop_title = "Shop Title $trademark->id $i $s";
                            $params = array(
                                'company_id' => $company->id,
                                'province_id' => rand(1,63),
                                'trademark_id' => $trademark->id,
                                'title' => $shop_title,
                                'address' => "Shop Address $trademark->id $i $s",
                                'image' => 'https://placeholdit.imgix.net/~text?txtsize=33&txt=Image&w=240&h=240&bg=fabc12',
                                'lat' => rand(10000,20000)/1000,
                                'lon' => rand(90000,120000)/1000,
                                'status' => 'true',
                                'created' => date('Y-m-d H:i:s'),
                                'sorting' => $i,
                                'author' => $user->ause_id,
                                );
                            $rs = $this->db->insert('__shop',$params);
                            if($rs){
                                echo 'Add Shop <br/>';
                            }
                        }
                        $shops = $this->db->where('trademark_id',$trademark->id)
                            ->get('__shop')
                            ->result();
                        $shop_ids = array();
                        foreach ($shops as $key => $value) {
                            $shop_ids[] = $value->id;
                        }
                        $shop_ids = implode(',',$shop_ids);
                        $cnum = rand(1, 30);
                        $min = strtotime('2017-09-01 00:00:00');
                        $max = strtotime('2017-10-30 23:59:00');
                        for($c = 1;$c<=$cnum;$c++){
                            $timestam = rand($min,$max);
                            $start_date = date("Y-m-d H:i:s",$min);
                            $end_date = date("Y-m-d H:i:s",$timestam);
                            $campaign_title = "Campaign Title $trademark->id $i $c";
                            $params = array(
                                'company_id' => $company->id,
                                'shop_ids' => $shop_ids,
                                'trademark_id' => $trademark->id,
                                'type_id' => rand(49,51),
                                'category_id' => rand(94,99),
                                'title' => "Khuyến mãi $trademark->id $end_date",
                                'desc' => 'Desciption',
                                'content' => "lorem lpsum...",
                                'image' => 'https://placeholdit.imgix.net/~text?txtsize=33&txt=Image&w=480&h=480&bg=fabc12',
                                'status' => 'true',
                                'created' => date('Y-m-d H:i:s'),
                                'start_date' => $start_date,
                                'end_date' => $end_date,
                                'sorting' => $i,
                                'author' => $user->ause_id,
                                );
                            $rs = $this->db->insert('__campaign',$params);
                            if($rs){
                                echo 'Add Campaign <br/>';
                            }
                        }
                    }
                }
                
            }
        }
        // for($i=1;$i < 20; $i++){
        //     $params = array(
        //         'title' => 'Company Name' . $i,
        //         'desc' => 'Company Desciption' . $i,
        //         'status' => 'true',
        //         'created' => date('Y-m-d H:i:s'),
        //         'sorting' => 1,
        //         'author' => 10001,
        //         );
        //     $rs = $this->db->insert('__company',$params);
        //     if($rs){
        //         print_r($params);
        //     }
        // }
    }
}
