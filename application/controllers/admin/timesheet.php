<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Variants Class
 * @author Rajesh Kumar Yadav
 * @version 1.0
 * @dated 20/01/2017
 */
class Timesheet extends MY_Controller 
{
    
    function __construct() 
    {
            parent::__construct();
        
            has_access('book_service');

            $this->load->model('admin/timesheet_model');
            $this->load->model('admin/employee_model');
            $this->load->model('admin/project_model');
            $this->load->model('admin/vehicle_model');
            $this->load->model('admin/department_model');
            $this->load->model('admin/systemconfig_model');

            $this->load->library('parser');
            $this->data['sys_config'] = $this->systemconfig_model->getSystemConfigurations();
    }


    /**
     * function manage():-To display brand list 
     */
    public function manage() 
    {
            $this->render('timesheet/index');     
    }


    /**
     * function For Create Landing page
     */
    public function add() 
    {
            if ($this->validate_form() == TRUE) {

                $assign_hrs = $this->assign_hrs($this->input->post('employees'), $this->input->post('entry_date'));

                $this->timesheet_model->add_timesheet_data($assign_hrs);
            }

            $this->render('timesheet/add');
    }
    

   /**
    * Edit Timesheet
    * 
    * @parm $edit_id
    * @return void   
    */
    public function edit($edit_id) 
    {
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
            $this->form_validation->set_rules('working_hours', 'Working Hours', 'required|max_length[10]|callback_validate_time');
            $this->form_validation->set_rules('remark', 'Remark', 'max_length[500]');

            if($this->form_validation->run() === TRUE) {
                
                $assign_hrs = $this->assign_hrs($this->input->post('employees'), $this->input->post('entry_date'));
                $this->timesheet_model->updatetTimesheet($edit_id, $assign_hrs);
            }
            else{
                
                $this->data['detail'] = $this->timesheet_model->getEditRecord($edit_id);
                
                $employees = $this->employee_model->fetch_row();

                $options = array("" => "Please select employee");

                if (is_array($employees)) {

                    foreach ($employees as $row) {

                        if ($row->status == 'active') {

                            $options[$row->id] = $row->emp_name . ' (' . emp_code($row->id) . ') - '.$this->assign_hrs($row->id, $this->data['detail']->entry_date).' hrs';;
                        }
                    }
                }

                $this->data['employees'] = form_dropdown('employee', $options, intval($this->data['detail']->emp_id), 'class="form-control" disabled="disabled" data-size="8" id="employees"');
                $this->render('timesheet/edit');             
            }
    }
    
     public function total_hours($start_time,$end_time){
			 
            $datetime1 = new DateTime(date("H:i:s", strtotime($start_time)));
            $datetime2 = new DateTime(date("H:i:s", strtotime($end_time)));
            $interval = $datetime1->diff($datetime2);
			return  $totalhr=$interval->h.":".$interval->i.":00";	   
		}
    

    private function validate_form()
    {
        if ($this->input->post()) {

             $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
             $this->form_validation->set_rules('entry_date', 'Entry Date', 'required|max_length[15]');
             $this->form_validation->set_rules('employees', 'Employee', 'required|max_length[50]|callback_check_unique_add_time');
             $this->form_validation->set_rules('working_hours', 'Working Hours', 'required|max_length[10]|callback_validate_time');
             $this->form_validation->set_rules('remark', 'Remark', 'max_length[500]');
             
             return ($this->form_validation->run() === TRUE);
         }
    }
    
    
//    function maximumCheck($num)
//    {
//            if ($num > 23) {
//                $this->form_validation->set_message('maximumCheck',"The working hour $num  must be less than or equal to 24 Hours");
//                return FALSE;
//            }
//            else{
//                return TRUE;
//            }
//    }
    
    
    public function validate_time($str) 
    {
            list($hh, $mm) = split('[:]', $str);

            if (!is_numeric($hh) || !is_numeric($mm)) {

                $this->form_validation->set_message('validate_time', 'Not numeric');
                return FALSE;
            } 
            else if ((int) $hh > 23 || (int) $mm > 59) {

                $this->form_validation->set_message('validate_time', 'Invalid time');
                return FALSE;
            } 
            else if (mktime((int) $hh, (int) $mm) === FALSE) {

                $this->form_validation->set_message('validate_time', 'Invalid time');
                return FALSE;
            }

            return TRUE;
    }
    

        public function check_unique_add_time()
        {                
		if($this->timesheet_model->check_unique_add_time_model()){
                    
			$this->form_validation->set_message('check_unique_add_time', "Entry already exists for selected date.");
			return false;
		}
                else{
			return true;
		}	
	}
	
	public function check_unique_edit_time()
	{		
		$count = $this->timesheet_model->check_unique_edit_time_model();
		if($count >= 1){
			$this->form_validation->set_message('check_unique_edit_time', "Employee already exist on entered entry date");
			return false;
		}else{
			return true;
		}		
	}

    
    /*
     * Function for delete
     * @param int $id
     */
    public function delete($service_id) {
        $this->timesheet_model->deleteRecord($service_id);
    }
    
    
    public function deleteDetail($id, $service_id) {
        
        $this->timesheet_model->deleteServiceDetail($service_id, $id);
    }

  
    public function timesheet_ajax()
    {
            if (isset($_REQUEST["customActionType"]) && $_REQUEST["customActionType"] == "group_action") {
                
                $ids = array_filter($_REQUEST['id']);
                
                switch($_REQUEST['customActionName']) {
                    case 'delete_all' : $this->timesheet_model->deleteAll($ids); 
                        break;
                }
                
                $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
                $records["customActionMessage"] = "Group action successfully has been completed. Well done!"; // pass custom message(useful for getting status of group actions)
            }
            
            $iTotalRecords  = $this->timesheet_model->count_row();
            $iDisplayLength = intval($_REQUEST['length']);
            $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
            $iDisplayStart = intval($_REQUEST['start']);
            $sEcho = intval($_REQUEST['draw']);

            $filter = array();
            
            if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'filter') {
                
                if(isset($_REQUEST['filter_entry_date']) && ($_REQUEST['filter_entry_date'] != '')){
                    $filter['entry_date'] = date("Y-m-d", strtotime(str_replace('/', '-', $_REQUEST['filter_entry_date'])));
                }
                
                if(isset($_REQUEST['filter_start_time']) && ($_REQUEST['filter_start_time'] != '')){
                    $filter['in_time'] = date("H:i:s", strtotime($_REQUEST['filter_start_time']));
                }
                
                if(isset($_REQUEST['filter_end_time']) && ($_REQUEST['filter_end_time'] != '')){
                    $filter['out_time'] = date("H:i:s", strtotime($_REQUEST['filter_end_time']));
                }
                
                if(isset($_REQUEST['filter_total_hours']) && ($_REQUEST['filter_total_hours'] != '')){
                    $filter['total_hours'] = $_REQUEST['filter_total_hours'];
                }
                
                if(isset($_REQUEST['filter_extra_hours']) && ($_REQUEST['filter_extra_hours'] != '')){
                    $filter['extra_hour'] = $_REQUEST['filter_extra_hours'];
                }

                
                if(isset($_REQUEST['filter_employee_name']) && ($_REQUEST['filter_employee_name'] != '')){
                    $filter['emp_name'] = $_REQUEST['filter_employee_name'];
                }
                
                 if(isset($_REQUEST['filter_employee_code']) && ($_REQUEST['filter_employee_code'] != '') && $_REQUEST['filter_employee_code']!='EMP'){
                    $filter['emp_id'] = intval(str_replace("EMP-00", "", $_REQUEST['filter_employee_code']));
                }
                
                if(isset($_REQUEST['filter_date_created']) && ($_REQUEST['filter_date_created'] != '')){
                    $filter['ut'] = date("Y-m-d", strtotime(str_replace('/', '-', $_REQUEST['filter_date_created'])));
                }
                
                if(isset($_REQUEST['filter_remark']) && ($_REQUEST['filter_remark'] != '')){
                    $filter['remarks'] = $_REQUEST['filter_remark'];
                }
                
            }
            
            $records = array();
            $records["data"] = array(); 

            $end = $iDisplayStart + $iDisplayLength;
            $end = $end > $iTotalRecords ? $iTotalRecords : $end;

            $services = $this->timesheet_model->fetch_timeheet_data($iDisplayStart, $iDisplayLength, $_REQUEST['order'][0], $filter);
            
            if(is_array($services)) {
                
                foreach ($services as $row){
                    
                    $chkbox = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                                    <input type="checkbox" class="checkboxes" value="'.$row->id.'" />
                                                    <span></span>
                                                </label>';
                    
                    $actions = '<a class="btn btn-outline btn-circle btn-sm purple" href="' . site_url('admin/timesheet/edit/' . $row->id) . '"><i class="fa fa-edit"></i> Edit</a>';

                    $actions .= '<a title="Delete" class="btn btn-outline btn-circle dark btn-sm red mt-sweetyalert"  href="javascript:;" onClick="return confirmation(\'' . site_url('admin/timesheet/delete/' . $row->id . '') . '\');"><i class="fa fa fa-trash"></i>Delete</a>';

                    $records["data"][] = array($chkbox, display_date($row->entry_date), emp_code($row->emp_id) ,$row->emp_name , '<p>'.date("H:i", strtotime($row->assign_hours)).'</p>', '<p>'.date("H:i", strtotime($row->total_hours)).'</p>', '<p>'.date("H:i", strtotime($row->extra_hour)).'</p>', $row->remarks, $actions);
                }
            }
            
            $records["draw"]            = $sEcho;
            $records["recordsTotal"]    = $iTotalRecords;
            $records["recordsFiltered"] = $iTotalRecords;

            echo json_encode($records);
    }
    
                /*
                * Function for time validation
                * @param start time and end time
                */
    
                public function time_checker()
                {

			$start_time=$this->input->post('start_time');
			$end_time=$this->input->post('end_time');
			

			$in_time = new DateTime(date("H:i:s", strtotime("$start_time")));
			$out_time = new DateTime(date("H:i:s", strtotime("$end_time")));

			if($in_time > $out_time){
				 $this->form_validation->set_message('time_checker', "Start time $start_time can not be greater than end time ");
				return false;
			}else{
				return true;
			}

		}
		
	
		
            public function calculate_extra_hr()
            {
                    
            $assigned_hr = $this->timesheet_model->get_emp_assigned_hour($this->input->post('employees') , $this->input->post('entry_date'));
            $datetime1 = new DateTime(date("H:i:s", strtotime($this->input->post('start_time'))));
            $datetime2 = new DateTime(date("H:i:s", strtotime($this->input->post('end_time'))));
            $interval = $datetime1->diff($datetime2);
            $totalhr = $interval->h . ":" . $interval->i . ":00";
            

            if (strtotime($assigned_hr) >= strtotime($totalhr))
                {
                  $extra_hr = "00:00:00";
                }
              else
                {
                    $assigned = new DateTime($assigned_hr);
                    $interval = $assigned->diff(new DateTime($totalhr));
                    $extra_hr1 = $interval->h . ":" . $interval->i . ":00";
                    $extra_hr  = date('H:i:s',strtotime(date('Y-m-d')." $extra_hr1 "));
                }

            if ($extra_hr <= "00:00:00" || $assigned_hr <= "00:00:00")
                {
                    $extra_hr = "00:00:00";
                }

            $output = json_encode(array(
                'extra_hr' => "$extra_hr",
                'status' => 'success'
            ));
            die($output);
				
	   }   

           
       /**
        * @ return list of emplyees on assign date
        *
        */
        public function check_working_employees()
        {
                $employees = $this->timesheet_model->check_working_employees_model();

                if(count($employees) == 0) {

                    $options = array("" => "No employee assigned on selected date.");
                }
                else{

                    $options = array("" => " - Select One - ");
                }

                $entry_date = ($this->input->post('entry_date') ? $this->input->post('entry_date') : date("Y-m-d"));
                
                if (is_array($employees)) {

                    foreach ($employees as $row) {

                        $options[$row->id] = $row->emp_name.' ('.emp_code($row->id).') - '.$this->assign_hrs($row->id, $entry_date).' hrs';
                    }
                }

                echo form_dropdown('employees', $options, set_value('employees'), 'class=" form-control"  data-size="8" onchange="get_assign_hrs(this);" id="employees"');
        }
        
        
        
        public function assign_hrs($empid = null, $entrydate = null)
        {
                $emp_id = ($empid > 0) ? $empid : $this->input->post('emp_id');

                $entry_date = $entrydate ? $entrydate : $this->input->post('entry_date');

                $qry = $this->db->get_where(TBL_BOOKSERVICE_INFO, array('resource_type' => 'employee', 'resource_id' => $emp_id, 'deleted_at' => null, 'booking_date' => $entry_date));

                $num_rows = $qry->num_rows();

                if($num_rows > 0) {

                    $minutes = convertToHoursMins(($num_rows * 30));
                }
                else{

                    $minutes = '00:00';
                }

                if($this->input->post('emp_id')){

                    echo $minutes; // for AJAX call;
                }
                else{
                    return $minutes; // for check_working_employees();
                }
        }

        
        /*
          @ return assign time 
        **/

            public function check_emp_assign_time(){
               
                $data       =   $this->timesheet_model->check_emp_assign_time_model();
                $start_time =   display_time($data->start_time);
                $end_time   =   display_time($data->end_time);

                if($data!=""){
                   $output=json_encode(array('start_time'=>$start_time,'end_time'=>$end_time));
                   die($output);  
                }
                               
            }


}


