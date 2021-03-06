<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Class for logistic reports
 * 
 * @author Sean Rock <sean@sparxitsolutions.com>
 * 
 * @version 1.0
 * @dated 20/02/2017
 */
class Reports_model extends CI_Model 
{

    public function __construct() 
    {
        parent::__construct();
    }
    
    
    public function count_row() 
    {
        return $this->db->where(array('deleted_at' => NULL))->count_all_results(TBL_SERVICE);
    }
    
    
    public function project_report() 
    {
            $report_date = date("Y-m-d");

            if($this->input->post('report_date')) {

                $report_date = $this->input->post('report_date');
            }

            $this->db->select(array('DISTINCT(b.contract_id)', 'c.code AS project'))
                        ->from(TBL_BOOKSERVICE_INFO . ' AS a')
                        ->join(TBL_BOOKSERVICE . ' AS b', 'b.id = a.bookservice_fk', 'left')
                        ->join(TBL_PROJECT . ' AS c', 'c.id = b.contract_id', 'left')
                        ->where(array('a.deleted_at' => NULL, "a.booking_date" => $report_date));

            $report = $this->db->order_by('b.contract_id')->get();

            if ($report->num_rows() > 0) {
                
                    $projects = $report->result();

                    foreach($projects as $row) {
                        
                            $this->db->select(array('DISTINCT(e.emp_name)', 'e.emp_pic', 'a.resource_id', 'b.contract_id', 'a.booking_date', 'b.service_title', 'c.name AS department_name'))
                                    ->from(TBL_BOOKSERVICE_INFO . ' AS a')
                                    ->join(TBL_BOOKSERVICE . ' AS b', 'b.id = a.bookservice_fk', 'left')
                                    ->join(TBL_DEPARTMENT . ' AS c', 'c.id = b.department_id', 'left')
                                    ->join(TBL_EMPLOYEE . ' AS e', 'e.id = a.resource_id AND a.resource_type="employee"', 'left')
                                    ->where(array('a.deleted_at' => NULL, "b.contract_id" => $row->contract_id, "a.booking_date" => $report_date, "a.resource_type" => "employee"));
    
                            $assign_employees = $this->db->order_by('a.booking_date', 'desc')->get()->result();
    
                            $row->assign_employees = array();
                            
                            foreach ($assign_employees as $obj1) {
                                
                                $this->db->select(array('a.start_time', 'a.booking_date'))
                                    ->from(TBL_BOOKSERVICE_INFO . ' AS a')
                                    ->join(TBL_BOOKSERVICE . ' AS b', 'b.id = a.bookservice_fk', 'left')
                                    ->where(array('a.deleted_at' => NULL, "b.contract_id" => $obj1->contract_id, "a.booking_date" => $obj1->booking_date, "a.resource_id" => $obj1->resource_id, "a.resource_type" => "employee"));
                                
                                $t_result1 = $this->db->order_by('a.booking_date', 'desc')->get();

                                $no_of_slots = $t_result1->num_rows();
                                
                                if($no_of_slots > 0){
                                    
                                    $obj1->timings = convertToHoursMins(($no_of_slots * 30));
                                }
                                else{
                                    
                                    $obj1->timings = 0;
                                }
                            }
                            
                            $row->assign_employees = $assign_employees;

                            $this->db->select(array('DISTINCT(f.regn_number)', 'f.model', 'a.resource_id', 'a.booking_date', 'b.contract_id', 'b.service_title', 'c.name AS department_name'))
                                    ->from(TBL_BOOKSERVICE_INFO . ' AS a')
                                    ->join(TBL_BOOKSERVICE . ' AS b', 'b.id = a.bookservice_fk', 'left')
                                    ->join(TBL_DEPARTMENT . ' AS c', 'c.id = b.department_id', 'left')
                                    ->join(TBL_VEHICLE . ' AS f', 'f.id = a.resource_id AND a.resource_type="vehicle"', 'left')
                                    ->where(array('a.deleted_at' => NULL, "b.contract_id" => $row->contract_id, "a.booking_date" => $report_date, "a.resource_type" => "vehicle"));
    
                            $assign_vehicles = $this->db->order_by('a.booking_date', 'desc')->get()->result();
                            
                            $row->assign_vehicles = array();

                            foreach ($assign_vehicles as $obj2) {
                                
                                $this->db->select(array('a.start_time', 'a.booking_date'))
                                    ->from(TBL_BOOKSERVICE_INFO . ' AS a')
                                    ->join(TBL_BOOKSERVICE . ' AS b', 'b.id = a.bookservice_fk', 'left')
                                    ->where(array('a.deleted_at' => NULL, "b.contract_id" => $obj2->contract_id, "a.booking_date" => $obj2->booking_date, "a.resource_id" => $obj2->resource_id, "a.resource_type" => "vehicle"));
                                
                                $t_result2 = $this->db->order_by('a.booking_date', 'desc')->get();

                                $no_of_slots2 = $t_result2->num_rows();
                                
                                if($no_of_slots2 > 0){
                                    
                                    $obj2->timings = convertToHoursMins(($no_of_slots2 * 30));
                                }
                                else{
                                    
                                    $obj2->timings = 0;
                                }
                            }
                            
                            $row->assign_vehicles = $assign_vehicles;
                    }

                    return $projects;
            }
    }
    
    
    /**
     * @backup Old Version 
     * 
    public function project_report() 
    {
        $report_date = date("Y-m-d");

        if($this->input->post('report_date')) {

            $report_date = $this->input->post('report_date');
        }

        $this->db->select(array('a.*', 'b.service_title', 'd.code AS project', 'c.name AS department_name'))
                ->from(TBL_SERVICE_DETAILS . ' AS a')
                ->join(TBL_SERVICE . ' AS b', 'b.id = a.service_id', 'left')
                ->join(TBL_DEPARTMENT . ' AS c', 'c.id = b.department_id', 'left')
                ->join(TBL_PROJECT . ' AS d', 'd.id = b.project_id', 'left')
                ->where(array('a.deleted_at' => NULL));

        $this->db->where('a.service_date', $report_date);

        $report = $this->db->order_by('b.project_id')->get();

        if ($report->num_rows() > 0) {

            return $report->result();
        }
    }*/
    
    
    public function employee_report() 
    {
        $report_date = date("Y-m-d");
        
        if($this->input->post('report_date')) {
            
            $report_date = $this->input->post('report_date');
        }
        
        /** Get list of employees */
        
        $result = $this->db->select(array("GROUP_CONCAT(employee_id) AS employees"))->get_where(TBL_SERVICE_DETAILS, array( 'service_date' => $report_date, "deleted_at" => null));

        if ($result->num_rows() > 0) {

            $records = array();
            
            $employees = array_unique(explode(",", $result->row()->employees));
            
            sort($employees);
            
            /** Get report of each employee */
            
            foreach($employees as $emp_id) {
                
                $report = $this->db->select(array('a.id', 'a.service_date', 'a.start_time', 'a.end_time', 'a.employee_id', 'b.service_title', 'd.code AS project_name', 'c.name AS department_name'))
                            ->from(TBL_SERVICE_DETAILS . ' AS a')
                            ->join(TBL_SERVICE . ' AS b', 'b.id = a.service_id', 'left')
                            ->join(TBL_DEPARTMENT . ' AS c', 'c.id = b.department_id', 'left')
                            ->join(TBL_PROJECT . ' AS d', 'd.id = b.project_id', 'left')
                            ->where(array("FIND_IN_SET('$emp_id',a.employee_id) !=" => 0, 'a.service_date' => $report_date, 'a.deleted_at' => NULL))
                            ->order_by("id")->get();

                if($report->num_rows() > 0) {
                    
                    foreach($report->result() as $row){
                        
                        $row->emp_name = $this->db->select("emp_name")->get_where(TBL_EMPLOYEE, array("id" => $emp_id))->row()->emp_name;
                        $records[$emp_id][] = $row;
                    }
                }
                
            }

            return $records;
        }
    }
    
        
    public function get_weekly_report($from, $to, $type = 'employee')
    {
            if($type == 'employee') {
                
                return $this->employee_weekly_report($from, $to);
            }
            
            if($type == 'vehicle') {
                
                return $this->vehicle_weekly_report($from, $to);
            }

    }
        
    
    private function vehicle_weekly_report($from, $to)
    {
            $report = array();

            /** Get list of employees */
            $result = $this->db->select(array("id", "regn_number", "model"))->get_where(TBL_VEHICLE, array("status" => 'active', "deleted_at" => null));

            if ($result->num_rows() > 0) {

                    /** Get report of each employee */
                    foreach($result->result() as $obj)
                    {
                        $veh_id = $obj->id;

                        $report[$veh_id]['emp_name'] = $obj->regn_number;
                        $report[$veh_id]['emp_code'] = $obj->model;

                        /** Get weekly data */
                        for($d = $from; $d <= $to; $d = date("Y-m-d", strtotime($d." +1 day"))) {

                            $report[$veh_id][$d] = $this->get_vehicle_report($veh_id, $d);
                        }
                    }

            }

            return $report;
    }
        
    
    private function employee_weekly_report($from, $to)
    {
            $report = array();
            
            /** Get list of employees */
            $result = $this->db->select(array("id","emp_name"))->get_where(TBL_EMPLOYEE, array("status" => 'active', "left_company" => 0, "deleted_at" => null));

            if ($result->num_rows() > 0) {
                
                    /** Get report of each employee */
                    foreach($result->result() as $obj)
                    {
                        $emp_id = $obj->id;
                        
                        $report[$emp_id]['emp_name'] = $obj->emp_name;
                        $report[$emp_id]['emp_code'] = emp_code($emp_id);
                        
                        /** Get weekly data */
                        for($d = $from; $d <= $to; $d = date("Y-m-d", strtotime($d." +1 day"))) {
                            
                            $report[$emp_id][$d] = $this->get_employee_report($emp_id, $d);
                        }
                    }

            }

            return $report;
    }


    public function human_monthly_report_model($from, $to)
    {
            $report = array();

            /** Get list of employees */
            $result = $this->db->select(array("id","emp_name","monthly_hours"))->get_where(TBL_EMPLOYEE, array("status" => 'active', "left_company" => 0, "deleted_at" => null));

            if ($result->num_rows() > 0) {

                    /** Get report of each employee */
                    foreach($result->result() as $obj)
                    {
                        $emp_id = $obj->id;

                        $report[$emp_id]['emp_name'] = $obj->emp_name;
                        $report[$emp_id]['emp_code'] = emp_code($emp_id);
                        $report[$emp_id]['monthly_hours'] = $obj->monthly_hours;

                        /** Get weekly data */
                        for($d = $from; $d <= $to; $d = date("Y-m-d", strtotime($d." +1 day"))) {
                            
                            $report[$emp_id][$d] = $this->get_employee_human_data($emp_id, $d);
                        }
                    }

            }

            return $report;
    }

    
    
    private function get_employee_human_data($emp_id, $report_date)
    {
            $report = $this->db->select("id, entry_date, emp_id, remarks, total_hours, extra_hour, ut")
                        ->from(TBL_TIMESHEET)
                        ->where(array("emp_id" => $emp_id, 'deleted_at' => NULL, 'entry_date' => $report_date))
                        ->order_by("id")->get();

            if($report->num_rows() > 0) {

                return $report->row();
            }
    }
    
        
    private function get_employee_report($emp_id, $report_date)
    {
            $report = $this->db->select(array('COUNT(a.booking_id) AS slots', 'a.resource_type', 'a.booking_date', 'a.start_time', 'b.service_title', 'c.name AS department', 'd.code AS project_name', 'd.customer_name'))
                        ->from(TBL_BOOKSERVICE_INFO . ' AS a')
                        ->join(TBL_BOOKSERVICE . ' AS b', 'b.id = a.bookservice_fk', 'left')
                        ->join(TBL_DEPARTMENT . ' AS c', 'c.id = b.department_id', 'left')
                        ->join(TBL_PROJECT . ' AS d', 'd.id = b.contract_id', 'left')
                        ->where(array("a.resource_id" => $emp_id, "a.resource_type" => "employee", 'a.booking_date' => $report_date, 'a.deleted_at' => NULL))
                        ->group_by("d.code")
                        ->order_by("a.booking_id")->get();

            if($report->num_rows() > 0) {

                return $report->result();

            }
    }
    
    
    private function get_vehicle_report($veh_id, $report_date)
    {
            $report = $this->db->select(array('COUNT(a.booking_id) AS slots', 'a.resource_type', 'a.booking_date', 'a.start_time', 'b.service_title', 'c.name AS department', 'd.code AS project_name', 'd.customer_name'))
                        ->from(TBL_BOOKSERVICE_INFO . ' AS a')
                        ->join(TBL_BOOKSERVICE . ' AS b', 'b.id = a.bookservice_fk', 'left')
                        ->join(TBL_DEPARTMENT . ' AS c', 'c.id = b.department_id', 'left')
                        ->join(TBL_PROJECT . ' AS d', 'd.id = b.contract_id', 'left')
                        ->where(array("a.resource_id" => $veh_id, "a.resource_type" => "vehicle", 'a.booking_date' => $report_date, 'a.deleted_at' => NULL))
                        ->group_by("d.code")
                        ->order_by("a.booking_id")->get();

            if($report->num_rows() > 0) {

                return $report->result();
            }
    }
    
        
    public function vehicle_report() 
    {
        $report_date = date("Y-m-d");
        
        if($this->input->post('report_date')) {
            
            $report_date = $this->input->post('report_date');
        }
        
        /** Get list of employees */
        
        $result = $this->db->select(array("GROUP_CONCAT(vehicle_id) AS vehicles"))->get_where(TBL_SERVICE_DETAILS, array( 'service_date' => $report_date, "deleted_at" => null));

        if ($result->num_rows() > 0) {

            $records = array();
            
            $vehicles = array_unique(explode(",", $result->row()->vehicles));
            
            /** Get report of each employee */
            
            foreach($vehicles as $vehicle_id) {
                
                $report = $this->db->select(array('a.id', 'a.service_date', 'a.start_time', 'a.end_time', 'a.vehicle_id', 'b.service_title', 'd.code AS project_name', 'c.name AS department_name'))
                            ->from(TBL_SERVICE_DETAILS . ' AS a')
                            ->join(TBL_SERVICE . ' AS b', 'b.id = a.service_id', 'left')
                            ->join(TBL_DEPARTMENT . ' AS c', 'c.id = b.department_id', 'left')
                            ->join(TBL_PROJECT . ' AS d', 'd.id = b.project_id', 'left')
                            ->where(array("FIND_IN_SET('$vehicle_id',a.vehicle_id) !=" => 0, 'a.service_date' => $report_date, 'a.deleted_at' => NULL))
                            ->order_by("id")->get();

                if($report->num_rows() > 0) {
                    
                    foreach($report->result() as $row){
                        
                        $obj = $this->db->select("regn_number, model")->get_where(TBL_VEHICLE, array("id" => $vehicle_id))->row();
                        
                        $row->regn_number = $obj->regn_number;
                        $row->model_no    = $obj->model;
                        
                        $records[$vehicle_id][] = $row;
                    }
                }
                
            }

            return $records;
        }
    }
    
    
    /**
     * Get project cost report ( employee wise )
     * 
     * @return array
     */
    public function project_cost_report() 
    {
            $report = array();
            
            $emp_query  = $this->db->select('*')->from(TBL_EMPLOYEE)->where(array('status' => 'active', "left_company" => 0, 'deleted_at' => NULL))->get();
            $proj_query = $this->db->select('*')->from(TBL_PROJECT)->where(array('status' => 'active', 'deleted_at' => NULL))->get();

            /** Employee list loop */
            if ($emp_query->num_rows() > 0) {

                foreach($emp_query->result() as $eObj) {
                    
                        /** Project list loop */
                        if ($proj_query->num_rows() > 0) {

                            foreach($proj_query->result() as $pObj) {
                                
                                    $aQuery = $this->db->select(array('a.id', 'a.service_date', 'a.start_time', 'a.end_time'))
                                                ->from(TBL_SERVICE_DETAILS . ' AS a')
                                                ->join(TBL_SERVICE . ' AS b', 'b.id = a.service_id', 'left')
                                                ->join(TBL_DEPARTMENT . ' AS c', 'c.id = b.department_id', 'left')
                                                ->join(TBL_PROJECT . ' AS d', 'd.id = b.project_id', 'left')
                                                ->where(array("FIND_IN_SET('$eObj->id', a.employee_id) !=" => 0, "b.project_id" => $pObj->id, 'a.deleted_at' => NULL, 'b.deleted_at' => NULL))
                                                ->order_by("id")->get();
                                    
                                    if($aQuery->num_rows() > 0) {

                                            foreach ($aQuery->result() as $row) {

                                                // Get assigned time.
                                                $date1 = new DateTime($row->service_date.''.$row->start_time);
                                                $date2 = new DateTime($row->service_date.''.$row->end_time);

                                                $total_hrs = date_diff($date1, $date2);

                                                $row->assigned_minutes = (($total_hrs->h * 60) + $total_hrs->i); // Convert into minutes.

                                                // Get consumed time.
                                                $bQuery = $this->db->select("total_hours")->order_by("id")->get_where(TBL_TIMESHEET, array("entry_date" => $row->service_date, "emp_id" => $eObj->id, "deleted_at" => null));

                                                if($bQuery->num_rows() > 0){

                                                    $whr = explode(":", $bQuery->row()->total_hours);

                                                    $row->working_minutes  =  (intval($whr[0]) * 60) + intval($whr[1]); // Convert into minutes.
                                                }
                                                else{
   