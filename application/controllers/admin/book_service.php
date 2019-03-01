<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Class to manage logistic services.
 * 
 * @author Sean Rock <sean@sparxitsolutions.com>
 * @version 1.0
 * @dated 30/01/2017
 */
class Book_service extends MY_Controller 
{
    function __construct() 
    {
            parent::__construct();

            has_access('book_service');

            $this->load->model('admin/service_model');
            $this->load->model('admin/employee_model');
            $this->load->model('admin/project_model');
            $this->load->model('admin/vehicle_model');
            $this->load->model('admin/department_model');
            $this->load->model('admin/systemconfig_model');

            $this->load->library('parser');

            $this->data['sys_config'] = $this->systemconfig_model->getSystemConfigurations();
    }
    
    
   /**
    * Display service listing page.
    * 
    * @return void
    */
    public function manage() 
    {
        if($this->input->get('start_date')) {
            
            $this->data['start_date'] = $this->input->get('start_date');
            $this->data['end_date'] = $this->input->get('end_date');
        }
        
        $this->render('book_service/index');
    }
    
    
   /**
    * Display service availability calendar.
    * 
    * @return void
    */
    public function calendar()
    {


        $get = $this->input->get();

        $temp_dd = $get['year'].'-'.$get['month'].'-01';

        $this->data['prev_mm'] = ($get['month']) ? date('m', strtotime($temp_dd."-1 month")) : date('m', strtotime("-1 month"));

        $this->data['prev_yy'] = ($get['year'])  ? date('Y', strtotime($temp_dd."-1 month")) : date('Y', strtotime("-1 month"));

        $this->data['next_mm'] = ($get['month']) ? date('m', strtotime($temp_dd."+1 month")) : date('m', strtotime("+1 month"));

        $this->data['next_yy'] = ($get['year'])  ? date('Y', strtotime($temp_dd."+1 month")) : date('Y', strtotime("+1 month"));

        $this->render('book_service/calendar');

    }

    
    
   /**
    * Get data for availability calendar.
    * 
    * @return void
    */
    public function get_services() 
    {
        if ($this->input->get('data_type') == 'employee') {
            $this->get_employee_calendar();
        } 
        else if ($this->input->get('data_type') == 'vehicle') {
            $this->get_vehicle_calendar();
        }
    }

    
    
   /**
    * Get vehicle status for availability calendar.
    * 
    * @return json
    */
    private function get_vehicle_calendar() 
    {
        $data = array();
        $buzy_in = array();

        $no_of_days = $this->input->get('month') ? date('t', strtotime($this->input->get('month'))) : date('t');

        $start_date = $this->input->get('month') ? $this->input->get('month') : date('Y-m-01');

        $all_vehicle = $this->vehicle_model->fetch_vehicles();

        if (!count($all_vehicle) > 0) {

                $output=json_encode(array('status'=>'no_record_found'));
                die($output);
        }

        // Loop for each day of current month.
        for ($i = 0; $i < $no_of_days; $i++) {

            $service_date = date("Y-m-d", strtotime($start_date . " + " . $i . " day"));

            $available_vehicles = $this->service_model->get_available_vehicles($service_date);
            $available_vehl = trim($available_vehicles) != "" ? explode(',', $available_vehicles) : '';

            foreach ($all_vehicle as $vehicle) {

                if ($vehicle->status == 'active') {

                    $obj = $this->service_model->getVehicleServices($vehicle->id, $service_date);

                    if (in_array($vehicle->id, $available_vehl)) {

                        $buzy_in = 'available';
                        $bgcolor = '#2E8B57';

                        $start_time=date("c", strtotime($service_date . ' ' .$this->data['sys_config']['SHIFT_START_TIME']));
                        $end_time=date("c", strtotime($service_date . ' ' . $this->data['sys_config']['SHIFT_END_TIME']));
                    } 
                    else {

                        $buzy_in = $obj->service_title;
                        $bgcolor = '#FF0000';

                        $start_time=date("c", strtotime($service_date . ' ' . (isset($obj->start_time) ? $obj->start_time : '00:00:00')));
                        $end_time=date("c", strtotime($service_date . ' ' . (isset($obj->end_time) ? $obj->end_time : '00:00:00')));
                    }

                    $data[] = array(
                        'title' => $vehicle->model.' ( '.$vehicle->regn_number.' )',
                        'start' => $start_time,
                        'end' => $end_time,
                        'backgroundColor' => $bgcolor
                    );
                }
            }
        }

        echo json_encode($data);
    }

    
    
   /**
    * Get employee status for availability calendar.
    * 
    * @return json
    */
    private function get_employee_calendar() 
    {
        $data = array();

        $records = $this->service_model->getEmployeeBookings();

        foreach ($records as $rec) {

            $start_time = date("c", strtotime($rec->booking_date . ' ' . (isset($rec->start_time) ? date("H:i:s", strtotime($rec->start_time)) : '00:00:00')));
            $end_time = date("c", strtotime($rec->booking_date . ' ' . (isset($start_time) ? date("H:i:s", strtotime($start_time." +1 hour")) : '00:00:00')));
            $bgcolor = '#FF0000';

            $data[] = array(
                'title' =>  $rec->resource_id . ' ('.emp_code($rec->resource_id).')',
                'start' => $start_time,
                'end' => $end_time,
                'backgroundColor' => $bgcolor
            );
        }

        echo json_encode($data);
    }

    
    
    public function check_availibility()
    {
        if($this->input->post()) {

            echo $this->service_model->get_availabilty();
        }
    }
    
    public function get_working_resources()
    {
        if($this->input->post()) {

            echo $this->service_model->get_working_resources();
        }
    }


    public function show_calendar($month)
    {

        echo $this->service_model->show_calendar_model();
        
    }
    
    
    
    public function available_slots()
    {
            $start = date("H:i", strtotime("00:00:00"));
            $end   = date("H:i", strtotime($start . " +30 minutes"));

            $tr  = '<tr><td colspan="3">';
                $tr .= '<input type="hidden" name="type" value="'.$this->input->post('type').'"/>';
                $tr .= '<input type="hidden" name="date" value="'.$this->input->post('date').'"/>';
                $tr .= '<input type="hidden" name="key" value="'.$this->input->post('key').'"/>';
            $tr .= '</td></tr>';

            $shift_starttime = strtotime(get_system_config('SHIFT_START_TIME'));
            $shift_endtime   = strtotime(get_system_config('SHIFT_END_TIME'));
            
            $tr_count = 0;
            
            for ($d = 1; $d <= 48; $d++) {

                    $past_time_bg = '';
                    $disabled =  '';
                    
                    $busy_slots = $this->service_model->get_busy_slots($this->input->post('type'), $this->input->post('key'), $this->input->post('date'), $start);
                    
                    if(($busy_slots[0]->bookservice_fk > 0) && ($busy_slots[0]->bookservice_fk != $this->input->post('service_id'))) {
                        
                        $disabled =  'disabled';
                    }

                    if(strtotime(str_replace("/", "-", $this->input->post('date')).' '.$start) < gmt_to_local(gmdate("Y-m-d H:i:s"))) {
                        
                        $disabled =  'disabled';
                        $past_time_bg = 'past_day_bg';
                    }

                    $checked = (count($busy_slots) > 0) ? 'checked' : '';
                    $busy_in = $busy_slots[0]->service_title ? $busy_slots[0]->customer_name.'/ '.$busy_slots[0]->service_title.'/ '.$busy_slots[0]->contract.'/ '.$busy_slots[0]->department : '-';

                    
                    $shift_class = '';
                    
                    if(($d < 48) && ($shift_starttime <= strtotime($start)) && ($shift_endtime >= strtotime($end))) {

                        $shift_class = 'shift_timing';
                    }
                    
                    if(($checked == 'checked') || ($disabled != 'disabled') && ($checked != 'checked')) {
                        
                        $tr .= '<tr class="'.$past_time_bg.'">';
                        $tr .= '<td>'.$start.'</td>';
                        $tr .= '<td>'.$end.'</td>';
                        $tr .= '<td><input type="checkbox" '.$checked.' '.$disabled.' class="'.$shift_class.'" name="slots[]" value="'.$start.'"/></td>';
                        $tr .= '<td>'.$busy_in.'</td>';
                        $tr .= '</tr>';
                        
                        if($disabled != 'disabled') {
                            
                            $tr_count++;
                        }
                    }
                    
                    $start = $end;
                    $end = date("H:i", strtotime($start . " +30 minutes"));                    
            }

            if($tr_count > 0) {
                
                $tr .= '<tr>';
                $tr .= '<td colspan="4"><center><input type="button" name="book" class="btn green" onclick="book_now();" value="Book Now"/></center></td>';
                $tr .= '</tr>';
            }
            else{
                $tr .= '<tr>';
                $tr .= '<td colspan="4"><center style="color:red;">Booking not allowed for past days</center></td>';
                $tr .= '</tr>';
            }
            
            if($this->input->post('type') == 'employee') {
                
                $emp_name = emp_name($this->input->post('key')).' ('.emp_code($this->input->post('key')).')';
            }
            
            if($this->input->post('type') == 'vehicle') {
                
                $emp_name = vehicle_name($this->input->post('key'));
            }

            echo json_encode(array("resource_name" => $emp_name, "slots" => $tr));
    }



      public function available_calendar_slots()
    {
            $start = date("H:i", strtotime("00:00:00"));
            $end   = date("H:i", strtotime($start . " +30 minutes"));

            $tr  = '<tr><td colspan="3">';
                $tr .= '<input type="hidden" name="type" value="'.$this->input->post('type').'"/>';
                $tr .= '<input type="hidden" name="date" value="'.$this->input->post('date').'"/>';
                $tr .= '<input type="hidden" name="key" value="'.$this->input->post('key').'"/>';
            $tr .= '</td></tr>';

            $shift_starttime = strtotime(get_system_config('SHIFT_START_TIME'));
            $shift_endtime   = strtotime(get_system_config('SHIFT_END_TIME'));
            
            $tr_count = 0;
            
            for ($d = 1; $d <= 48; $d++) {

                    $past_time_bg = '';
                    $disabled =  '';
                    
                    $busy_slots = $this->service_model->get_busy_slots($this->input->post('type'), $this->input->post('key'), $this->input->post('date'), $start);
                    
                    if(($busy_slots[0]->bookservice_fk > 0) && ($busy_slots[0]->bookservice_fk != $this->input->post('service_id'))) {
                        
                        $disabled =  'disabled';
                    }

                    if(strtotime(str_replace("/", "-", $this->input->post('date')).' '.$start) < gmt_to_local(gmdate("Y-m-d H:i:s"))) {
                        
                        $disabled =  'disabled';
                        $past_time_bg = 'past_day_bg';
                    }

                    $checked = (count($busy_slots) > 0) ? 'checked' : '';
                    $busy_in = $busy_slots[0]->service_title ? '<span style="color:red;">'.$busy_slots[0]->customer_name.'/ '.$busy_slots[0]->service_title.'/ '.$busy_slots[0]->contract.'/ '.$busy_slots[0]->department.'</span>' : '<span style="color:green;">Available</span>';

                    
                    $shift_class = '';
                    
                    if(($d < 48) && ($shift_starttime <= strtotime($start)) && ($shift_endtime >= strtotime($end))) {

                        $shift_class = 'shift_timing';
                    }
                    
                    if(($checked == 'checked') || ($disabled != 'disabled') && ($checked != 'checked')){
                        
                        $tr .= '<tr class="'.$past_time_bg.'">';
                        $tr .= '<td>'.$start.'</td>';
                        $tr .= '<td>'.$end.'</td>';
                        $tr .= '<td> - </td>';
                        $tr .= '<td>'.$busy_in.'</td>';
                        $tr .= '</tr>';
                        
                        if($disabled != 'disabled') {
                            
                            $tr_count++;
                        }
                    }
                    
                    $start = $end;
                    $end = date("H:i", strtotime($start . " +30 minutes"));                    
            }

            if($tr_count > 0) {
                
                $tr .= '<tr>';
               
                $tr .= '</tr>';
            }
            else{
                $tr .= '<tr>';
                $tr .= '<td colspan="4"><center style="color:red;">Booking not allowed for past days</center></td>';
                $tr .= '</tr>';
            }
            

            
            if($this->input->post('type') == 'employee') {
                
                $emp_name = emp_name($this->input->post('key')).' ('.emp_code($this->input->post('key')).')';
            }
            
            if($this->input->post('type') == 'vehicle') {
                
                $emp_name = vehicle_name($this->input->post('key'));
            }

            echo json_encode(array("resource_name" => $emp_name, "slots" => $tr));
    }
    
    
    
    public function book_slots($edit_id)
    {
        echo $this->service_model->book_slots($edit_id);
    }
    
    
   /**
    * Add Service
    * 
    * @return void
    */
    public function add() 
    {
            $this->data['errors'] = '';

            if ($this->validate_form()) {

                $last_id = $this->service_model->create_service();

                redirect('admin/book_service/edit/'.$last_id);
            }

            $this->data['employees'] = $this->employee_model->fetch_row();
            $this->data['projects'] = $this->project_model->fetch_row();
            $this->data['vehicles'] = $this->vehicle_model->fetch_row();
            $this->data['departments'] = $this->department_model->fetch_row();

            $this->render('book_service/add');
    }
    
    
    
    /**
    * Edit Service
    * 
    * @return void
    */
    public function edit($edit_id) 
    {
            if ($this->validate_form($edit_id)) {
                
                if($this->service_model->update_service($edit_id)){
                    
                    $this->session->set_flashdata('success', 'Service has been updated successfully!');        
                    
                    redirect('admin/book_service/edit/'.$edit_id);
                }
                
            }

            $this->data['errors'] = '';

            $this->data['edit_id']     = $edit_id;
            $this->data['employees']   = $this->employee_model->fetch_row();
            $this->data['projects']    = $this->project_model->fetch_row();
            $this->data['vehicles']    = $this->vehicle_model->fetch_row();
            $this->data['departments'] = $this->department_model->fetch_row();

            $this->data['detail'] = $this->service_model->get_service($edit_id);

            $this->render('book_service/edit');
    }

    
   /**
    * Validation rule for unique service ( Edit Case )
    * 
    * @return bool
    */
    public function unique_service($value, $params) 
    {
        $value = clean_text($value);

        $this->form_validation->set_message('unique_service', 'The %s "' . $value . '" is already being used.');

        list($table, $field, $id) = explode(".", $params, 3);

        if($value != ""){

            $query = $this->db->select($field)->from($table)->where($field, $value)->where(array('id !=' => $id, 'deleted_at' => null))->limit(1)->get();

            if ($query->num_rows() > 0) {
                return false;
            } 
            else {
                return true;
            }    
        }
    }

    
    
   /**
    * Validation rule for unique service ( Add Case )
    * 
    * @return bool
    */
    public function unique_service_add($value, $params) 
    {
        $value = clean_text($value);
        
        $this->form_validation->set_message('unique_service_add', 'The %s "' . $value . '" is already being used.');

        list($table, $field, $id) = explode(".", $params, 3);

        if($value != "") {

            $query = $this->db->select($field)->from($table)->where($field, $value)->where(array('deleted_at' => null))->limit(1)->get();
            
            if ($query->num_rows() > 0) {
                return false;
            } 
            else {
                return true;
            }  
        }               
    }
    
    
    public function unique_service_edit($value, $params) 
    {
        $value = clean_text($value);
        
        $this->form_validation->set_message('unique_service_add', 'The %s "' . $value . '" is already being used.');

        list($table, $field, $id) = explode(".", $params, 3);

        if($value != "") {

            $query = $this->db->select($field)->from($table)->where($field, $value)->where(array('deleted_at' => null, 'id !=' => $id))->limit(1)->get();
            
            if ($query->num_rows() > 0) {
                return false;
            } 
            else {
                return true;
            }  
        }               
    }
    
    
    
   /**
    * Validate service form
    * 
    * @return bool
    */
    private function validate_form($edit_id = null) 
    {
        if ($this->input->post()) {
            
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
            
            $this->form_validation->set_rules('start_date', 'Initial Date', 'required|max_length[50]');
            $this->form_validation->set_rules('end_date', 'Ending Date', 'required|max_length[50]');
            
            if($edit_id > 0){
                $this->form_validation->set_rules('service_title', 'Service Title', 'max_length[100]|callback_unique_service_edit[bookservice.service_title.'.$edit_id.']');
            }
            else{
                $this->form_validation->set_rules('service_title', 'Service Title', 'max_length[100]|callback_unique_service_add[bookservice.service_title]');
            }
            
            
            $this->form_validation->set_rules('department', 'Department', 'required|max_length[100]');
            $this->form_validation->set_rules('project', 'Project', 'required|max_length[100]');

            return ($this->form_validation->run() === TRUE);
        }
    }

    
    
   /**
    * Delete service
    * 
    * @param $service_id int
    * @return void
    */
    public function delete($service_id) 
    {
        $this->service_model->deleteBooking($service_id);
    }
        
    
   /**
    * Delete service details
    * 
    * @param $id int
    * @param $service_id int
    * @return void
    */
    public function deleteDetail($id, $service_id) 
    {
        $this->service_model->deleteServiceDetail($service_id, $id);
    }
    
    
    
   /**
    * Display service detail page.
    * 
    * @param $service_id int
    * @return void
    */
    public function view_more($service_id) 
    {
        $this->data['service_id'] = $service_id;
        $this->data['service'] = $this->service_model->getEditRecord($service_id);
        $this->render('book_service/service_detail');
    }
    
    
   /**
    * Datatable AJAX Call for service listing.
    * 
    * @return json
    */
    public function service_ajax() 
    {
        if (isset($_REQUEST["customActionType"]) && $_REQUEST["customActionType"] == "group_action") {

            $ids = array_filter($_REQUEST['id']);

            switch ($_REQUEST['customActionName']) {
                case 'delete_all' : $this->service_model->deleteAll($ids);
                    break;
            }

            $records["customActionStatus"]  = "OK"; // pass custom message(useful for getting status of group actions)
            $records["customActionMessage"] = "Group action successfully has been completed. Well done!"; // pass custom message(useful for getting status of group actions)
        }

        $iTotalRecords  = $this->service_model->count_bookservice();
        $iDisplayLength = intval($_REQUEST['length']);
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
        $iDisplayStart  = intval($_REQUEST['start']);
        $sEcho          = intval($_REQUEST['draw']);

        $filter = array();

        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'filter') {

            if (isset($_REQUEST['filter_service_title']) && ($_REQUEST['filter_service_title'] != '')) {
                $filter['b.service_title'] = $_REQUEST['filter_service_title'];
            }

            if (isset($_REQUEST['filter_project_code']) && ($_REQUEST['filter_project_code'] != '')) {
                $filter['d.code'] = $_REQUEST['filter_project_code'];
            }
            
            if (isset($_REQUEST['filter_customer_name']) && ($_REQUEST['filter_customer_name'] != '')) {
                $filter['d.customer_name'] = $_REQUEST['filter_customer_name'];
            }

            if (isset($_REQUEST['filter_start_date']) && ($_REQUEST['filter_start_date'] != '')) {
                $filter['b.start_date'] = date("Y-m-d", strtotime(str_replace('/', '-', $_REQUEST['filter_start_date'])));
            }

            if (isset($_REQUEST['filter_end_date']) && ($_REQUEST['filter_end_date'] != '')) {
                $filter['b.end_date'] = date("Y-m-d", strtotime(str_replace('/', '-', $_REQUEST['filter_end_date'])));
            }

            if (isset($_REQUEST['filter_start_time']) && ($_REQUEST['filter_start_time'] != '')) {
                $filter['b.start_time'] = date("H:i:s", strtotime($_REQUEST['filter_start_time']));
            }

            if (isset($_REQUEST['filter_end_time']) && ($_REQUEST['filter_end_time'] != '')) {
                $filter['b.end_time'] = date("H:i:s", strtotime($_REQUEST['filter_end_time']));
            }

            if (isset($_REQUEST['filter_department_name']) && ($_REQUEST['filter_department_name'] != '')) {
                $filter['c.name'] = $_REQUEST['filter_department_name'];
            }

            if (isset($_REQUEST['filter_creaded_on']) && ($_REQUEST['filter_creaded_on'] != '')) {
                $filter['b.created_at'] = date("Y-m-d", strtotime(str_replace('/', '-', $_REQUEST['filter_creaded_on'])));
            }
        }


        $records = array();
        $records["data"] = array();

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        $services = $this->service_model->fetch_bookings($iDisplayStart, $iDisplayLength, $_REQUEST['order'][0], $filter);

        if (is_array($services)) {

            foreach ($services as $row) {

                $chkbox = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                                    <input type="checkbox" class="checkboxes" value="' . $row->id . '" />
                                                    <span></span>
                                                </label>';

                $actions  = '<a class="btn btn-outline btn-circle btn-sm purple" href="' . site_url('admin/book_service/edit/' . $row->id) . '"><i class="fa fa-edit"></i> Edit</a>';
              
                $actions .= '<a title="Delete" class="btn btn-outline btn-circle dark btn-sm red mt-sweetyalert"  href="javascript:;" onClick="return confirmation(\'' . site_url('admin/book_service/delete/' . $row->id . '') . '\');"><i class="fa fa fa-trash"></i>Delete</a>';

                $records["data"][] = array($chkbox, $row->service_title, $row->contract, $row->customer_name, $row->department, display_date($row->start_date), display_date($row->end_date), display_datetime($row->ut), $actions);
            }
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        echo json_encode($records);
    }

}
