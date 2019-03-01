<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Class to manage logistic reports.
 * 
 * @author Sean Rock <sean@sparxitsolutions.com>
 * @version 1.0
 * @dated 17/02/2017
 */
class Reports extends MY_Controller 
{
    function __construct() 
    {
            parent::__construct();

            has_access('reports');

            $this->load->model('admin/reports_model');        
            $this->load->model('admin/project_model');
            $this->load->model('admin/employee_model');
            $this->load->model('admin/department_model');

            $this->load->library('parser');
    }

    
    /**
     * Display daily report
     * 
     * @return void
     */
    public function daily() 
    {
            has_access('project_daily_report');

            switch ($this->input->post('report_type')) {

                case 'project'  : $this->data['reports'] = $this->reports_model->project_report();  break;
                case 'employee' : $this->data['reports'] = $this->reports_model->employee_report(); break;
                case 'vehicle'  : $this->data['reports'] = $this->reports_model->vehicle_report();  break;
                default         : $this->data['reports'] = $this->reports_model->project_report();
            }

            if($this->input->post('report_type')) {

                $this->data['report_type'] = $this->input->post('report_type');
            }

            if($this->input->post('report_date')) {

                $this->data['report_date'] = $this->input->post('report_date');
            }

            // Export to excel
            if($this->input->post('export_xls') && ($this->input->post('export_xls') == 'excel')) {

                $this->export_xls('daily_report');
            }

            $this->render('reports/daily_report');
    }
    
    
    
    /**
     * Display weekly report
     * 
     * @return void
     */
    public function weekly()
    {
            has_access('weekly_report');

            $this->data['from_date'] = $this->input->post('report_date') ? $this->input->post('report_date') : date("Y-m-d", strtotime("-6 days"));
            $this->data['to_date']   = date("Y-m-d", strtotime($this->data['from_date']." +6 days"));

            $report_type = $this->input->post('report_type');

            switch ($report_type) {
                case 'employee' : $this->data['reports'] = $this->reports_model->get_weekly_report($this->data['from_date'], $this->data['to_date'], 'employee'); break;
                case 'vehicle'  : $this->data['reports'] = $this->reports_model->get_weekly_report($this->data['from_date'], $this->data['to_date'], 'vehicle'); break;
                case 'contract' : $this->data['reports'] = $this->reports_model->get_weekly_report($this->data['from_date'], $this->data['to_date'], 'contract'); break;
                default         : $this->data['reports'] = $this->reports_model->get_weekly_report($this->data['from_date'], $this->data['to_date'], 'employee');
            }

            $this->data['report_type'] = $report_type;


            // Export to excel
            if($this->input->post('export_xls') && ($this->input->post('export_xls') == 'excel')) {

                $this->export_xls('weekly_report');
            }

            $this->render('reports/weekly_report');
    }
    
    
    /**
     * Display monthly report
     * 
     * @return void
     */
    public function monthly()
    {
            has_access('monthly_report');
        
            $this->data['from_date'] = $this->input->post('report_month') ? $this->input->post('report_month') : date("Y-m-01");
            
            $days = date("t", strtotime($this->data['from_date']));
            
            $this->data['to_date'] = date("Y-m-d", strtotime($this->data['from_date']." +".($days - 1)." days"));

            $report_type = $this->input->post('report_type');

            switch ($report_type) {

                case 'employee' : $this->data['reports'] = $this->reports_model->get_weekly_report($this->data['from_date'], $this->data['to_date'], 'employee'); break;
                case 'vehicle'  : $this->data['reports'] = $this->reports_model->get_weekly_report($this->data['from_date'], $this->data['to_date'], 'vehicle');  break;
                case 'contract' : $this->data['reports'] = $this->reports_model->get_weekly_report($this->data['from_date'], $this->data['to_date'], 'contract'); break;
                default         : $this->data['reports'] = $this->reports_model->get_weekly_report($this->data['from_date'], $this->data['to_date'], 'employee');
            }
            
            $this->data['report_type'] = $report_type;
            
            // Export to excel
            if($this->input->post('export_xls') && ($this->input->post('export_xls') == 'excel')) {
                
                $this->export_xls('monthly_report');
            }

            $this->render('reports/monthly_report');
    }
    
    
    /**
     * Display project cost report
     * 
     * @return void
     */
    public function project_cost() 
    {
            has_access('project_cost_report');
        
            $this->data['employees'] = $this->employee_model->fetch_row();
            $this->data['projects']  = $this->project_model->fetch_row();
            $this->data['reports']   = $this->reports_model->project_cost_report();
            
            // Export to excel
            if($this->input->post('export_xls') && ($this->input->post('export_xls') == 'excel')) {
                
                $this->export_xls('project_cost');
            }
            
            $this->render('reports/project_cost_report');
    }
    
    
    /**
     * Display department cost report
     * 
     * @return void
     */
    public function department_cost() 
    {
            has_access('department_cost_report');
            
            $this->data['employees']   = $this->employee_model->fetch_row();
            $this->data['departments'] = $this->department_model->fetch_row();
            $this->data['reports']     = $this->reports_model->department_cost_report();
            
            // Export to excel
            if($this->input->post('export_xls') && ($this->input->post('export_xls') == 'excel')) {

                $this->export_xls('department_cost');
            }
            
            $this->render('reports/department_cost_report');
    }


    /**
     * Display human resource report
     * 
     * @return void
     */
    public function human_resource($emp_id = null)
    {
            has_access('hr_report');

            if($emp_id > 0){
                
                $this->single_employee_report($emp_id);
            }
            else{
                $this->all_employee_report();
            }
    }
    
    
    private function all_employee_report()
    {
            $year  = date('Y', strtotime($this->input->post('report_month')));
            $month = date('F', strtotime($this->input->post('report_month')));

            if($this->input->post()){

                $this->data['from_date']   = date("$year-m-01",strtotime($month));
                $this->data['to_date']     = date("$year-m-t",strtotime($month));
            }
            else{

                $this->data['from_date']  = date('Y-m-01',strtotime(date("M")));
                $this->data['to_date']    = date('Y-m-t',strtotime(date("M"))); 
            }

            $this->data['report_type']  = $this->input->post('report_type');
            $this->data['reports']      = $this->reports_model->human_monthly_report_model($this->data['from_date'], $this->data['to_date']);

            // Export to excel
            if($this->input->post('export_xls') && ($this->input->post('export_xls') == 'excel')) {

                $this->export_xls('hr_report');
            }

            $this->render('reports/human_monthly_report');
    }
    
    
    private function single_employee_report($emp_id)
    {
            $this->data['emp_name'] = emp_name($emp_id);

            $sql = $this->db->select("id, entry_date, assign_hours, total_hours, extra_hour, remarks")
                    ->where(array("emp_id" => $emp_id, "deleted_at" => null))
                    ->order_by("entry_date")->get(TBL_TIMESHEET);

            if($sql->num_rows() > 0) {

                $this->data['reports'] = $sql->result();
            }

            // Export to excel
            if($this->input->post('export_xls') && ($this->input->post('export_xls') == 'excel')) {

                $this->export_xls('hr_report_single');
            }

            $this->render('reports/single_human_report');
    }
    
    
    
    /**
     * Create excel and download.
     * 
     * @param string $template_name
     * @return void
     */
    private function export_xls($template_name)
    {

            //echo $template_name; die;
           
            $xls_html = $this->parser->parse('admin/reports/'.$template_name.'_xls', $this->data, true);

            write_file('uploads/'.$template_name.'.xls', $xls_html);

            $this->load->helper('download');

            $data = file_get_contents('uploads/'.$template_name.'.xls');

            $name = 'report.xls';

            force_download($name, $data);
    }
    
}
