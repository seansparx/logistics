<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Dashboard_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
		@ count total of things	
		return array;

    */

    public function count_total_details(){
    	
    	$total_services	= $this->db->select("*")->from(TBL_BOOKSERVICE)->where('deleted_at',NULL)->get()->num_rows();
    	$total_vehicle	= $this->db->select("*")->from(TBL_VEHICLE)->where('deleted_at',NULL)->get()->num_rows();
    	$total_emp      = $this->db->select("*")->from(TBL_EMPLOYEE)->where('deleted_at',NULL)->get()->num_rows();
    	$total_project	= $this->db->select("*")->from(TBL_PROJECT)->where('deleted_at',NULL)->get()->num_rows();
    	
    	$array = array(
                    'total_services'=> $total_services,
                    'total_vehicle' => $total_vehicle,
                    'total_emp'     => $total_emp,
                    'total_project' => $total_project
    		);
        
  	return $array;
    }
    
    
    

}

?>
