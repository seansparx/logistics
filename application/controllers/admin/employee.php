<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Class to manage logistic employees.
 * 
 * @author Sean Rock <sean@sparxitsolutions.com>
 * @version 1.0
 * @dated 5/02/2017
 */
class Employee extends MY_Controller 
{
    
    function __construct()
    {
        parent::__construct();
        
        has_access('manage_employees');
        
        $this->load->model('admin/employee_model');
    }
    
    
    
    /**
     * Display employee listing page
     * 
     * @return void
     */
    public function manage() 
    {
        $this->data['content_datas'] = $this->employee_model->fetch_row();
                        
        $this->render('employee/manage');
    }
    
    
    
    /**
     * Add employee
     * 
     * @return void
     */
    public function add()
    {
        if($this->input->post()) {
            
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
            $this->form_validation->set_rules('emp_name', 'Employee Name', 'required|min_length[2]|max_length[50]|callback_unique_employee_add_time[employees.emp_name]');
            $this->form_validation->set_rules('state', 'State', 'required|max_length[50]');
            $this->form_validation->set_rules('contract', 'Contract', 'required|max_length[100]');
            $this->form_validation->set_rules('monthly_hours', 'No. of Hours', 'required|max_length[20]');
            $this->form_validation->set_rules('category', 'Category', 'required|max_length[100]');
            $this->form_validation->set_rules('status', 'Status', 'required');
            
            if ($this->form_validation->run() === TRUE) {
                
                if(isset($_FILES['emp_pic']) && (trim($_FILES['emp_pic']['name']) != '')) {

                        $msg = $this->do_upload();

                        if($msg['uploaded'] == true) {

                            $pic_name = $msg['upload_data']['file_name'];

                            // convert image to base64 data.
                            $path = 'uploads/emp/'.$pic_name;
                            $type = pathinfo($path, PATHINFO_EXTENSION);
                            $data = file_get_contents($path);
                            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

                            $this->employee_model->add_employee($base64);

                            unlink($path); // delete image.

                            redirect('admin/employee/manage');
                        }
                        else{
                            $data['pic_error'] = $msg['pic_error'];
                        }
                }
                else{
                    
                        $this->employee_model->add_employee();
                        redirect('admin/employee/manage');
                }
                
            } 
         }
         
        $this->render('employee/add');		
   }

    /**
     * Upload image
     * 
     * @return array
     */
    public function do_upload()
    {
            $config['upload_path']      = 'uploads/emp/';
            $config['allowed_types']    = 'gif|jpg|png|jpeg';
            $config['max_size']         = 2048; // 2 MB
            $config['max_width']        = 1024;
            $config['max_height']       = 1024;
            $config['file_name']        = 'pic_'.md5(time());

            $this->load->library('upload', $config);

            if ( ! $this->upload->do_upload('emp_pic')) {

                return array('uploaded' => false, 'pic_error' => $this->upload->display_errors());
            }
            else {
                
                return array('uploaded' => true, 'upload_data' => $this->upload->data());
            }
    }
   
   
    /**
     * Delete employee
     * 
     * @param $id int
     * @return void
     */
    public function delete($id) 
    {        
        $this->employee_model->deleteRecord($id);
    }
    
    
    public function delete_leave($id, $emp_id) 
    {        
        $this->employee_model->delete_leave($id, $emp_id);
    }
    
    
    
    /**
     * Edit employee
     * 
     * @param $id int
     * @return void
     */
    public function edit($id)
    {
            $data = array();

            if($this->input->post('btn_leave')) {

                $data = $this->add_leave($id);
            }
            else if($this->input->post()) {

                $data = $this->edit_basic_info($id);
            }

            $data['emp_id'] = $id;

            $data['details'] = $this->employee_model->getEditRecord($id);

            $this->data = $data;

            $this->render('employee/edit');
    }
    
    
    
    private function add_leave($id)
    {        
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            $this->form_validation->set_rules('start_date', 'Start Date', 'required|max_length[50]|callback_unique_leave[leaves.date.'.$id.']');
            $this->form_validation->set_rules('end_date', 'End Date', 'required|max_length[50]');
            $this->form_validation->set_rules('reason', 'Reason', 'required|max_length[500]');
           
            if (($this->form_validation->run() === TRUE)) {

                $this->employee_model->add_leave($id);    
                
                redirect("admin/employee/edit/".$id);
            }
    }
    
    
    public function unique_leave($value, $params)
    {
            $start_date = $value;
            $end_date   = $this->input->post('end_date');

            $this->form_validation->set_message('unique_leave', 'A leave already exists between selected date.');

            list($table, $field, $emp_id) = explode(".", $params, 3);

            $this->db->select($field)->from($table);
            $this->db->where($field.' >=', date("Y-m-d", strtotime(str_replace("/", "-", $start_date))));
            $this->db->where($field.' <=', date("Y-m-d", strtotime(str_replace("/", "-", $end_date))));
            $this->db->where('emp_id', $emp_id);

            $query = $this->db->limit(1)->get();

            if ($query->num_rows() > 0) {
                return false;
            } 
            else {
                return true;
            }
    }
    
    
    private function edit_basic_info($id)
    {
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
            $this->form_validation->set_rules('emp_name', 'Employee Name', 'required|min_length[2]|max_length[50]|callback_unique_employee[employees.emp_name.'.$id.']');
            $this->form_validation->set_rules('state', 'State', 'required|max_length[50]');
            $this->form_validation->set_rules('contract', 'Contract', 'required|max_length[100]');
            $this->form_validation->set_rules('monthly_hours', 'No. of Hours', 'required|max_length[20]');
            $this->form_validation->set_rules('category', 'Category', 'required|max_length[100]');
            $this->form_validation->set_rules('status', 'Status', 'required');
           
            if (($this->form_validation->run() === TRUE)){
                
                if(isset($_FILES['emp_pic']) && (trim($_FILES['emp_pic']['name']) != '')) {

                        $msg = $this->do_upload();

                        if($msg['uploaded'] == true) {

                            $pic_name = $msg['upload_data']['file_name'];

                            // convert image to base64 data.
                            $path = 'uploads/emp/'.$pic_name;
                            $type = pathinfo($path, PATHINFO_EXTENSION);
                            $data = file_get_contents($path);
                            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

                            $this->employee_model->update_employee($id, $base64);
                            
                            unlink($path); // delete image.
                            
                            redirect("admin/employee/manage");
                        }
                        else{
                            $data['pic_error'] = $msg['pic_error'];
                        }
                }
                else{
                        $this->employee_model->update_employee($id);
                        redirect("admin/employee/manage");
                }
            }

            $data['id'] = $id;
            
            return $data;
    }
    
    
    /**
     * Edit employee
     * 
     * @param $id int
     * @return void
     */
    public function picture($id)
    {
        $emp = $this->employee_model->getEditRecord($id);
        echo '<img src="'.$emp['emp_pic'].'"/>';
    }
    
    
    /**
     * Validation rule for unique employees
     * 
     * @param $value int
     * @param $params array
     * @return bool
     */
    public function unique_employee_add_time($value, $params)
    {
        $value = clean_text($value);
        $this->form_validation->set_message('unique_employee_add_time', 'The %s "'.$value.'" is already being used.');

        list($table, $field) = explode(".", $params);

        $query = $this->db->select($field)->from($table)->where($field, $value)->where('deleted_at',NULL)->limit(1)->get();        
        
        if ($query->num_rows() > 0) {
            return false;
        } 
        else {
            return true;
        }
    }

    
    public function unique_employee($value, $params)
    {   
        $value = clean_text($value);
        $this->form_validation->set_message('unique_employee', 'The %s "'.$value.'" is already being used.');
        
        list($table, $field, $id) = explode(".", $params, 3);

        $query = $this->db->select($field)->from($table)->where($field, $value)->where('id !=', $id)->limit(1)->get();        
        
        if ($query->num_rows() > 0) {
            return false;
        } 
        else {
            return true;
        }
    }
    
    
    
    /**
     * Datatable AJAX Call for employee list
     * 
     * @return json
     */
    public function employee_ajax()
    {
            if (isset($_REQUEST["customActionType"]) && $_REQUEST["customActionType"] == "group_action") {
                
                $ids = array_filter($_REQUEST['id']);
                
                switch($_REQUEST['customActionName']) {
                    case 'delete_all' : $this->employee_model->deleteAll($ids); 
                        break;
                }
                
                $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
                $records["customActionMessage"] = "Group action successfully has been completed. Well done!"; // pass custom message(useful for getting status of group actions)
            }
            
            $iTotalRecords  = $this->employee_model->count_row();
            $iDisplayLength = intval($_REQUEST['length']);
            $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
            $iDisplayStart = intval($_REQUEST['start']);
            $sEcho = intval($_REQUEST['draw']);

            $filter = array();
            
            if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'filter') {
                
                if(isset($_REQUEST['filter_id']) && ($_REQUEST['filter_id'] != '') && ($_REQUEST['filter_id'] != 'EMP')){
                    $filter['id'] = intval(str_replace("EMP-00", "", $_REQUEST['filter_id']));
                }
                
                if(isset($_REQUEST['filter_emp_name']) && ($_REQUEST['filter_emp_name'] != '')){
                    $filter['emp_name'] = $_REQUEST['filter_emp_name'];
                }
                
                if(isset($_REQUEST['filter_state']) && ($_REQUEST['filter_state'] != '')){
                    $filter['state'] = $_REQUEST['filter_state'];
                }
                
                if(isset($_REQUEST['filter_contract']) && ($_REQUEST['filter_contract'] != '')){
                    $filter['contract'] = $_REQUEST['filter_contract'];
                }
                
                if(isset($_REQUEST['filter_category']) && ($_REQUEST['filter_category'] != '')){
                    $filter['category'] = $_REQUEST['filter_category'];
                }
                
                if(isset($_REQUEST['filter_monthly_hours']) && ($_REQUEST['filter_monthly_hours'] != '')){
                    $filter['monthly_hours'] = $_REQUEST['filter_monthly_hours'];
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

            $services = $this->employee_model->fetch_employees($iDisplayStart, $iDisplayLength, $_REQUEST['order'][0], $filter);

            if(is_array($services)) {
                
                foreach ($services as $row) {
                    
                    $nos = $this->employee_model->get_nos($row->id);
                    
                    if($nos > 0) {
                        
                        $chkbox = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                        <input type="checkbox" disabled name="ids" class="checkboxes" value="" />
                                        <span></span>
                                    </label>';
                    }
                    else{
                        
                        $chkbox = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                        <input type="checkbox" name="ids" class="checkboxes" value="'.$row->id.'" />
                                        <span></span>
                                    </label>';
                    }
                    
                    $img = (trim($row->emp_pic) != '') ? '<a target="_blank" href="'.site_url('admin/employee/picture/'.$row->id).'"><img height="35" src="' . $row->emp_pic . '" /></a>' : '<img height="35" src="'.base_url('assets/layouts/layout/img/avatar.png').'" />';
                    
                    $actions  = '<a class="btn btn-outline btn-circle btn-sm purple" href="' . site_url('admin/employee/edit/' . $row->id) . '"><i class="fa fa-edit"></i> Edit</a>';
                    
                    if($nos > 0) {
                        
                        $actions .= '<a title="Delete Not Allowed" class="btn btn-outline btn-circle dark btn-sm red disabled" onClick="alert(\'Delete operation is not allowed, because employee using '.$nos.' service.\');" href="javascript:;"><i class="fa fa fa-trash"></i> '.$nos.' Used</a>';
                    }
                    else{
                        
                        $actions .= '<a title="Delete" class="btn btn-outline btn-circle dark btn-sm red mt-sweetyalert"  href="javascript:;" onClick="return confirmation(\'' . site_url('admin/employee/delete/' . $row->id . '') . '\');"><i class="fa fa fa-trash"></i>Delete</a>';
                    }

                    $status_icon = ($row->status == 'active') ? '<a href="javascript:void(0);"  onclick="return status_get('."'active',"."'$row->id'".');"  title="Active"><i class="fa fa-2x fa-eye"></i></a>' : '<a href="javascript:void(0);" onclick="return status_get('."'deactive',"."'$row->id'".');"   title="Deactive"><i class="fa fa-2x fa-eye-slash"></i></a>';
                    
                    $m_hrs = explode(":", $row->monthly_hours);
                    
                    unset($m_hrs[2]);
                    
                    $monthly_hours = implode(":", $m_hrs) ?  : '-';
                    $records["data"][] = array($chkbox, $img, emp_code($row->id), $row->emp_name, $row->state, $row->contract, $row->category, $monthly_hours, $status_icon, display_datetime($row->created_at), $actions);
                }
            }
            
            

            $records["draw"]            = $sEcho;
            $records["recordsTotal"]    = $iTotalRecords;
            $records["recordsFiltered"] = $iTotalRecords;

            echo json_encode($records);
    }
    
    
    public function leave_ajax($emp_id)
    {
            $iTotalRecords  = $this->employee_model->count_leave_row($emp_id);

            $iDisplayLength = intval($_REQUEST['length']);
            $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
            $iDisplayStart = intval($_REQUEST['start']);
            $sEcho = intval($_REQUEST['draw']);

            $filter = array();
            
            $records = array();
            $records["data"] = array(); 

            $end = $iDisplayStart + $iDisplayLength;
            $end = $end > $iTotalRecords ? $iTotalRecords : $end;

            $services = $this->employee_model->fetch_leave($emp_id, $iDisplayStart, $iDisplayLength, $_REQUEST['order'][0], $filter);

            if(is_array($services)) {
                
                foreach ($services as $row){
                    
                    $chkbox = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                        <input type="checkbox" name="ids" class="checkboxes" value="'.$row->id.'" />
                                        <span></span>
                                    </label>';
                    
                    $actions = '<a title="Delete" class="btn btn-outline btn-circle dark btn-sm red mt-sweetyalert"  href="javascript:;" onClick="return confirmation(\'' . site_url('admin/employee/delete_leave/' . $row->id . '/'.$emp_id) . '\');"><i class="fa fa fa-trash"></i> Delete</a>';
                    
                    $records["data"][] = array($chkbox, $row->date, $row->reason, display_datetime($row->ut), $actions);
                }
            }
            
            

            $records["draw"]            = $sEcho;
            $records["recordsTotal"]    = $iTotalRecords;
            $records["recordsFiltered"] = $iTotalRecords;

            echo json_encode($records);
    }
    
    
    

     public function update_active_status(){

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

        $this->db->where(array('deleted_at' => NULL,'id' => $id))->update(TBL_EMPLOYEE, $array);
        $output = json_encode(array("status" => 'success'));
        die($output);
    }
  
}


