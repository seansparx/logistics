<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Class to manage logistic projects.
 * 
 * @author Sean Rock <sean@sparxitsolutions.com>
 * @version 1.0
 * @dated 10/02/2017
 */
class Project extends MY_Controller 
{
    
    function __construct() 
    {
        parent::__construct();
        
        has_access('manage_projects');
        
        $this->load->model('admin/project_model');
    }
    
    
    
    /**
     * Display project listing page
     * 
     * @return void
     */
    public function manage() 
    {
        $this->data['content_datas']     = $this->project_model->fetch_row();
                
        $this->render('project/manage');
    }
    
    
    /**
     * Add project
     * 
     * @return void
     */
    public function add() 
    {
        if($this->input->post()) {

                if ($this->validate_form()) {

                    $this->project_model->add_vehicle();
                    redirect('admin/project/manage');
                }
         }

        $this->render('project/add');
   }
   
   
   /**
     * Validate project form
     * 
     * @return bool
     */
    private function validate_form()
    {
         $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
         $this->form_validation->set_rules('code', 'Project Code', 'required|min_length[2]|max_length[100]|callback_unique_project[projects.code.add_time.]');
         $this->form_validation->set_rules('cust_name', 'Customer Name', 'required|min_length[2]|max_length[100]');
         $this->form_validation->set_rules('description', 'Description', 'required|min_length[5]|max_length[500]');
         $this->form_validation->set_rules('status', 'Status', 'required');

         return ($this->form_validation->run() === TRUE);
    }
    
   
   
   
    /**
     * Delete project
     * 
     * @param $id int
     * @return void
     */
    public function delete($id) {
        
        $this->project_model->deleteRecord($id);
    }
    
    
    
    /**
     * Edit project
     * 
     * @param $id int
     * @return void
     */
    public function edit($id)
    {
        $data=null;

         $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
         $this->form_validation->set_rules('code', 'Project Code', 'required|min_length[2]|max_length[100]||callback_unique_project[projects.code.edit_time.'.$id.']');
         $this->form_validation->set_rules('cust_name', 'Customer Name', 'required|min_length[2]|max_length[100]');
         $this->form_validation->set_rules('description', 'Description', 'required|min_length[5]|max_length[500]');
         $this->form_validation->set_rules('status', 'Status', 'required');


        if($this->input->post()) {

            if($this->form_validation->run() === TRUE) {
                
                $this->project_model->update_vehicle($id);
                redirect("admin/project/manage");
            }

            $data['id'] = $id;
        }
        
        $data['details'] = $this->project_model->getEditRecord($id);
        
        $this->data = $data;
        $this->render('project/edit');
    }
    

    /**
     * Check unique record
     * 
     * @return boolean;
     */
    
    public function unique_project($value,$parms){ 


        $value=clean_name($value);
        $this->form_validation->set_message('unique_project', 'The %s "' . $value . '" is already being used.');
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
     * Datatable AJAX Call for project listing
     * 
     * @return void
     */
    public function project_ajax()
    {
            if (isset($_REQUEST["customActionType"]) && $_REQUEST["customActionType"] == "group_action") {
                
                $ids = array_filter($_REQUEST['id']);
                
                switch($_REQUEST['customActionName']) {
                    case 'delete_all' : $this->project_model->deleteAll($ids); 
                        break;
                }
                
                $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
                $records["customActionMessage"] = "Group action successfully has been completed. Well done!"; // pass custom message(useful for getting status of group actions)
            }
            
            $iTotalRecords  = $this->project_model->count_row();
            $iDisplayLength = intval($_REQUEST['length']);
            $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
            $iDisplayStart = intval($_REQUEST['start']);
            $sEcho = intval($_REQUEST['draw']);

            $filter = array();
            
            if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'filter') {
                
                if(isset($_REQUEST['filter_id']) && ($_REQUEST['filter_id'] != '') && (($_REQUEST['filter_id'] != '#') && ($_REQUEST['filter_id'] != '#0') && ($_REQUEST['filter_id'] != '#00')) ){
                    $filter['id'] = intval(str_replace("#", "", $_REQUEST['filter_id']));
                }
                
                if(isset($_REQUEST['filter_code']) && ($_REQUEST['filter_code'] != '')){
                    $filter['code'] = $_REQUEST['filter_code'];
                }
                
                if(isset($_REQUEST['filter_customer_name']) && ($_REQUEST['filter_customer_name'] != '')){
                    $filter['customer_name'] = $_REQUEST['filter_customer_name'];
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

            $services = $this->project_model->fetch_projects($iDisplayStart, $iDisplayLength, $_REQUEST['order'][0], $filter);

            if(is_array($services)) {
                
                foreach ($services as $row){
                    
                    $nos = $this->project_model->get_nos($row->id); // Total number of entries.
                    //$noe = $this->project_model->get_noe($row->id); // Total number of assigned employee.
                    //$nov = $this->project_model->get_nov($row->id); // Total number of assigned vehicle.
                    
                    $chkbox = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                    <input type="checkbox" name="ids" class="checkboxes" value="'.$row->id.'" />
                                    <span></span>
                                </label>';
                    
                    $actions  = '<a class="btn btn-outline btn-circle btn-sm purple" href="' . site_url('admin/project/edit/' . $row->id) . '"><i class="fa fa-edit"></i> Edit</a>';
                    
                    if($nos > 0){
                        $actions .= '<a title="Delete Not Allowed" class="btn btn-outline btn-circle dark btn-sm red disabled" onClick="alert(\'Delete operation is not allowed, because employee using '.$nos.' service.\');" href="javascript:;"><i class="fa fa fa-trash"></i> '.$nos.' Used</a>';
                    }
                    else{
                         $actions .= '<a title="Delete" class="btn btn-outline btn-circle dark btn-sm red mt-sweetyalert"  href="javascript:;" onClick="return confirmation(\'' . site_url('admin/project/delete/' . $row->id . '') . '\');"><i class="fa fa fa-trash"></i>Delete</a>';
                    }
                    
                     $status_icon = ($row->status == 'active') ? '<a href="javascript:void(0);"  onclick="return status_get('."'active',"."'$row->id'".');"  title="Active"><i class="fa fa-2x fa-eye"></i></a>' : '<a href="javascript:void(0);" onclick="return status_get('."'deactive',"."'$row->id'".');"   title="Deactive"><i class="fa fa-2x fa-eye-slash"></i></a>';

                    $records["data"][] = array($chkbox, code($row->id), $row->code, $row->customer_name, $row->description, $status_icon, display_datetime($row->created_at), $actions);
                }
            }
            
            

            $records["draw"]            = $sEcho;
            $records["recordsTotal"]    = $iTotalRecords;
            $records["recordsFiltered"] = $iTotalRecords;

            echo json_encode($records);
    }

    public function update_active_status(){

        $post       = $this->input->post();
        $status     = $post['status'];
        $id         = $post['id'];
        $array      = array();

        if ($status == 'active')
        {
            $array['status'] = 'inactive';
        }
        else
        {
            $array['status'] = 'active';
        }

        $this->db->where(array('deleted_at' => NULL,'id' => $id))->update(TBL_PROJECT, $array);
        $output = json_encode(array("status" => 'success'));
        die($output);
    }
  
}


