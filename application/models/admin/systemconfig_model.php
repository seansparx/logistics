<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Systemconfig_model extends CI_Model {

    public function __construct() 
    {
        parent::__construct();
        $this->load->library("session");
    }

    /*     * ************ Start function getsystemconfigdata() to get system configurations *************** */

    function getsystemconfigdata($fieldname) 
    {
        $this->db->where('systemName', $fieldname);
        
        $query = $this->db->get(TBL_SYSTEMCONFIG);
        
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $value) {
                return stripslashes($value->systemVal);
            }
        }
    }

    /*     * ************ End function getsystemconfigdata() *************** */

    /*     * ************ Start function savestemconfigdata() to save syetem configurations *************** */

    function savestemconfigdata() 
    {
            $post = $this->input->post();
            $post['WEEKLY_OFF'] = implode(",", $post['WEEKLY_OFF']);

            $query = $this->db->get(TBL_SYSTEMCONFIG);

            if ($query->num_rows() > 0) {

                foreach ($query->result() as $line) {

                    if (isset($post[$line->systemName])) {
                        $this->db->where('systemName', $line->systemName);
                        $postarray = array('systemVal' => addslashes($post[$line->systemName]));
                        $this->db->update(TBL_SYSTEMCONFIG, $postarray);
                    }
                }
            }

            $this->session->set_flashdata('success', 'Settings has been updated successfully!');
            redirect('admin/settings/site_config');
    }

    /*     * ************ End function savestemconfigdata() *************** */

    /*     * ************ Function to get getSystemConfigurations() to get  system configuration configurations ******************** */

    public function getSystemConfigurations($fieldname = null) 
    {
        $result = array();
        
        if(trim($fieldname) != '') {

            $this->db->select('*')->where('systemName', $fieldname);
        }
        
        $query = $this->db->get(TBL_SYSTEMCONFIG);
        
        foreach ($query->result() as $value) {

            $result[$value->systemName] = stripslashes($value->systemVal);
        }
        
        return $result;
    }

}

?>