<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Variants Class
 * @author Rajesh Kumar Yadav
 * @version 1.0
 * @dated 20/01/2017
 */
class Employee_model extends CI_Model 
{
    public function __construct() 
    {
        parent::__construct();
    }

    public function count_row() 
    {
        return $this->db->where(array('deleted_at' => NULL))->count_all_results(TBL_EMPLOYEE);
    }
    
    
    public function count_leave_row($emp_id) 
    {
        return $this->db->where(array('emp_id' => $emp_id))->count_all_results(TBL_LEAVE);
    }

    /*
     * Function for get all landing page details
     * @access public
     * @param $query_data (string)
     * @return array
     */

    public function fetch_row($ids = null)
    {
        $this->db->select('*')->from(TBL_EMPLOYEE)->where(array('deleted_at' => NULL));
        
        if(is_array($ids) && (count($ids) > 0)){
            $this->db->where_in('id', $ids);
        }
        
        $query = $this->db->order_by('id', 'asc')->get();

        if ($query->num_rows() > 0) {
            
            return $query->result();
        }
    }
    
    
    public function get_working_employees($d = null)
    {
            $date = $d ? $d : date("Y-m-d");
        
            $query = $this->db->select(array('GROUP_CONCAT(employee_id) AS employees'))->from('services_detail')->where( array('service_date' => date("Y-m-d", strtotime($date)), 'deleted_at' => NULL) );

            //echo $this->db->last_query();
            
            if ($query->num_rows() > 0) {
                
                //echo $query->row()->employees; die;
            }
    }
    
    
    public function fetch_employees($offset = 0, $limit = null, $order = null, $filter = null)
    {
        $this->db->select('*')->from(TBL_EMPLOYEE)->where(array('deleted_at' => NULL));
        
        if(is_array($filter)){
            
            foreach ($filter as $column => $keyword){
                $this->db->like($column, $keyword);
                
                if($column == 'status'){
                    $this->db->where($column, $keyword);
                }
            }
        }
        
        if($limit){
            $this->db->limit($limit, $offset);
        }
                
        if(isset($order['column'])) {
            
            switch ($order['column']) {
                case 1: $this->db->order_by('id', $order['dir']); break;
                case 2: $this->db->order_by('emp_name', $order['dir']); break;
                case 3: $this->db->order_by('state', $order['dir']); break;
                case 4: $this->db->order_by('contract', $order['dir']); break;
                case 5: $this->db->order_by('category', $order['dir']); break;
                case 6: $this->db->order_by('created_at', $order['dir']); break;
                default: $this->db->order_by('id', 'desc'); break;
            }
        }
        
        $department = $this->db->get();

        if ($department->num_rows() > 0) {
            return $department->result();
        }
    }
    
    
    public function fetch_leave($emp_id, $offset = 0, $limit = null, $order = null, $filter = null)
    {
            $this->db->select('*')->from(TBL_LEAVE)->where("emp_id", $emp_id);

            if($limit){
                $this->db->limit($limit, $offset);
            }

            if(isset($order['column'])) {

                switch ($order['column']) {
                    case 1: $this->db->order_by('date', $order['dir']); break;
                    case 2: $this->db->order_by('reason', $order['dir']); break;

                    default: $this->db->order_by('id', 'desc'); break;
                }
            }

            $sql = $this->db->get();

            if ($sql->num_rows() > 0) {
                return $sql->result();
            }
    }

    /*
     * Function for add Department page information
     * @access public
     * @param array ($data)
     * @return true/false
     */

    public function add_employee($picture = '') 
    {
            $post = $this->input->post();

            $data = array(
                "emp_pic" => $picture,
                "emp_name" => clean_text($post['emp_name']),
                "state" => $post['state'],
                "contract" => $post['contract'],
                "monthly_hours" => $post['monthly_hours'],
                "category" => $post['category'],
                "status" => $post['status'],
                "added_by"   => $this->session->userdata(SITE_SESSION_NAME . 'ADMINID'),
                "created_at" => GMT_DATE_TIME,
                "updated_at" => GMT_DATE_TIME
            );

            $flag = $this->db->insert(TBL_EMPLOYEE, $data);

            if($flag){
                $this->session->set_flashdata('success', 'Employee has been added successfully!');        
                return $flag;
            }
    }

    /*
     * Function for update department information
     * @access public
     * @param array ($data)
     * @return true/false
     */

    public function update_employee($id, $picture = '') 
    {
            $post = $this->input->post();

            $data = array(
                "emp_name"  => clean_text($post['emp_name']),
                "state"     => $post['state'],
                "contract"  => $post['contract'],
                "monthly_hours" => $post['monthly_hours'],
                "category"  => $post['category'],
                "status"    => $post['status'],
                "left_company" => (isset($post['left_company']) ? 1 : 0),
                "updated_at" => GMT_DATE_TIME
            );

            if($picture) {

                $data['emp_pic'] = $picture;
            }

            $this->db->where('id', $id)->update(TBL_EMPLOYEE, $data);
            $this->session->set_flashdata('success', 'Employee has been updated successfully!');
            return TRUE;
    }
    
    
    public function add_leave($emp_id) 
    {
            $post = $this->input->post();

            $post['start_date'] = str_replace("/", "-", $post['start_date']);
            $post['end_date'] = str_replace("/", "-", $post['end_date']);
            
            $service_date = strtotime($post['start_date']);
            
            if(strtotime($post['start_date']) == strtotime($post['end_date'])) {

                $data = array("date" => date("Y-m-d", $service_date), "reason" => clean_text($post['reason']), "emp_id" => $emp_id);
                $this->db->insert(TBL_LEAVE, $data);
            }
            else if(strtotime($post['end_date']) > strtotime($post['start_date'])) {

                $i = 0;
                
                while ($service_date < strtotime($post['end_date'])) {

                    $service_date = strtotime($post['start_date']." +".$i." days");

                    $data = array("date" => date("Y-m-d", $service_date), "reason" => clean_text($post['reason']), "emp_id" => $emp_id);
                    $this->db->insert(TBL_LEAVE, $data);
                    
                    $i++;
                }
            }

            $this->session->set_flashdata('success', 'Leave has been marked successfully!');
    }

    
    /**
     * Function for get Department for edit
     * @access public
     * @param int ($id)
     * @return array
     */
    public function getEditRecord($id) 
    {
            $result = array();

            $query = $this->db->where('id', $id)->get(TBL_EMPLOYEE);

            if ($query->num_rows() > 0) {

                foreach ($query->result_array() as $value) {

                    $result = $value;
                }
            }

            return $result;
    }
    
    
    public function get_leaves($id) 
    {
            $query = $this->db->where('emp_id', $id)->get(TBL_LEAVE);

            if ($query->num_rows() > 0) {

                return $query->result();
            }
    }
    
    
    public function get_nos($employee_id)
    {
        $query = $this->db->query("SELECT b.id FROM ".TBL_BOOKSERVICE_INFO." AS a LEFT JOIN ".TBL_BOOKSERVICE." AS b ON(b.id = a.bookservice_fk) WHERE b.deleted_at IS NULL AND a.resource_type='employee' AND a.resource_id=".$employee_id." GROUP BY a.bookservice_fk");
        return $query->num_rows();
    }
    
    
    public function get_name($emp_id) 
    {
        $result = array();
        $query = $this->db->select('emp_name')->where('id', $emp_id)->get(TBL_EMPLOYEE);
        
        if ($query->num_rows() > 0) {
            return $query->row()->emp_name;
        }
    }

    /*
     * Function for Delete Landing page information
     * @access public
     * @param int ($id)
     * @return void
     */

    public function deleteRecord($id) 
    {
        if ($this->db->where('id', $id)->update(TBL_EMPLOYEE, array("deleted_at" => GMT_DATE_TIME))){
            
            $this->session->set_flashdata('success', 'Employee has been deleted successfully!!!');
        } 
        else {
            $this->session->set_flashdata('error', 'There is some problem in deletion!!!');
        }
        
        redirect("admin/employee/manage");
    }
    
    
    public function delete_leave($id, $emp_id) 
    {
        if ($this->db->where('id', $id)->delete(TBL_LEAVE)){
            
            $this->session->set_flashdata('success', 'Leave has been deleted successfully!!!');
        } 
        else {
            $this->session->set_flashdata('error', 'There is some problem in deletion!!!');
        }
        
        redirect("admin/employee/edit/".$emp_id);
    }
    
    
    public function deleteAll($ids) 
    {
        $ids = array_filter($ids);
        
        if ($this->db->where_in('id', $ids)->delete(TBL_EMPLOYEE)) {
            
            $this->db->where_in('emp_id', $ids)->delete(TBL_LEAVE);
            
            $this->db->where_in('emp_id', $ids)->delete(TBL_TIMESHEET);
            
            $this->session->set_flashdata('success', 'Your data has been deleted successfully!!!');
        } 
        else {
            $this->session->set_flashdata('error', 'There is some problem in deletion!!!');
        }
    }

}//end class

?>
