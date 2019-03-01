<?php

class MY_Controller extends CI_Controller 
{
    protected $data = null;
    
    protected $my_id = null;
    protected $super_user = null;
    protected $super_role = null;
    
    function __construct() 
    {
        parent::__construct();

        if (!$this->login_model->checkSession()) {
             redirect('admin/user');
        }
        
        $siteName=  $this->general_model->fetchValue(TBL_SYSTEMCONFIG,"systemVal","systemName = 'ADMIN_SITE_NAME'");
        define('SITENAME', $siteName);
        
        $this->super_user = 1;
        $this->super_role = 1;
        $this->my_role    = role_id();
        $this->my_id      = $this->session->userdata(SITE_SESSION_NAME . 'ADMINID');
    }
    

    protected function render($page) 
    {

        $this->load->model('admin/administrator_model');
        $this->data['user_data'] = $this->administrator_model->get_user();
        
        $this->load->view('admin/maintemplate/header', $this->data);
        $this->load->view('admin/'.$page, $this->data);
        $this->load->view('admin/maintemplate/footer', $this->data);
    }

}

//End of class
?>
