<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Holiday Model
 * 
 * @author Sean <sean@sparxitsolutions.com>
 * @version 1.0
 * @dated 20/01/2017
 */
class Holiday_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function count_row() {
        return $this->db->count_all_results(TBL_HOLIDAY);
    }

    /*
     * Function for get all landing page details
     * @access public
     * @param $query_data (string)
     * @return array
     */

    public function fetch_row()
    {
        $qry = $this->db->select('*')->from(TBL_HOLIDAY)->get();

        if ($qry->num_rows() > 0) {

            return $qry->result();
        }
    }
    
    
    public function fetch_holidays($offset = 0, $limit = null, $order = null, $filter = null)
    {
            $this->db->select('*')->from(TBL_HOLIDAY);

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

                    case 2: $this->db->order_by('holiday_name', $order['dir']); break;
                    case 3: $this->db->order_by('holiday_date', $order['dir']); break;
                    
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

    public function add_holiday() 
    {
            $post = $this->input->post();

            $data = array(
                        "holiday_name" => clean_text($post['holiday_name']),
                        "holiday_date" => date("Y-m-d", strtotime($post['holiday_date'])),
                        "status" => 1
                    );

            if($this->db->insert(TBL_HOLIDAY, $data)) {

                    $this->session->set_flashdata('success', 'Information has been added successfully!');
            }
            
    }

    /*
     * Function for update department information
     * @access public
     * @param array ($data)
     * @return true/false
     */

    public function update_holiday($id) 
    {
        $post = $this->input->post();
        
        $data = array(
                    "holiday_name" => clean_text($post['holiday_name']),
                    "holiday_date" => date("Y-m-d", strtotime($post['holiday_date'])),
                    "status" => 1
                );
            
        if($this->db->where('id', $id)->update(TBL_HOLIDAY, $data)){
            $this->session->set_flashdata('success', 'Information has been updated successfully!');
        }

    }

    /*
     * Function for get Department for edit
     * @access public
     * @param int ($id)
     * @return array
     */

    public function getEditRecord($id) 
    {
        $result = array();
        $query = $this->db->where('id', $id)->get(TBL_HOLIDAY);
        
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $value) {
                $result = $value;
            }
        }
        
        return $result;
    }
    
    
//    public function get_nos($id)
//    {
//        $query = $this->db->query("SELECT `id` FROM `".TBL_HOLIDAY."` WHERE `id` = '$id'");
//        return $query->num_rows();
//    }

    /*
     * Function for Delete Landing page information
     * @access public
     * @param int ($id)
     * @return void
     */
    public function deleteRecord($id) 
    {
        if ($this->db->where('id', $id)->delete(TBL_HOLIDAY)) {
            
            $this->session->set_flashdata('success', 'Record has been deleted successfully!!!');
        }
        else {
            
            $this->session->set_flashdata('error', 'There is some problem in deletion!!!');
        }
        
        redirect("admin/holiday/manage");
    }
    
    
    public function deleteAll($ids) 
    {
        $ids = array_filter($ids);
        
        if ($this->db->where_in('id', $ids)->delete(TBL_HOLIDAY)) {
            
            $this->session->set_flashdata('success', 'All selected records has been deleted successfully!!!');
        } 
        else {
            $this->session->set_flashdata('error', 'There is some problem in deletion!!!');
        }
    }

    //end class
}

?>
