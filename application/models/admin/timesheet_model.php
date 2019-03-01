<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Variants Class
 * @author Rajesh Kumar Yadav
 * @version 1.0
 * @dated 20/01/2017
 */
class Timesheet_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function count_row() {
		
        return $this->db->where('deleted_at',NULL)->from(TBL_TIMESHEET)->count_all_results();
    }

    /*
     * Function for get all landing page details
     * @access public
     * @param $query_data (string)
     * @return array
     */
    public function fetch_timeheet_data($offset = 0, $limit = null, $order = null, $filter = null)
    {
            $this->db->select(array('b.*', 'c.emp_name'))
                    ->from(TBL_TIMESHEET.' AS b')                
                    ->join('ld_employees'.' AS c', 'c.id = b.emp_id', 'left')
                    ->where(array('b.deleted_at' => NULL));


            if(is_array($filter)){

                foreach ($filter as $column => $keyword){

                    $this->db->like($column, $keyword);
                }
            }

            if($limit){
                $this->db->limit($limit, $offset);
            }

            if(isset($order['column'])) {

                switch ($order['column']) {

                    case 1: $this->db->order_by('entry_date', $order['dir']); break;
                    case 2: $this->db->order_by('in_time', $order['dir']); break;
                    case 3: $this->db->order_by('out_time', $order['dir']); break;
                    case 4: $this->db->order_by('emp_id', $order['dir']); break;
                    case 5: $this->db->order_by('remarks', $order['dir']); break;
                    case 6: $this->db->order_by('ut', $order['dir']); break;
                }
            }

            $department = $this->db->get();

            if ($department->num_rows() > 0) {
                
                return $department->result();
            }
        
        
    }

    /*
     * Function for add new entry
     * @access public
     * @param array ($data)
     * @return true/false
     */


        public function add_timesheet_data($assign_hr) 
        {
                $post = $this->input->post();

                $working_hours  = explode(":", $post['working_hours']);
                $assign_hrs     = explode(":", $assign_hr);

                $working_minutes = ($working_hours[0] > 0) ? (($working_hours[0] * 60) + $working_hours[1]) : 0;
                $assign_minutes  = ($assign_hrs[0] > 0) ? (($assign_hrs[0] * 60) + $assign_hrs[1]) : 0;

                $extra_hours = ($working_minutes > $assign_minutes) ? convertToHoursMins(($working_minutes - $assign_minutes)) : '00:00';

                $data = array(
                    "entry_date"   => $post['entry_date'],
                    "emp_id"       => $post['employees'],
                    "remarks"      => $post['remark'],
                    "total_hours"  => $post['working_hours'],
                    "extra_hour"   => $extra_hours,
                    "assign_hours" => $assign_hr
                );
                
                if($this->db->insert(TBL_TIMESHEET, $data)){

                    $this->session->set_flashdata('success', 'Timesheet has been added successfully!');
                    redirect('admin/timesheet/manage');
                }
        }


        public function check_unique_add_time_model()
        {
                $this->db->select("id")->from(TBL_TIMESHEET);
                
                $this->db->where(array('emp_id' => $this->input->post('employees'), 'entry_date' => $this->input->post('entry_date'), 'deleted_at' => NULL));
                
                return ($this->db->get()->num_rows() > 0);
        }


        public function check_unique_edit_time_model()
        {
                $this->db->select("id")->from(TBL_TIMESHEET);
                $this->db->where(array('emp_id'=>$this->input->post('employees'),'entry_date'=>$this->input->post('entry_date'),'id !=' => $this->input->post('id'),'deleted_at'=>NULL));
                return $count=$this->db->get()->num_rows();	
        }



    /*
     * Function for get data for edit
     * @access public
     * @param int ($id)
     * @return array
     */

    public function getEditRecord($id) {
        
        $result = array();
        $query = $this->db->where('id', $id)->get(TBL_TIMESHEET);
        
        if ($query->num_rows() > 0) {
            return $query->row();
        }
    }

    
    /*
     * Function for Delete Landing page information
     * @access public
     * @param int ($id)
     * @return void
     */

    public function deleteRecord($timesheet_id) 
    {
        if ($this->db->where('id', $timesheet_id)->update(TBL_TIMESHEET, array('deleted_at' => GMT_DATE_TIME))) {

            $this->session->set_flashdata('success', 'Record has been deleted successfully!!!');
        } 
        else {
            $this->session->set_flashdata('error', 'There is some problem in deletion!!!');
        }
        
        redirect("admin/timesheet/manage");
    }
 
    
    public function updatetTimesheet($edit_id, $assign_hr) 
    {
            $post = $this->input->post();
            
            $working_hours = explode(":", $post['working_hours']);
            $assign_hrs    = explode(":", $assign_hr);

            $working_minutes = ($working_hours[0] > 0) ? (($working_hours[0] * 60) + $working_hours[1]) : 0;
            $assign_minutes  = ($assign_hrs[0] > 0) ? (($assign_hrs[0] * 60) + $assign_hrs[1]) : 0;

            $extra_hours = ($working_minutes > $assign_minutes) ? convertToHoursMins(($working_minutes - $assign_minutes)) : '00:00';

            $data = array(
                        "remarks"      => $post['remark'], 
                        "total_hours"  => $post['working_hours'], 
                        "extra_hour"   => $extra_hours,
                        "assign_hours" => $assign_hr 
                    );

            if($this->db->where(array('id' => $edit_id))->update(TBL_TIMESHEET, $data)) {

                $this->session->set_flashdata('success', 'Timesheet has been updated successfully!');

                redirect('admin/timesheet/manage');
            }
    }
    
    
    public function deleteAll($ids) 
    {
        $ids = array_filter($ids);
        
        if ($this->db->where_in('id', $ids)->update(TBL_TIMESHEET, array('deleted_at' => GMT_DATE_TIME))) {
        
            $this->session->set_flashdata('success', 'Record has been deleted successfully!!!');
        } 
        else {
            $this->session->set_flashdata('error', 'There is some problem in deletion!!!');
        }
    }
    
    
    public function delete_All_Details($ids) 
    {
		
        if ($this->db->where_in('service_id', $ids)->update(TBL_SERVICE_DETAILS, array('deleted_at' => GMT_DATE_TIME))){
                          
             $this->db->where_in('id', $ids)->update(TBL_SERVICE, array('deleted_at' => GMT_DATE_TIME));
                    
            $this->session->set_flashdata('success', 'Services has been deleted successfully!!!');
        } 
        else {
            $this->session->set_flashdata('error', 'There is some problem in deletion!!!');
        }
    }

    
    public function get_emp_assigned_hour($emp_id, $service_date)
    {
        $this->db->select(array("a.*"));
        $this->db->from(TBL_SERVICE_DETAILS." AS a");
        $this->db->join(TBL_SERVICE." AS b", "b.id = a.service_id", "left");
        $this->db->where(array("a.service_date" => $service_date, "a.deleted_at" => null, "b.deleted_at" => null));
        $this->db->where("FIND_IN_SET(".$emp_id.", a.employee_id)");
        $sql = $this->db->get();

        if($sql->num_rows() > 0) {

            $result = $sql->row();

            $start_time =  isset($result->start_time)?$result->start_time:'';	
            $end_time   =  isset($result->end_time)?$result->end_time:'';
            $datetime1  =  new DateTime($start_time);
            $datetime2  =  new DateTime($end_time);
            $interval   =  $datetime1->diff($datetime2);

            return $interval->h.":".$interval->i.":00";
        }
    }

    
    public function check_working_employees_model()
    {
            $entry_date = ($this->input->post('entry_date') ? $this->input->post('entry_date') : date("Y-m-d"));

            $qry = $this->db->select(array("DISTINCT(b.id)", "b.emp_pic", "b.emp_name", "b.status"))
                            ->from(TBL_BOOKSERVICE_INFO." AS a")
                            ->join(TBL_EMPLOYEE." AS b", "a.resource_id = b.id AND a.resource_type = 'employee'", 'left')
                            ->where(array('a.deleted_at' => null, 'a.booking_date' => $entry_date, "b.left_company" => 0))
                            ->get();

            return $qry->result();
    }
    

    public function check_emp_assign_time_model()
    {
            $entry_date = $this->input->post('entry_date');
            $emp_id     = $this->input->post('emp_id');

            $sql =$this->db->select('start_time,end_time')->from(TBL_SERVICE_DETAILS)->where("FIND_IN_SET('$emp_id',employee_id)!=",0)->where(array('service_date'=>$entry_date,'deleted_at' => NULL))->get();

            if($sql->num_rows() > 0) {
               return $result=$sql->row();
            }
    }

}

?>
