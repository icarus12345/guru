<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Utility extends Api_Controller {
    function __construct() {
        parent::__construct('Category');
        $this->load->model('api/Type_Model');
        $this->load->model('api/Country_Model');
        $this->load->model('api/Province_Model');
    }

    public $rules = array(
        'search_poi_by_latlng' => array(
                'lat' => array(
                    'field'=>'lat',
                    'label'=>'Lat',
                    'rules'=>'trim|required|numeric'
                    ),
                'lng' => array(
                    'field'=>'lng',
                    'label'=>'Lng',
                    'rules'=>'trim|required|numeric',
                ),
        ),
    );

    function index(){
        $this->_output['code'] = 1;
        $this->_output['text'] = 'ok';
        $this->_output['message'] = 'success';
        $type_data = $this->Type_Model
            ->gets();
        $this->_output['TypeData']['items'] = $type_data;
        $this->_output['TypeData']['hit'] = count($type_data);
        $category_data = $this->Category_Model
            ->gets();
        $this->_output['CategoryData']['items'] = $category_data;
        $this->_output['CategoryData']['hit'] = count($category_data);
        $this->display();
    }

    function search_poi_by_latlng(){
        $this->_output['code'] = -1;
        $this->_output['text'] = 'fail';
        $this->form_validation->set_rules($this->rules['search_poi_by_latlng']);
        if ($this->form_validation->run() == FALSE) {
            $this->_output['validation'] = validation_errors_array();
            $this->_output['message'] = validation_errors();
            $this->_output['code'] = -1;
            $this->_output['text'] = 'fail';
        } else {
            $this->_output['message'] = 'Cant get address information';
            $lat = $this->input->get_post('lat');
            $lng = $this->input->get_post('lng');
            $res = file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?latlng={$lat},{$lng}&sensor=false");
            $res = (json_decode($res,true));
            if(!empty($res['results'][0]['address_components'])){
                $this->_output['code'] = 1;
                $this->_output['text'] = 'ok';
                $this->_output['message'] = 'success';
                $address_components = $res['results'][0]['address_components'];
                foreach ($address_components as $address_component) {
                    if($address_component['types'][0] == 'administrative_area_level_1'){
                        $area_name = $address_component['short_name'];
                        $province = $this->Province_Model
                            ->get_by_name($area_name);
                        $this->_output['data']['province'] = $province;
                    }
                    if($address_component['types'][0] == 'country'){
                        $country_code = $address_component['short_name'];
                        $country = $this->Country_Model
                            ->get_by_code($country_code);
                        $this->_output['data']['country'] = $country;

                    }
                }
            }
        }
        $this->display();
    }
}
