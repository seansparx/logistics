<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Class to manage logistic holidays.
 * 
 * @author Sean Rock <sean@sparxitsolutions.com>
 * @version 1.0
 * @dated 30/01/2017
 */

class Holiday extends MY_Controller 
{
    function __construct() 
    {
        parent::__construct();
        
        has_access('holidays');
        
        $this->load->model('admin/holiday_model');
    }
    
    
    
    /**
     * Display holiday listing page 
     * 
     * @return void
     */
    public function manage() 
    {
        $this->data['content_datas'] = $this->holiday_model->fetch_row();

        $this->render('holiday/manage');
    }
    
    
    /**
     * Add holiday
     * 
     * @return void
     */
    public function add() 
    {
        if($this->input->post()){
            
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
            $this->form_validation->set_rules('holiday_name', 'Name', 'required|min_length[2]|max_length[50]|callback_unique_holiday[holidays.holiday_name]');
            $this->form_validation->set_rules('holiday_date', 'Date', 'max_length[500]');
            
            if ($this->form_validation->run() === TRUE) {

                $this->holiday_model->add_holiday();
                
                redirect('admin/holiday/manage');
            } 
         }
         
        $this->render('holiday/add');		
   }
         
    
    
    /**
     * Delete holiday
     * 
     * @param $id int
     * @return void
     */
    public function delete($id) {
        
        $this->holiday_model->deleteRecord($id);
    }
    
    
    
    /**
     * Edit holiday
     * 
     * @param $id int
     * @return void
     */
    public function edit($id) 
    {
        $data = null;
        
        if($this->input->post()){
           
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
            $this->form_validation->set_rules('holiday_name', 'Name', 'required|min_length[2]|max_length[50]|callback_unique_holiday[holidays.holiday_name.'.$id.']');
            $this->form_validation->set_rules('holiday_date', 'Date', 'max_length[500]');
           
            if (($this->form_validation->run() === TRUE)){

                $this->holiday_model->update_holiday($id);
                
                redirect("admin/holiday/manage");
            }
            
            $data['id'] = $id;
        }
        
        $data['details'] = $this->holiday_model->getEditRecord($id);
        
        $this->data = $data;
        
        $this->render('holiday/edit');
    }
    
    
    
    /**
     * Validation rule for unique holiday
     * 
     * @param $value string
     * @param $params array
     * @return bool
     */
    public function unique_holiday($value, $parms)
    {
            $value = clean_text($value);

            $this->form_validation->set_message('unique_holiday', 'The %s "' . $value . '" is already exists.');

            list($table, $field, $edit_id) = explode(".", $parms);

            if(isset($edit_id) && ($edit_id > 0)){
                
                $where = array($field => $value, "id !=" => $edit_id);
            }
            else{
                
                $where = array($field => $value);
            }

            $query = $this->db->select($field)->from($table)->where($where)->get();

            if($query->num_rows() > 0){
                
                return false;
            }
            else{

                return true;
            }
    }
    
    
    
    /**
     * Datatable AJAX Call for holiday list
     * 
     * @return json
     */
    public function holiday_ajax()
    {
        
            if (isset($_REQUEST["customActionType"]) && $_REQUEST["customActionType"] == "group_action") {
                
                $ids = array_filter($_REQUEST['id']);
                
                switch($_REQUEST['customActionName']) {
                    case 'delete_all' : $this->holiday_model->deleteAll($ids); 
                        break;
                }
                
                $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
                $records["customActionMessage"] = "Group action successfully has been completed. Well done!"; // pass custom message(useful for getting status of group actions)
            }
            
            $iTotalRecords  = $this->holiday_model->count_row();
            $iDisplayLength = intval($_REQUEST['length']);
            $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
            $iDisplayStart  = intval($_REQUEST['start']);
            $sEcho = intval($_REQUEST['draw']);

            $filter = array();
            
            if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'filter') {
                
                if(isset($_REQUEST['filter_id']) && ($_REQUEST['filter_id'] != '' ) && (($_REQUEST['filter_id'] != '#') && ($_REQUEST['filter_id'] != '#0') && ($_REQUEST['filter_id'] != '#00')) ){
                    $filter['id'] = intval(str_replace("#", "", $_REQUEST['filter_id']));
                }
                
                if(isset($_REQUEST['filter_name']) && ($_REQUEST['filter_name'] != '')){
                    $filter['holiday_name'] = $_REQUEST['filter_name'];
                }
                
                if(isset($_REQUEST['filter_description']) && ($_REQUEST['filter_description'] != '')){
                    $filter['holiday_date'] = $_REQUEST['filter_description'];
                }                
            }
            
            $records = array();
            $records["data"] = array(); 

            $end = $iDisplayStart + $iDisplayLength;
            $end = $end > $iTotalRecords ? $iTotalRecords : $end;

            $services = $this->holiday_model->fetch_holidays($iDisplayStart, $iDisplayLength, $_REQUEST['order'][0], $filter);

            if(is_array($services)) {
                
                foreach ($services as $row){
                    
                    $chkbox = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                    <input type="checkbox" name="keys" class="checkboxes" value="'.$row->id.'" />
                                    <span></span>
                                </label>';
                    
                    $actions  = '<a class="btn btn-outline btn-circle btn-sm purple" href="' . site_url('admin/holiday/edit/' . $row->id) . '"><i class="fa fa-edit"></i> Edit</a>';
                    
                    $actions .= '<a title="Delete" class="btn btn-outline btn-circle dark btn-sm red mt-sweetyalert"  href="javascript:;" onClick="return confirmation(\'' . site_url('admin/holiday/delete/' . $row->id . '') . '\');"><i class="fa fa fa-trash"></i>Delete</a>';

                    $status_icon = ($row->status == 'active') ? '<a href="javascript:void(0);"  onclick="return status_get('."'active',"."'$row->id'".');"  title="Active"><i class="fa fa-2x fa-eye"></i></a>' : '<a href="javascript:void(0);" onclick="return status_get('."'deactive',"."'$row->id'".');"   title="Deactive"><i class="fa fa-2x fa-eye-slash"></i></a>';
                    
                    $records["data"][] = array($chkbox, $row->holiday_name, display_date($row->holiday_date), date("l", strtotime($row->holiday_date)), display_datetime($row->ut), $actions);
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


