<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Class to manage admin dashboard.
 * 
 * @author Sean Rock <sean@sparxitsolutions.com>
 * @version 1.0
 * @dated 01/02/2017
 */
class Adminarea extends MY_Controller
{
    
    function __construct() 
    {
        parent::__construct();
        
        $this->load->model('admin/dashboard_model');        
        $this->load->model('admin/administrator_model');        
        $this->load->model('admin/vehicle_model');        
        $this->load->model('admin/service_model');        
        $this->load->model('admin/project_model'); 
        $this->load->model('admin/reports_model');      
        $this->load->library('parser');  

    }
    
    
    
    /**
     * Display Dashboard 
     * 
     * @return void
     */
    public function index() 
    {
        
        $this->data['count']=$this->dashboard_model->count_total_details();



            switch ($this->input->post('report_type')) {

                case 'project'  : $this->data['reports'] = $this->reports_model->project_report();  break;
                case 'employee' : $this->data['reports'] = $this->reports_model->employee_report(); break;
                case 'vehicle'  : $this->data['reports'] = $this->reports_model->vehicle_report();  break;
                default         : $this->data['reports'] = $this->reports_model->project_report();
            }

            if($this->input->post('report_type')) {

                $this->data['report_type'] = $this->input->post('report_type');
            }

            if($this->input->post('report_date')) {

                $this->data['report_date'] = $this->input->post('report_date');
            }

            if($this->input->post('export_export') && ($this->input->post('export_export') == 'excel')) {

                $this->export_xls('daily_report');
            }

        
          $this->render('dashboard/dashboard');

        
    
    }


    private function export_xls($template_name)
    {   



        $xls_html = $this->parser->parse('admin/reports/'.$template_name.'_xls', $this->data, true);

        

        write_file('uploads/'.$template_name.'.xls', $xls_html);

        $this->load->helper('download');

        $data = file_get_contents('uploads/'.$template_name.'.xls');

        $name = 'report.xls';

        force_download($name, $data);
    }
    
    
    /**
     * Display admin profile page
     * 
     * @return void
     */
    public function profile() 
    {
        $this->data['active_tab'] = 1;
        
        if($this->input->post()) {
            
            if($this->input->post('btn_info') == 'Save Changes') {
                 
                $this->data['active_tab'] = 1;
                
                $this->validate_personal_info();

            }
            else if($this->input->post('btn_avatar') == 'Submit') {
                
                $this->data['active_tab'] = 2;
                $upload = $this->do_upload();
                $this->administrator_model->update_avatar($upload['file_name']);
               redirect('admin/adminarea/profile');
            }
            else if($this->input->post('btn_password') == 'Change Password') {

                $this->data['active_tab'] = 3;
                $this->validate_password_form();
            }


        }


        $this->data['total_vehicles'] = $this->db->where('added_by', $this->my_id)->count_all_results(TBL_VEHICLE);
        $this->data['total_services'] = $this->db->where('added_by', $this->my_id)->count_all_results(TBL_SERVICE);
        $this->data['total_projects'] = $this->db->where('added_by', $this->my_id)->count_all_results(TBL_PROJECT);
        
        $this->render('dashboard/my_profile');
        
    }
    
    
    /**
     * Validate profile personal information form
     * 
     * @return bool
     */
    private function validate_personal_info()
    {
            $admin_id = $this->session->userdata(SITE_SESSION_NAME . 'ADMINID');                
        
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');            
            $this->form_validation->set_rules('first_name', 'first name', 'required|min_length[2]|max_length[50]');
            $this->form_validation->set_rules('last_name', 'last name', 'required|min_length[2]|max_length[50]');
            $this->form_validation->set_rules('mobile', 'mobile no.', 'max_length[50]');
            $this->form_validation->set_rules('email_id', 'email address', 'required|valid_email|callback_edit_unique[adminlogin.emailId.'.$admin_id.']');
            $this->form_validation->set_rules('about', 'about', 'max_length[500]');

            if ($this->form_validation->run() === TRUE) {
                
                $this->administrator_model->update_profile();
            }
    }
    
    
    
    /**
     * Validation rule to check unique email address. ( Edit case )
     * 
     * @return bool
     */
    public function edit_unique($value, $params)
    {
        $this->form_validation->set_message('edit_unique', 'The %s "'.$value.'" is already being used by another account.');
        
        list($table, $field, $id) = explode(".", $params, 3);

        $query = $this->db->select($field)->from($table)->where($field, $value)->where('id !=', $id)->limit(1)->get();
        
        if ($query->row()) {
            return false;
        } 
        else {
            return true;
        }
    }
    
    
    
    /**
     * Validate update password form
     * 
     * @return bool
     */
    private function validate_password_form()
    {
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('current_password', 'password', 'required|min_length[8]|max_length[30]|callback_validate_password');
        $this->form_validation->set_rules('new_password', 'New Password', 'required|min_length[8]|max_length[30]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|min_length[8]|max_length[30]|matches[new_password]');

        if ($this->form_validation->run() === TRUE) {
            
            $this->administrator_model->update_password();
        }
    }
    
    
    /**
     * Validation old password before changing new password.
     * 
     * @return bool
     */
    public function validate_password($value)
    {
        $this->form_validation->set_message('validate_password', 'Please enter correct password.');

        $admin_id = $this->session->userdata(SITE_SESSION_NAME . 'ADMINID');                
        $user     = $this->administrator_model->get_user($admin_id);
        $flag     = $this->login_model->validate_password($value, $user->password);
        
        return ($flag == true);
    }
    
    
    
    /**
     * Upload profile image
     * 
     * @return array
     */
    private function do_upload()
    {
            $config['upload_path']      = 'uploads/users/';
            $config['allowed_types']    = 'gif|jpg|png|jpeg';
            $config['file_name']        = 'avatar_'.  md5(time());
            $config['overwrite']        = TRUE;
            $config['max_size']         = 100;
            $config['max_width']        = 1024;
            $config['max_height']       = 1024;

            $this->load->library('upload', $config);

            if ( ! $this->upload->do_upload('image_file'))
            {
                    pr($this->upload->display_errors());
            }
            else
            {
                $upload_data = $this->upload->data();
                
                $this->image_crop($upload_data, 100, 100);
                
                $this->resize_image($upload_data, 30, 30);
                
                return $upload_data;
            }
    }
    
    
    /**
     * Crop profile image
     * 
     * @return mix
     */
    private function image_crop($upload_data, $targ_w, $targ_h)
    {
            $img_config = array(
                'source_image'      => $upload_data['full_path'],
                'new_image'         => $upload_data['file_path'].''.$upload_data['file_name'],
                'maintain_ratio'    => false,
                'width'             => $targ_w,
                'height'            => $targ_h
            );
            
            $img_config['image_library'] = 'ImageMagick';
            $img_config['library_path'] = '/usr/bin/';
            
            //Set cropping for y or x axis, depending on image orientation
            if ($upload_data['image_width'] > $upload_data['image_height']) {
                $img_config['width']  = $upload_data['image_height'];
                $img_config['height'] = $upload_data['image_height'];
                $img_config['x_axis'] = (($upload_data['image_width'] / 2) - ($img_config['width'] / 2));
            }
            else {
                $img_config['height'] = $upload_data['image_width'];
                $img_config['width']  = $upload_data['image_width'];
                $img_config['y_axis'] = (($upload_data['image_height'] / 2) - ($img_config['height'] / 2));
            }

            $this->load->library('image_lib', $img_config);
            
            if ($this->image_lib->crop()) {
                echo json_encode($this->image_lib->display_errors());
            }
    }
    
    
    
    /**
     * Resize profile image
     * 
     * @return mix
     */
    private function resize_image($upload_data, $w, $h)
    {
            $img_config                     = array();
            $img_config['image_library']    = 'gd2';
            $img_config['source_image']     = $upload_data['file_path'].'crop_'.$upload_data['file_name'];
            $img_config['new_image']        = $upload_data['file_path'].$w.'x'.$h.'_'.$upload_data['file_name'];
            $img_config['create_thumb']     = TRUE;
            $img_config['maintain_ratio']   = TRUE;
            $img_config['width']            = $w;
            $img_config['height']           = $h;

            $this->load->library('image_lib', $img_config);

            $this->image_lib->resize();
    }



    
}
