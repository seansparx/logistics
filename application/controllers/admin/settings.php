<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Class to manage settings
 * 
 * @author Sean Rock <sean@sparxitsolutions.com>
 * @version 1.0
 * @dated 30/01/2017
 */
class Settings extends MY_Controller 
{
    
    function __construct() 
    {
        parent::__construct();
        
        has_access('settings');
        
        $this->load->model('admin/administrator_model');
        $this->load->model('admin/role_model');
    }
    
    
    
    /**
     * Display user listing page
     * 
     * @return void
     */
    public function manage_users() 
    {
        if(has_access('manage_administrators')){
            
            $this->render('settings/users/manage');
        }
    }
    
    
    
    /**
     * Display role listing page
     * 
     * @return void
     */
    public function manage_role() 
    {
        if(has_access('manage_roles')){
            
            $this->render('settings/roles/manage');
        }
    }
    
    
    
    /**
     * Add users
     * 
     * @return void
     */
    public function add_user() 
    {
        has_access('manage_administrators');
        
        if($this->input->post()) {

            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
            $this->form_validation->set_rules('role', 'role', 'required');
            $this->form_validation->set_rules('first_name', 'first name', 'required|min_length[2]|max_length[50]');
            $this->form_validation->set_rules('last_name', 'last name', 'required|min_length[2]|max_length[50]');
            $this->form_validation->set_rules('username', 'userame', 'required|min_length[5]|max_length[15]|is_unique[adminlogin.username]');
            $this->form_validation->set_rules('email_id', 'email address', 'required|valid_email|is_unique[adminlogin.emailId]');
            $this->form_validation->set_rules('new_password', 'password', 'required|min_length[8]|max_length[30]');
            $this->form_validation->set_rules('confirm_password', 'confirm password', 'required|min_length[8]|max_length[30]|matches[new_password]');
            $this->form_validation->set_rules('status', 'status', 'required');
            
            if ($this->form_validation->run() === TRUE) {
                
                $this->administrator_model->addrecords();
            }
        }
        
        $this->data['roles_options'] = $this->create_role_options();
        
        $this->render('settings/users/add');
   }
   
   
   
    /**
     * Edit users
     * 
     * @return void
     */
    public function edit_user($id) 
    {
         has_access('manage_administrators');

         if(in_array($id, array($this->super_user, $this->my_id))) {
             show_error("You are not allowed to edit this user.", 403, "Access Denied");
         }

         if($this->input->post()) {

             $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
             $this->form_validation->set_rules('role', 'role', 'required');
             $this->form_validation->set_rules('first_name', 'first name', 'required|min_length[2]|max_length[50]');
             $this->form_validation->set_rules('last_name', 'last name', 'required|min_length[2]|max_length[50]');
             $this->form_validation->set_rules('username', 'userame', 'required|min_length[5]|max_length[15]|callback_edit_unique[adminlogin.username.'.$id.']');
             $this->form_validation->set_rules('email_id', 'email address', 'required|valid_email|callback_edit_unique[adminlogin.emailId.'.$id.']');
             $this->form_validation->set_rules('status', 'status', 'required');

             if ($this->form_validation->run() === TRUE) {

                 $this->administrator_model->edit_record($id);
             }
         }

         $this->data['roles_options'] = $this->create_role_options();
         $this->data['edit_data']     = $this->administrator_model->get_user($id);

         $this->render('settings/users/edit');
     }
   
    
    
    /**
     * Validation rule for unique users. ( Edit Case )
     * 
     * @return bool
     */
    public function edit_unique($value, $params)
    {
        $this->form_validation->set_message('edit_unique', 'The %s is already being used by another account.');
        
        list($table, $field, $id) = explode(".", $params, 3);

        $query = $this->db->select($field)->from($table)->where($field, $value)->where('id !=', $id)->limit(1)->get();
        
        if ($query->row()) {
            return false;
        } else {
            return true;
        }
    }
    
    
    
    /**
     * Dropdown options for role.
     * 
     * @return array
     */
    private function create_role_options()
    {
       $roles = $this->role_model->fetch_roles();
       
       $options = array('' => '-- Select Role --');
       
       foreach($roles as $role){
           
           if($role->status == 'active'){
               
               $options[$role->id] = $role->title;
           }
       }
       
       return $options;
    }
   
    
    
    /**
     * Add roles
     * 
     * @return void
     */
    public function add_role() 
    {
         has_access('manage_roles');

         if($this->input->post()){
             $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

             $this->form_validation->set_rules('role_title', 'role title', 'required|min_length[2]|max_length[50]');
             $this->form_validation->set_rules('description', 'description', 'max_length[500]');
             $this->form_validation->set_rules('permissions', 'module', 'required', array('required' => 'You must select a %s.'));
             $this->form_validation->set_rules('status', 'status', 'required');

             if ($this->form_validation->run() === TRUE) {

                 $this->role_model->add_role();
                 redirect('admin/settings/manage_role');
             } 
          }

         $this->data['menus'] = $this->role_model->fetch_modules();
         $this->render('settings/roles/add');
    }


    
    /**
     * Edit Roles
     * 
     * @param $role_id int
     * @return void
     */
    public function edit_role($role_id) 
    {
         has_access('manage_roles');

         if(in_array($role_id, array($this->my_role, $this->super_role))) {
             show_error("You are not allowed to edit this role.", 403, "Access Denied");
         }

         if(! intval($role_id) > 0){
             redirect('admin/settings/manage_role');
         }

         if($role_id == 1){
             die('Action Not Allowed');
         }

         if($this->input->post()){
             $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

             $this->form_validation->set_rules('role_title', 'role title', 'required|min_length[2]|max_length[50]');
             $this->form_validation->set_rules('description', 'description', 'max_length[500]');
             $this->form_validation->set_rules('permissions', 'module', 'required', array('required' => 'You must select a %s.'));
             $this->form_validation->set_rules('status', 'status', 'required');

             if ($this->form_validation->run() === TRUE) {

                 $this->role_model->update_role($role_id);
                 redirect('admin/settings/manage_role');
             } 
          }

         $this->data['role']        = $this->role_model->get_role($role_id);
         $this->data['menus']       = $this->role_model->fetch_modules();
         $this->data['permissions'] = $this->role_model->get_role_permission($role_id);
         $this->render('settings/roles/edit');
    }


    
    /**
     * Delete role
     * 
     * @param $id int
     * 
     * @return void
     */
    public function delete_role($id) 
    {
        has_access('manage_roles');
        
        if(in_array($id, array($this->my_role, $this->super_role))) {
            show_error("You are not allowed to delete this role.", 403, "Access Denied");
        }
        
        $this->role_model->delete_role($id);
        
        redirect('admin/settings/manage_role');
    }
    
    
    
    /**
     * Delete users
     * 
     * @param $id int
     * @return void
     */
    public function delete_user($id) 
    {
        has_access('manage_administrators');
        
        if(in_array($id, array($this->super_user, $this->my_id))) {
            show_error("You are not allowed to delete this user.", 403, "Access Denied");
        }
        
        $this->administrator_model->delete_user($id);
        
        redirect('admin/settings/manage_users');
    }
        
    
    
    /**
     * Datatable AJAX call for admin listing
     * 
     * @return json
     */
    public function admin_ajax()
    {
            has_access('manage_administrators');
        
            if (isset($_REQUEST["customActionType"]) && $_REQUEST["customActionType"] == "group_action") {
                
                $ids = array_filter($_REQUEST['id']);
                
                switch($_REQUEST['customActionName']) {
                    case 'delete_all' : $this->administrator_model->deleteAll($ids); 
                        break;
                }
                
                $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
                $records["customActionMessage"] = "Group action successfully has been completed. Well done!"; // pass custom message(useful for getting status of group actions)
            }
            
            $iTotalRecords  = $this->administrator_model->count_row();
            $iDisplayLength = intval($_REQUEST['length']);
            $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
            $iDisplayStart = intval($_REQUEST['start']);
            $sEcho = intval($_REQUEST['draw']);

            $filter = array();
            
            if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'filter') {
                
                if(isset($_REQUEST['filter_role']) && ($_REQUEST['filter_role'] != '')){
                    $filter['b.title'] = $_REQUEST['filter_role'];
                }
                
                if(isset($_REQUEST['filter_username']) && ($_REQUEST['filter_username'] != '')){
                    $filter['a.username'] = $_REQUEST['filter_username'];
                }
                
                if(isset($_REQUEST['filter_firstname']) && ($_REQUEST['filter_firstname'] != '')){
                    $filter['a.first_name'] = $_REQUEST['filter_firstname'];
                }
                
                if(isset($_REQUEST['filter_lastname']) && ($_REQUEST['filter_lastname'] != '')){
                    $filter['a.last_name'] = $_REQUEST['filter_lastname'];
                }
                
                if(isset($_REQUEST['filter_email']) && ($_REQUEST['filter_email'] != '')){
                    $filter['a.emailId'] = $_REQUEST['filter_email'];
                }
                
                if(isset($_REQUEST['filter_status']) && ($_REQUEST['filter_status'] != '')){
                    $filter['a.status'] = $_REQUEST['filter_status'];
                }
                
                if(isset($_REQUEST['filter_date_from']) && ($_REQUEST['filter_date_from'] != '')){
                    $filter['a.addDate'] = date("Y-m-d", strtotime(str_replace('/', '-', $_REQUEST['filter_date_from'])));
                }
                
            }
            
            $records = array();
            $records["data"] = array(); 

            $end = $iDisplayStart + $iDisplayLength;
            $end = $end > $iTotalRecords ? $iTotalRecords : $end;

            $services = $this->administrator_model->fetch_admins($iDisplayStart, $iDisplayLength, $_REQUEST['order'][0], $filter);

            if(is_array($services)) {
                
                foreach ($services as $row){
                    
                    if(in_array($row->id, array($this->super_user, $this->my_id))) {
                        $chkbox = '';
                        $status_icon='<a href="javascript:void(0);" title="Active"><i class="fa fa-2x fa-eye"></i></a>';
                    }
                    else{
                        $chkbox = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                    <input type="checkbox" name="ids" class="checkboxes" value="'.$row->id.'" />
                                    <span></span>
                                </label>';

                         $status_icon = ($row->status == '1') ? '<a href="javascript:void(0);"  onclick="return status_get('."'1',"."'$row->id'".');"  title="Active"><i class="fa fa-2x fa-eye"></i></a>' : '<a href="javascript:void(0);" onclick="return status_get('."'0',"."'$row->id'".');"   title="Deactive"><i class="fa fa-2x fa-eye-slash"></i></a>';        
                    }
                    
                    $image = (trim($row->userImage) != '' && file_exists('uploads/users/'.$row->userImage)) ? '<img width="45" class="img-circle" src="'.base_url('uploads/users/'.$row->userImage).'"/>' : '<img class="img-circle" src="'.base_url('assets/layouts/layout/img/avatar.png').'"/>';
                    
                    if(in_array($row->id, array($this->super_user, $this->my_id))) {
                        $actions  = '<a class="btn btn-outline btn-circle btn-sm purple disabled" href="javascript:void(0);"><i class="fa fa-edit"></i> Edit</a>';
                    }
                    else{
                        $actions  = '<a class="btn btn-outline btn-circle btn-sm purple" href="' . site_url('admin/settings/edit_user/' . $row->id) . '"><i class="fa fa-edit"></i> Edit</a>';
                    }
                    
                    if(in_array($row->id, array($this->super_user, $this->my_id))) {
                        $actions .= '<a title="Delete" class="btn btn-outline btn-circle dark btn-sm red disabled" href="javascript:void(0);"><i class="fa fa fa-trash"></i> Delete</a>';
                    }
                    else{
                        $actions .= '<a title="Delete" class="btn btn-outline btn-circle dark btn-sm red" onClick="if(confirm(\'Do you want to delete this user ?\')){window.location.href=\'' . site_url('admin/settings/delete_user/' . $row->id . '') . '\';}" href="javascript:;"><i class="fa fa fa-trash"></i> Delete</a>';
                    }
                    
                    

                       

                    $records["data"][] = array($chkbox, $image, $row->role_title, $row->username, $row->first_name, $row->last_name, $row->emailId, $status_icon, display_datetime($row->addDate), $actions);
                }
            }
            
            

            $records["draw"]            = $sEcho;
            $records["recordsTotal"]    = $iTotalRecords;
            $records["recordsFiltered"] = $iTotalRecords;

            echo json_encode($records);
    }
    
    
    
    /**
     * Datatable AJAX call for roles listing
     * 
     * @return json
     */
    public function role_ajax()
    {
            has_access('manage_roles');
        
            if (isset($_REQUEST["customActionType"]) && $_REQUEST["customActionType"] == "group_action") {
                
                $ids = array_filter($_REQUEST['id']);
                
                switch($_REQUEST['customActionName']) {
                    case 'delete_all' : $this->role_model->deleteAll($ids); 
                        break;
                }
                
                $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
                $records["customActionMessage"] = "Group action successfully has been completed. Well done!"; // pass custom message(useful for getting status of group actions)
            }
            
            $iTotalRecords  = $this->role_model->count_row();
            $iDisplayLength = intval($_REQUEST['length']);
            $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
            $iDisplayStart = intval($_REQUEST['start']);
            $sEcho = intval($_REQUEST['draw']);

            $filter = array();
            
            if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'filter') {
                
                if(isset($_REQUEST['filter_title']) && ($_REQUEST['filter_title'] != '')){
                    $filter['title'] = $_REQUEST['filter_title'];
                }
                
                if(isset($_REQUEST['filter_description']) && ($_REQUEST['filter_description'] != '')){
                    $filter['description'] = $_REQUEST['filter_description'];
                }
                
                if(isset($_REQUEST['filter_status']) && ($_REQUEST['filter_status'] != '')){
                    $filter['status'] = $_REQUEST['filter_status'];
                }
                
                if(isset($_REQUEST['filter_date_from']) && ($_REQUEST['filter_date_from'] != '')){
                    $filter['created_at'] = date("Y-m-d", strtotime(str_replace('/', '-', $_REQUEST['filter_date_from'])));
                }
                
            }
            
            
            $records = array();
            $records["data"] = array(); 

            $end = $iDisplayStart + $iDisplayLength;
            $end = $end > $iTotalRecords ? $iTotalRecords : $end;

            $services = $this->role_model->fetch_roles($iDisplayStart, $iDisplayLength, $_REQUEST['order'][0], $filter);

            if(is_array($services)) {
                
                foreach ($services as $row){
                    
                    if(in_array($row->id, array($this->my_role, $this->super_role))) {
                        $chkbox = '';
                        
                        $actions  = '<a class="btn btn-outline btn-circle btn-sm purple disabled" href="javascript:void(0);"><i class="fa fa-edit"></i> Edit</a>';
                        $actions .= '<a title="Delete" class="btn btn-outline btn-circle dark btn-sm red disabled" href="javascript:void(0);"><i class="fa fa fa-trash"></i> Delete</a>';
                        $status_icon='<a href="javascript:void(0);" title="Active" class="disabled"><i class="fa fa-2x fa-eye"></i></a>';
                    }
                    else{
                        $chkbox = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                    <input type="checkbox" name="ids" class="checkboxes" value="'.$row->id.'" />
                                    <span></span>
                                </label>';
                        
                        $actions  = '<a class="btn btn-outline btn-circle btn-sm purple" href="' . site_url('admin/settings/edit_role/' . $row->id) . '"><i class="fa fa-edit"></i> Edit</a>';
                        $actions .= '<a title="Delete" class="btn btn-outline btn-circle dark btn-sm red" onClick="if(confirm(\'Do you want to delete this record ?\')){window.location.href=\'' . site_url('admin/settings/delete_role/' . $row->id . '') . '\';}" href="javascript:;"><i class="fa fa fa-trash"></i> Delete</a>';

                        $status_icon = ($row->status == 'active') ? '<a href="javascript:void(0);"  onclick="return status_get('."'active',"."'$row->id'".');"  title="Active" class="disabled"><i class="fa fa-2x fa-eye"></i></a>' : '<a href="javascript:void(0);" onclick="return status_get('."'deactive',"."'$row->id'".');"   title="Deactive"><i class="fa fa-2x fa-eye-slash"></i></a>';  
                      
                    } 

                

                    
                        

                    
                    
                    $records["data"][] = array($chkbox, $row->title, $row->description, $status_icon, display_datetime($row->created_at), $actions);
                }
            }
            
            

            $records["draw"]            = $sEcho;
            $records["recordsTotal"]    = $iTotalRecords;
            $records["recordsFiltered"] = $iTotalRecords;

            echo json_encode($records);
    }

    
    
    /**
     * Display site configuration page.
     * 
     * @return void
     */
    function site_config()
    {
		has_access('system_config');
               
            $this->load->model('admin/systemconfig_model');
            
            $sys_config = $this->systemconfig_model->getSystemConfigurations();
            
            if($this->input->post()) {

                $this->systemconfig_model->savestemconfigdata();

            }

            $this->data['sys_config'] = $sys_config;
            $this->data['timezones']  = $this->get_timezones();
            $this->render('settings/config/edit');
    }
    
    
    
    /**
     * Get timezone list 
     * 
     * @return array
     */
    private function get_timezones()
    {
            $timezones = array();
            
            $query = $this->db->get(TBL_TIMEZONE);
            
            foreach($query->result() as $qry){
                
                $timezones[$qry->timezone] = $qry->display_name;
            }
            
            return $timezones;
    }

    public function user_active_status(){

        $post       = $this->input->post();
        $status     = $post['status'];
        $id         = $post['id'];
        $array      = array();

        if ($status == '1')
        {
            $array['status'] = '0';
        }
        else
        {
            $array['status'] = '1';
        }

        $this->db->where(array('deleted_at' => NULL,'id' => $id))->update(TBL_ADMINLOGIN, $array);
        $output = json_encode(array("status" => 'success'));
        die($output);
    }

    public function roles_active_status(){

        $post       = $this->input->post();
        $status     = $post['status'];
        $id         = $post['id'];
        $array      = array();

        if ($status == 'active')
        {
            $array['status'] = 'inactive';
        }
        else
        {
            $array['status'] = 'active';
        }

        $this->db->where(array('deleted_at' => NULL,'id' => $id))->update(TBL_ROLE, $array);
        $output = json_encode(array("status" => 'success'));
        die($output);
    }

    
}


