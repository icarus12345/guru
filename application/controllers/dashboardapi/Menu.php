<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Menu extends DApi_Controller {
    function __construct() {
        parent::__construct();
        $this->Module_Model = new Core_Model('tbl_module');
        $this->load->model("dashboard/Category_Model");
    }
    
    function index(){
        echo 'Welcome API';
    }
    public $rules = array(
        'unique' => array(
            'data[title]' => array(
                'field'=>'data[title]',
                'label'=>'Title',
                'rules'=>'callback_title_check'
            ),
        ),
        'insert' => array(
            // 'data[title]' => array(
            //     'field'=>'data[title]',
            //     'label'=>'Title',
            //     'rules'=>'trim|required|max_length[255]'
            //     ),
            // 'data[alias]' => array(
            //     'field'=>'data[alias]',
            //     'label'=>'Alias',
            //     'rules'=>'trim|required|max_length[255]'
            //     ),
            // 'data[type]' => array(
            //     'field'=>'data[type]',
            //     'label'=>'Type',
            //     'rules'=>'trim|max_length[50]'
            //     ),
            // 'data[category]' => array(
            //     'field'=>'data[category]',
            //     'label'=>'Category',
            //     'rules'=>'trim|integer'
            //     ),
            'sid' => array(
                'field'=>'sid',
                'label'=>'Setting Entry',
                'rules'=>'trim|required|is_natural_no_zero',
                'errors' => array (
                    // 'required' => 'Error Message rule "required" for field Type',
                    // 'trim' => 'Error message for rule "trim" for field email',
                )
            ),
        ),
        'update' => array(
            // 'data[title]' => array(
            //     'field'=>'data[title]',
            //     'label'=>'Title',
            //     'rules'=>'trim|required|max_length[255]'
            //     ),
            // 'data[alias]' => array(
            //     'field'=>'data[alias]',
            //     'label'=>'Alias',
            //     'rules'=>'trim|required|max_length[255]'
            //     ),
            // 'data[type]' => array(
            //     'field'=>'data[type]',
            //     'label'=>'Type',
            //     'rules'=>'trim|max_length[50]'
            //     ),
            // 'data[category]' => array(
            //     'field'=>'data[category]',
            //     'label'=>'Category',
            //     'rules'=>'trim|integer'
            //     ),

            'sid' => array(
                'field'=>'sid',
                'label'=>'Setting Entry',
                'rules'=>'trim|required|is_natural_no_zero',
                'errors' => array (
                    // 'required' => 'Error Message rule "required" for field Type',
                    // 'trim' => 'Error message for rule "trim" for field email',
                )
            ),
            'id' => array(
                'field'=>'id',
                'label'=>'Id',
                'rules'=>'trim|is_natural_no_zero|required'
            ),
        )
    );
    public function title_check($str){
        $alias = $this->input->post('data[alias]');
        $type = $this->input->post('data[type]');
        $id = $this->input->post('id');
        $sid = $this->input->post('sid');
        $row = $this->Core_Model
            ->set_type($type)
            ->get_by_alias($alias);
        if($row && $row->id!=$id){
            $this->form_validation->set_message('title_check', 'The {field} field are already inserted');
            return FALSE;
        }
        return TRUE;
    }
    function see(){
        $this->load->library('table');
        $output = array(
            'text' => 'ok',
            'code' => 1,
            'data' => null
        );
        $id = $this->input->post('id');
        $sid = $this->input->post('sid');
        $onlysave = $this->input->post('onlysave');
        if(!empty($sid)) {
            $entry_setting = $this->Module_Model->get($sid);
            if($entry_setting){
                $this->prefix = $entry_setting->data['prefix'];
                if(
                    !empty($entry_setting->data['catetype']) &&
                    (
                        $entry_setting->data['cateviewer'] == 'tree' ||
                        $entry_setting->data['cateviewer'] == 'list'
                    )
                    ){
                    $cat_type = $entry_setting->data['catetype'];
                    $cate_data = $this->Category_Model->get_by_type($cat_type);
                    $entry_setting->data['categories'] = $this->Category_Model
                        ->buildTreeArray($cate_data);
                }
                if(!empty($entry_setting->data['columns'])) foreach ($entry_setting->data['columns'] as $key => $column) {
                    if($column['type'] == 'catetree'){
                        $cat_type = $column['name'];
                        $cate_data = $this->Category_Model->get_by_type($cat_type);
                        $entry_setting->data['columns'][$key]['categories'] = $this->Category_Model
                            ->buildTreeArray($cate_data);
                    } else if($column['type'] == 'catelist'){
                        $cat_type = $column['name'];
                        $cate_data = $this->Category_Model->get_by_type($cat_type);
                        $entry_setting->data['columns'][$key]['categories'] = $cate_data;
                    } else if(
                        $column['type'] == 'server-dropdown' ||
                        $column['type'] == 'server-multidropdown'
                        ){
                        $c_sid = $column['sid'];
                        $column_setting = $this->Module_Model->get($c_sid);

                        $sub_storage = $column_setting->data['storage'];
                        $sub_type = $column_setting->data['type'];
                        $sub_prefix = $column_setting->data['prefix'];
                        $sub_model = new Core_Model($sub_storage,$sub_prefix);
                        $sub_typeviewer = $column_setting->data['typeviewer'];
                        if(!empty($sub_typeviewer)){
                            $sub_entrys = $sub_model->select("{$sub_prefix}id,{$sub_prefix}title")
                                ->get_by_type($sub_type);
                        } else {
                            $sub_entrys = $sub_model->select("{$sub_prefix}id,{$sub_prefix}title")
                                ->gets();
                        }
                        foreach ($sub_entrys as $key => $value) {
                            $sub_entrys[$key]->id = $sub_entrys[$key]->{$sub_prefix.'id'};
                            $sub_entrys[$key]->title = $sub_entrys[$key]->{$sub_prefix.'title'};
                        }
                        $entry_setting->data['columns'][$key]['sub_data'] = $sub_entrys;

                    }
                }
                $this->load->vars(array(
                    'entry_setting' => $entry_setting
                ));
                $storage = $entry_setting->data['storage'];
                if(!empty($storage)){
                    $this->Core_Model = new Core_Model($storage,$this->prefix);
                    $entry_detail = $this->Core_Model->get($id);
                    $tmp = get_object_vars($entry_detail);
                    $data_table_entry[] = array('Column','Value');
                    foreach ($tmp as $key => $value) {
                        if($key!='data'){

                            $data_table_entry[] = array($key,$value);
                        } else {
                            if(!empty($tmp['data']))
                            foreach ($tmp['data'] as $key => $value) {
                                $data_table_entry[] = array($key,$value);
                            }
                        }
                    }
                    $template = array(
                            'table_open' => '<table class="table">'
                    );  
                    $this->table->set_template($template);
                    $this->load->vars(array(
                        'entry_detail' => $entry_detail,
                        'debug_table' => $this->table->generate($data_table_entry),
                        // 'debug_table' => $data_table_entry,
                        'onlysave'=>$onlysave
                        ));
                    $output['data'] = $entry_detail; 
                    
                    $output['html'] = $this->load->view('dashboard/forms/menu_see',null,true);
                } else {
                    $output['text'] = 'fail';
                    $output['code'] = -1;
                    $output['message'] = 'Storage does\'t exitst.';
                }
            } else {
                $output['text'] = 'fail';
                $output['code'] = -1;
                $output['message'] = 'Setting does\'t exitst.';
            }
        };
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($output));
    }

    function detail(){
        $output = array(
            'text' => 'ok',
            'code' => 1,
            'data' => null
        );
        $id = $this->input->post('id');
        $sid = $this->input->post('sid');
        $onlysave = $this->input->post('onlysave');
        if(!empty($sid)) {
            $entry_setting = $this->Module_Model->get($sid);
            if($entry_setting){
                $this->prefix = $entry_setting->data['prefix'];
                if(
                    !empty($entry_setting->data['catetype']) &&
                    (
                        $entry_setting->data['cateviewer'] == 'tree' ||
                        $entry_setting->data['cateviewer'] == 'list'
                    )
                    ){
                    $cat_type = $entry_setting->data['catetype'];
                    $cate_data = $this->Category_Model->get_by_type($cat_type);
                    $entry_setting->data['categories'] = $this->Category_Model
                        ->buildTreeArray($cate_data);
                }
                if(!empty($entry_setting->data['columns'])) foreach ($entry_setting->data['columns'] as $key => $column) {
                    if($column['type'] == 'catetree'){
                        $cat_type = $column['name'];
                        $cate_data = $this->Category_Model->get_by_type($cat_type);
                        $entry_setting->data['columns'][$key]['categories'] = $this->Category_Model
                            ->buildTreeArray($cate_data);
                    } else if($column['type'] == 'catelist'){
                        $cat_type = $column['name'];
                        $cate_data = $this->Category_Model->get_by_type($cat_type);
                        $entry_setting->data['columns'][$key]['categories'] = $cate_data;
                    } else if(
                        $column['type'] == 'server-dropdown' ||
                        $column['type'] == 'server-multidropdown'
                        ){
                        $c_sid = $column['sid'];
                        $column_setting = $this->Module_Model->get($c_sid);

                        $sub_storage = $column_setting->data['storage'];
                        $sub_type = $column_setting->data['type'];
                        $sub_prefix = $column_setting->data['prefix'];
                        $sub_model = new Core_Model($sub_storage,$sub_prefix);
                        $sub_typeviewer = $column_setting->data['typeviewer'];
                        if(!empty($sub_typeviewer)){
                            $sub_entrys = $sub_model->select("{$sub_prefix}id,{$sub_prefix}title")
                                ->get_by_type($sub_type);
                        } else {
                            $sub_entrys = $sub_model->select("{$sub_prefix}id,{$sub_prefix}title")
                                ->gets();
                        }
                        foreach ($sub_entrys as $key => $value) {
                            $sub_entrys[$key]->id = $sub_entrys[$key]->{$sub_prefix.'id'};
                            $sub_entrys[$key]->title = $sub_entrys[$key]->{$sub_prefix.'title'};
                        }
                        $entry_setting->data['columns'][$key]['sub_data'] = $sub_entrys;

                    }
                }
                $this->load->vars(array(
                    'entry_setting' => $entry_setting
                ));
                $storage = $entry_setting->data['storage'];
                if(!empty($storage)){
                    if(!empty($id)) {
                        if($entry_setting->data['edit']=='false'){
                            $output['code'] = -1;
                            $output['message'] = 'Access Denied .';
                        }
                        $this->Core_Model = new Core_Model($storage,$this->prefix);
                        $entry_detail = $this->Core_Model->get($id);
                        $this->load->vars(array(
                            'entry_detail' => $entry_detail,
                            'onlysave'=>$onlysave
                            ));
                        $output['data'] = $entry_detail;
                    } else{
                        if($entry_setting->data['add']=='false'){
                            $output['code'] = -1;
                            $output['message'] = 'Access Denied .';
                        }
                    }
                    $output['html'] = $this->load->view('dashboard/forms/menu_detail',null,true);
                } else {
                    $output['text'] = 'fail';
                    $output['code'] = -1;
                    $output['message'] = 'Storage does\'t exitst.';
                }
            } else {
                $output['text'] = 'fail';
                $output['code'] = -1;
                $output['message'] = 'Setting does\'t exitst.';
            }
        };
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($output));
    }
    function subdetail(){
        $output = array(
            'text' => 'ok',
            'code' => 1,
            'data' => null
        );
        $data = $this->input->post('data');
        $sid = $this->input->post('sid');
        if(!empty($sid)) {
            $entry_setting = $this->Module_Model->get($sid);
            if($entry_setting){
                if(
                    !empty($entry_setting->data['catetype']) &&
                    (
                        $entry_setting->data['cateviewer'] == 'tree' ||
                        $entry_setting->data['cateviewer'] == 'list'
                    )
                    ){
                    $cat_type = $entry_setting->data['catetype'];
                    $cate_data = $this->Category_Model->get_by_type($cat_type);
                    $entry_setting->data['categories'] = $this->Category_Model
                        ->buildTreeArray($cate_data);
                }
                if(!empty($entry_setting->data['columns'])) foreach ($entry_setting->data['columns'] as $key => $column) {
                    if($column['type'] == 'catetree'){
                        $cat_type = $column['name'];
                        $cate_data = $this->Category_Model->get_by_type($cat_type);
                        $entry_setting->data['columns'][$key]['categories'] = $this->Category_Model
                            ->buildTreeArray($cate_data);
                    } else if($column['type'] == 'catelist'){
                        $cat_type = $column['name'];
                        $cate_data = $this->Category_Model->get_by_type($cat_type);
                        $entry_setting->data['columns'][$key]['categories'] = $cate_data;
                    }
                }
                $this->load->vars(array(
                    'entry_setting' => $entry_setting
                ));
                $storage = $entry_setting->data['storage'];
                if(!empty($storage)){
                    if(!empty($data)) {
                        $this->load->vars(array(
                            'entry_detail' => (object)$data
                            ));
                        $output['data'] = $data;
                    }
                    $output['html'] = $this->load->view('dashboard/forms/subcommon_detail',null,true);
                } else {
                    $output['text'] = 'fail';
                    $output['code'] = -1;
                    $output['message'] = 'Storage does\'t exitst.';
                }
            } else {
                $output['text'] = 'fail';
                $output['code'] = -1;
                $output['message'] = 'Setting does\'t exitst.';
            }
        }
        ;
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($output));
    }

    function commit(){
        $id = $this->input->post('id');
        if(!empty($id)) $this->onupdate();
        else $this->oncreate();
    }

    function update(){
        $output = array(
            'text' => 'fail',
            'code' => -1,
            'data' => null
        );
        $id = $this->input->post('id');
        $sid = $this->input->post('sid');
        $data = $this->input->post('data');

        $entry_setting = $this->Module_Model->get($sid);
        if($entry_setting){
            $prefix =
            $this->prefix = $entry_setting->data['prefix'];
            $storage = $entry_setting->data['storage'];
            $this->Core_Model = new Core_Model($storage,$this->prefix);

            if($entry_setting->data['columns']) foreach ($entry_setting->data['columns'] as $key => $column) {
                if(!empty($column['server'])){
                    $field = 'data['.$column['name'].']';
                    if($column['columndata'] == '0'){
                        $field = 'data[data]['.$column['name'].']';
                    }
                    if($column['columndata'] == '1'){
                        $field = 'data[longdata]['.$column['name'].']';
                    }
                    $label = $column['title'];
                    $rule = $column['server'];
                    $this->form_validation->set_rules($field,$label,$rule);
                }
            }
            $this->form_validation->set_rules($this->rules['update']);
            if ($this->form_validation->run() == FALSE) {
                $output['validation'] = validation_errors_array();
                $output['message'] = validation_errors();
                $output['code'] = -1;
            } else {

                $params = $data;
                $entry_detail = $this->Core_Model->get($id);
                if($entry_detail){
                    if(empty($entry_detail->{$prefix.'author'})) 
                        $params[$prefix.'author'] = $user->ause_id;

                    if(!empty($params[$prefix.'data']) && isset($entry_detail->{$prefix.'data'})){
                        foreach ($params[$prefix.'data'] as $key => $value) {
                            $entry_detail->{$prefix.'data'}[$key] = $value;
                        }
                        $params[$prefix.'data'] = serialize($entry_detail->{$prefix.'data'});
                    }
                    if(!empty($params[$prefix.'longdata']) && isset($entry_detail->{$prefix.'longdata'})){
                        foreach ($params[$prefix.'longdata'] as $key => $value) {
                            $entry_detail->{$prefix.'longdata'}[$key] = $value;
                        }
                        $params[$prefix.'longdata'] = serialize($entry_detail->{$prefix.'longdata'});
                    }
                    if($entry_setting->data['columns']) foreach ($entry_setting->data['columns'] as $key => $column) {
                        if(
                            $column['type'] == 'server-dropdown' ||
                            $column['type'] == 'server-multidropdown'
                            ){
                            if(!empty($params[$column['name']]))
                                $params[$column['name']] = implode(',', $params[$column['name']]);
                            else
                                $params[$column['name']] = null;
                        }
                    }
                    $rs = $this->Core_Model->onUpdate($id, $params);
                    if ($rs === true) {
                        $output["code"] = 1;
                        $output["text"] = 'ok';
                        $output["message"] = 'Updated Entry to database.';
                    } else {
                        $output["code"] = -1;
                        $output["message"] = "Entry faily to update. Please check data input and try again.";
                    }
                }else{
                    $output["message"] = 'Entry doest exists.';
                }
            }
        } else {
            $output["message"] = 'Setting Entry doest exists.';
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($output));
    }

    public function onupdate(){
        $output = array(
            'text' => 'fail',
            'code' => -1,
            'data' => null
        );
        $user = $this->session->userdata('dasbboard_user');
        $id = $this->input->post('id');
        $sid = $this->input->post('sid');;
        $data = $this->input->post('data');

        $entry_setting = $this->Module_Model->get($sid);
        $this->entry_setting = $entry_setting;
        if($entry_setting){
            $prefix =
            $this->prefix = $entry_setting->data['prefix'];
            $storage = $entry_setting->data['storage'];
            $this->Core_Model = new Core_Model($storage,$this->prefix);
            if($entry_setting->data['columns']) foreach ($entry_setting->data['columns'] as $key => $column) {
                if(!empty($column['server'])){
                    $field = 'data['.$column['name'].']';
                    if($column['columndata'] == '0'){
                        $field = 'data[data]['.$column['name'].']';
                    }
                    if($column['columndata'] == '1'){
                        $field = 'data[longdata]['.$column['name'].']';
                    }
                    $label = $column['title'];
                    $rule = $column['server'];
                    $this->form_validation->set_rules($field,$label,$rule);
                }
            }
            $this->form_validation->set_rules($this->rules['update']);
            if($entry_setting->data['unique'] == 'true'){
                $this->form_validation->set_rules($this->rules['unique']);
            }
            if ($this->form_validation->run() == FALSE) {
                $output['validation'] = validation_errors_array();
                $output['message'] = validation_errors();
                // $output['code'] = -1;
            } else {

                $params = $data;
                
                
                $entry_detail = $this->Core_Model->get($id);
                if($entry_detail){
                    if(empty($entry_detail->{$prefix.'author'})) 
                        $params[$prefix.'author'] = $user->ause_id;

                    if(!empty($params[$prefix.'data']) && isset($entry_detail->{$prefix.'data'})){
                        foreach ($params[$prefix.'data'] as $key => $value) {
                            $entry_detail->{$prefix.'data'}[$key] = $value;
                        }
                        $params[$prefix.'data'] = serialize($entry_detail->{$prefix.'data'});
                    }
                    if(!empty($params[$prefix.'longdata']) && isset($entry_detail->{$prefix.'longdata'})){
                        foreach ($params[$prefix.'longdata'] as $key => $value) {
                            $entry_detail->{$prefix.'longdata'}[$key] = $value;
                        }
                        $params[$prefix.'longdata'] = serialize($entry_detail->{$prefix.'longdata'});
                    }
                    if($entry_setting->data['columns']) foreach ($entry_setting->data['columns'] as $key => $column) {
                        if(
                            // $column['type'] == 'server-dropdown' ||
                            $column['type'] == 'server-multidropdown'
                            ){
                            if(!empty($params[$column['name']]))
                                $params[$column['name']] = implode(',', $params[$column['name']]);
                            else
                                $params[$column['name']] = null;
                        }
                    }
                    $rs = $this->Core_Model->onUpdate($id, $params);
                    if ($rs === true) {
                        $output["code"] = 1;
                        $output["text"] = 'ok';
                        $output["message"] = 'Updated Entry to database.';
                    } else {
                        $output["code"] = -1;
                        $output["message"] = "Entry faily to update. Please check data input and try again.";
                    }
                }else{
                    $output["message"] = 'Entry doest exists.';
                }
            }
        } else {
            $output["message"] = 'Setting Entry doest exists.';
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($output));
    }

    public function oncreate(){
        $output = array(
            'text' => 'fail',
            'code' => -1,
            'data' => null
        );
        $user = $this->session->userdata('dasbboard_user');
        $sid = $this->input->post('sid');
        $data = $this->input->post('data');

        $entry_setting = $this->Module_Model->get($sid);
        $this->entry_setting = $entry_setting;
        if($entry_setting){
            $prefix =
            $this->prefix = $entry_setting->data['prefix'];
            $storage = $entry_setting->data['storage'];
            $this->Core_Model = new Core_Model($storage,$this->prefix);
            if($entry_setting->data['columns']) foreach ($entry_setting->data['columns'] as $key => $column) {
                if(!empty($column['server'])){
                    $field = 'data['.$column['name'].']';
                    if($column['columndata'] == '0'){
                        $field = 'data[data]['.$column['name'].']';
                    }
                    if($column['columndata'] == '1'){
                        $field = 'data[longdata]['.$column['name'].']';
                    }
                    $label = $column['title'];
                    $rule = $column['server'];
                    $this->form_validation->set_rules($field,$label,$rule);
                }
            }
            $this->form_validation->set_rules($this->rules['insert']);
            if($entry_setting->data['unique'] == 'true'){
                $this->form_validation->set_rules($this->rules['unique']);
            }
            if ($this->form_validation->run() == FALSE) {
                $output['validation'] = validation_errors_array();
                $output['message'] = validation_errors();
                // $output['code'] = -1;
            } else {

                $params = $data;
                
                // if(isset($category)) $params['category'] = $category;
                // if(isset($title)) $params['title'] = $title;
                // if(isset($alias)) $params['alias'] = $alias;
                // if(isset($type)) $params['type'] = $type;
                if(!empty($params[$prefix.'data'])) $params[$prefix.'data'] = serialize($params[$prefix.'data']);
                if(!empty($params[$prefix.'longdata'])) $params[$prefix.'longdata'] = serialize($params[$prefix.'longdata']);
                // if(isset($longdata)) $params['longdata'] = serialize($longdata);
                $params[$this->prefix.'status'] = true;
                $params[$this->prefix.'author'] = $user->ause_id;
                if($entry_setting->data['columns']) foreach ($entry_setting->data['columns'] as $key => $column) {
                    if(
                        // $column['type'] == 'server-dropdown' ||
                        $column['type'] == 'server-multidropdown'
                        ){
                        if(!empty($params[$column['name']]))
                            $params[$column['name']] = implode(',', $params[$column['name']]);
                        else
                            $params[$column['name']] = null;
                    }
                }
                
                $rs = $this->Core_Model->onInsert($params);
                if ($rs === true) {
                    $output["code"] = 1;
                    $output["text"] = 'ok';
                    $output["message"] = 'Register record to database.';
                } else {
                    $output["code"] = -1;
                    $output["message"] = "Record faily to insert. Please check data input and try again.";
                }
            }
        } else {
            $output["message"] = 'Setting Entry doest exists.';
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($output));
    }

    public function sendlatest(){
        $output = array(
            'text' => 'fail',
            'code' => -1,
            'data' => null
        );
        $id = $this->input->post('id');
        $sid = $this->input->post('sid');
        $entry_setting = $this->Module_Model->get($sid);
        $this->entry_setting = $entry_setting;
        if($entry_setting){
            $storage = $entry_setting->data['storage'];
            $this->Core_Model = new Core_Model($storage);
            
            $entry_detail = $this->Core_Model->get($id);
            if($entry_detail){
                
                $rs = $this->Core_Model->onSendLatest($id);
                if ($rs === true) {
                    $output["code"] = 1;
                    $output["text"] = 'ok';
                    $output["message"] = 'Success.';
                } else {
                    $output["code"] = -1;
                    $output["message"] = "Entry faily to update. Please check data input and try again.";
                }
            }else{
                $output["message"] = 'Entry doest exists.';
            }
        } else {
            $output["message"] = 'Setting Entry doest exists.';
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($output));
    }

    public function sendoldest(){
        $output = array(
            'text' => 'fail',
            'code' => -1,
            'data' => null
        );
        $id = $this->input->post('id');
        $sid = $this->input->post('sid');
        $entry_setting = $this->Module_Model->get($sid);
        $this->entry_setting = $entry_setting;
        if($entry_setting){
            $storage = $entry_setting->data['storage'];
            $this->Core_Model = new Core_Model($storage);
            
            $entry_detail = $this->Core_Model->get($id);
            if($entry_detail){
                
                $rs = $this->Core_Model->onSendOldest($id);
                if ($rs === true) {
                    $output["code"] = 1;
                    $output["text"] = 'ok';
                    $output["message"] = 'Success.';
                } else {
                    $output["code"] = -1;
                    $output["message"] = "Entry faily to update. Please check data input and try again.";
                }
            }else{
                $output["message"] = 'Entry doest exists.';
            }
        } else {
            $output["message"] = 'Setting Entry doest exists.';
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($output));
    }
    public function delete(){
        $output = array(
            'text' => 'fail',
            'code' => -1,
            'data' => null
        );
        $id = $this->input->post('id');
        $sid = $this->input->post('sid');
        $entry_setting = $this->Module_Model->get($sid);
        $this->entry_setting = $entry_setting;
        if($entry_setting){
            $storage = $entry_setting->data['storage'];
            $this->Core_Model = new Core_Model($storage);
            
            $entry_detail = $this->Core_Model->get($id);
            if($entry_detail){
                
                $rs = $this->Core_Model->onDelete($id);
                if ($rs === true) {
                    $output["code"] = 1;
                    $output["text"] = 'ok';
                    $output["message"] = 'Deleted record on database.';
                } else {
                    $output["code"] = -1;
                    $output["message"] = "Record faily to delete. Please check data input and try again.";
                }
            }else{
                $output["message"] = 'Record doest exists.';
            }
        } else {
            $output["message"] = 'Setting Entry doest exists.';
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($output));
    }

    public function custom_bind(){

        $output = array(
            'text' => 'fail',
            'code' => -1,
            'data' => null
        );
        $sid = $this->input->get_post('sid');
        if(!empty($sid)) {
            $entry_setting = $this->Module_Model->get($sid);
            if($entry_setting){
                $this->_table = $entry_setting->data['storage'];
                $this->prefix = $entry_setting->data['prefix'];
                $type = $entry_setting->data['type'];
                $custom_select = $entry_setting->data['bind_select'];
                if(empty($custom_select)) $custom_select = "*";
                $custom_from = $entry_setting->data['bind_from'];
                if(empty($custom_from)) $custom_from = "{$this->_table}";
                $custom_columns_map = json_decode($entry_setting->data['bind_columns_map'],true);
                if(empty($custom_columns_map)) $custom_columns_map = array();
                $custom_order_by = $entry_setting->data['bind_order_by'];
                if(empty($custom_order_by)) $custom_order_by = "{$this->_table}.{$this->prefix}id DESC";

                $custom_where = $entry_setting->data['bind_where'];
                if(!empty($type)) $custom_where .= " AND {$this->_table}.`{$this->prefix}type` = '$type'";

                $this->Core_Model = new Core_Model($this->_table,$this->prefix);
                $this->Core_Model->table_config = array(
                    "table"     => "{$this->_table}",
                    "select"    => "
                        SELECT SQL_CALC_FOUND_ROWS $custom_select",
                    "from"      => "
                        FROM $custom_from
                    ",
                    "where"     => "
                        WHERE $custom_where
                    ",
                    "order_by"  => "
                        ORDER BY $custom_order_by
                    ",
                    "columnmaps"=> $custom_columns_map,
                    "filterfields"=>array(
                    ),
                );
                // echo '<pre>';
                // print_r($custom_columns_map);
                // // print_r($entry_setting);
                // print_r($this->Core_Model->table_config);die;
                $output = $this->Core_Model->jqxBinding();
            } else {
                $output['message'] = 'Setting does\'t exitst.';
            }
        }
        $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($output));
    }
}
