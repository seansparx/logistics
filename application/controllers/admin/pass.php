<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pass extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('menu_model');
        $this->load->model('login_model');
        $siteName=  $this->general_model->fetchValue(TBL_SYSTEMCONFIG,"systemVal","systemName = 'ADMIN_SITE_NAME'");
        define('SITENAME',$siteName);
    }

    public function index() {
        $data = $this->input->get();
        $type = $data['type'];
        $action = $data['action'];
         //////////////////////////////Test status change /////////////////////
        if ($action == 'test'):
            $this->load->model('admin/test_model');
            if ($type == 'changestatus'):
                $result = $this->test_model->changStatus($data);
                echo $result;
            endif;
        endif;
        
        //////////////////////////////Menu  /////////////////////
        if ($action == 'menu'):
            if ($type == 'changestatus'):
                $result = $this->menu_model->changStatus($data);
                echo $result;
            endif;
            if ($type == 'deleteAllRecord'):
                $result = $this->menu_model->deleteAllRecord();
                echo $result;
            endif;
            if ($type == 'restoreAll'):
                $result = $this->menu_model->restoreAllRecord();
                echo $result;
            endif;
        endif;

        
        
        ////////Searchability///////
        
        
    }

}
