<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends CI_Controller {

    function __construct() {
        parent::__construct();
        $siteName=  $this->general_model->fetchValue(TBL_SYSTEMCONFIG,"systemVal","systemName = 'ADMIN_SITE_NAME'");
        define('SITENAME',$siteName);
    }

    /*************** Start function index() to load login page ***************/
    
    public function index($post = 0) {
        $result = $this->login_model->checkSession();
        if ($result) {
            redirect('admin/adminarea');
        } else {
            $value = array();
            $value['userName'] = "";
            $value['userName'] = @$post['userName'];
            $this->load->view('admin/welcome', $value);
        }
    }

    /*************** End function index() ***************/
    
    /*************** Start function login() for user login ***************/
    
    public function login() {
        //pr($_POST);die;
        $this->form_validation->set_rules('username', 'login ID', 'trim|required|min_length[4]|max_length[20]|xss_clean');
        $this->form_validation->set_rules('password', 'password', 'trim|required|min_length[4]|max_length[20]|xss_clean');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        if ($this->form_validation->run() == TRUE) {
            $result = $this->login_model->adminLogin($_POST);
            if ($result) {
                if ($this->session->userdata('RETURN_URL')) {
                    redirect($this->session->userdata('RETURN_URL'));
                } else {
                    redirect('admin/adminarea');
                }
            } else {
                redirect('admin/user');
            }
        } else {
            $this->index($_POST);
        }
    }

    /*************** End function login() ***************/
    
    /*************** Start function lostpassword() to load lost password view page ***************/
    
    public function lostpassword() {
        
        if($this->input->post()){
            $this->form_validation->set_rules('email', 'Email Id', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
            if ($this->form_validation->run() == TRUE) {
                if ($this->login_model->isExistsAdminEmailId($_POST['email']) && $this->login_model->adminResendPassword($_POST)){
                    $this->session->set_flashdata('success', 'Your Password has been send successfully to your email Id.');
                 }else{
                    $this->session->set_flashdata('errordata', 'Email Id does not exist!');
                 }
               redirect('admin/user');
            }
        }
        $this->load->view('admin/user', $value=array());
    }

    /*************** End function lostpassword() ***************/
    
    /*************** Start function adminresendpassword() to send admin password ***************/
    
    public function adminresendpassword() {
        $this->form_validation->set_rules('email', 'Email Id', 'trim|required|valid_email|xss_clean');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        if ($this->form_validation->run() == TRUE) {

            $result = $this->login_model->isExistsAdminEmailId($_POST['email']);
            if ($result) {
                $reset = $this->login_model->adminResendPassword($_POST);
                if ($reset)
                    redirect('user/lostpassword');
            }
            else {
                redirect('user/lostpassword');
            }
        } else {
            $this->lostpassword();
        }
    }

    /*************** End function adminresendpassword() ***************/
    
    /*************** Start function logout() to logout from admin ***************/
    
    public function logout() {
        $items = array(
              SITE_SESSION_NAME.'ADMINID'             =>  '',
              SITE_SESSION_NAME.'ADMINNNAME'          =>  '',
              SITE_SESSION_NAME.'USERIMAGE'           =>  '',
              SITE_SESSION_NAME.'ADMINTBL_USERHASH'   =>  '',
              SITE_SESSION_NAME.'USERLEVELID'         =>  '',
              SITE_SESSION_NAME.'DEFAULTLANGUAGEID'   =>  '',
              SITE_SESSION_NAME.'DEFAULTLANGUAGE'     =>  '',
              SITE_SESSION_NAME.'PHPSESSIONID'        =>  '',
              SITE_SESSION_NAME.'ADMIN_LAST_LOGIN'    =>  ''                        
            );
        $this->session->unset_userdata($items);
        redirect('admin/user');
    }
    /*************** End function logout() ***************/    
    public function test(){
        $this->load->view("maintemplate/header");
        $this->load->view("test");
        $this->load->view("maintemplate/footer");
    }
}