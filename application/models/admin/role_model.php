<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Role_model extends CI_Model {

    public function __construct() 
    {
        parent::__construct();
    }
    
    
    public function count_row()
    {
        return $this->db->count_all_results(TBL_ROLE);
    }
    
    
    public function fetch_row($ids = null)
    {
        $this->db->select('*')->from(TBL_ROLE)->where(array('deleted_at' => NULL));
        
        if(is_array($ids) && (count($ids) > 0)) {
            $this->db->where_in('id', $ids);
        }
                
        $department = $this->db->order_by('id', 'desc')->get();

        if ($department->num_rows() > 0) {
            
            return $department->result();
        }
    }
    
    
    public function get_role($id)
    {
        return $this->db->select('*')->from(TBL_ROLE)->where(array('id' => $id, 'deleted_at' => NULL))->get()->row();
    }
        
    
    public function fetch_roles($offset = 0, $limit = null, $order = null, $filter = null)
    {
        $this->db->select('*')->from(TBL_ROLE)->where(array('deleted_at' => NULL));
        
        if(is_array($filter)){
            
            foreach ($filter as $column => $keyword){
                $this->db->like($column, $keyword);
                
                if($column == 'status') {
                    $this->db->where($column, $keyword);
                }
            }
        }
        
        if($limit){
            $this->db->limit($limit, $offset);
        }
                
        if(isset($order['column'])) {
            
            switch ($order['column']) {
                
                case 1:  $this->db->order_by('title', $order['dir']); break;
                case 2:  $this->db->order_by('description',  $order['dir']); break;
                case 3:  $this->db->order_by('status',  $order['dir']); break;
                case 4:  $this->db->order_by('created_at',  $order['dir']); break;
                default: $this->db->order_by('id', 'desc'); break;
            }
        }
        
        $department = $this->db->get();

        if ($department->num_rows() > 0) {
            return $department->result();
        }
    }
    
    
    public function fetch_modules()
    {
        $main_menus = $this->db->select("*")->from(TBL_PERMISSION)->where(array("parent_id" => 0))->order_by('sort', 'asc')->get()->result();
                
        if(is_array($main_menus)) {

            foreach($main_menus as $main) {


                $main->sub_menus = $this->db->select("*")->from(TBL_PERMISSION)->where(array("parent_id" => $main->id))->order_by('sort', 'asc')->get()->result();

            }
        }

       
        
        return $main_menus;
    }
    
        
    public function get_role_permission($role_id)
    {
        $role_permissions = $this->db->select(array("GROUP_CONCAT(permission_id) as perm_ids"))->from(TBL_ROLE_PERMISSION)->where(array('role_id' => $role_id))->get()->row();
        
        if(isset($role_permissions->perm_ids)){
            return explode(",", $role_permissions->perm_ids);
        }
    }
    
    
    public function add_role()
    {
            $title       = $this->input->post('role_title');
            $description = $this->input->post('description');
            $permissions = $this->input->post('permissions');
            $status      = $this->input->post('status');
            $added_by    = $this->session->userdata(SITE_SESSION_NAME . 'ADMINID');

            $this->db->insert(TBL_ROLE, array('title' => $title, 'description' => $description, 'status' => $status, 'added_by' => $added_by));

            $role_id = $this->db->insert_id();

            foreach(explode(",", $permissions) as $permission_id) {

                $this->db->insert(TBL_ROLE_PERMISSION, array('permission_id' => $permission_id, 'role_id' => $role_id));

                $parent = $this->db->select('parent_id')->get_where(TBL_PERMISSION, array("id" => $permission_id));

                if($parent->num_rows() > 0) {
                    
                    $parent_id = $parent->row()->parent_id;
                    
                    //$this->db->insert(TBL_ROLE_PERMISSION, array('permission_id' => $parent_id, 'role_id' => $role_id));
                }

            }
    }
    
    
    public function update_role($role_id)
    {
        if($role_id == 1) {
            die('Action Not Allowed');
        }
        
        $title       = $this->input->post('role_title');
        $description = $this->input->post('description');
        $permissions = $this->input->post('permissions');
        $status      = $this->input->post('status');
        
        $this->db->where(array('role_id' => $role_id))->delete(TBL_ROLE_PERMISSION);
                
        foreach(explode(",", $permissions) as $permission_id) {

            $this->db->insert(TBL_ROLE_PERMISSION, array('permission_id' => $permission_id, 'role_id' => $role_id));
            
            $parent = $this->db->select('parent_id')->get_where(TBL_PERMISSION, array("id" => $permission_id));

            if($parent->num_rows() > 0) {

                $parent_id = $parent->row()->parent_id;

                //$this->db->insert(TBL_ROLE_PERMISSION, array('permission_id' => $parent_id, 'role_id' => $role_id));
            }
        }
        
        $this->db->where(array('id' => $role_id))->update(TBL_ROLE, array('title' => $title, 'description' => $description, 'status' => $status));
    }
    
    
    public function deleteAll($ids) 
    {
        $id = array_filter($ids);
        
        if ($this->db->where_in('role_id', $id)->delete(TBL_ROLE_PERMISSION)) {
            $this->db->where_in('id', $id)->delete(TBL_ROLE);
            $this->session->set_flashdata('success', 'Your data has been deleted successfully!!!');
        } 
        else {
            $this->session->set_flashdata('error', 'There is some problem in deletion!!!');
        }
    }
    
    
    public function delete_role($id) 
    {
        if($id == 1) {
            die('Action Not Allowed');
        }
        
        if ($this->db->where('role_id', $id)->delete(TBL_ROLE_PERMISSION)) {
            
            $this->db->where('id', $id)->delete(TBL_ROLE);
            $this->session->set_flashdata('success', 'Role has been deleted successfully!!!');
        } 
        else {
            $this->session->set_flashdata('error', 'There is some problem in deletion!!!');
        }
    }
}

?>
