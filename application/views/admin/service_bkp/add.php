
<div class="page-content-wrapper">
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <!-- BEGIN THEME PANEL -->

        <!-- END THEME PANEL -->
        <!-- BEGIN PAGE BAR -->
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <a href="<?php echo site_url('admin/adminarea'); ?>">Dashboard</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <a href="<?php echo site_url('admin/service/manage'); ?>">Service Management</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>Add Service</span>
                </li>
            </ul>

        </div>
        <!-- END PAGE BAR -->

        <div class="row">
            <div class="col-md-12">

                <!-- BEGIN VALIDATION STATES-->
                <div class="portlet light portlet-fit portlet-form bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-settings font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">Create Service</span>
                        </div>
                    </div>                    
                    <div class="portlet-body">
                        <!-- BEGIN FORM-->
                        <form action="<?php echo site_url('admin/service/add'); ?>" method="post" id="create_service_form"  class="form-horizontal" enctype="multipart/form-data">
                            <div class="form-body">
                                
                                    <?php if ($errors != "") { ?>
                                        <div class="alert alert-danger">
                                        <button class="close" data-close="alert"></button> <?php echo $errors; ?>  </div>
                                    <?php } ?>
                                
                                <div class="alert alert-success display-hide">
                                    <button class="close" data-close="alert"></button> Your form validation is successful! </div>
                                <div class="form-group  margin-top-20">
                                    <label class="control-label col-md-3">Service Title
                                        
                                    </label>
                                    <div class="col-md-4">
                                        <div class="input-icon right">
                                            <i class="fa"></i>
                                            <input type="text" class="form-control" value="<?php echo set_value('service_title'); ?>" name="service_title" maxlength="50" id="service_title" data-required="1" />
                                            <span class="help-block error"> <?php echo form_error("service_title"); ?> </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group  margin-top-20">
                                    <label class="control-label col-md-3">Initial Day
                                        <span class="required"> * </span>
                                    </label>
                                    <div class="col-md-4">
                                        <div class="input-group input-medium input-daterange">
                                            <input type="text" name="start_date" id="start_date" value="<?php echo set_value('start_date'); ?>" class="form-control" readonly>
                                            <span class="input-group-btn">
                                                <button class="btn default" type="button">
                                                    <i class="fa fa-calendar"></i>
                                                </button>
                                            </span>
                                        </div>
                                        <span class="help-block error"> <?php echo form_error("start_date"); ?> </span>
                                    </div>
                                </div>
                                <div class="form-group  margin-top-20">
                                    <label class="control-label col-md-3">Ending Day
                                        <span class="required"> * </span>
                                    </label>
                                    <div class="col-md-4">
                                        <div class="input-group input-medium input-daterange" data-date-format="dd-mm-yyyy" data-date-start-date="+0d">
                                            <input type="text" name="end_date" id="end_date" value="<?php echo set_value('end_date'); ?>" class="form-control" readonly>
                                            <span class="input-group-btn">
                                                <button class="btn default" type="button">
                                                    <i class="fa fa-calendar"></i>
                                                </button>
                                            </span>
                                        </div>
                                        <span class="help-block error"> <?php echo form_error("end_date"); ?> </span>
                                    </div>
                                </div>
                                <div class="form-group  margin-top-20">
                                    <label class="control-label col-md-3">Contract
                                        <span class="required"> * </span>
                                    </label>
                                    <div class="col-md-3">
                                        <div class="input-icon right">
                                            <i class="fa"></i>
                                            <?php
                                                $options = array("" => "-- Select contract --");
                                                if(is_array($projects)){

                                                    foreach ($projects as $row){
                                                        
                                                        if($row->status == 'active'){
                                                            $options[$row->id] = $row->code.' ('.$row->customer_name.')';
                                                        }
                                                    }
                                                }
                                                echo form_dropdown('project', $options, set_value('project'), 'class="bs-select form-control" data-live-search="true" data-size="8" id="project" data-required="1"');
                                            ?>
                                            <span class="help-block error"> <?php echo form_error("project"); ?> </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group  margin-top-20">
                                    <label class="control-label col-md-3">Employee
                                        <span class="required"> * </span>
                                    </label>
                                    <div id="emp_dropdown" class="col-md-3">
                                        <div class="input-icon right">
                                            <i class="fa"></i>
                                            <?php
                                                $options = array();
                                            
                                                if(is_array($employees)){

                                                    foreach ($employees as $row){
                                                        
                                                        if($row->status == 'active'){
                                                            
                                                            $options[$row->id] = $row->emp_name.' ('.emp_code($row->id).')';
                                                        }
                                                    }
                                                }
                                                
                                                echo form_multiselect('employees[]', $options, $selected_employees, 'class="bs-select form-control" data-live-search="true" data-size="8" id="employees" data-required="1"');
                                            ?>
                                            <span class="help-block error"> <?php echo form_error("employees"); ?> </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group  margin-top-20">
                                    <label class="control-label col-md-3">Vehicle
                                       
                                    </label>
                                    <div id="veh_dropdown" class="col-md-3">
                                        <div class="input-icon right">
                                            <i class="fa"></i>
                                            <?php
                                                $options = array();

                                                if(is_array($vehicles)){

                                                    foreach ($vehicles as $row){
                                                        
                                                        if($row->status == 'active'){
                                                            
                                                            $options[$row->id] = $row->regn_number.' ( '.$row->model.' )';
                                                        }
                                                    }
                                                }
                                                
                                                echo form_multiselect('vehicles[]', $options, $selected_vehicles, 'class="bs-select form-control" data-live-search="true" data-size="8" id="vehicles" data-required="1"');
                                            ?>
                                            <span class="help-block error"> <?php echo form_error("vehicles"); ?> </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group  margin-top-20">
                                    <label class="control-label col-md-3">Department
                                        <span class="required"> * </span>
                                    </label>
                                    <div class="col-md-3">
                                        <div class="input-icon right">
                                            <i class="fa"></i>
                                            <?php
                                                $options = array("" => "-- Select Department --");

                                                if(is_array($departments)){

                                                    foreach ($departments as $row){

                                                        if($row->status == 'active'){
                                                            
                                                            $options[$row->id] = $row->name;
                                                        }
                                                    }
                                                }

                                                echo form_dropdown('department', $options, set_value('department'), 'class="bs-select form-control" data-live-search="true" data-size="8" id="department" data-required="1"');
                                            ?>
                                            <span class="help-block error"> <?php echo form_error("department"); ?> </span>
                                        </div>
                                    </div>
                                </div>
                                
                                
                                
                                <div class="form-group  margin-top-20">
                                    <label class="control-label col-md-3">Initial Time
                                        <span class="required"> * </span>
                                    </label>
                                    <div class="col-md-2">
                                        <div class="input-icon">
                                        <i class="fa fa-clock-o"></i>
                                        <input type="text" name="start_time" id="start_time" value="<?php echo set_value('start_time'); ?>" class="form-control timepicker"> </div>
                                        <span class="help-block error"> <?php echo form_error("start_time"); ?> </span>
                                    </div>
                                </div>
                                <div class="form-group  margin-top-20">
                                    <label class="control-label col-md-3">Ending Time
                                        <span class="required"> * </span>
                                    </label>
                                    <div class="col-md-2">
                                        <div class="input-icon">
                                        <i class="fa fa-clock-o"></i>
                                        <input type="text" name="end_time" id="end_time" value="<?php echo set_value('end_time'); ?>" class="form-control timepicker"> </div>
                                        <span class="help-block error"> <?php echo form_error("end_time"); ?> </span>
                                    </div>
                                </div>
                                
                                <div class="form-group  margin-top-20">
                                    <label class="control-label col-md-3">Service Description
                                       
                                    </label>
                                    <div class="col-md-4">
                                        <div class="input-icon right">
                                            <i class="fa"></i>
                                            <textarea class="form-control" rows="5" id="service_desc" name="service_desc" maxlength="500"><?php echo set_value('service_desc'); ?></textarea>
                                            <span class="help-block error"> <?php echo form_error("service_desc"); ?> </span>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">Submit</button>
                                        <button type="button" onclick="location.href = '<?php echo site_url('admin/service/manage'); ?>'" class="btn default">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!-- END VALIDATION STATES-->
                    </div>
                </div>
            </div>
        </div>
        <!-- END CONTENT BODY -->
    </div>

    <script>

	$(document).ready(function() {
            
                // with date only.
                $("#start_date").datetimepicker({
                    format: 'yyyy-mm-dd',
                    startDate: '-1m',
                    endDate: '+6m',
                    minView: 2, // this forces the picker to not go any further than days view
                    pickerPosition: "bottom-left"
                    
                }).on('changeDate', function(selected){
                    startDate = new Date(selected.date.valueOf());
                    startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
                    $('#end_date').datetimepicker('setStartDate', startDate);
                    load_employee_dropdown();
                    load_vehicle_dropdown();
                });
                
                // with date only.
                $("#end_date").datetimepicker({
                    format: 'yyyy-mm-dd',
                    startDate: '+1d',
                    minView: 2, // this forces the picker to not go any further than days view
                    pickerPosition: "bottom-left"
                    
                }).on('changeDate', function(selected){
                    endDate = new Date(selected.date.valueOf());
                    endDate.setDate(endDate.getDate(new Date(selected.date.valueOf())));
                    $('#start_date').datetimepicker('setEndDate', endDate);
                    load_employee_dropdown();
                    load_vehicle_dropdown();
                });
                
                // with time only.
                $('#start_time').timepicker({
                    defaultTime:'<?php echo $sys_config['SHIFT_START_TIME']; ?>',
                    minuteStep: 1,
                    secondStep: 1,
                    showInputs: true,
                    disableFocus: true
                });
                
                $('#end_time').timepicker({
                    defaultTime:'<?php echo $sys_config['SHIFT_END_TIME']; ?>',
                    minuteStep: 1,
                    secondStep: 1,
                    showInputs: true,
                    disableFocus: true
                });
                
                
                if($("#start_date").val() != '') {
                    
                    var vhl = '<?php echo serialize($selected_vehicles); ?>';
                    var emp = '<?php echo serialize($selected_employees); ?>';
                    
                    load_employee_dropdown(emp);
                    load_vehicle_dropdown(vhl);
                    
                    $('#end_date').datetimepicker('setStartDate', $("#start_date").val());
                    $('#start_date').datetimepicker('setEndDate', $("#end_date").val());
                }

	});
        
        
        function load_employee_dropdown(selected)
        {
            var param = $("#create_service_form").serialize()+"&selected="+selected;
            
            $.post("<?php echo site_url('admin/service/load_employee_dropdown'); ?>",param, function(htmls){
                
                $("#emp_dropdown").html(htmls);
                
                    $('.bs-select').selectpicker({
                        iconBase: 'fa',
                        tickIcon: 'fa-check'
                    });
            });
        }
        
        
        function load_vehicle_dropdown(selected)
        {
            var param = $("#create_service_form").serialize()+"&selected="+selected;
            
            $.post("<?php echo site_url('admin/service/load_vehicle_dropdown'); ?>",param, function(htmls){
                
                $("#veh_dropdown").html(htmls);
                
                    $('.bs-select').selectpicker({
                        iconBase: 'fa',
                        tickIcon: 'fa-check'
                    });
            });
        }

    </script>
