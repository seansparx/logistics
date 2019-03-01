<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Variants Class
 * @author Rajesh Kumar Yadav
 * @version 1.0
 * @dated 20/01/2017
 */
class Department_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function count_row() {
        return $this->db->count_all_results(TBL_DEPARTMENT);
    }

    /*
     * Function for get all landing page details
     * @access public
     * @param $query_data (string)
     * @return array
     */

    public function fetch_row()
    {
        $this->db->select('*')->from(TBL_DEPARTMENT)->where(array('deleted_at' => NULL));
        
        $department = $this->db->get();

        if ($department->num_rows() > 0) {
            
            return $department->result();
        }

    }
    
    
    public function fetch_departments($offset = 0, $limit = null, $order = null, $filter = null)
    {
        $this->db->select('*')->from(TBL_DEPARTMENT)->where(array('deleted_at' => NULL));
        
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
                case 2: $this->db->order_by('name', $order['dir']); break;
                case 3: $this->db->order_by('description', $order['dir']); break;
                case 6: $this->db->order_by('created_at', $order['dir']); break;
                default: $this->db->order_by('id', 'desc'); break;
            }
        }
        
        $department = $this->db->get();

        if ($department->num_rows() > 0) {
            return $department->result();
        }
    }

    /*
     * Function for add Department page information
     * @access public
     * @param array ($data)
     * @return true/false
     */

    public function addDepartment($post) {
        $data = array(
            "name" => clean_text($post['name']),
            "description" => clean_text($post['description']),
            "status" => $post['status'],
            "added_by"   => $this->session->userdata(SITE_SESSION_NAME . 'ADMINID'),
            "created_at" => GMT_DATE_TIME,
            "updated_at" => GMT_DATE_TIME
        );
        $this->db->insert(TBL_DEPARTMENT, $data);

        $this->session->set_flashdata('success', 'Information has been added successfully!');
        return TRUE;
    }

    /*
     * Function for update department information
     * @access public
     * @param array ($data)
     * @return true/false
     */

    public function updateDepartment($post, $id) {
        $data = array(
            "name" => clean_text($post['name']),
            "description" => clean_text($post['description']),
            "status" => $post['status'],
            "updated_at" => GMT_DATE_TIME
        );
        $this->db->where('id', $id)->update(TBL_DEPARTMENT, $data);
        $this->session->set_flashdata('success', 'Information has been updated successfully!');
        return TRUE;
    }

    /*
     * Function for get Department for edit
     * @access public
     * @param int ($id)
     * @return array
     */

    public function getEditRecord($id) {
        $result = array();
        $query = $this->db->where('id', $id)->get(TBL_DEPARTMENT);
        
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $value) {
                $result = $value;
            }
        }
        return $result;
    }
    
    
    public function get_nos($dept_id)
    {
        $query = $this->db->query("SELECT `id` FROM `".TBL_BOOKSERVICE."` WHERE `department_id` = '$dept_id' AND `deleted_at` IS NULL");
        return $query->num_rows();
    }

    /*
     * Function for Delete Landing page information
     * @access public
     * @param int ($id)
     * @return void
     */
    public function deleteRecord($id) 
    {
        if ($this->db->where('id', $id)->update(TBL_DEPARTMENT, array("deleted_at" => GMT_DATE_TIME))) {
            $this->session->set_flashdata('success', 'Department has been deleted successfully!!!');
        } 
        else {
            $this->session->set_flashdata('error', 'There is some problem in deletion!!!');
        }
        
        redirect("admin/department/manage");
    }
    
    
    public function deleteAll($ids) 
    {
        $ids = array_filter($ids);
        
        if ($this->db->where_in('id', $ids)->update(TBL_DEPARTMENT, array("deleted_at" => GMT_DATE_TIME))) {
            $this->session->set_flashdata('success', 'All selected department has been deleted successfully!!!');
        } 
        else {
            $this->session->set_flashdata('error', 'There is some problem in deletion!!!');
        }
    }

    //end class
}

?>
