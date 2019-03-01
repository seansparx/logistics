<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Variants Class
 * @author Rajesh Kumar Yadav
 * @version 1.0
 * @dated 20/01/2017
 */
class Vehicle_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function count_row() {
        
        return $this->db->where(array('deleted_at' => NULL))->count_all_results(TBL_VEHICLE);
    }

    public function count_checkups_row($vehicle_id) 
    {
        return $this->db->where(array('vehicle_id' => $vehicle_id))->count_all_results(TBL_CHECKUP);
    }
    
    /*
     * Function for get all landing page details
     * @access public
     * @param $query_data (string)
     * @return array
     */

    public function fetch_row($ids = null)
    {
        $this->db->select('*')->from(TBL_VEHICLE)->where(array('deleted_at' => NULL));
        
        if(is_array($ids) && (count($ids) > 0)){
            $this->db->where_in('id', $ids);
        }
        
        $department = $this->db->get();

        if ($department->num_rows() > 0) {
            
            return $department->result();
        }

    }
    
    
    public function fetch_vehicles($offset = 0, $limit = null, $order = null, $filter = null)
    {
        $this->db->select('*')->from(TBL_VEHICLE)->where(array('deleted_at' => NULL));
        
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
                case 2: $this->db->order_by('regn_number', $order['dir']); break;
                case 3: $this->db->order_by('model', $order['dir']); break;
                case 6: $this->db->order_by('created_at', $order['dir']); break;
                default: $this->db->order_by('id', 'desc'); break;
            }
        }
        
        $department = $this->db->get();

        if ($department->num_rows() > 0) {
            return $department->result();
        }
    }
    
    
    
    
    public function fetch_checkups($vehicle_id, $offset = 0, $limit = null, $order = null, $filter = null)
    {
            $this->db->select('*')->from(TBL_CHECKUP)->where("vehicle_id", $vehicle_id);

            if($limit){
                $this->db->limit($limit, $offset);
            }

            if(isset($order['column'])) {

                switch ($order['column']) {

                    case 1: $this->db->order_by('date', $order['dir']);   break;
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

    public function add_vehicle() 
    {
            $post = $this->input->post();

            $data = array(
                "regn_number" => clean_name($post['regn_number']),
                "model"     => clean_name($post['model']),
                "status"    => $post['status'],
                "left_company" => (isset($post['left_company']) ? 1 : 0),
                "added_by"   => $this->session->userdata(SITE_SESSION_NAME . 'ADMINID'),
                "created_at" => GMT_DATE_TIME,
                "updated_at" => GMT_DATE_TIME
            );

            $flag = $this->db->insert(TBL_VEHICLE, $data);

            if($flag){
                $this->session->set_flashdata('success', 'Vehicle has been added successfully!');        
                return $flag;
            }
    }
    
    
    public function get_checkups($vehicle_id) 
    {
            $query = $this->db->where('vehicle_id', $vehicle_id)->get(TBL_CHECKUP);

            if ($query->num_rows() > 0) {

                return $query->result();
            }
    }
    
    
    
    public function add_checkup($vehicle_id) 
    {
            $post = $this->input->post();

            $post['start_date'] = str_replace("/", "-", $post['start_date']);
            $post['end_date']   = str_replace("/", "-", $post['end_date']);

            $service_date = strtotime($post['start_date']);

            if(strtotime($post['start_date']) == strtotime($post['end_date'])) {

                $data = array("date" => date("Y-m-d", $service_date), "reason" => clean_text($post['reason']), "vehicle_id" => $vehicle_id);
                $this->db->insert(TBL_CHECKUP, $data);
            }
            else if(strtotime($post['end_date']) > strtotime($post['start_date'])) {

                $i = 0;

                while ($service_date < strtotime($post['end_date'])) {

                    $service_date = strtotime($post['start_date']." +".$i." days");

                    $data = array("date" => date("Y-m-d", $service_date), "reason" => clean_text($post['reason']), "vehicle_id" => $vehicle_id);
                    $this->db->insert(TBL_CHECKUP, $data);
                    $i++;
                }
            }

            $this->session->set_flashdata('success', 'Entry has been added successfully!');
    }
    

    /*
     * Function for update department information
     * @access public
     * @param array ($data)
     * @return true/false
     */

    public function update_vehicle($id) 
    {
        $post = $this->input->post();
        
        $data = array(
            "regn_number" => clean_name($post['regn_number']),
            "model" => clean_name($post['model']),
            "status" => $post['status'],
            "left_company" => (isset($post['left_company']) ? 1 : 0),
            "updated_at" => GMT_DATE_TIME
        );
        
        $this->db->where('id', $id)->update(TBL_VEHICLE, $data);
        $this->session->set_flashdata('success', 'Vehicle has been updated successfully!');
        return TRUE;
    }

    /*
     * Function for get Department for edit
     * @access public
     * @param int ($id)
     * @return array
     */

    public function getEditRecord($id) {
        
        $query = $this->db->where('id', $id)->get(TBL_VEHICLE);
        
        if ($query->num_rows() > 0) {
            return $query->row();
        }
    }
    
    
    public function get_nos($vehicle_id)
    {
        $query = $this->db->query("SELECT b.id FROM ".TBL_BOOKSERVICE_INFO." AS a LEFT JOIN ".TBL_BOOKSERVICE." AS b ON(b.id = a.bookservice_fk) WHERE b.deleted_at IS NULL AND a.resource_type='vehicle' AND a.resource_id=".$vehicle_id." GROUP BY a.bookservice_fk");
        return $query->num_rows();
    }
        
    
    public function get_name($vehicle_id) 
    {
        $result = array();
        $query = $this->db->select('regn_number')->where('id', $vehicle_id)->get(TBL_VEHICLE);
        
        if ($query->num_rows() > 0) {
            return $query->row()->regn_number;
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
        if ($this->db->where('id', $id)->update(TBL_VEHICLE, array("deleted_at" => GMT_DATE_TIME))) {
            $this->session->set_flashdata('success', 'Vehicle has been deleted successfully!!!');
        } 
        else {
            $this->session->set_flashdata('error', 'There is some problem in deletion!!!');
        }
        
        redirect("admin/vehicle/manage");
    }
    
    
    public function deleteAll($ids) 
    {
        $ids = array_filter($ids);
        
        if ($this->db->where_in('id', $ids)->update(TBL_VEHICLE, array("deleted_at" => GMT_DATE_TIME))) {
            
            $this->session->set_flashdata('success', 'Your data has been deleted successfully!!!');
        } 
        else {
            $this->session->set_flashdata('error', 'There is some problem in deletion!!!');
        }
    }

}//end class

?>
