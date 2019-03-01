 
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
                    <span>Edit Service</span>
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
                            <span class="caption-subject font-dark sbold uppercase">Edit Service</span>
                        </div>
                    </div>  
                   
                                      
                    <div class="portlet-body">
                        <!-- BEGIN FORM-->
                        <form action="" method="post" id="create_service_form"  class="form-horizontal" enctype="multipart/form-data">
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
                                            <input type="text" class="form-control" value="<?php echo $detail->service_title; ?>" name="service_title" maxlength="50" id="service_title" data-required="1" />
                                            <span class="help-block error"> <?php echo form_error("service_title");?> </span>
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
                                        <input type="text" name="start_time" id="start_time" value="<?php echo $detail->start_time; ?>" class="form-control timepicker"> </div>
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
                                        <input type="text" name="end_time" id="end_time" value="<?php echo $detail->end_time; ?>" class="form-control timepicker"> </div>
                                        <span class="help-block error"> <?php echo form_error("end_time"); ?> </span>
                                    </div>
                                </div>
                                
                                <div class="form-group  margin-top-20">
                                    <label class="control-label col-md-3">Service Description
                                        
                                    </label>
                                    <div class="col-md-4">
                                        <div class="input-icon right">
                                            <i class="fa"></i>
                                            <textarea class="form-control" rows="5" id="service_desc" name="service_desc" maxlength="500"><?php echo $detail->service_desc; ?></textarea>
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
