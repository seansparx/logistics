<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Class to manage logistic departments.
 * 
 * @author Sean Rock <sean@sparxitsolutions.com>
 * @version 1.0
 * @dated 30/01/2017
 */
class Department extends MY_Controller 
{
    
    function __construct() 
    {
        parent::__construct();
        
        has_access('manage_departments');
        
        $this->load->model('admin/department_model');
    }
    
    
    
    /**
     * Display department listing page 
     * 
     * @return void
     */
    public function manage() 
    {
        $this->data['content_datas']     = $this->department_model->fetch_row();
                
        $this->render('department/manage');
    }
    
    
    /**
     * Add department
     * 
     * @return void
     */
    public function add($post = 0) 
    {        
        if($this->input->post()){
            
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
            $this->form_validation->set_rules('name', 'Department Name', 'required|min_length[2]|max_length[50]|callback_unique_department[department.name.add_time.]');
            $this->form_validation->set_rules('description', 'Description', 'max_length[500]');
            $this->form_validation->set_rules('status', 'Status', 'required');
            
            if ($this->form_validation->run() === TRUE) {
                $this->department_model->addDepartment($_POST);
                redirect('admin/department/manage');
            } 
         }
         
        $this->render('department/add');		
   }
         
    
    
    /**
     * Delete department
     * 
     * @param $id int
     * @return void
     */
    public function delete($id) {
        
        $this->department_model->deleteRecord($id);
    }
    
    
    
    /**
     * Edit department
     * 
     * @param $id int
     * @return void
     */
    public function edit($id) 
    {
        $data=null;
        if($this->input->post()){
           
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
            $this->form_validation->set_rules('name', 'Department Name', 'required|min_length[2]|max_length[50]|callback_unique_department[department.name.edit_time.'.$id.']');
            $this->form_validation->set_rules('description', 'Description', 'max_length[500]');
            $this->form_validation->set_rules('status', 'Status', 'required');
           
            if (($this->form_validation->run() === TRUE)){
                $this->department_model->updateDepartment($_POST, $id);
                redirect("admin/department/manage");
            }
            $data['id']=$id;
        }
        
        $data['details'] = $this->department_model->getEditRecord($id);
        
        $this->data = $data;
        $this->render('department/edit');
    }
    
    
    
    /**
     * Validation rule for unique department
     * 
     * @param $value string
     * @param $params array
     * @return bool
     */


public function unique_department($value,$parms){ 

        $value=clean_text($value);
        $this->form_validation->set_message('unique_department', 'The %s "' . $value . '" is already being used.');
        list($table,$field,$action,$id)=explode(".",$parms);

         if($action=="add_time"){
            $where=array($field=>$value,'deleted_at'=>NULL);
         }   
        if($action=="edit_time"){
            $where=array($field=>$value, 'id !=' => $id ,'deleted_at'=>NULL);
         }   
        $query=$this->db->select($field)->from($table)->where($where)->get();

            if($query->num_rows()>0){
                return false;
            }else{
                return true;
            }
    }
    
    
    
    /**
     * Datatable AJAX Call for department list
     * 
     * @return json
     */
    public function department_ajax()
    {
        
            if (isset($_REQUEST["customActionType"]) && $_REQUEST["customActionType"] == "group_action") {
                
                $ids = array_filter($_REQUEST['id']);
                
                switch($_REQUEST['customActionName']) {
                    case 'delete_all' : $this->department_model->deleteAll($ids); 
                        break;
                }
                
                $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
                $records["customActionMessage"] = "Group action successfully has been completed. Well done!"; // pass custom message(useful for getting status of group actions)
            }
            
            $iTotalRecords  = $this->department_model->count_row();
            $iDisplayLength = intval($_REQUEST['length']);
            $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
            $iDisplayStart = intval($_REQUEST['start']);
            $sEcho = intval($_REQUEST['draw']);

            $filter = array();
            
            if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'filter') {
                
                if(isset($_REQUEST['filter_id']) && ($_REQUEST['filter_id'] != '' ) && (($_REQUEST['filter_id'] != '#') && ($_REQUEST['filter_id'] != '#0') && ($_REQUEST['filter_id'] != '#00')) ){
                    $filter['id'] = intval(str_replace("#", "", $_REQUEST['filter_id']));
                }
                
                if(isset($_REQUEST['filter_name']) && ($_REQUEST['filter_name'] != '')){
                    $filter['name'] = $_REQUEST['filter_name'];
                }
                
                if(isset($_REQUEST['filter_description']) && ($_REQUEST['filter_description'] != '')){
                    $filter['description'] = $_REQUEST['filter_description'];
                }
                
                if(isset($_REQUEST['filter_status']) && ($_REQUEST['filter_status'] != '')){
                    $filter['status'] = $_REQUEST['filter_status'];
                }
                
                if(isset($_REQUEST['filter_date_from']) && ($_REQUEST['filter_date_from'] != '')){
                    $filter['created_at'] = date("Y-m-d", strtotime(str_replace('/', '-', $_REQUEST['filter_date_from'])));
                }
                
            }
            
            $records = array();
            $records["data"] = array(); 

            $end = $iDisplayStart + $iDisplayLength;
            $end = $end > $iTotalRecords ? $iTotalRecords : $end;

            $services = $this->department_model->fetch_departments($iDisplayStart, $iDisplayLength, $_REQUEST['order'][0], $filter);

            if(is_array($services)) {
                
                foreach ($services as $row){
                    
                    $nos = $this->department_model->get_nos($row->id);
                    
                    if($nos > 0){
                        $chkbox = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                    <input type="checkbox" name="keys" class="checkboxes" disabled="disabled" value="0" />
                                    <span></span>
                                </label>';
                    }
                    else{
                        $chkbox = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                    <input type="checkbox" name="keys" class="checkboxes" value="'.$row->id.'" />
                                    <span></span>
                                </label>';
                    }
                    
                    
                    $actions  = '<a class="btn btn-outline btn-circle btn-sm purple" href="' . site_url('admin/department/edit/' . $row->id) . '"><i class="fa fa-edit"></i> Edit</a>';
                    
                    if($nos > 0){
                        $actions .= '<a title="Delete Not Allowed" class="btn btn-outline btn-circle dark btn-sm red disabled" onClick="alert(\'Delete operation is not allowed, because It is used in '.$nos.' service.\');" href="javascript:void(0);"><i class="fa fa fa-trash"></i> '.$nos.' Used</a>';
                    }
                    else{
                        //$actions .= '<a title="Delete" class="btn btn-outline btn-circle dark btn-sm red" onClick="if(confirm(\'Do you want to delete this reocrd ?\')){window.location.href=\'' . site_url('admin/department/delete/' . $row->id . '') . '\';}" href="javascript:;"><i class="fa fa fa-trash"></i> Delete</a>';

                        $actions .= '<a title="Delete" class="btn btn-outline btn-circle dark btn-sm red mt-sweetyalert"  href="javascript:;" onClick="return confirmation(\'' . site_url('admin/department/delete/' . $row->id . '') . '\');"><i class="fa fa fa-trash"></i>Delete</a>';
                    
                    }

                    $status_icon = ($row->status == 'active') ? '<a href="javascript:void(0);"  onclick="return status_get('."'active',"."'$row->id'".');"  title="Active"><i class="fa fa-2x fa-eye"></i></a>' : '<a href="javascript:void(0);" onclick="return status_get('."'deactive',"."'$row->id'".');"   title="Deactive"><i class="fa fa-2x fa-eye-slash"></i></a>';
                    
                    $records["data"][] = array($chkbox, code($row->id), $row->name, $row->description, $status_icon, display_datetime($row->created_at), $actions);
                }
            }
            

            $records["draw"]            = $sEcho;
            $records["recordsTotal"]    = $iTotalRecords;
            $records["recordsFiltered"] = $iTotalRecords;

            echo json_encode($records);
    }


    public function update_active_status(){

        $post=$this->input->post();
        $status=$post['status'];
        $id=$post['id'];
        $array=array();

        if($status=='active'){
            $array['status']='inactive';
        }else{
            $array['status']='active';
        }
        
        $this->db->where(array('deleted_at'=>NULL,'id'=>$id))->update(TBL_DEPARTMENT,$array);

        $output=json_encode(array("status"=>'success'));
        die($output);

    }
  
}


