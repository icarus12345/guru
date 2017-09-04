<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Project extends Api_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model("api/Project_Model");
        $this->load->model("api/Answer_Model");
        $this->load->model("api/Share_Model");
        $this->load->library('CI_Phpmailer');
        // $this->load->model("api/Account_Model");
    }

    public $rules = array(
        'create' => array(
                'title' => array(
                    'field'=>'title',
                    'label'=>'Project Name',
                    'rules'=>'trim|required|min_length[2]|max_length[50]'
                    ),
                'desc' => array(
                    'field'=>'desc',
                    'label'=>'Description',
                    'rules'=>'trim|max_length[255]'
                    ),
        ),
        'update' => array(
                'title' => array(
                    'field'=>'title',
                    'label'=>'Project Name',
                    'rules'=>'trim|required|min_length[2]|max_length[50]'
                    ),
                'desc' => array(
                    'field'=>'desc',
                    'label'=>'Description',
                    'rules'=>'trim|max_length[255]'
                    ),
        ),
        'share' => array(
                'pid' => array(
                    'field'=>'pid',
                    'label'=>'Project',
                    'rules'=>'trim|required'
                    ),
                'email' => array(
                    'field'=>'email',
                    'label'=>'Email',
                    'rules'=>'trim|valid_email|required'
                    ),
                'mode' => array(
                    'field'=>'mode',
                    'label'=>'Mode',
                    'rules'=>'trim|required'
                    ),
        ),
        'send' => array(
                'pid' => array(
                    'field'=>'pid',
                    'label'=>'Project',
                    'rules'=>'trim|required'
                    ),
                'email' => array(
                    'field'=>'email',
                    'label'=>'Email',
                    'rules'=>'trim|valid_email|required'
                    ),
                'pdf' => array(
                    'field'=>'pdf',
                    'label'=>'PDF File',
                    'rules'=>'trim|required'
                    ),
        )
    );

    function index(){
        echo 'Welcome API';
    }

    function gets(){
        $code = 200;
        $output = array(
            'text' => 'fail',
            'message' => 'Bad request.',
            'code' => -1,
        );
        $perpage = 20;
        $page = $this->input->post('page');
        $perpage = $this->input->post('perpage');
        if($page<=0) $page = 1;
        if($perpage<=0) $perpage = 100;
        // $this->form_validation->set_rules($this->rules['get_list']);
        // if ($this->form_validation->run() == FALSE) {
        //     $output['text'] = 'Fail.';
        //     $output['validation'] = validation_errors_array();
        //     $output['message'] = validation_errors();
        // } else {
            $rs = $this->Project_Model->get_all_by_uid($this->user->id,$page,$perpage);

            $output['code'] = 1;
            $output['text'] = 'Success.';
            $output['message'] = 'Get list project success.';
            $output['data'] = $rs;
        // }
        $this->output
            ->set_content_type('application/json')
            ->set_status_header($code)
            ->set_output(json_encode($output,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    function create(){
        $code = 200;
        $output = array(
            'text' => 'fail',
            'message' => 'Bad request.',
            'code' => -1,
        );
        $title = $this->input->post('title');
        $desc = $this->input->post('desc');
        $id = $this->input->post('id');
        $this->form_validation->set_rules($this->rules['create']);
        if ($this->form_validation->run() == FALSE) {
            $output['text'] = 'Fail.';
            $output['validation'] = validation_errors_array();
            $output['message'] = validation_errors();
        } else {
            $project = $this->Project_Model->get($id);
            if($project){
                $params = array(
                    'title' => $title,
                    'data'=>array('desc' => $desc)
                    );
                $rs = $this->Project_Model->update($id, $params);
                if($rs){
                    $output['message'] = 'Update project success.';
                } else {
                    $output['message'] = 'Can\'t update project.';
                }
            }else{
                $params = array(
                    'title' => $title,
                    'data'=>array('desc' => $desc),
                    'status' => 1,
                    'uid' => $this->user->id
                    );
                $rs = $this->Project_Model->insert($params);
                if($rs){
                    $output['message'] = 'Created project success.';
                } else {
                    $output['message'] = 'Can\'t create project.';
                }
            }
            
            if($rs){
                $code = 200;
                $output['code'] = 1;
                $output['text'] = 'Success.';
                $insert_id = $this->db->insert_id();
                $project = $this->Project_Model->get($insert_id);
                $output['data'] = $project;
            } else {
                $code = 200;
                $output['text'] = 'Fail.';
            }
        }
        $this->output
            ->set_content_type('application/json')
            ->set_status_header($code)
            ->set_output(json_encode($output,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    function get(){
        $code = 200;
        $output = array(
            'text' => 'fail',
            'message' => 'Bad request.',
            'code' => -1,
        );
        $id = $this->input->post('id');
        $uid = $this->user->id;
        // $this->form_validation->set_rules($this->rules['get_list']);
        // if ($this->form_validation->run() == FALSE) {
        //     $output['text'] = 'Fail.';
        //     $output['validation'] = validation_errors_array();
        //     $output['message'] = validation_errors();
        // } else {
            $project = $this->Project_Model->get($id);
            if($project){
                $project->alias = convertUrl($project->title);
                $answereds[] = $this->Answer_Model->get_by_uid_pid($uid,$id);
                $this->db->where('email !=',$this->user->email);
                $shareds = $this->Share_Model->get_by_pid($id);
                foreach ($shareds as $key => $value) {
                    $u = $this->Account_Model->get_by_email($value->email);
                    $answereds[] = $this->Answer_Model->get_by_uid_pid($u->id,$id);
                }

                if($project->uid == $uid){
                    $project->mode = 2;
                } else {
                    $mode = $this->Share_Model->get_by_pid_email($id,$this->user->email);
                    if($mode){
                        $project->mode = $mode->mode;
                    } else {
                        $project->mode = 0;
                    }
                }

                $output['code'] = 1;
                $output['text'] = 'Success.';
                $output['message'] = 'Get project success.';
                $output['data'] = array(
                    'info' => $project,
                    'answereds' => $answereds,
                    'shareds' => $shareds,
                    );
            } else {
                $output['message'] = 'Project does\'t not exists.';
            }
        // }
        $this->output
            ->set_content_type('application/json')
            ->set_status_header($code)
            ->set_output(json_encode($output,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
    function _export(){

    }
    function export(){
        $this->load->library('mpdf/mpdf');
        
        $id = $this->input->post('id');
        $project = $this->Project_Model->get($id);
        $uid = $this->user->id;
        if($project){
            $shareds = $this->Share_Model->get_by_pid($id);
            $items = $this->input->post('items');
            $info = $this->input->post('info');
            $setting = $this->input->post('setting');
            $show_global = $this->input->post('show_global');
            $this->load->vars(array(
                'items' => $items,
                'info' => $info,
                'shareds' => $shareds,
                'user' => $this->user,
                'setting' => $setting,
                'show_global' => (bool)$show_global,
            ));
            $html = $this->load->view('risk/pdf',null,true);

            $file_name = convertUrl($project->title)."-{$uid}";
            if($setting['all_user']){
                $file_name.="-user";
            }
            if($setting['all_comment']){
                $file_name.="-comment";
            }
            $mpdf = new Mpdf('c','A4','','' , 0 , 0 , 0 , 0 , 0 , 0); 
            $mpdf->SetDisplayMode('fullpage');
            // $mpdf->SetHTMLFooter('<div class="footer">{PAGENO}/{nbpg}</div>');
            $mpdf->list_indent_first_level = 0;  // 1 or 0 - whether to indent the first level of a list
            $mpdf->WriteHTML($html);
            $pdffile = APPPATH . "../pdf/{$file_name}.pdf";
            $mpdf->Output($pdffile,'F');
            $this->_output['text'] = 'Success.';
            $this->_output['code'] = 1;
            $this->_output['message'] = 'Export success.';
            $this->_output['data'] = array(
                'url' => base_url("pdf/{$file_name}.pdf"),
                'file_name' => "{$file_name}.pdf",
                );
        } else {
            $this->_output['message'] = 'Project doest exits.';
        }
        $this->_code = 200;
        $this->display();
    }
    function share(){
        $email = $this->input->post('email');
        $mode = $this->input->post('mode');
        $pid = $this->input->post('pid');
        $this->form_validation->set_rules($this->rules['share']);
        if ($this->form_validation->run() == FALSE) {
            $this->_output['text'] = 'Fail.';
            $this->_output['validation'] = validation_errors_array();
            $this->_output['message'] = validation_errors('','');
        } else {
            $shareds = $this->Share_Model->get_by_pid($pid);
            if(count($shareds) < 5){
                $project = $this->Project_Model->get($pid);
                $user = $this->Account_Model->get_by_email($email);
                if($project){
                    $this->load->vars(array(
                        'project' => $project,
                        'mode' => $mode
                    ));
                    if(!$user || $user->id != $project->uid){
                        $shared = $this->Share_Model->get_by_pid_email($pid,$email);
                        if($shared){
                            $params = array(
                                'pid' => $pid,
                                'email' => $email,
                                'mode' => $mode,
                                );
                            $rs = $this->Share_Model->update($shared->id,$params);
                        }else{
                            $params = array(
                                'pid' => $pid,
                                'email' => $email,
                                'mode' => $mode,
                                );
                            $rs = $this->Share_Model->insert($params);
                        }
                        if($rs){
                            $this->_output['code'] = 1;
                            $this->_output['text'] = 'Success.';
                            $this->_output['message'] = 'Share success.';
                            $shareds = $this->Share_Model->get_by_pid($pid);
                            $this->_output['data'] = $shareds;
                            // $output['data'] = $rs;
                            $to = $email;
                            $subject = "I want to share project \"{$project->title}\" with you!";
                            $message = $this->load->view('risk/share_mail_body',null,true);
                            $this->ci_phpmailer->send_mail($to,$subject, $message);
                        } else {
                            $this->_output['text'] = 'fail';
                            $this->_output['message'] = 'Cant share.';
                        }
                    }else{
                        $this->_output['message'] = 'This is your project.';
                        $this->_output['data'] = array($project,$user
                            );
                    }
                }else{
                    $this->_output['message'] = 'Project not found.';
                }
            }else{
                $this->_output['message'] = 'Maximum sharing.';
            }
        }
        $this->_code = 200;
        $this->display();
    }
    function unshare(){
        $id = $this->input->post('id');
        $this->form_validation->set_rules($this->rules['share']);
        $email = $this->input->post('email');
        $pid = $this->input->post('pid');
        if ($this->form_validation->run() == FALSE) {
            $this->_output['text'] = 'Fail.';
            $this->_output['validation'] = validation_errors_array();
            $this->_output['message'] = validation_errors('','');
        } else {
            $shared = $this->Share_Model->get_by_pid_email($pid,$email);
            $user = $this->Account_Model->get_by_email($email);
            if($shared){
                $this->db->trans_begin();
                if($user) $this->Answer_Model->delete_by_uid_pid($user->id,$pid);
                $this->Share_Model->delete($shared->id);
                if($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                    $this->_output['text'] = 'fail';
                    $this->_output['message'] = 'Cant share.';
                } else {
                    $this->_output['code'] = 1;
                    $this->_output['text'] = 'Success.';
                    $this->_output['message'] = 'Share success.';
                    $shareds = $this->Share_Model->get_by_pid($pid);
                    $this->_output['data'] = $shareds;
                    $this->db->trans_commit();
                }
            }else{
                $this->_output['message'] = 'People not found.';
            }
        }
        $this->_code = 200;
        $this->display();
    }
    function delete(){
        $pid = $this->input->post('id');
        
        $project = $this->Project_Model->get($pid);
        if($project){
            $this->db->trans_begin();
            $this->Answer_Model->delete_by_pid($pid);
            $this->Share_Model->delete_by_pid($pid);
            $this->Project_Model->delete($pid);
            if($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $this->_output['text'] = 'fail';
                $this->_output['message'] = 'Cant delete.';
            } else {
                $this->_output['code'] = 1;
                $this->_output['text'] = 'Success.';
                $this->_output['message'] = 'Deleted prject success.';
                $this->db->trans_commit();
            }
        }else{
            $this->_output['message'] = 'Project not found.';
        }
        
        $this->_code = 200;
        $this->display();
    }
    function send(){
        $email = $this->input->post('email');
        $pdf = $this->input->post('pdf');
        $pid = $this->input->post('pid');
        $this->form_validation->set_rules($this->rules['send']);
        if ($this->form_validation->run() == FALSE) {
            $this->_output['text'] = 'Fail.';
            $this->_output['validation'] = validation_errors_array();
            $this->_output['message'] = validation_errors('','');
        } else {
            $project = $this->Project_Model->get($pid);
            if($project){
                $this->load->vars(array(
                    'project' => $project,
                    'pdf' => $pdf
                ));
                
                $this->_output['code'] = 1;
                $this->_output['text'] = 'Success.';
                $this->_output['message'] = 'Send success.';
                // $output['data'] = $rs;
                $to = $email;
                $subject = "Report project \"{$project->title}\" !";;
                $message = $this->load->view('risk/send_mail_body',null,true);
                $this->ci_phpmailer->AddAttachment(APPPATH . '../' . $pdf);
                $this->ci_phpmailer->send_mail($to,$subject, $message);
                    
            }else{
                $this->_output['message'] = 'Project not found.';
            }
        }
        $this->_code = 200;
        $this->display();
    }
    function debug(){
        $this->ci_phpmailer->send_mail('khuongxuantruong@gmail.com','blabla', 'blabla');
    }
}