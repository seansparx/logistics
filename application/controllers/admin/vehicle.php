<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Class to manage vehicles
 * 
 * @author Sean Rock <sean@sparxitsolutions.com>
 * @version 1.0
 * @dated 30/01/2017
 */
class Vehicle extends MY_Controller 
{
    
    function __construct() 
    {
        parent::__construct();
        
        has_access('manage_vehicles');
        
        $this->load->model('admin/vehicle_model');
    }
    
    
    /**
     * Display vehicle listing page
     * 
     * @return void
     */
    public function manage() 
    {
        $this->data['content_datas'] = $this->vehicle_model->fetch_row();
        $this->render('vehicle/manage');
    }
    
        
    
    /**
     * Add vehicles
     * 
     * @return void
     */
    public function add() 
    {
        if($this->input->post()) {
            
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
            $this->form_validation->set_rules('regn_number', 'Registration Number', 'required|min_length[2]|max_length[100]|callback_unique_vehicle[vehicles.regn_number.add_time.]');

            $this->form_validation->set_rules('model', 'Vehicle Model', 'required|min_length[2]|max_length[100]');
            $this->form_validation->set_rules('status', 'Status', 'required|max_length[50]');
            
            if ($this->form_validation->run() === TRUE) {
                
                $this->vehicle_model->add_vehicle();
                redirect('admin/vehicle/manage');
            } 
         }
         
        $this->render('vehicle/add');		
    }
    
    
    /**
     * Delete vehicles
     * 
     * @param $id int
     * @return void
     */
    public function delete($id) 
    {
        $this->vehicle_model->deleteRecord($id);
    }
    
    
    /**
     * Edit vehicles
     * 
     * @param $id int
     * @return void
     */
    public function edit($id)
    {
            if($this->input->post('btn_checkup')) {

                $data = $this->add_checkup($id);
            }
            else if($this->input->post()) {

                $data = $this->edit_basic_info($id);
            }
            
            $this->data['id'] = $id;

            $this->data['details'] = $this->vehicle_model->getEditRecord($id);
            //$data['checkups']  = $this->vehicle_model->get_checkups($id);
            
            $this->render('vehicle/edit');
    }
    
    
    private function add_checkup($id)
    {
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            $this->form_validation->set_rules('start_date', 'Start Date', 'required|max_length[50]|callback_unique_checkup[checkups.date.'.$id.']');
            $this->form_validation->set_rules('end_date', 'End Date', 'required|max_length[50]');
            $this->form_validation->set_rules('reason', 'Reason', 'required|max_length[1000]');
           
            if (($this->form_validation->run() === TRUE)) {

                $this->vehicle_model->add_checkup($id);    
                
                redirect("admin/vehicle/edit/".$id);
            }
    }
    
    
    private function edit_basic_info($id)
    {
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
            $this->form_validation->set_rules('regn_number', 'Registration Number', 'required|min_length[2]|max_length[100]|callback_unique_vehicle[vehicles.regn_number.edit_time.'.$id.']');
            $this->form_validation->set_rules('model', 'Vehicle Model', 'required|min_length[2]|max_length[100]');
            $this->form_validation->set_rules('status', 'Status', 'required|max_length[50]');
           
            if (($this->form_validation->run() === TRUE)){
                $this->vehicle_model->update_vehicle($id);
                redirect("admin/vehicle/manage");
            }
    }
    
    
    public function unique_checkup($value, $params)
    {
            $start_date = $value;
            $end_date   = $this->input->post('end_date');

            $this->form_validation->set_message('unique_checkup', 'A checkup entry already exists between selected date.');

            list($table, $field, $vehicle_id) = explode(".", $params, 3);

            $this->db->select($field)->from($table);
            $this->db->where($field.' >=', date("Y-m-d", strtotime(str_replace("/", "-", $start_date))));
            $this->db->where($field.' <=', date("Y-m-d", strtotime(str_replace("/", "-", $end_date))));
            $this->db->where('vehicle_id', $vehicle_id);
            
            $query = $this->db->limit(1)->get();

            if ($query->num_rows() > 0) {
                return false;
            } 
            else {
                return true;
            }
    }

    

    public function unique_vehicle($value,$parms){ 

        $value=clean_name($value);

        $this->form_validation->set_message('unique_vehicle', 'The %s "' . $value . '" is already being used.');
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
     * Vehicle datatable AJAX call
     * 
     * @return json
     */
    public function vehicle_ajax()
    {        
            if (isset($_REQUEST["customActionType"]) && $_REQUEST["customActionType"] == "group_action") {
                
                $ids = array_filter($_REQUEST['id']);
                
                switch($_REQUEST['customActionName']) {
                    case 'delete_all' : $this->vehicle_model->deleteAll($ids); 
                        break;
                }
                
                $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
                $records["customActionMessage"] = "Group action successfully has been completed. Well done!"; // pass custom message(useful for getting status of group actions)
            }
            
            $iTotalRecords  = $this->vehicle_model->count_row();
            $iDisplayLength = intval($_REQUEST['length']);
            $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
            $iDisplayStart  = intval($_REQUEST['start']);
            $sEcho = intval($_REQUEST['draw']);

            $filter = array();
            
            if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'filter') {
                
                if(isset($_REQUEST['filter_id']) && ($_REQUEST['filter_id'] != '') && (($_REQUEST['filter_id'] != '#') && ($_REQUEST['filter_id'] != '#0') && ($_REQUEST['filter_id'] != '#00')) ){
                    $filter['id'] = intval(str_replace("#", "", $_REQUEST['filter_id']));
                }
                
                if(isset($_REQUEST['filter_regn']) && ($_REQUEST['filter_regn'] != '')){
                    $filter['regn_number'] = $_REQUEST['filter_regn'];
                }
                
                if(isset($_REQUEST['filter_model']) && ($_REQUEST['filter_model'] != '')){
                    $filter['model'] = $_REQUEST['filter_model'];
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

            $services = $this->vehicle_model->fetch_vehicles($iDisplayStart, $iDisplayLength, $_REQUEST['order'][0], $filter);

            if(is_array($services)) {
                
                foreach ($services as $row){
                    
                    $nos = $this->vehicle_model->get_nos($row->id);
                    
                    if($nos > 0){
                         $chkbox = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                    <input type="checkbox" disabled="disabled" name="keys" class="checkboxes" value="" />
                                    <span></span>
                                </label>';
                    }
                    else{
                        $chkbox = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                    <input type="checkbox" name="keys" class="checkboxes" value="'.$row->id.'" />
                                    <span></span>
                                </label>';
                    }
                    
                    $actions  = '<a class="btn btn-outline btn-circle btn-sm purple" href="' . site_url('admin/vehicle/edit/' . $row->id) . '"><i class="fa fa-edit"></i> Edit</a>';
                    
                    if($nos > 0){
                        $actions .= '<a title="Delete Not Allowed" class="btn btn-outline btn-circle dark btn-sm red disabled" onClick="alert(\'Delete operation is not allowed, because vehicle using '.$nos.' service.\');" href="javascript:void(0);"><i class="fa fa fa-trash"></i> '.$nos.' Used</a>';
                    }
                    else{
                        $actions .= '<a title="Delete" class="btn btn-outline btn-circle dark btn-sm red mt-sweetyalert"  href="javascript:;" onClick="return confirmation(\'' . site_url('admin/vehicle/delete/' . $row->id . '') . '\');"><i class="fa fa fa-trash"></i>Delete</a>';
                    }

                    $status_icon = ($row->status == 'active') ? '<a href="javascript:void(0);"  onclick="return status_get('."'active',"."'$row->id'".');"  title="Active"><i class="fa fa-2x fa-eye"></i></a>' : '<a href="javascript:void(0);" onclick="return status_get('."'deactive',"."'$row->id'".');"   title="Deactive"><i class="fa fa-2x fa-eye-slash"></i></a>';
                    
                    $records["data"][] = array($chkbox, code($row->id), $row->regn_number, $row->model, $status_icon, display_datetime($row->created_at), $actions);
                }
            }
            
            $records["draw"]            = $sEcho;
            $records["recordsTotal"]    = $iTotalRecords;
            $records["recordsFiltered"] = $iTotalRecords;

            echo json_encode($records);
    }

    
    public function checkups_ajax($vehicle_id)
    {
            $iTotalRecords  = $this->vehicle_model->count_checkups_row($vehicle_id);

            $iDisplayLength = intval($_REQUEST['length']);
            $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
            $iDisplayStart  = intval($_REQUEST['start']);
            $sEcho          = intval($_REQUEST['draw']);

            $filter = array();

            $records = array();
            $records["data"] = array(); 

            $end = $iDisplayStart + $iDisplayLength;
            $end = $end > $iTotalRecords ? $iTotalRecords : $end;

            $services = $this->vehicle_model->fetch_checkups($vehicle_id, $iDisplayStart, $iDisplayLength, $_REQUEST['order'][0], $filter);

            if(is_array($services)) {

                foreach ($services as $row){

                    $chkbox = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                        <input type="checkbox" name="ids" class="checkboxes" value="'.$row->id.'" />
                                        <span></span>
                                    </label>';

                    $actions = '<a title="Delete" class="btn btn-outline btn-circle dark btn-sm red mt-sweetyalert"  href="javascript:;" onClick="return confirmation(\''.site_url('admin/vehicle/delete_checkup/'.$row->id . '/'.$vehicle_id) . '\');"><i class="fa fa fa-trash"></i> Delete</a>';

                    $records["data"][] = array($chkbox, $row->date, $row->reason, display_datetime($row->ut), $actions);
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

        $this->db->where(array('deleted_at' => NULL,'id' => $id))->update(TBL_VEHICLE, $array);
        $output = json_encode(array("status" => 'success'));
        die($output);

    }
    
    
    public function delete_checkup($id, $vehicle_id) 
    {
        if ($this->db->where('id', $id)->delete(TBL_CHECKUP)){
            
            $this->session->set_flashdata('success', 'Entry has been deleted successfully!!!');
        } 
        else {
            $this->session->set_flashdata('error', 'There is some problem in deletion!!!');
        }
        
        redirect("admin/vehicle/edit/".$vehicle_id);
    }
  
}


