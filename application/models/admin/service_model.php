<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Variants Class
 * @author Rajesh Kumar Yadav
 * @version 1.0
 * @dated 20/01/2017
 */
class Service_model extends CI_Model {

		public function __construct() {
			
			parent::__construct();
		}

		
		public function count_bookservice() 
		{
			return $this->db->where(array('deleted_at' => NULL))->count_all_results(TBL_BOOKSERVICE);
		}

    
		/*
		 * Function for get all landing page details
		 * @access public
		 * @param $query_data (string)
		 * @return array
		 */
		public function fetch_bookings($offset = 0, $limit = null, $order = null, $filter = null)
		{
				$this->db->select(array('b.id','b.service_title', 'b.start_date', 'b.end_date', 'c.name AS department', 'd.code AS contract', 'd.customer_name', 'b.deleted_at'))
						->from(TBL_BOOKSERVICE.' AS b')
						->join(TBL_DEPARTMENT.' AS c', 'c.id = b.department_id', 'left')
						->join(TBL_PROJECT.' AS d', 'd.id = b.contract_id', 'left');
				
				$this->db->where(array('b.deleted_at' => NULL));
				
				if($this->input->get('start_date')) {

					$start_date = date("Ymd", strtotime(str_replace("/", "-", $this->input->get('start_date'))));
					$end_date = date("Ymd", strtotime(str_replace("/", "-", $this->input->get('end_date'))));
					
					if($start_date == $end_date) {
						
						$this->db->where(array("b.start_date <=" => $start_date, "b.end_date >=" => $start_date));
					}
					else{
						$this->db->where("((`b`.`start_date` >=".$start_date." AND `b`.`start_date` <=".$end_date.") OR (`b`.`end_date` >=".$start_date." AND `b`.`end_date` <=".$end_date."))");
					}
				}

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

						case 1: $this->db->order_by('b.service_title', $order['dir']);  break;
						case 2: $this->db->order_by('contract', $order['dir']);         break;
						case 3: $this->db->order_by('b.start_date', $order['dir']);     break;
						case 4: $this->db->order_by('b.end_date', $order['dir']);       break;
						case 5: $this->db->order_by('department', $order['dir']);       break;
						case 6: $this->db->order_by('b.ut', $order['dir']);             break;
					}
				}

				$department = $this->db->get();

				if ($department->num_rows() > 0) {

					return $department->result();
				}
		}

    
    
    
    
		public function get_service($id) 
		{
			$query = $this->db->where(array('id' => $id, "deleted_at" => null))->get(TBL_BOOKSERVICE);
			
			if ($query->num_rows() > 0) {
				return $query->row();
			}
		}
    
    
    
    
        public function getEmployeeBookings()
        {
            $this->db->select("a.*, b.service_title, c.customer_name, c.code AS contract, d.name AS department");
            $this->db->from(TBL_BOOKSERVICE_INFO.' AS a');
            $this->db->join(TBL_BOOKSERVICE.' AS b', 'b.id = a.bookservice_fk', 'left');
            $this->db->join(TBL_PROJECT.' AS c', 'c.id = b.contract_id', 'left');
            $this->db->join(TBL_DEPARTMENT.' AS d', 'd.id = b.department_id', 'left');
            $this->db->where(array("a.resource_type" => 'employee', "a.deleted_at" => null));
            
            $query = $this->db->get();
            
            if ($query->num_rows() > 0) {

                return $query->result();
            }
        }
    
    
        /*
         * Function for Delete Landing page information
         * @access public
         * @param int ($id)
         * @return void
         */
        public function deleteRecord($service_id) 
        {
            if ($this->db->where('id', $service_id)->delete(TBL_BOOKSERVICE)) {

                $this->db->where('bookservice_fk', $service_id)->delete(TBL_BOOKSERVICE_INFO);
                $this->session->set_flashdata('success', 'Service has been deleted successfully!!!');
            } 
            else {

                $this->session->set_flashdata('error', 'There is some problem in deletion!!!');
            }

            redirect("admin/service/manage");
        }


        public function deleteBooking($service_id) 
        {
                if ($this->db->where('id', $service_id)->update(TBL_BOOKSERVICE, array('deleted_at' => GMT_DATE_TIME))) {

                    $this->db->where('bookservice_fk', $service_id)->update(TBL_BOOKSERVICE_INFO, array('deleted_at' => GMT_DATE_TIME));
                    $this->session->set_flashdata('success', 'Service has been deleted successfully!!!');
                    
                    redirect("admin/book_service/manage");
                } 
                else {
                    $this->session->set_flashdata('error', 'There is some problem in deletion!!!');
                }
        }
 
    
    
    public function deleteAll($ids) 
    {
        $ids = array_filter($ids);
        
        if ($this->db->where_in('id', $ids)->delete(TBL_BOOKSERVICE)) {
            
            $this->db->where_in('bookservice_fk', $ids)->delete(TBL_BOOKSERVICE_INFO);
                    
            $this->session->set_flashdata('success', 'Services has been deleted successfully!!!');
        } 
        else {
            $this->session->set_flashdata('error', 'There is some problem in deletion!!!');
        }
    }
    
    
    
    /************************************ New Functions ***************************************/
    
    private function get_weekly_off()
    {
        return explode(",", get_system_config('WEEKLY_OFF'));
    }
    
    
    private function emp_avail_html( $from_date, $to_date, $calendar = NULL, $working_only = false)
    {
        if($calendar == 'full_calendar'){

            $btn_title = ' Available';
        }
        else{

            $btn_title = ' book-now';
        }

        $holidays   = $this->get_holidays();
        $weekly_off = $this->get_weekly_off();

        $employees = $this->db->select(array('*'))->from(TBL_EMPLOYEE)->where(array('status' => 'active','deleted_at' => NULL))->get()->result();

        $table  = '<table width="100%" class="table table-striped table-bordered table-hover table-checkable">';

        $table .= '<tr>';
        $table .= '<th>Employees</th>';

        for ($d = $from_date; $d <= $to_date; $d = date("Y-m-d", strtotime($d . " +1 day"))) {

                $holiday_bg = (date("Ymd", strtotime($d)) < date("Ymd")) ? 'class="past_day_bg"' : '';

                if( is_array($weekly_off) && in_array(strtolower(date("D", strtotime($d))), $weekly_off) ){

                    $holiday_bg = 'class="weekly_off"';
                }
                else if((is_array($holidays) && in_array($d, $holidays))){

                    $holiday_bg = 'class="holiday_bg"';
                }

                $table .= '<th '.$holiday_bg.'><div class="text-center"><b>'.display_date($d).'<br/>'.date("D",strtotime($d)).'</b></div></th>';
        }

        $table .= '</tr>';

        $no_of_slots = 48;
        $we = 0;
        
        $shift_starttime = get_system_config('SHIFT_START_TIME');
		$shift_endtime   = get_system_config('SHIFT_END_TIME');
		
		$shift_hrs = dateDifference($shift_starttime , $shift_endtime , '%h');

        foreach($employees as $emp) {

            $total_busy_slots = 0;
            
            $tr  = '<tr>';
            $tr .= '<td><a target="_blank" href="'.site_url('admin/employee/edit/'.$emp->id).'">'.$emp->emp_name.' ('.emp_code($emp->id).')</a></td>';

            for ($d = $from_date; $d <= $to_date; $d = date("Y-m-d", strtotime($d . " +1 day"))) {

                $leaves = $this->get_leaves($emp->id, $d);

                $busy_slots = count($this->get_busy_slots('employee', $emp->id, $d));

                $total_busy_slots += $busy_slots;

                $pop_over = ' data-placement="top" data-trigger="hover" data-container="body" ';
                
                $extra_hrs = (($busy_slots / 2) - $shift_hrs);
                
                if(count($leaves) > 0) {

                    $status = '<span class="badge badge-danger badge-roundless popovers"><b>On Leave</b></span>';
                }
                else if($busy_slots >= $no_of_slots) {
					
					$pop_over .= ' data-original-title="Extra Hours : '.($extra_hrs > 0 ? $extra_hrs : 0).'" data-content="Click to open time-slots." ';

                    $status = '<a class="popovers" '.$pop_over.' href="javascript:show_time_slots(&#39;'.display_date($d).'&#39;, &#39;employee&#39;,&#39;'.$emp->id.'&#39;);"><span class="badge badge-danger badge-roundless"><b>Unavailable</b></span></a>';
                }
                else if($busy_slots > 0) {
					
					$pop_over .= ' data-original-title="Extra Hours : '.($extra_hrs > 0 ? $extra_hrs : 0).'" data-content="Click to open time-slots." ';

                    $status = '<a class="popovers" '.$pop_over.' href="javascript:show_time_slots(&#39;'.display_date($d).'&#39;, &#39;employee&#39;,&#39;'.$emp->id.'&#39;);"><span class="badge badge-warning"><b>'.(($no_of_slots - $busy_slots)/2).'</b></span>'.$btn_title.'</a>';
                }
                else {
					
					$pop_over .= ' data-original-title="Extra Hours : '.($extra_hrs > 0 ? $extra_hrs : 0).'" data-content="Click to open time-slots." ';
					
                    $status = '<a class="popovers" '.$pop_over.' href="javascript:show_time_slots(&#39;'.display_date($d).'&#39;, &#39;employee&#39;,&#39;'.$emp->id.'&#39;);"><span class="badge badge-success"><b>'.(($no_of_slots - $busy_slots)/2).'</b></span>'.$btn_title.'</a>';
                }

                $holiday_bg = (date("Ymd", strtotime($d)) < date("Ymd")) ? 'class="past_day_bg"' : '';

                if( is_array($weekly_off) && in_array(strtolower(date("D", strtotime($d))), $weekly_off) ) {

                    $holiday_bg = 'class="weekly_off"';
                }
                else if((is_array($holidays) && in_array($d, $holidays))) {

                    $holiday_bg = 'class="holiday_bg"';
                }

                $tr .= '<td '.$holiday_bg.'><div class="text-center">'.$status.'</div></td>';                            
            }

            $tr .= '</tr>';

            if(($working_only == true) && !($total_busy_slots > 0)){
				
                $tr = '';
            }
            else{
				
				$we++;	
			}

            $table .= $tr;
        }
        
		if(($working_only == true) && ($we == 0)) {
			
			$table .= '<tr><td><p class="error">No Employee Found.</p></td></tr>';
        }

        $table .= '</table>';

        return $table;
    }
    
    
    
    public function get_busy_slots($resource_type, $id, $date, $time = null)
    {
            $this->db->select("a.*, b.service_title, c.customer_name, c.code AS contract, d.name AS department");
            $this->db->from(TBL_BOOKSERVICE_INFO.' AS a');
            $this->db->join(TBL_BOOKSERVICE.' AS b', 'b.id = a.bookservice_fk', 'left');
            $this->db->join(TBL_PROJECT.' AS c', 'c.id = b.contract_id', 'left');
            $this->db->join(TBL_DEPARTMENT.' AS d', 'd.id = b.department_id', 'left');
            $this->db->where(array(
                                "a.resource_type" => $resource_type,
                                "a.booking_date" => date("Y-m-d", strtotime(str_replace("/", "-", $date))),
                                "a.resource_id" => $id,
                                "a.deleted_at" => null
                            ));
            
            if($time) {
                $this->db->where("a.start_time", date("H:i:s", strtotime($time)));
            }
            
            $sql = $this->db->get();

            //echo $this->db->last_query();
            
            if($sql->num_rows() > 0){
                
                return $sql->result();    
            }
    }
    
    
    
    private function vehicle_avail_html($from_date, $to_date, $calendar = null, $working_only = false)
    {
            if($calendar=='full_calendar'){

                $btn_title = ' Available';

            }else{

                $btn_title = ' book-now';
            }

            $holidays = $this->get_holidays();
            $weekly_off = $this->get_weekly_off();
        
            $vehicles  = $this->db->select(array('*'))->from(TBL_VEHICLE)->where(array('status' => 'active','deleted_at' => NULL))->get()->result();
        
            $table = '<table width="100%" class="table table-striped table-bordered table-hover table-checkable">';
            
            $table .= '<tr>';
            $table .= '<th>Vehicles</th>';
            
            for ($d = $from_date; $d <= $to_date; $d = date("Y-m-d", strtotime($d . " +1 day"))) {

                $holiday_bg = (date("Ymd", strtotime($d)) < date("Ymd")) ? 'class="past_day_bg"' : '';
                
                if( is_array($weekly_off) && in_array(strtolower(date("D", strtotime($d))), $weekly_off) ){

                    $holiday_bg = 'class="weekly_off"';
                }
                else if((is_array($holidays) && in_array($d, $holidays))){

                    $holiday_bg = 'class="holiday_bg"';
                }
                
                $table .= '<th '.$holiday_bg.'><div class="text-center"><b>'.display_date($d).'<br/>'.date("D",strtotime($d)).'</b></div></th>';
            }
            
            $table .= '</tr>';
            
            $no_of_slots = 48;
            $wv = 0;
            
            $shift_starttime = get_system_config('SHIFT_START_TIME');
			$shift_endtime   = get_system_config('SHIFT_END_TIME');
			
			$shift_hrs = dateDifference($shift_starttime , $shift_endtime , '%h');
            
            foreach($vehicles as $vhl) {

                    $tr = '<tr>';
                    $tr .= '<td>'.$vhl->regn_number.' ('.$vhl->model.')</td>';
                    
                    $total_busy_slots = 0;

                    for ($d = $from_date; $d <= $to_date; $d = date("Y-m-d", strtotime($d . " +1 day"))) {
                        
                        $checkup = $this->get_checkups($vhl->id, $d);
                        
                        $busy_slots = count($this->get_busy_slots('vehicle', $vhl->id, $d));
                        
                        $total_busy_slots += $busy_slots;
                        
                        $pop_over = ' data-placement="top" data-trigger="hover" data-container="body" ';
                        
                        $extra_hrs = (($busy_slots / 2) - $shift_hrs);
                        
                        if(count($checkup) > 0){

                            $status = '<span class="badge badge-danger badge-roundless"><b>Under Maintenance</b></span>';
                        }
                        else if($busy_slots >= $no_of_slots){
							
							$pop_over .= ' data-original-title="Extra Hours : '.($extra_hrs > 0 ? $extra_hrs : 0).'" data-content="Click to open time-slots." ';
                            
                            $status = '<a class="popovers" '.$pop_over.' href="javascript:show_time_slots(&#39;'.display_date($d).'&#39;, &#39;vehicle&#39;,&#39;'.$vhl->id.'&#39;);"><span class="badge badge-danger badge-roundless"><b>Unavailable</b></span></a>';
                        }
                        else if($busy_slots > 0){

							$pop_over .= ' data-original-title="Extra Hours : '.($extra_hrs > 0 ? $extra_hrs : 0).'" data-content="Click to open time-slots." ';

                            $status = '<a class="popovers" '.$pop_over.' href="javascript:show_time_slots(&#39;'.display_date($d).'&#39;, &#39;vehicle&#39;,&#39;'.$vhl->id.'&#39;);"><span class="badge badge-warning"><b>'.(($no_of_slots - $busy_slots)/2).'</b></span>'.$btn_title.'</a>';
                        }
                        else{
							
							$pop_over .= ' data-original-title="Extra Hours : '.($extra_hrs > 0 ? $extra_hrs : 0).'" data-content="Click to open time-slots." ';

                            $status = '<a class="popovers" '.$pop_over.' href="javascript:show_time_slots(&#39;'.display_date($d).'&#39;, &#39;vehicle&#39;,&#39;'.$vhl->id.'&#39;);"><span class="badge badge-success"><b>'.(($no_of_slots - $busy_slots)/2).'</b></span>'.$btn_title.'</a>';
                        }

                        $holiday_bg = (date("Ymd", strtotime($d)) < date("Ymd")) ? 'class="past_day_bg"' : '';
                        
                        if( is_array($weekly_off) && in_array(strtolower(date("D", strtotime($d))), $weekly_off) ){

                            $holiday_bg = 'class="weekly_off"';
                        }
                        else if((is_array($holidays) && in_array($d, $holidays))){

                            $holiday_bg = 'class="holiday_bg"';
                        }

                        $tr .= '<td '.$holiday_bg.'><div class="text-center">'.$status.'</div></td>';
                    }
    
                    $tr .= '</tr>';
                    
                    if(($working_only == true) && !($total_busy_slots > 0)){
                        $tr = '';
                    }
                    else{
						$wv++;
					}

                    $table .= $tr;
            }
            
            if(($working_only == true) && ($wv == 0)) {
				
				$table .= '<tr><td><p class="error">No Vehicle Found.</p></td></tr>';
            }
                    
            $table .= '</table>';
            
            return $table;
    }
    
    
    
    private function get_checkups($vehicle_id, $date)
    {
            $sql = $this->db->get_where(TBL_CHECKUP, array("vehicle_id" => $vehicle_id, "date" => $date));

            if($sql->num_rows() > 0) {

                return $sql->row();
            }
    }
    
    
    
    private function get_leaves($emp_id, $date)
    {
            $sql = $this->db->get_where(TBL_LEAVE, array("emp_id" => $emp_id, "date" => $date));

            if($sql->num_rows() > 0) {

                return $sql->row();
            }
    }
    
    
    private function get_holidays()
    {
            $qry = $this->db->select('GROUP_CONCAT(holiday_date) AS holidays')->from(TBL_HOLIDAY)->get();

            if ($qry->num_rows() > 0) {

                return explode(",", $qry->row()->holidays);
            }
    }
    
    
    public function get_availabilty()
    {
            $this->session->set_userdata("service_data", serialize($this->input->post()));
            
            $from_date = date("Y-m-d", strtotime(str_replace("/", "-", $this->input->post('start_date'))));
            $to_date   = date("Y-m-d", strtotime(str_replace("/", "-", $this->input->post('end_date'))));

            $emp_table = $this->emp_avail_html($from_date, $to_date);
            
            $vehicle_table = $this->vehicle_avail_html($from_date, $to_date);
            
            return json_encode(array("employee" => $emp_table, "vehicle" => $vehicle_table));
    }
    
    
    public function get_working_resources()
    {
            $this->session->set_userdata("service_data", serialize($this->input->post()));
            
            $from_date = date("Y-m-d", strtotime(str_replace("/", "-", $this->input->post('start_date'))));
            $to_date   = date("Y-m-d", strtotime(str_replace("/", "-", $this->input->post('end_date'))));

            $emp_table = $this->emp_avail_html($from_date, $to_date, null, true);
            
            $vehicle_table = $this->vehicle_avail_html($from_date, $to_date, null, true);
            
            return json_encode(array("employee" => $emp_table, "vehicle" => $vehicle_table));
    }



    public function show_calendar_model()
    {   
            $month = $this->input->post('month');
            $year = $this->input->post('year');

            $from_date = $year.'-'.$month.'-01';
            $to_date   = $year.'-'.$month.'-'.date('t');

            $emp_table = $this->emp_avail_html($from_date, $to_date,'full_calendar');
            
            $vehicle_table = $this->vehicle_avail_html($from_date, $to_date,'full_calendar');
            
            return json_encode(array("employee" => $emp_table, "vehicle" => $vehicle_table));
    }
    
    
    
    
    public function create_service()
    {
            $post = $this->input->post();

            $start_date = date("Y-m-d", strtotime(str_replace("/", "-", $post['start_date'])));
            $end_date   = date("Y-m-d", strtotime(str_replace("/", "-", $post['end_date'])));

            $book_service = array(
                                'start_date'    => $start_date,
                                'end_date'      => $end_date,
                                'service_title' => $post['service_title'],
                                'contract_id'   => $post['project'],
                                'department_id' => $post['department']
                            );

            $this->db->insert(TBL_BOOKSERVICE, $book_service);

            return $this->db->insert_id();
    }
    
    
    public function update_service($edit_id)
    {
            $post = $this->input->post();

            $start_date = date("Y-m-d", strtotime(str_replace("/", "-", $post['start_date'])));
            $end_date   = date("Y-m-d", strtotime(str_replace("/", "-", $post['end_date'])));

            $book_service = array(
                                'start_date'    => $start_date,
                                'end_date'      => $end_date,
                                'service_title' => $post['service_title'],
                                'contract_id'   => $post['project'],
                                'department_id' => $post['department']
                            );

            if($this->db->where("id", $edit_id)->update(TBL_BOOKSERVICE, $book_service)){

                return true;
            }
    }
    
    
    public function book_slots($edit_id)
    {
            $post = $this->input->post();

            $last_id = $edit_id;
            $date = date("Y-m-d", strtotime(str_replace("/", "-", $post['date'])));

            $flag = $this->db->where(array("bookservice_fk" => $last_id, 'booking_date'  => $date, "resource_type" => $post['type'], 'resource_id' => $post['key']))->delete(TBL_BOOKSERVICE_INFO);

            if($flag) {

                $assign_minutes = 0;
                
                foreach($post['slots'] as $slot) {
                    
                    $time = date("H:i:s", strtotime($slot));

                    if($this->slot_exists($time)) {

                        $flag = $this->db->insert(TBL_BOOKSERVICE_INFO, array(
                            'resource_type' => $post['type'],
                            'booking_date'  => $date,
                            'start_time'    => $time,
                            'resource_id'   => $post['key'],
                            'bookservice_fk'=> $last_id
                        ));
                    }

                    $assign_minutes += 30;
                }

                if($flag && ($post['type'] == 'employee')) {
                    
                    $assign_hours = convertToHoursMins($assign_minutes);
                    $this->create_timesheet($date, $post['key'], $assign_hours);
                }
                
                return $flag;
            }
    }
    
    
    
    private function create_timesheet($entry_date, $emp_id, $assign_hours)
    {
        if($this->db->get_where(TBL_TIMESHEET, array('entry_date' => $entry_date, 'emp_id' => $emp_id ))->num_rows() > 0){
            
            $flag = $this->db->where( array('entry_date' => $entry_date, 'emp_id' => $emp_id) )->update(TBL_TIMESHEET, array('assign_hours' => $assign_hours ));
        }
        else{
            
            $flag = $this->db->insert(TBL_TIMESHEET, array('entry_date' => $entry_date, 'emp_id' => $emp_id, 'assign_hours' => $assign_hours ));    
        }
        
        return $flag;
    }
    
    
    private function slot_exists($time)
    {
            $post = $this->input->post();

            $date = date("Y-m-d", strtotime(str_replace("/", "-", $post['date'])));

            $qry = $this->db->get_where(TBL_BOOKSERVICE_INFO, array(
                            'resource_type' => $post['type'],
                            'booking_date' => $date,
                            'start_time' => $time,
                            'resource_id' => $post['key'],
                            'deleted_at' => null
                        ));

            return !($qry->num_rows() > 0);
    }

}//end class

?>
