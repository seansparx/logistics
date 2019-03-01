<style>
    ul{ list-style: none; margin: 0; padding: 0}
</style>
<div class="page-content-wrapper">
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->

        <!-- BEGIN PAGE BAR -->
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                     <a href="<?php echo site_url('admin/adminarea'); ?>">Home</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>Reports</span>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>Department Cost Report</span>
                </li>
            </ul>

        </div>
        <!-- END PAGE BAR -->
        <!-- BEGIN PAGE TITLE-->
        <h1 class="page-title"></h1>
        <!-- END PAGE TITLE-->
        <!-- END PAGE HEADER-->
        <div class="row full-text">

            <div class="col-md-12">
                <!-- BEGIN BORDERED TABLE PORTLET-->
                <div class="portlet light portlet-fit bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="caption-subject font-dark bold uppercase">Department Cost Report</span>
                        </div>
                        <div class="actions">
                            
                        </div>

                        <?php echo form_open('', array("name" => "filter_form")); ?>
                        
                            <div class="form-group">
                                <label class="control-label col-md-1">Date Range :</label>
                                <div class="col-md-1">
                                    <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="dd/mm/yyyy">
                                        <input type="text" class="form-control" name="start_date" id="start_date" placeholder="dd/mm/yyyy" value="<?php echo $this->input->post('start_date'); ?>">
                                        <span class="input-group-addon"> to </span>
                                        <input type="text" class="form-control" name="end_date" id="end_date" placeholder="dd/mm/yyyy" value="<?php echo $this->input->post('end_date'); ?>"> 
                                        
                                        <span class="input-group-btn">
                                            <button  class="btn btn-success" name="btn_date_range" type="submit" value="go"> Go </button>
                                        </span>
                                        <span class="input-group-btn">
                                            <a href="<?php echo site_url('admin/reports/department_cost'); ?>" class="btn btn-default"> Reset </a>
                                        </span>
                                    </div>
                                    <!-- /input-group -->
                                    <span class="help-block"> <?php echo form_error("start_date"); ?> </span>
                                </div>
                            </div>
                            <div class="btn-group pull-right">
                                <div class="input-group input-medium">
                                  <span class="input-group-btn">
                                     <button  class="btn btn-success print_btn" type="button">Print Report</button>
                                 </span>
                                    <span class="input-group-btn">
                                        <button name="export_xls" value="excel" class="btn purple download_btn" type="submit"><i class="fa fa-file-excel-o"></i> Download</button>
                                    </span>
                                </div>
                            </div>
                        <?php echo form_close();?>

                    </div>



                    <div class="portlet-body">
                        <div class="table-scrollable">

                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th> # </th>
                                        <th> Code </th>
                                        <th> Employee Name </th>                                        
                                        <?php
                                            foreach ($departments as $dept_obj) {
                                                
                                                if($dept_obj->status == 'active') {
                                                    ?>
                                                    <th><?php echo $dept_obj->name; ?></th>   
                                                    <?php
                                                }
                                            }
                                        ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($employees) && count($employees) > 0) {
                                        $i = 1;
                                        foreach ($employees as $emp_obj) {
                                            
                                            if($emp_obj->status == 'active') {
                                                ?>
                                                <tr>
                                                    <td width="30"><?php echo $i++; ?></td>
                                                    <td width="80"><?php echo emp_code($emp_obj->id); ?></td>
                                                    <td width="200"><?php echo $emp_obj->emp_name; ?></td>
                                                    <?php
                                                        foreach ($departments as $dept_obj) {

                                                            if($dept_obj->status == 'active') {
                                                                ?>
                                                                <td>
                                                                    <?php 
                                                                        $total_hrs  = array_sum($reports[$emp_obj->id][$dept_obj->id]['assignd_hrs']); 
                                                                        $consumed_hrs = array_sum($reports[$emp_obj->id][$dept_obj->id]['consumed_hrs']); 
                                                                        $percentage = round((($consumed_hrs * 100) / $total_hrs),2);
                                                                        
                                                                        echo $percentage.'%';
                                                                    ?>
                                                                </td>   
                                                                <?php
                                                            }
                                                        }
                                                    ?>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        
                                    } 
                                    else {
                                        ?>
                                        <tr><td colspan="9">No report found</td></tr>    
                                        <?php
                                    }
                                    ?>  
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
                <!-- END BORDERED TABLE PORTLET-->
            </div>
        </div>
    </div>
</div>
<!-- END CONTENT BODY -->
<script>

    $(document).ready(function () {

        // with date only.
        $("#report_date").datetimepicker({
            format: 'yyyy-mm-dd',
            endDate: '+6m',
            minView: 2, // this forces the picker to not go any further than days view
            pickerPosition: "bottom-left"

        }).on('changeDate', function (selected) {

        });

    });

</script>

<!-- Printing jquery-->

<script type='text/javascript'>

    jQuery(function($) { 'use strict';

        $('.print_btn').on('click', function() {
            
            $(".full-text").print({
                globalStyles : false,
                mediaPrint : true,
                stylesheet : "<?php echo base_url();?>/assets/admin/css/print.css",
                iframe : false,
                noPrintSelector : ".print_btn, .download_btn",
                deferred: $.Deferred().done(function() { console.log('Printing done', arguments); })
            });
        });  
    });
</script>