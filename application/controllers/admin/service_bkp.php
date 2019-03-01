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
class Service extends MY_Controller 
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
        $this->render('service/index');
    }
    
    
   /**
    * Display service availability calendar.
    * 
    * @return void
    */
    public function calendar($type = 'employee')
    {
        $this->data['data_type'] = $type;

        $this->render('service/calendar');
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

                    } else {

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

        //pr($this->data['sys_config']['SHIFT_START_TIME']); die;       

        $data = array();
        $buzy_in = array();
        $no_of_days = $this->input->get('month') ? date('t', strtotime($this->input->get('month'))) : date('t');

        $start_date = $this->input->get('month') ? $this->input->get('month') : date('Y-m-01');

        $all_employees = $this->employee_model->fetch_employees();

        if (!count($all_employees) > 0) {
                $output=json_encode(array('status'=>'no_record_found'));
                die($output);
        }

        // Loop for each day of current month.
        for ($i = 0; $i < ($no_of_days); $i++) {

            $service_date = date("Y-m-d", strtotime($start_date . " + " . $i . " day"));

            $available_emp = explode(',', $this->service_model->get_available_employees($service_date));

            foreach ($all_employees as $emp) {

                if ($emp->status == 'active') {

                    $obj = $this->service_model->getEmployeeServices($emp->id, $service_date);

                    if (in_array($emp->id, $available_emp)) {

                        $buzy_in = 'available';
                        $bgcolor = '#2E8B57';
                        $start_time=date("c", strtotime($service_date . ' ' .$this->data['sys_config']['SHIFT_START_TIME']));
                        $end_time=date("c", strtotime($service_date . ' ' . $this->data['sys_config']['SHIFT_END_TIME']));

                    } else {

                        $buzy_in = $obj->service_title;
                        $bgcolor = '#FF0000';

                        $start_time=date("c", strtotime($service_date . ' ' . (isset($obj->start_time) ? $obj->start_time : '00:00:00')));
                        $end_time=date("c", strtotime($service_date . ' ' . (isset($obj->end_time) ? $obj->end_time : '00:00:00')));
                    }



                    $data[] = array(
                        'title' =>  $emp->emp_name . ' ('.emp_code($emp->id).')',
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
    * Add Service
    * 
    * @return void
    */
    public function add() 
    {
        $this->data['errors'] = '';

        if ($this->input->post()) {

            $post = $this->input->post();

            $this->data['selected_employees'] = isset($post['employees']) ? $post['employees'] : array();
            $this->data['selected_vehicles'] = isset($post['vehicles']) ? $post['vehicles'] : array();



            if ($this->validate_form() == TRUE) {

                $start_date = date("Y-m-d", strtotime($post['start_date']));
                $end_date = date("Y-m-d", strtotime($post['end_date']));

                $this->session->set_flashdata('error', '');

                $errors = array();

                // Employee availability check
                if (count($post['employees']) > 0) {

                    $avail_employees = $this->service_model->get_available_employees($start_date, $end_date);

                    foreach ($post['employees'] as $emp_id) {

                        if (!in_array(intval($emp_id), explode(",", $avail_employees))) {

                            $errors[] = 'Employee "' . $this->employee_model->get_name($emp_id) . '" is not available between selected date range ( ' . date("d/m/Y", strtotime($start_date)) . ' - ' . date("d/m/Y", strtotime($end_date)) . ' )';
                        }
                    }
                }

                // Vehicle availability check.
                if (count($post['vehicles']) > 0) {

                    $avail_vehicles = $this->service_model->get_available_vehicles($start_date, $end_date);

                    foreach ($post['vehicles'] as $vehicle_id) {

                        if (!in_array(intval($vehicle_id), explode(",", $avail_vehicles))) {

                            $errors[] = 'Vehicle "' . $this->vehicle_model->get_name($vehicle_id) . '" is not available between selected date range ( ' . date("d/m/Y", strtotime($start_date)) . ' - ' . date("d/m/Y", strtotime($end_date)) . ' )';
                        }
                    }
                }


                if (count($errors) > 0) {
                    $this->data['errors'] = implode("<br/>", $errors);
                } else {
                    $status = $this->service_model->add_service();
                    redirect('admin/service/manage');
                }
            }
        }

        $this->data['employees'] = $this->employee_model->fetch_row();
        $this->data['projects'] = $this->project_model->fetch_row();
        $this->data['vehicles'] = $this->vehicle_model->fetch_row();
        $this->data['departments'] = $this->department_model->fetch_row();

        $this->render('service/add');
    }

    
    
   /**
    * Edit Service
    * 
    * @return void
    */
    public function edit($edit_id) 
    {
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->form_validation->set_rules('service_title', 'Service Title', 'max_length[100]|callback_unique_service[services.service_title.' . $edit_id . ']');
        $this->form_validation->set_rules('start_time', 'Initial Time', 'required|max_length[50]');
        $this->form_validation->set_rules('end_time', 'Ending Time', 'required|max_length[50]');
        $this->form_validation->set_rules('service_desc', 'Description', 'max_length[500]');

        if ($this->form_validation->run() === TRUE) {
            $status = $this->service_model->updateService($edit_id);
            redirect('admin/service/manage');
        } 
        else {
            $this->data['detail'] = $this->service_model->getEditRecord($edit_id);
            $this->render('service/edit');
        }
    }

    
    
   /**
    * Validation rule for unique service ( Edit Case )
    * 
    * @return bool
    */
    public function unique_service($value, $params) 
    {


        $value=clean_text($value);
        $this->form_validation->set_message('unique_service', 'The %s "' . $value . '" is already being used.');

        list($table, $field, $id) = explode(".", $params, 3);

        if($value!=""){
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

         $value=clean_text($value);
        $this->form_validation->set_message('unique_service_add', 'The %s "' . $value . '" is already being used.');

        list($table, $field, $id) = explode(".", $params, 3);

            if($value!=""){

                $query = $this->db->select($field)->from($table)->where($field, $value)->where(array('deleted_at' => null))->limit(1)->get();
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
    private function validate_form() 
    {


        if ($this->input->post()) {
            
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
            $this->form_validation->set_rules('service_title', 'Service Title', 'callback_unique_service_add[services.service_title.' . $this->input->post('service_title') . ']');
            $this->form_validation->set_rules('start_date', 'Initial Date', 'required|max_length[50]');
            $this->form_validation->set_rules('end_date', 'Ending Date', 'required|max_length[50]');
            $this->form_validation->set_rules('employees', 'Employee', 'required|max_length[100]');
            $this->form_validation->set_rules('vehicles', 'Vehicle', 'max_length[100]');
            $this->form_validation->set_rules('department', 'Department', 'required|max_length[100]');
            $this->form_validation->set_rules('project', 'Project', 'required|max_length[100]');
            $this->form_validation->set_rules('start_time', 'Initial Time', 'required|max_length[50]');
            $this->form_validation->set_rules('end_time', 'Ending Time', 'required|max_length[50]');
            $this->form_validation->set_rules('service_desc', 'Description', 'max_length[500]');

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
        $this->service_model->deleteRecord($service_id);
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
        $this->render('service/service_detail');
    }

    
    
   /**
    * Get list of assigned vehicles. ( Popup Modal )
    * 
    * @return html
    */
    public function show_vehicles() 
    {
        if ($this->input->post()) {


            $this->load->model('vehicle_model');

            $data['assign_id'] = $this->input->post('assign_id');

            // Get Service info.
            $service = $this->service_model->getServiceDetail($data['assign_id']);

            // Get list of assigned vehicles.
            $data['vehicles'] = $this->vehicle_model->fetch_row(explode(",", $this->input->post('ids')));

            // Get list of available vehicles.
            $avail_vehicles = $this->service_model->get_available_vehicles($service->service_date);

            $data['free_vehicles'] = $this->vehicle_model->fetch_row(explode(",", $avail_vehicles));

            $data['service_date'] = $service->service_date;

            $this->parser->parse('admin/service/modal_popup_vehicle_table.html', $data);
        }
    }

    
    
   /**
    * Get list of assigned employees. ( Popup Modal )
    * 
    * @return html
    */
    public function show_employees() 
    {
        if ($this->input->post()) {

            $this->load->model('employee_model');

            $data['assign_id'] = $this->input->post('assign_id');

            // Get Service info.
            $service = $this->service_model->getServiceDetail($data['assign_id']);

            // Get list of assigned employees.
            $data['employees'] = $this->employee_model->fetch_row(explode(",", $this->input->post('ids')));

            // Get list of available employees.
            $avail_employees = $this->service_model->get_available_employees($service->service_date);

            $data['free_employee'] = $this->employee_model->fetch_row(explode(",", $avail_employees));

            $data['service_date'] = $service->service_date;

            $this->parser->parse('admin/service/modal_popup_emp_table.html', $data);
        }
    }

   
    
   /**
    * Add employee to service. ( Popup Modal )
    * 
    * @return html
    */
    public function assign_employee() 
    {
        if ($this->input->post()) {
            $sd = $this->service_model->getServiceDetail($this->input->post('assign_id'));

            $all_emp = $this->service_model->addServiceEmployee();

            $ids = explode(",", $all_emp);
            $data['employees'] = $this->employee_model->fetch_row($ids);
            $data['assign_id'] = $this->input->post('assign_id');

            $free_employee = $this->service_model->get_available_employees($sd->service_date);

            $data['free_employee'] = $this->employee_model->fetch_row(explode(",", $free_employee));

            $this->parser->parse('admin/service/modal_popup_emp_table.html', $data);
        }
    }

   
    
   /**
    * Add vehicle to service. ( Popup Modal )
    * 
    * @return html
    */
    public function assign_vehicle() 
    {
        if ($this->input->post()) {
            $sd = $this->service_model->getServiceDetail($this->input->post('assign_id'));

            $all_veh = $this->service_model->addServiceVehicle();

            $ids = explode(",", $all_veh);
            $data['vehicles'] = $this->vehicle_model->fetch_row($ids);
            $data['assign_id'] = $this->input->post('assign_id');

            $free_vehicles = $this->service_model->get_available_vehicles($sd->service_date);

            $data['free_vehicles'] = $this->vehicle_model->fetch_row(explode(",", $free_vehicles));

            $this->parser->parse('admin/service/modal_popup_vehicle_table.html', $data);
        }
    }

    
    
   /**
    * Remove employee from service. ( Popup Modal )
    * 
    * @return html
    */
    public function unassign_employee() 
    {
        if ($this->input->post()) {
            $sd = $this->service_model->getServiceDetail($this->input->post('assign_id'));

            $left_emp = $this->service_model->removeServiceEmployee();

            $this->load->library('parser');
            $this->load->model('employee_model');

            $ids = explode(",", $left_emp);

            $data['employees'] = $this->employee_model->fetch_row($ids);
            $data['assign_id'] = $this->input->post('assign_id');

            $free_employee = $this->service_model->get_available_employees($sd->service_date);

            $data['free_employee'] = $this->employee_model->fetch_row(explode(",", $free_employee));

            $this->parser->parse('admin/service/modal_popup_emp_table.html', $data);
        }
    }

    
    
   /**
    * Remove vehicle from service. ( Popup Modal )
    * 
    * @return html
    */
    public function unassign_vehicle() 
    {
        if ($this->input->post()) {
            $sd = $this->service_model->getServiceDetail($this->input->post('assign_id'));

            $left_vehicles = $this->service_model->removeServiceVehicle();

            $this->load->library('parser');
            $this->load->model('employee_model');

            $ids = explode(",", $left_vehicles);
            $data['vehicles'] = $this->vehicle_model->fetch_row($ids);
            $data['assign_id'] = $this->input->post('assign_id');

            $free_vehicles = $this->service_model->get_available_vehicles($sd->service_date);

            $data['free_vehicles'] = $this->vehicle_model->fetch_row(explode(",", $free_vehicles));


            $this->parser->parse('admin/service/modal_popup_vehicle_table.html', $data);
        }
    }

    
    
   /**
    * Datatable AJAX Call for service detail listing.
    * 
    * @param $service_id int
    * @return json
    */
    public function service_detail_ajax($service_id) 
    {
        if(!$service_id){
            $output=json_encode(array('status'=>'id_error'));
            die($output);
        }
        

        if (isset($_REQUEST["customActionType"]) && $_REQUEST["customActionType"] == "group_action") {

            $ids = array_filter($_REQUEST['id']);

            switch ($_REQUEST['customActionName']) {
                case 'delete_all' : $this->service_model->delete_All_Details($service_id);
                    break;
            }

            $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
            $records["customActionMessage"] = "Group action successfully has been completed. Well done!"; // pass custom message(useful for getting status of group actions)
        }

        $iTotalRecords = $this->service_model->count_row();
        $iDisplayLength = intval($_REQUEST['length']);
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
        $iDisplayStart = intval($_REQUEST['start']);
        $sEcho = intval($_REQUEST['draw']);

        $filter = array();

        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'filter') {

            if (isset($_REQUEST['filter_date_from']) && ($_REQUEST['filter_date_from'] != '')) {
                $filter['created_at'] = date("Y-m-d", strtotime(str_replace('/', '-', $_REQUEST['filter_date_from'])));
            }
        }

        $records = array();
        $records["data"] = array();

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        $services = $this->service_model->fetch_row($iDisplayStart, $iDisplayLength, $_REQUEST['order'][0], $service_id, $filter);

        if (is_array($services)) {

            foreach ($services as $row) {

                $chkbox = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                                    <input type="checkbox" class="checkboxes" value="1" />
                                                    <span></span>
                                                </label>';

                $no_of_employee = '<a data-toggle="modal" onclick="show_employees(&#39;' . $row->employee_id . '&#39;,&#39;' . $row->id . '&#39;);" href="javascript:void(0);">click to see.. (' . ( trim($row->employee_id) != '' ? count(explode(",", $row->employee_id)) : 0) . ')</a>';
                $no_of_vehicles = '<a data-toggle="modal" onclick="show_vehicles(&#39;' . $row->vehicle_id . '&#39;,&#39;' . $row->id . '&#39;);" href="javascript:void(0);">click to see.. (' . ( trim($row->vehicle_id) != '' ? count(explode(",", $row->vehicle_id)) : 0) . ')</a>';

                $actions = '<a title="Delete" class="btn btn-outline btn-circle dark btn-sm red mt-sweetyalert"  href="javascript:;" onClick="return confirmation(\'' . site_url('admin/service/deleteDetail/' . $row->id .'/'.$service_id. '') . '\');"><i class="fa fa fa-trash"></i>Delete</a>';

                $records["data"][] = array($chkbox, display_date($row->service_date), display_time($row->start_time), display_time($row->end_time), $row->project, $no_of_employee, $no_of_vehicles, display_datetime($row->ut), $actions);
            }
        }


        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        echo json_encode($records);
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

            $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
            $records["customActionMessage"] = "Group action successfully has been completed. Well done!"; // pass custom message(useful for getting status of group actions)
        }

        $iTotalRecords = $this->service_model->count_row();
        $iDisplayLength = intval($_REQUEST['length']);
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
        $iDisplayStart = intval($_REQUEST['start']);
        $sEcho = intval($_REQUEST['draw']);

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

        $services = $this->service_model->fetch_services($iDisplayStart, $iDisplayLength, $_REQUEST['order'][0], $filter);

        if (is_array($services)) {

            foreach ($services as $row) {

                $chkbox = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                                    <input type="checkbox" class="checkboxes" value="' . $row->id . '" />
                                                    <span></span>
                                                </label>';

                $actions = '<a class="btn btn-outline btn-circle btn-sm blue" href="' . site_url('admin/service/view_more/' . $row->id) . '"><i class="fa fa-search"></i> Details..</a>';
                $actions .= '<a class="btn btn-outline btn-circle btn-sm purple" href="' . site_url('admin/service/edit/' . $row->id) . '"><i class="fa fa-edit"></i> Edit</a>';
              
                $actions .= '<a title="Delete" class="btn btn-outline btn-circle dark btn-sm red mt-sweetyalert"  href="javascript:;" onClick="return confirmation(\'' . site_url('admin/service/delete/' . $row->id . '') . '\');"><i class="fa fa fa-trash"></i>Delete</a>';

                $records["data"][] = array($chkbox, $row->service_title, $row->project, $row->customer_name, $row->department, display_date($row->start_date), display_date($row->end_date), display_time($row->start_time), display_time($row->end_time), display_datetime($row->created_at), $actions);
            }
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        echo json_encode($records);
    }

    
    
   /**
    * Refresh employee dropdown. ( Add/Edit Service )
    * 
    * @return html
    */
    public function load_employee_dropdown() 
    {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');

        if ($this->input->post()) {

            $avail_employees = $this->service_model->get_available_employees($start_date, $end_date);
            ?>
            <div class="input-icon right">
                <i class="fa"></i>
            <?php
            $options = array();

            $employees = $this->employee_model->fetch_row(); //explode(",", $avail_employees)

            if (is_array($employees)) {

                foreach ($employees as $row) {

                    if ($row->status == 'active') {

                        $avail = (in_array($row->id, explode(",", $avail_employees))) ? 'A' : 'U';
                        $options[$row->id] = $row->emp_name . ' (' . emp_code($row->id) . ') - ' . $avail;
                    }
                }
            }

            echo form_multiselect('employees[]', $options, unserialize($this->input->post('selected')), 'class="bs-select form-control" data-live-search="true" data-size="8" id="employees" data-required="1"');
            ?>
                <span class="help-block error"> <?php echo form_error("employees"); ?> </span>
            </div>    
            <?php
        }
    }

    
    
   /**
    * Refresh vehicle dropdown. ( Add/Edit Service )
    * 
    * @return html
    */
    public function load_vehicle_dropdown() 
    {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');

        if ($this->input->post()) {
            $avail_vehicles = $this->service_model->get_available_vehicles($start_date, $end_date);
            ?>
            <div class="input-icon right">
                <i class="fa"></i>
            <?php
            $options = array();

            $vehicles = $this->vehicle_model->fetch_row(); //explode(",", $avail_employees)

            if (is_array($vehicles)) {

                foreach ($vehicles as $row) {

                    if ($row->status == 'active') {

                        $avail = (in_array($row->id, explode(",", $avail_vehicles))) ? 'A' : 'U';
                        $options[$row->id] = $row->regn_number . ' ( ' . $row->model . ' ) - ' . $avail;
                    }
                }
            }

            echo form_multiselect('vehicles[]', $options, unserialize($this->input->post('selected')), 'class="bs-select form-control" data-live-search="true" data-size="8" id="vehicles" data-required="1"');
            ?>
                <span class="help-block error"> <?php echo form_error("employees"); ?> </span>
            </div>
            <?php
        }
    }

}
