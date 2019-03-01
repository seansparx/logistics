<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Administrator_model extends CI_Model {

    public function __construct() 
    {
            parent::__construct();

            $this->load->library("session");
    }

    
    public function count_row() 
    {
            return $this->db->count_all_results(TBL_ADMINLOGIN);
    }

    
    public function fetch_row($ids = null) 
    {
            $this->db->select('*')->from(TBL_ADMINLOGIN)->where(array('deleted_at' => NULL));

            if (is_array($ids) && (count($ids) > 0)) {
                $this->db->where_in('id', $ids);
            }

            $department = $this->db->order_by('id', 'desc')->get();

            if ($department->num_rows() > 0) {

                return $department->result();
            }
    }

    
    public function fetch_admins($offset = 0, $limit = null, $order = null, $filter = null) 
    {
        $this->db->select('a.*, b.title AS role_title')
                ->from(TBL_ADMINLOGIN . ' AS a')
                ->join(TBL_ROLE . ' AS b', 'b.id = a.role_id', 'left')
                ->where(array('a.deleted_at' => NULL));

        if (is_array($filter)) {

            foreach ($filter as $column => $keyword) {
                
                $this->db->like($column, $keyword);

                if ($column == 'status') {
                    $this->db->where($column, $keyword);
                }
            }
        }

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        if (isset($order['column'])) {

            switch ($order['column']) {

                case 2: $this->db->order_by('role_title', $order['dir']);
                    break;
                
                case 3: $this->db->order_by('a.username', $order['dir']);
                    break;
                
                case 4: $this->db->order_by('a.first_name', $order['dir']);
                    break;
                
                case 5: $this->db->order_by('a.last_name', $order['dir']);
                    break;
                
                case 6: $this->db->order_by('a.emailId', $order['dir']);
                    break;
                
                case 8: $this->db->order_by('a.addDate', $order['dir']);
                    break;
                
                default: $this->db->order_by('a.id', 'desc');
                    break;
            }
        } 
        else {
            $this->db->order_by('a.id', 'desc');
        }

        $department = $this->db->get();

        if ($department->num_rows() > 0) {
            return $department->result();
        }
    }

    
    public function get_user($id = null) 
    {
        if ($id > 0) {
            $admin_id = $id;
        }
        else {
            $admin_id = $this->session->userdata(SITE_SESSION_NAME . 'ADMINID');
        }

        $this->db->select(array('a.*', 'b.title AS role_title'));
        $this->db->from(TBL_ADMINLOGIN . ' AS a');
        $this->db->join(TBL_ROLE . ' AS b', 'b.id = a.role_id', 'left');
        $this->db->where(array("a.id" => $admin_id));
        return $this->db->get()->row();
    }

    
    public function addrecords() 
    {
        $post = $this->input->post();
        $fileName = '';

        $password = $this->encrypt_password($post['new_password']);
        $hash = md5($post['username'] . ":" . $password);

        $user_data = array(
            'role_id' => $post['role'],
            'first_name' => clean_text($post['first_name']),
            'last_name' => clean_text($post['last_name']),
            'username' => $post['username'],
            'password' => $password,
            'emailId' => $post['email_id'],
            'userImage' => $fileName,
            'hash' => $hash,
            'addDate' => GMT_DATE_TIME,
            'addedBy' => $this->session->userdata(SITE_SESSION_NAME . 'ADMINID'),
            'status' => $post['status']
        );

        $this->db->insert(TBL_ADMINLOGIN, $user_data);
        $user_id = $this->db->insert_id();

        if ($user_id > 0) {
            $this->session->set_flashdata('success', 'User has been added successfully!');
            redirect("admin/settings/manage_users");
        }
    }

    
    public function edit_record($id) 
    {
        $post = $this->input->post();

        $user_data = array(
            'role_id' => $post['role'],
            'first_name' => clean_text($post['first_name']),
            'last_name' => clean_text($post['last_name']),
            'username' => $post['username'],
            'emailId' => $post['email_id'],
            'modDate' => GMT_DATE_TIME,
            'modBy' => $this->session->userdata(SITE_SESSION_NAME . 'ADMINID'),
            'status' => $post['status']
        );

        if ($this->db->where('id', $id)->update(TBL_ADMINLOGIN, $user_data)) {
            $this->session->set_flashdata('success', 'User has been updated successfully!');
            redirect("admin/settings/manage_users");
        }
    }
    
    
    public function update_profile() 
    {

        $admin_id = $this->session->userdata(SITE_SESSION_NAME . 'ADMINID');
        $post = $this->input->post();

        $user_data = array(
            'first_name' => $post['first_name'],
            'last_name' => $post['last_name'],
            'emailId' => $post['email_id'],
            'mobile' => $post['mobile'],
            'about' => $post['about'],
            'modDate' => GMT_DATE_TIME,
            'modBy' => $admin_id
        );

        if ($this->db->where('id', $admin_id)->update(TBL_ADMINLOGIN, $user_data)) {
            $this->session->set_flashdata('success', 'Profile has been updated successfully!');

            redirect('admin/adminarea/profile');
        }
        
    }
    
    
    public function update_password() 
    {
        $admin_id = $this->session->userdata(SITE_SESSION_NAME . 'ADMINID');
        $post = $this->input->post();
        
        $user = $this->get_user($admin_id);

        $correct = $this->login_model->validate_password($post['current_password'], $user->password);
        
        if($correct != true) {
            $this->session->set_flashdata('error', 'current password is wrong!');
        }
        else{
            
            $password = $this->encrypt_password($post['new_password']);
            $hash = md5($user->username . ":" . $password);

            $record = array(
                'password' => $password,
                'hash'     => $hash,
                'modDate'  => GMT_DATE_TIME,
                'modBy'    => $admin_id
            );

            if ($this->db->where('id', $admin_id)->update(TBL_ADMINLOGIN, $record)) {
                $this->session->set_flashdata('success', 'Password has been changed successfully!');
                redirect('admin');
            }
        }
    }
    
    
    public function update_avatar($file_name) 
    {
        $admin_id = $this->session->userdata(SITE_SESSION_NAME . 'ADMINID');
        $this->db->where('id', $admin_id);
        
        if($this->db->update(TBL_ADMINLOGIN, array("userImage" => $file_name))) {
            $this->session->set_flashdata('success', 'Avatar has been change successfully!');
        }
    }

    /*     * *************** Start function deleteRecord() to delete administrator **************** */

    public function deleteAll($ids) 
    {
        $ids = array_filter($ids);

        if ($this->db->where_in('id', $ids)->delete(TBL_ADMINLOGIN)) {
            $this->session->set_flashdata('success', 'Your data has been deleted successfully!!!');
        } else {
            $this->session->set_flashdata('error', 'There is some problem in deletion!!!');
        }
    }

    
    public function delete_user($id) 
    {
        if ($this->db->where('id', $id)->delete(TBL_ADMINLOGIN)) {
            $this->session->set_flashdata('success', 'User has been deleted successfully!!!');
        } else {
            $this->session->set_flashdata('error', 'There is some problem in deletion!!!');
        }

        redirect("admin/settings/manage_users");
    }

    /*     * *************** End function deleteRecord() **************** */

    /*     * *************** Start function encrypt_password() to encrypt password **************** */

    function encrypt_password($plain) {
        $password = '';
        for ($i = 0; $i < 10; $i++) {
            $password .= $this->tep_rand();
        }
        $salt = substr(md5($password), 0, 2);
        $password = md5($salt . $plain) . ':' . $salt;
        return $password;
    }

    /*     * *************** End function encrypt_password() **************** */

    /*     * *************** Start function tep_rand() to generate random number series **************** */

    function tep_rand($min = null, $max = null) {
        static $seeded;
        if (!$seeded) {
            mt_srand((double) microtime() * 1000000);
            $seeded = true;
        }
    }

    /*     * *************** End function tep_rand() **************** */

    /*     * *************** Start function fetchValue() to fetch single value from table **************** */

    function fetchValue($table, $field, $where) {
        $this->db->select($field);
        $this->db->where($where);
        $query = $this->db->get($table);
        $row = $query->row_array();
        if ($row)
            return $row[$field];
        return 0;
    }

    /*     * *************** End function fetchValue() **************** */

    /*     * *************** Start function getdataResult() to get admin login data **************** */

    function getdataResult($id) {
        $this->db->where('id', $id);
        $query = $this->db->get(TBL_ADMINLOGIN);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $value) {
                return $value;
            }
        }
    }

    /*     * *************** End function getdataResult() **************** */

    /*     * *************** Start function getrecord() to get admin login details **************** */

    public function getrecord($id) {
        $result = array();

        $query = $this->db->get_where(TBL_ADMINLOGIN, array("id" => $id));

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $value) {
                $result = $value;
            }
        }
        return $result;
    }

    /*     * *************** End function getrecord() **************** */

    /*     * *************** Start function editrecord() to edit record **************** */

    

}
?>
